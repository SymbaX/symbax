<?php

namespace App\UseCases\Admin;

use App\Models\User;
use App\Models\College;
use App\Models\Department;
use App\Models\Role;
use App\Http\Controllers\OperationLogController;

class ListUsersUseCase
{
    private $operationLogController;

    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    public function execute()
    {
        $users = User::paginate(10);
        $colleges = College::all();
        $departments = Department::all();
        $roles = Role::all();

        $this->operationLogController->store('● ユーザー一覧を表示しました');

        return [
            'users' => $users,
            'colleges' => $colleges,
            'departments' => $departments,
            'roles' => $roles,
        ];
    }
}
