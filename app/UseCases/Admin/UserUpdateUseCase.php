<?php

namespace App\UseCases\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Mail\MailSendAdmin;
use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Mail;

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
