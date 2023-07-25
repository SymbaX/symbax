<?php

namespace App\UseCases\Admin;

use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;

class AdminDashboardUseCase
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
        $users = User::where('role_id', 'admin')->get();

        $this->operationLogUseCase->store([
            'detail' => '管理者ダッシュボードを表示しました。',
            'user_id' => auth()->user()->id,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'admin-top-show',
            'ip' => request()->ip(),
        ]);
        return $users;
    }
}
