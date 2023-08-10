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
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * AuthSessionUseCaseのコンストラクタ
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

            $this->operationLogUseCase->store([
                'detail' => null,
                'user_id' => auth()->user()->id,
                'target_event_id' => null,
                'target_user_id' => null,
                'target_topic_id' => null,
                'action' => 'login',
                'ip' => request()->ip(),
            ]);

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
        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => auth()->user()->id,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'logout',
            'ip' => request()->ip(),
        ]);

        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
