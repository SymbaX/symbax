<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Controllers\OperationLogController;

/**
 * ユーザーセッションコントローラー
 *
 * ユーザーのログイン、ログアウトなどの認証関連の機能を提供します。
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * @var OperationLogController
     */
    private $operationLogController;

    /**
     * OperationLogControllerの新しいインスタンスを作成します。
     *
     * @param  OperationLogController  $operationLogController
     * @return void
     */
    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    /**
     * ログインビューの表示
     *
     * ログインビューを表示します。
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * 認証リクエストの処理
     *
     * ログイン認証リクエストを処理します。
     *
     * @param  LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $this->operationLogController->store('ログインしました');

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * 認証セッションの破棄
     *
     * 認証セッションを破棄し、ログアウトします。
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->operationLogController->store('ログアウトしました');

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
