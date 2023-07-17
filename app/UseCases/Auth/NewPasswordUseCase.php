<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordUseCase
{
    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /**
     * パスワードリセット処理を行います。
     *
     * @param array $requestData
     * @return string
     */
    public function resetPassword(Request $request): string
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ユーザーのパスワードリセットを試みます。成功した場合は実際のユーザーモデル上のパスワードを更新し、データベースに保存します。
        // 失敗した場合はエラーを解析し、結果を返します。
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        $user = User::where('email', $request->get('email'))->first();
        $userId = $user ? $user->id : '不明';

        if ($status == Password::PASSWORD_RESET) {
            $this->operationLogUseCase->store('Email: ' . $request->get('email') . ' (USER-ID: ' . $userId . ') のパスワードをリセットしました');
        } else {
            $this->operationLogUseCase->store('Email: ' . $request->get('email') . ' (USER-ID: ' . $userId . ') のパスワードリセットに失敗しました');
        }

        return $status;
    }
}
