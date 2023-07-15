<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OperationLogController;
use App\Mail\MailSend;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EventStatusController extends Controller
{
    /**
     * @var OperationLogController
     */
    private $operationLogController;

    /**
     * OperationLogControllerの新しいインスタンスを作成します。
     *
     * @param  OperationLogController  $operationLogController
     * @return void
     */
    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }



    /**
     * イベントへの参加リクエスト
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントに参加リクエストを送信します。
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function joinRequest(Request $request)
    {
        $user_id = Auth::id();
        $event_id = $request->input('event_id');
        $event = Event::findOrFail($event_id);
        $participantCount = EventParticipant::where('event_id', $event_id)->count();

        // 自分が作成したイベントであればエラー
        if ($event->organizer_id == $user_id) {
            return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'your-event-owner');
        }

        // ユーザーが既に参加している場合はエラー
        $alreadyJoined = EventParticipant::where('event_id', $event_id)->where('user_id', $user_id)->exists();
        if ($alreadyJoined) {
            return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'already-joined');
        }

        // 参加可能な枠がなければエラー
        if ($event->number_of_recruits <= $participantCount) {
            return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'no-participation-slots');
        }

        EventParticipant::create([
            'user_id' => $user_id,
            'event_id' => $event_id,
            'status' => 'pending',
        ]);

        $this->operationLogController->store('ID:' . $event_id . 'のイベントに参加リクエストを送信しました');

        // メール送信処理
        $mail = new MailSend($event);
        $mail->eventJoinRequest($event);
        Mail::to($event->organizer->email)->send($mail); // イベントの作成者にメールを送信



        return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'join-request-event');
    }

    /**
     * イベントへの参加のキャンセル
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントへの参加をキャンセルします。
     *
     * @param  Request * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelJoin(Request $request)
    {
        $user_id = Auth::id();
        $event_id = $request->input('event_id');

        // ユーザーが参加しているか確認
        $participant = EventParticipant::where('event_id', $event_id)->where('user_id', $user_id)->first();

        // 参加していない場合はエラー
        if (!$participant) {
            return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'not-joined');
        }

        // 参加者のステータスがrejectedの場合はキャンセル不可
        if ($participant->status === 'rejected') {
            return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'cancel-not-allowed');
        }

        // 参加をキャンセル
        $participant->delete();

        $this->operationLogController->store('ID:' . $event_id . 'のイベントへの参加をキャンセルしました');


        return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'canceled-join');
    }


    /**
     * イベントへの参加ステータスを変更
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントへの参加ステータスを変更します。
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request)
    {
        $event_id = $request->input('event_id');
        $user_id = $request->input('user_id');
        $status = $request->input('status');

        $user = User::find($user_id);

        $event = Event::find($event_id);

        if (!$event) {
            return redirect()->route('list.upcoming')->with('status', 'not-found');
        }

        // イベント作成者の場合の処理
        if ($event->organizer_id === Auth::id()) {
            $participant = EventParticipant::where('event_id', $event_id)
                ->where('user_id', $user_id)
                ->first();

            if ($participant) {
                $participant->status = $status;
                $participant->save();

                $this->operationLogController->store('USER-ID: ' . $user_id . 'のイベント(EVENT-ID: ' . $event_id . ')への参加ステータスを' . $status . 'に変更しました');

                // メール送信処理
                $mail = new MailSend($event);
                $mail->eventChangeStatus($event, $status);
                Mail::to($user->email)->send($mail); // 変更された参加者にメールを送信
            } else {
                return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'not-change-status');
            }
        }

        // 二重送信防止
        $request->session()->regenerateToken();

        return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'changed-status');
    }
}
