<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Auth;

/**
 * 認証セッションユースケースクラス
 *
 * このクラスは、認証セッションに関連する処理を提供します。
 */
class AuthSessionUseCase
{
    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * OperationLogUseCaseの新しいインスタンスを生成します。
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関連するユースケースインスタンス
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /**
     * ログイン認証を処理します。
     *
     * @param  array  $credentials 認証に使用する資格情報（ユーザー名、パスワードなど）
     * @return bool 認証が成功した場合はtrueを返します。認証に失敗した場合はfalseを返します。
     */
    public function authenticate(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    /**
     * ログイン処理を行います。
     *
     * @param  array  $credentials 認証に使用する資格情報（ユーザー名、パスワードなど）
     * @return bool ログインが成功した場合はtrueを返します。ログインに失敗した場合はfalseを返します。
     */
    public function processLogin(array $credentials): bool
    {
        if ($this->authenticate($credentials)) {
            $this->refreshSession();
            $this->operationLogUseCase->store('ログインしました');

            return true;
        }

        return false;
    }

    /**
     * セッションをリフレッシュします。
     *
     * @return void
     */
    public function refreshSession(): void
    {
        request()->session()->regenerate();
    }

    /**
     * ログアウト処理を行います。
     *
     * @return void
     */
    public function logout(): void
    {
        $this->operationLogUseCase->store('ログアウトしました');

        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
