<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\UseCases\Auth\AuthSessionUseCase;

/**
 * 認証セッションコントローラークラス
 *
 * このクラスは、認証セッションに関連する操作を提供します。
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * @var AuthSessionUseCase
     */
    private $authSessionUseCase;

    /**
     * AuthenticatedSessionControllerの新しいインスタンスを生成します。
     *
     * @param AuthSessionUseCase $authSessionUseCase 認証セッションのユースケースインスタンス
     */
    public function __construct(AuthSessionUseCase $authSessionUseCase)
    {
        $this->authSessionUseCase = $authSessionUseCase;
    }

    /**
     * ログイン画面を表示するメソッド
     *
     * @return View ログイン画面のViewインスタンス
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * ログイン処理を行うメソッド
     *
     * @param LoginRequest $request ログインリクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        // ログイン処理が成功した場合、指定されたリダイレクト先にリダイレクトします
        if ($this->authSessionUseCase->processLogin($credentials)) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // ログイン処理が失敗した場合、エラーメッセージと共にログイン画面にリダイレクトします
        return back()->withErrors([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * ログアウト処理を行うメソッド
     *
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function destroy(): RedirectResponse
    {
        $this->authSessionUseCase->logout();

        return redirect('/');
    }
}
