<?php

namespace App\UseCases\Admin;

use App\Models\OperationLog;
use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * 管理者用の操作ログリスト取得ユースケース
 */
class ListOperationLogsUseCase
{
    /**
     * 操作ログを保存するためのユースケースインスタンス。
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * ListOperationLogsUseCaseのコンストラクタ
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
     * 最新の操作ログを取得する
     * 
     * 最新の操作ログを取得し、それらのログに関連するユーザー名をセットします。
     * さらに、この操作のログも保存します。
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator 操作ログのリスト
     */
    public function fetchLogs()
    {
        $operation_logs = OperationLog::latest('created_at')->paginate(100);
        $users = User::pluck('name', 'id');

        // $operation_logsの各操作ログのユーザーIDを利用して、名前に変換
        foreach ($operation_logs as $operation_log) {
            $operation_log->user_name = $users[$operation_log->user_id] ?? 'Unknown';
        }

        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => auth()->user()->id,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'admin-operation-log-show',
            'ip' => request()->ip(),
        ]);

        return $operation_logs;
    }
}
