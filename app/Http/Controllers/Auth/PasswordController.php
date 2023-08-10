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
     * パスワードの更新に関連するビジネスロジックを提供するユースケース
     * 
     * @var PasswordUseCase パスワードの更新に使用するUseCaseインスタンス
     */
    private $passwordUseCase;

    /**
     * PasswordControllerのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param PasswordUseCase $passwordUseCase パスワードの更新に関連するユースケース
     */
    public function __construct(PasswordUseCase $passwordUseCase)
    {
        $this->passwordUseCase = $passwordUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * パスワードを更新するメソッド
     *
     * @param Request $request リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function update(Request $request): RedirectResponse
    {
        // リクエストデータを検証します。指定のルールに従って入力データのバリデーションを行い、
        // 問題がある場合はエラーメッセージをセットしてリクエストをリダイレクトします。
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // バリデーションが成功した場合、ユーザーのパスワードを更新します。
        $this->passwordUseCase->updatePassword($request->user(), $validated);

        // パスワードの更新が完了した後、ステータスメッセージを付けて前のページにリダイレクトします。
        return back()->with('status', 'password-updated');
    }
}
