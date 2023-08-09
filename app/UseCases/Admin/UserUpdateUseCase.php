<?php

namespace App\UseCases\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * 管理者向けユーザー更新ユースケースクラス
 */
class UserUpdateUseCase
{
    /**
     * 操作ログを保存するためのユースケースインスタンス。
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * UserUpdateUseCaseのコンストラクタ
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
     * ユーザー情報の更新処理
     *
     * @param UserUpdateRequest $request 更新情報が含まれるリクエスト
     * @param User $user 対象ユーザーのモデル
     * 
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function updateUser(UserUpdateRequest $request, User $user): RedirectResponse
    {
        // ユーザーデータのコピーを作成
        $originalUser = clone $user;

        $user->login_id = $request->login_id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->college_id = $request->college;
        $user->department_id = $request->department;
        $user->role_id = $request->role;

        $fields = ['login_id', 'name', 'email', 'college_id', 'department_id', 'role_id'];
        $detail = "";

        foreach ($fields as $field) {
            $originalValue = $originalUser->$field;
            $updatedValue = $user->$field;
            if ($originalValue != $updatedValue) {
                $detail .= "▼ {$field}: {$originalValue} ▶ {$updatedValue}\n";
            }
        }

        $isChanged = !empty($detail); // 変更があるかどうかを確認

        if ($isChanged) {
            $this->operationLogUseCase->store([
                'detail' => $detail,
                'user_id' => auth()->user()->id,
                'target_event_id' => null,
                'target_user_id' => $user->id,
                'target_topic_id' => null,
                'action' => 'admin-user-update',
                'ip' => request()->ip(),
            ]);

            // ユーザーの変更を保存
            $user->save();

            return Redirect::route('admin.users')->with('status', 'user-updated');
        }

        // 変更がない場合はリダイレクトせずにそのまま終了
        return back();
    }
}
