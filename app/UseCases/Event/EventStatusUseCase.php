<?php

namespace App\UseCases\Event;


use App\Mail\MailSend;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


/**
 * イベント参加ステータスユースケース
 *
 * イベントの参加ステータスに関連するユースケースを提供するクラスです。
 */
class EventStatusUseCase
{
    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * OperationLogUseCaseの新しいインスタンスを作成します。
     *
     * @param  OperationLogUseCase  $operationLogUseCase
     * @return void
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /**
     * イベントへの参加リクエスト
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントに参加リクエストを送信します。
     *
     * @param Request $request リクエストデータ
     * @return string 参加リクエストの結果を表す文字列
     */
    public function joinRequest(Request $request): string
    {
        $user_id = Auth::id();
        $event_id = $request->input('event_id');
        $event = Event::findOrFail($event_id);
        $participantCount = EventParticipant::where('event_id', $event_id)->count();

        // 自分が作成したイベントであればエラー
        if ($event->organizer_id == $user_id) {
            return 'your-event-owner';
        }

        // ユーザーが既に参加している場合はエラー
        $alreadyJoined = EventParticipant::where('event_id', $event_id)->where('user_id', $user_id)->exists();
        if ($alreadyJoined) {
            return 'already-joined';
        }

        // 参加可能な枠がなければエラー
        if ($event->number_of_recruits <= $participantCount) {
            return 'no-participation-slots';
        }

        EventParticipant::create([
            'user_id' => $user_id,
            'event_id' => $event_id,
            'status' => 'pending',
        ]);

        $this->operationLogUseCase->store('EVENT-ID:' . $event_id . 'のイベントに参加リクエストを送信しました');

        // メール送信処理
        $mail = new MailSend($event);
        $mail->eventJoinRequest($event);
        Mail::to($event->organizer->email)->send($mail); // イベントの作成者にメールを送信

        return 'join-request-event';
    }

    /**
     * イベントへの参加のキャンセル
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントへの参加をキャンセルします。
     *
     * @param Request $request リクエストデータ
     * @return string 参加キャンセルの結果を表す文字列
     */
    public function cancelJoin(Request $request): string
    {
        $user_id = Auth::id();
        $event_id = $request->input('event_id');

        // ユーザーが参加しているか確認
        $participant = EventParticipant::where('event_id', $event_id)->where('user_id', $user_id)->first();

        // 参加していない場合はエラー
        if (!$participant) {
            return 'not-joined';
        }

        // 参加者のステータスがrejectedの場合はキャンセル不可
        if ($participant->status === 'rejected') {
            return 'cancel-not-allowed';
        }

        // 参加をキャンセル
        $participant->delete();

        $this->operationLogUseCase->store('EVENT-ID:' . $event_id . 'のイベントへの参加をキャンセルしました');

        return 'canceled-join';
    }

    /**
     * イベントへの参加ステータスを変更
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントへの参加ステータスを変更します。
     *
     * @param Request $request リクエストデータ
     * @return string 参加ステータス変更の結果を表す文字列
     */
    public function changeStatus(Request $request): string
    {
        $event_id = $request->input('event_id');
        $user_id = $request->input('user_id');
        $status = $request->input('status');

        $user = User::find($user_id);

        $event = Event::find($event_id);

        if (!$event) {
            return 'not-found';
        }

        // イベント作成者の場合の処理
        if ($event->organizer_id === Auth::id()) {
            $participant = EventParticipant::where('event_id', $event_id)
                ->where('user_id', $user_id)
                ->first();

            if ($participant) {
                $participant->status = $status;
                $participant->save();

                $this->operationLogUseCase->store('USER-ID: ' . $user_id . 'のイベント(EVENT-ID: ' . $event_id . ')への参加ステータスを' . $status . 'に変更しました');

                // メール送信処理
                $mail = new MailSend($event);
                $mail->eventChangeStatus($event, $status);
                Mail::to($user->email)->send($mail); // 変更された参加者にメールを送信
            } else {
                return 'not-change-status';
            }
        }

        // 二重送信防止
        $request->session()->regenerateToken();

        return 'changed-status';
    }
}
