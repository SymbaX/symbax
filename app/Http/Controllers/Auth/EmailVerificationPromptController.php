<?php

/**
 * メール検証プロンプトコントローラー
 *
 * このファイルではメール検証プロンプトコントローラーを記載。
 * 
 */

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
    /**
     * メール検証プロンプトを表示する
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
