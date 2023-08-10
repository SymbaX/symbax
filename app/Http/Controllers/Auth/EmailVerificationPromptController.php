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
 * この処理は、ユーザーがログインした後に、
 * メール検証が完了しているかどうかをチェックするために使用されます。
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
        // ユーザーがメールアドレスを検証済みかどうかをチェックします。
        // 検証済みの場合は、ホーム画面にリダイレクトします。
        // 未検証の場合は、メール検証画面を表示します。
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(RouteServiceProvider::HOME)
            : view('auth.verify-email');
    }
}
