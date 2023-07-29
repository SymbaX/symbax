<?php

namespace App\UseCases\Event;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Topic;
use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CheckEventOrganizerService;
use Illuminate\Support\Facades\DB;

/**
 * イベントとコミュニティに関連するビジネスロジックを扱うクラス
 */
class EventCommunityUseCase
{
    protected $checkParticipantStatus;
    protected $checkEventOrganizerService;

    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * コンストラクタ
     *
     * @param CheckEventParticipantStatusUseCase $checkParticipantStatus
     * @param CheckEventOrganizerService $checkEventOrganizerService
     * @param  OperationLogUseCase  $operationLogUseCase
     */
    public function __construct(
        CheckEventParticipantStatusUseCase $checkParticipantStatus,
        CheckEventOrganizerService $checkEventOrganizerService,
        OperationLogUseCase $operationLogUseCase
    ) {
        $this->checkParticipantStatus = $checkParticipantStatus;
        $this->checkEventOrganizerService = $checkEventOrganizerService;
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /**
     * イベントへのアクセス権限をチェックする
     *
     * @param int $id イベントのID
     * @return bool 参加者が承認されているか、またはイベント主催者である場合はtrueを返す。それ以外の場合はfalseを返す。
     */
    public function checkAccess($id): bool
    {
        $isParticipantApproved = $this->checkParticipantStatus->execute($id);

        if ($isParticipantApproved === "approved" || $this->checkEventOrganizerService->check($id)) {
            return true;
        }

        return false;
    }

    /**
     * 指定したイベントに関連するトピックを取得する
     *
     * @param int $id イベントのID
     * @return \Illuminate\Database\Eloquent\Collection 最新のトピックのコレクションを返す
     */
    public function getTopics($id)
    {
        return Topic::where("event_id", $id)->latest()->get();
    }

    /**
     * トピックを保存する
     *
     * @param \Illuminate\Http\Request $request HTTPリクエストインスタンス
     * @return \App\Models\Topic 保存されたトピックのインスタンスを返す
     */
    public function saveTopic(Request $request)
    {
        $topic = new Topic();

        if ($request->content) {
            $topic->user_id = Auth::id();
            $topic->event_id = $request->event_id;
            $topic->content = $request->content;
            $topic->save();
        }


        $this->operationLogUseCase->store([
            'detail' => "topic:\n{$request->content}\n",
            'user_id' => null,
            'target_event_id' => $request->event_id,
            'target_user_id' => null,
            'target_topic_id' => $topic->id,
            'action' => 'create-topic',
            'ip' => request()->ip(),
        ]);

        // 検出したメンションの処理
        preg_match_all('/@(\w+)/', $request->content, $matches);
        $mentionedLoginIds = $matches[1] ?? [];

        $eventOrganizer = Event::where('id', $request->event_id)->first()->organizer;
        $participants = $this->getEventParticipants($request->event_id);

        $participants[] = $eventOrganizer->id;
        $participants = array_unique($participants);

        foreach ($mentionedLoginIds as $loginId) {
            $user = $this->getUserByLoginId($loginId);
            if ($user && in_array($user->id, $participants)) {
                $this->sendMentionNotification($user, $topic);
            }
        }

        return $topic;
    }

    /**
     * メンションの通知メールを送る
     *
     * @param \App\Models\User $user ユーザーモデル
     * @param \App\Models\Topic $topic トピックモデル
     * @return void
     */
    protected function sendMentionNotification(User $user, Topic $topic)
    {
        dd("メンションの通知メールを送る{{ $user->name }}さんに{{ $topic->content}} {{ $user->email}}");
    }


    public function getEventParticipants(int $eventId)
    {
        return EventParticipant::where('event_id', $eventId)
            ->where('status', 'approved')
            ->pluck('user_id')
            ->all();
    }

    public function getUserByLoginId(string $loginId)
    {
        // login_idに基づいてユーザーを取得する
        return User::where('login_id', $loginId)->first();
    }
}
