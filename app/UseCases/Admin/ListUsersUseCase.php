<?php

namespace App\UseCases\Admin;

use App\Models\User;
use App\Models\College;
use App\Models\Department;
use App\Models\Role;
use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * ユーザー情報および関連データの取得に関するユースケース
 */
class ListUsersUseCase
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
     * ユーザー情報および関連データを取得する
     *
     * @return array ユーザー情報、カレッジ情報、学科情報、ロール情報を含む配列
     */
    public function fetchUsersData()
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
