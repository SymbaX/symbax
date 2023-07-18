<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\Auth\PasswordUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

/**
 * パスワードコントローラークラス
 *
 * このクラスは、パスワードの更新に関連する処理を提供します。
 */
class PasswordController extends Controller
{
    /**
     * @var PasswordUseCase
     */
    private $passwordUseCase;

    /**
     * PasswordControllerの新しいインスタンスを生成します。
     *
     * @param PasswordUseCase $passwordUseCase パスワードの更新に関連するユースケースインスタンス
     */
    public function __construct(PasswordUseCase $passwordUseCase)
    {
        $this->passwordUseCase = $passwordUseCase;
    }

    /**
     * パスワードを更新します。
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

        $this->passwordUseCase->updatePassword($request->user(), $validated);

        return back()->with('status', 'password-updated');
    }
}
