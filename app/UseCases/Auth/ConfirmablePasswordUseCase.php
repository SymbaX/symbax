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
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * ConfirmablePasswordUseCaseのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関するユースケース
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /* =================== 以下メインの処理 =================== */

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

        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => auth()->user()->id,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'confirm-password',
            'ip' => request()->ip(),
        ]);
    }
}
