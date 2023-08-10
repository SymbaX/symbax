<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConfirmPasswordRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\UseCases\Auth\ConfirmablePasswordUseCase;

/**
 * パスワード確認コントローラークラス
 *
 * このクラスは、パスワード確認に関連する処理を提供します。
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * パスワード確認のビジネスロジックを提供するユースケース
     * 
     * @var ConfirmablePasswordUseCase パスワード確認に使用するUseCaseインスタンス
     */
    private $confirmablePasswordUseCase;

    /**
     * ConfirmablePasswordControllerのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param ConfirmablePasswordUseCase $confirmablePasswordUseCase パスワード確認のユースケース
     */
    public function __construct(ConfirmablePasswordUseCase $confirmablePasswordUseCase)
    {
        $this->confirmablePasswordUseCase = $confirmablePasswordUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * パスワード確認画面を表示するメソッド
     *
     * @return View パスワード確認画面のViewインスタンス
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * パスワード確認を行うメソッド
     * @param ConfirmPasswordRequest $request パスワード確認のリクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function store(ConfirmPasswordRequest $request): RedirectResponse
    {
        $this->confirmablePasswordUseCase->confirmPassword(
            $request->user()->email,
            $request->password
        );

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
