<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class AuthSessionUseCase
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
     * ログイン認証を処理します。
     *
     * @param  array  $credentials
     * @return bool
     */
    public function authenticate(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

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
