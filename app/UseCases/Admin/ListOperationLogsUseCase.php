<?php

namespace App\UseCases\Admin;

use App\Models\OperationLog;
use App\Models\User;
use App\Http\Controllers\OperationLogController;

class ListOperationLogsUseCase
{
    private $operationLogController;

    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    public function execute()
    {
        $operation_logs = OperationLog::latest('created_at')->paginate(100);
        $users = User::pluck('name', 'id');

        // $operation_logsの各操作ログのユーザーIDを利用して、名前に変換
        foreach ($operation_logs as $operation_log) {
            $operation_log->user_name = $users[$operation_log->user_id] ?? 'Unknown';
        }

        $this->operationLogController->store('● 操作ログを表示しました');

        return $operation_logs;
    }
}
