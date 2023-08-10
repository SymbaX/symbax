<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\Auth\PasswordResetLinkUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * パスワードリセットリンクコントローラークラス
 *
 * このクラスは、パスワードリセットリンクの作成に関連する処理を提供します。
 */
class PasswordResetLinkController extends Controller
{
    /**
     * パスワードリセットリンクの作成に関連するビジネスロジックを提供するユースケース
     * 
     * @var PasswordResetLinkUseCase パスワードリセットリンクの作成に使用するUseCaseインスタンス
     */
    private $passwordResetLinkUseCase;

    /**
     * PasswordResetLinkControllerのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param PasswordResetLinkUseCase $passwordResetLinkUseCase パスワードリセットリンクの作成に関連するユースケース
     */
    public function __construct(PasswordResetLinkUseCase $passwordResetLinkUseCase)
    {
        $this->passwordResetLinkUseCase = $passwordResetLinkUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * パスワードリセットリンク作成画面を表示するメソッド
     *
     * @return View パスワードリセットリンク作成画面のViewインスタンス
     */
    public function create(): View
    {
        // パスワードリセットリンク作成画面を表示する
        return view('auth.forgot-password');
    }

    /**
     * パスワードリセットリンクを作成するメソッド
     *
     * @param Request $request リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function store(Request $request): RedirectResponse
    {
        // リクエストデータを検証します。入力されたメールアドレスが正しい形式であることを確認します。
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 指定されたメールアドレスにパスワードリセットリンクを送信します。
        $status = $this->passwordResetLinkUseCase->sendResetLink($request->only('email'));

        // リセットリンクが正常に送信された場合、ステータスメッセージを付けて前のページにリダイレクトします。
        // それ以外の場合、エラーメッセージを付けて前のページにリダイレクトします。
        return $status == \Illuminate\Support\Facades\Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
