<?php

namespace App\UseCases\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;

class UserUpdateUseCase
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

    public function execute(UserUpdateRequest $request, User $user): RedirectResponse
    {
        // バリデーションはリクエストクラスに記述したルールにより自動的に実行される

        // College IDとDepartment IDを更新
        $user->college_id = $request->input('college');
        $user->department_id = $request->input('department');

        // ロールを更新
        $user->role_id = $request->input('role');

        // ユーザーの変更を保存
        $user->save();

        $this->operationLogUseCase->store('● USER-ID:' . $user->id . 'のユーザー情報を更新しました', $user->id);


        return Redirect::route('admin.users')->with('status', 'user-updated');
    }
}
