<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\OperationLogController;

/**
 * メール検証コントローラー
 *
 * メール検証に関連するコントローラー
 */
class VerifyEmailController extends Controller
{
    private $operationLogController;

    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    /**
     * 認証済みユーザーのメールアドレスを確認済みとしてマークする
     *
     * @param EmailVerificationRequest $request メール検証リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $this->operationLogController->store('メールアドレスの検証が完了しました');

        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}
