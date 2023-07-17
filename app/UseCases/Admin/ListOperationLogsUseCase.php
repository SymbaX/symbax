<?php

namespace App\UseCases\Admin;

use App\Models\OperationLog;
use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;

class ListOperationLogsUseCase
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


    public function execute()
    {
        $operation_logs = OperationLog::latest('created_at')->paginate(100);
        $users = User::pluck('name', 'id');

        // $operation_logsの各操作ログのユーザーIDを利用して、名前に変換
        foreach ($operation_logs as $operation_log) {
            $operation_log->user_name = $users[$operation_log->user_id] ?? 'Unknown';
        }

        $this->operationLogUseCase->store('● 操作ログ一覧を表示しました');

        return $operation_logs;
    }
}
