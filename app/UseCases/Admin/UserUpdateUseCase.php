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

        $isChanged = false; // 変更があるかどうかを示すフラグ

        if ($user->login_id !== $request->login_id) {
            $user->login_id = $request->login_id;
            $isChanged = true;
        }

        if ($user->name !== $request->name) {
            $user->name = $request->name;
            $isChanged = true;
        }

        if ($user->email !== $request->email) {
            $mail = new MailSendAdmin();
            $mail->changeEmail($user->name, $request->email);
            Mail::to($user->email)->send($mail);

            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
            $isChanged = true;
        }

        if ($user->college_id !== $request->college) {
            $user->college_id = $request->college;
            $isChanged = true;
        }

        if ($user->department_id !== $request->department) {
            $user->department_id = $request->department;
            $isChanged = true;
        }

        if ($user->role_id !== $request->role) {
            $user->role_id = $request->role;
            $isChanged = true;
        }

        // いずれかの項目が変更された場合の処理
        if ($isChanged) {
            // ユーザーの変更を保存
            $user->save();

            $this->operationLogUseCase->store([
                'detail' => 'ユーザー情報を更新しました',
                'user_id' => auth()->user()->id,
                'target_event_id' => null,
                'target_user_id' => $user->id,
                'target_topic_id' => null,
                'action' => 'admin-user-update',
                'ip' => request()->ip(),
            ]);


            return Redirect::route('admin.users')->with('status', 'user-updated');
        }

        // 変更がない場合はリダイレクトせずにそのまま終了
        return back();
    }
}
