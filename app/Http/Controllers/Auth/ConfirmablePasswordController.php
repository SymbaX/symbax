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
     * @var ConfirmablePasswordUseCase
     */
    private $confirmablePasswordUseCase;

    /**
     * ConfirmablePasswordControllerの新しいインスタンスを生成します。
     *
     * @param ConfirmablePasswordUseCase $confirmablePasswordUseCase パスワード確認のユースケースインスタンス
     */
    public function __construct(ConfirmablePasswordUseCase $confirmablePasswordUseCase)
    {
        $this->confirmablePasswordUseCase = $confirmablePasswordUseCase;
    }

    /**
     * パスワード確認画面を表示します。
     *
     * @return View パスワード確認画面のViewインスタンス
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * パスワード確認を行います。
     *
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
