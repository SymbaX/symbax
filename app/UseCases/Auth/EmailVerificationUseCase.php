<?php

namespace App\UseCases\Auth;

use App\Providers\RouteServiceProvider;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * メールアドレス検証ユースケースクラス
 *
 * このクラスは、メールアドレスの検証を行います。
 */
class EmailVerificationUseCase
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
     * メールアドレスを確認済みとしてマークします。
     *
     * @param EmailVerificationRequest $request メールアドレス検証リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => auth()->user()->id,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'verify',
            'ip' => request()->ip(),
        ]);

        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}
