<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * メール検証プロンプトコントローラー
 *
 * メール検証プロンプトに関連するコントローラー
 */
class EmailVerificationPromptController extends Controller
{
    /* =================== 以下メインの処理 =================== */

    /**
     * メール検証プロンプトを表示するメソッド
     *
     * @param Request $request リクエスト
     * @return RedirectResponse|View リダイレクトレスポンスまたはビュー
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(RouteServiceProvider::HOME)
            : view('auth.verify-email');
    }
}
