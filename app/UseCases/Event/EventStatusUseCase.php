<?php

namespace App\UseCases\Event;


use App\Mail\MailSend;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use App\Services\CheckEventOrganizerService;
use App\UseCases\OperationLog\OperationLogUseCase;
use Carbon\Carbon;
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
     * @var CheckEventOrganizerService
     */
    private $checkEventOrganizerService;

    /**
     * OperationLogUseCaseの新しいインスタンスを作成します。
     *
     * @param  OperationLogUseCase  $operationLogUseCase
     * @param  CheckEventOrganizerService  $checkEventOrganizerService
     * @return void
     */
    public function __construct(OperationLogUseCase $operationLogUseCase, CheckEventOrganizerService $checkEventOrganizerService)
    {
        $this->operationLogUseCase = $operationLogUseCase;
        $this->checkEventOrganizerService = $checkEventOrganizerService;
    }

    /* =================== 以下メインの処理 =================== */

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
        $event = Event::where('id', $event_id)->where('is_deleted', false)->firstOrFail();
        $participantCount = EventParticipant::where('event_id', $event_id)
            ->where(function ($query) {
                $query->where('status', 'approved')
                    ->orWhere('status', 'pending');
            })->count();

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

        // 現在日時が締切日の終わり（23:59:59）を過ぎていればエラー
        $deadline = Carbon::parse($event->deadline_date)->endOfDay();
        if (Carbon::now() > $deadline) {
            return 'deadline-passed';
        }

        EventParticipant::create([
            'user_id' => $user_id,
            'event_id' => $event_id,
            'status' => 'pending',
        ]);

        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => null,
            'target_event_id' => $event->id,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'event-request-join',
            'ip' => request()->ip(),
        ]);

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

        Event::where('id', $event_id)->where('is_deleted', false)->firstOrFail();


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

        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => null,
            'target_event_id' => $event_id,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'event-cancel-join',
            'ip' => request()->ip(),
        ]);
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

        Event::where('id', $event_id)->where('is_deleted', false)->firstOrFail();


        // イベント作成者でない場合はエラー
        if (!$this->checkEventOrganizerService->check($event_id)) {
            return 'not-change-status';
        }

        // セッションに保存されたイベントIDとトークンを取得し、リクエストの値と比較
        $status_change_token = $request->input('status_change_token');
        if ($status_change_token !== session('status_change_token') || $event_id !== session('status_change_event_id')) {
            abort(403);
        }

        if ($status !== 'approved' and $status !== 'rejected') {
            return 'not-change-status';
        }


        $participant = EventParticipant::where('event_id', $event_id)
            ->where('user_id', $user_id)
            ->first();

        if ($participant) {
            $originalStatus = $participant->status; // 変更前のステータスを保存

            if ($originalStatus === $status) {
                return 'no-change'; // ステータスに変更がなければ以降の処理をスキップ
            }

            $participant->status = $status;
            $participant->save();

            $detail = "status: {$originalStatus} ▶ {$status}"; // ステータスの変更をdetailに追加

            $this->operationLogUseCase->store([
                'detail' => $detail,
                'user_id' => null,
                'target_event_id' => $event_id,
                'target_user_id' => $user_id,
                'target_topic_id' => null,
                'action' => 'event-change-status',
                'ip' => request()->ip(),
            ]);


            $user = User::find($user_id);
            $event = Event::find($event_id);

            // メール送信処理
            $mail = new MailSend($event);
            $mail->eventChangeStatus($event, $status);
            Mail::to($user->email)->send($mail); // 変更された参加者にメールを送信
        } else {
            return 'not-change-status';
        }


        // 二重送信防止
        $request->session()->regenerateToken();

        return 'changed-status';
    }
}
