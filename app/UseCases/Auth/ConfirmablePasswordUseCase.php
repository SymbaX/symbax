<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * パスワード確認ユースケースクラス
 *
 * このクラスは、ユーザーのパスワードを確認するための処理を提供します。
 */
class ConfirmablePasswordUseCase
{
    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * ConfirmablePasswordUseCaseの新しいインスタンスを生成します。
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関連するユースケースインスタンス
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /**
     * ユーザーのパスワードを確認します。
     *
     * @param string $email ユーザーのメールアドレス
     * @param string $password 確認するパスワード
     * @throws ValidationException パスワードの確認に失敗した場合にスローされるValidationException
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
