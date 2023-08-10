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
     * 新しいパスワードの作成に関連するビジネスロジックを提供するユースケース
     * 
     * @var NewPasswordUseCase 新しいパスワードの作成に使用するUseCaseインスタンス
     */
    private $newPasswordUseCase;

    /**
     * NewPasswordControllerのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param NewPasswordUseCase $newPasswordUseCase 新しいパスワードの作成に関連するユースケース
     */
    public function __construct(NewPasswordUseCase $newPasswordUseCase)
    {
        $this->newPasswordUseCase = $newPasswordUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * パスワードリセット画面を表示するメソッド
     *
     * @param Request $request リクエスト
     * @return View パスワードリセット画面のViewインスタンス
     */
    public function create(Request $request): View
    {
        // パスワードリセット画面を表示する
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * パスワードリセットを行うメソッド
     *
     * @param Request $request リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function store(Request $request): RedirectResponse
    {
        // パスワードリセット処理を行う
        $status = $this->newPasswordUseCase->resetPassword($request);

        // パスワードリセット処理の結果に応じてリダイレクトする
        // パスワードリセット処理が成功した場合はログイン画面にリダイレクトする
        // パスワードリセット処理が失敗した場合はパスワードリセット画面にリダイレクトする
        return $status == \Illuminate\Support\Facades\Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
