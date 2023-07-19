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

        $user->name = $request->name;

        // メールアドレスが変更されていたら、変更し、メール認証をリセット
        if ($request->email != $user->email) {
            $mail = new MailSendAdmin();
            $mail->changeEmail($user->name, $request->email);
            Mail::to($user->email)->send($mail);

            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        $user->college_id = $request->college;
        $user->department_id = $request->department;
        $user->role_id = $request->role;

        // ユーザーの変更を保存
        $user->save();

        $this->operationLogUseCase->store('● USER-ID:' . $user->id . 'のユーザー情報を更新しました', $user->id);


        return Redirect::route('admin.users')->with('status', 'user-updated');
    }
}
