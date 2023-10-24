<?php

namespace App\UseCases\Admin;

use App\Models\Event;
use App\Models\EventParticipant;
use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * ユースケースクラス
 */
class EventDeleteUseCase
{
    /**
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     *
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * EventDeleteUseCaseのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関するユースケース
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /* =================== 以下メインの処理 =================== */
    public function deleteEvent($event_id)
    {
        $event = Event::findOrFail($event_id);

        // イベントの参加者全員のステータスを'rejected'に更新
        EventParticipant::where('event_id', $event_id)->update(['status' => 'rejected']);

        // イベントに削除フラグを立てます
        $event->is_deleted = true;
        $event->save();

        // 操作ログを保存
        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => null,
            'target_event_id' => $event_id,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'admin-event-delete',
            'ip' => request()->ip(),
        ]);

        return true;
    }
}
