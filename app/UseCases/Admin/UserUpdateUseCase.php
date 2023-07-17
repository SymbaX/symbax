<?php

namespace App\UseCases\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use App\Http\Controllers\OperationLogController;
use App\Models\User;

class UserUpdateUseCase
{
    private $operationLogController;

    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    public function execute(Request $request, User $user)
    {
        // バリデーション
        $validatedData = $request->validate([
            'college' => ['required', 'exists:colleges,id'],
            'department' => [
                'required', 'exists:departments,id', Rule::exists('departments', 'id')->where(function ($query) use ($request) {
                    $query->where('college_id', $request->input('college'));
                })
            ],
            'role' => 'required',
        ]);

        // College IDとDepartment IDを更新
        $user->college_id = $validatedData['college'];
        $user->department_id = $validatedData['department'];

        // ロールを更新
        $user->role_id = $validatedData['role'];

        // ユーザーの変更を保存
        $user->save();

        $this->operationLogController->store('● ID:' . $user->id . 'のユーザー情報を更新しました', $user->id);

        return Redirect::route('admin.users')->with('status', 'user-updated');
    }
}
