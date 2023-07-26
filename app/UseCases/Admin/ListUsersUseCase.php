<?php

namespace App\UseCases\Admin;

use App\Models\User;
use App\Models\College;
use App\Models\Department;
use App\Models\Role;
use App\UseCases\OperationLog\OperationLogUseCase;

class ListUsersUseCase
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
        $users = User::paginate(10);
        $colleges = College::all();
        $departments = Department::all();
        $roles = Role::all();

        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => auth()->user()->id,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'admin-users-list-show',
            'ip' => request()->ip(),
        ]);

        return [
            'users' => $users,
            'colleges' => $colleges,
            'departments' => $departments,
            'roles' => $roles,
        ];
    }
}
