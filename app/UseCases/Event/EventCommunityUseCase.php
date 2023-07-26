<?php

namespace App\UseCases\Event;

use App\Models\Topic;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * イベントとコミュニティに関連するビジネスロジックを扱うクラス
 */
class EventCommunityUseCase
{
    protected $checkParticipantStatus;
    protected $checkEventOrganizer;

    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * コンストラクタ
     *
     * @param CheckEventParticipantStatusUseCase $checkParticipantStatus
     * @param CheckEventOrganizerUseCase $checkEventOrganizer
     * @param  OperationLogUseCase  $operationLogUseCase
     */
    public function __construct(
        CheckEventParticipantStatusUseCase $checkParticipantStatus,
        CheckEventOrganizerUseCase $checkEventOrganizer,
        OperationLogUseCase $operationLogUseCase
    ) {
        $this->checkParticipantStatus = $checkParticipantStatus;
        $this->checkEventOrganizer = $checkEventOrganizer;
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
        $isEventOrganizer = $this->checkEventOrganizer->execute($id);

        if ($isParticipantApproved === "approved" || $isEventOrganizer) {
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
            'detail' => '投稿しました',
            'user_id' => null,
            'target_event_id' => $request->event_id,
            'target_user_id' => null,
            'target_topic_id' => $topic->id,
            'action' => 'create-topic',
            'ip' => request()->ip(),
        ]);


        return $topic;
    }
}
