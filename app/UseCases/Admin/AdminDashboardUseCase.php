<?php

namespace App\UseCases\Admin;

use App\Models\User;
use App\Http\Controllers\OperationLogController;

class AdminDashboardUseCase
{
    private $operationLogController;

    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    public function execute()
    {
        $users = User::where('role_id', 'admin')->get();
        $this->operationLogController->store('● 管理者ダッシュボードを表示しました');

        return $users;
    }
}
