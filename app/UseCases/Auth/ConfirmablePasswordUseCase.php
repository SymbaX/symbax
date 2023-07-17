<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ConfirmablePasswordUseCase
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
     * ユーザーのパスワードを確認します。
     *
     * @param string $email
     * @param string $password
     * @throws ValidationException
     */
    public function confirmPassword(string $email, string $password): void
    {
        if (!Auth::guard('web')->validate([
            'email' => $email,
            'password' => $password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        request()->session()->put('auth.password_confirmed_at', time());
        $this->operationLogUseCase->store('パスワードを確認しました');
    }
}
