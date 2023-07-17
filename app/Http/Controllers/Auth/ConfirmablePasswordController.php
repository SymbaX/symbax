<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * パスワード確認コントローラー
 *
 * ユーザーのパスワード確認に関連するコントローラー
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * OperationLogUseCaseの新しいインスタンスを作成します。
     *
     * @param  OperationLogUseCase  $operationLogUseCase
     * @return void
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /**
     * パスワード確認画面を表示する
     *
     * @return View パスワード確認画面の表示
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * ユーザーのパスワードを確認する
     *
     * 簡単な処理を行った後、HOMEページへアクセスする
     *
     * @param Request $request リクエスト
     * @return RedirectResponse HOMEページの表示
     *
     * @throws ValidationException バリデーション例外
     * 
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        $this->operationLogUseCase->store('パスワードを確認しました');

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
