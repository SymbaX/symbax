<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * メール検証コントローラー
 *
 * メール検証に関連するコントローラー
 */
class VerifyEmailController extends Controller
{
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

        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}
