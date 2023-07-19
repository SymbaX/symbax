<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\UseCases\Auth\NewPasswordUseCase;

/**
 * 新しいパスワードコントローラークラス
 *
 * このクラスは、新しいパスワードの作成に関連する処理を提供します。
 */
class NewPasswordController extends Controller
{
    /**
     * @var NewPasswordUseCase
     */
    private $newPasswordUseCase;

    /**
     * NewPasswordControllerの新しいインスタンスを生成します。
     *
     * @param NewPasswordUseCase $newPasswordUseCase 新しいパスワードの作成に関連するユースケースインスタンス
     */
    public function __construct(NewPasswordUseCase $newPasswordUseCase)
    {
        $this->newPasswordUseCase = $newPasswordUseCase;
    }

    /**
     * パスワードリセット画面を表示します。
     *
     * @param Request $request リクエスト
     * @return View パスワードリセット画面のViewインスタンス
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * パスワードリセットを行います。
     *
     * @param Request $request リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function store(Request $request): RedirectResponse
    {
        $status = $this->newPasswordUseCase->resetPassword($request);

        return $status == \Illuminate\Support\Facades\Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
