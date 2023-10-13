<?php

namespace App\UseCases\Admin;

use App\Models\Event;
use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * ユーザー情報および関連データの取得に関するユースケース
 */
class ListEventsUseCase
{
    /**
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     *
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * ListEventsUseCaseのコンストラクタ
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

    /**
     * イベント情報および関連データを取得する
     *
     * @return array
     */
    public function fetchEventsData()
    {
        // イベントリストを取得する
        $events = Event::paginate(100);

        // 操作ログを記録する
        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => auth()->user()->id,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'admin-events-list-show',
            'ip' => request()->ip(),
        ]);

        // イベントリストを返す
        return [
            'events' => $events,
        ];
    }
}
