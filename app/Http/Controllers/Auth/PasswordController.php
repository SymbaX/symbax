<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * パスワードコントローラー
 *
 * ユーザーのパスワードに関連するコントローラー
 */
class PasswordController extends Controller
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
     * ユーザーのパスワードを更新する
     *
     * @param Request $request リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->operationLogUseCase->store('パスワードを更新しました');

        return back()->with('status', 'password-updated');
    }
}
