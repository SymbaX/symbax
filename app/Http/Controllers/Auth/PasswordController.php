<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\OperationLogController;
use Illuminate\Support\Facades\Auth;

/**
 * パスワードコントローラー
 *
 * ユーザーのパスワードに関連するコントローラー
 */
class PasswordController extends Controller
{
    private $operationLogController;

    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
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

        $this->operationLogController->store('Email: ' . $request->email . ' のパスワードを更新しました', "不明");

        return back()->with('status', 'password-updated');
    }
}
