<?php

namespace App\UseCases\Admin;

use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * 管理者ダッシュボードのユースケースクラス
 * 
 * 管理者ダッシュボードの動作やログ記録に関連する処理を担当します。
 */
class AdminDashboardUseCase
{
    /**
     * 操作ログを保存するためのユースケースインスタンス。
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * コンストラクタ
     * 
     * 操作ログを管理するユースケースをインジェクションします。
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関するユースケース
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * 管理者ダッシュボードのアクセスログを保存する
     * 
     * 管理者ダッシュボードがアクセスされた際のログ情報を保存します。
     */
    public function dashboardLog()
    {
        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => auth()->user()->id,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'admin-top-show',
            'ip' => request()->ip(),
        ]);
    }
}
