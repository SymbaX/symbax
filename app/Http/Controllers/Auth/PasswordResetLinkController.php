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
     * @var PasswordResetLinkUseCase
     */
    private $passwordResetLinkUseCase;

    /**
     * PasswordResetLinkControllerの新しいインスタンスを生成します。
     *
     * @param PasswordResetLinkUseCase $passwordResetLinkUseCase パスワードリセットリンクの作成に関連するユースケースインスタンス
     */
    public function __construct(PasswordResetLinkUseCase $passwordResetLinkUseCase)
    {
        $this->passwordResetLinkUseCase = $passwordResetLinkUseCase;
    }

    /**
     * パスワードリセットリンク作成画面を表示します。
     *
     * @return View パスワードリセットリンク作成画面のViewインスタンス
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * パスワードリセットリンクを作成します。
     *
     * @param Request $request リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = $this->passwordResetLinkUseCase->sendResetLink($request->only('email'));

        return $status == \Illuminate\Support\Facades\Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
