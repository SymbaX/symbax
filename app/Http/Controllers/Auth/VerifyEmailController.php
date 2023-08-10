<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\Auth\EmailVerificationUseCase;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * メールアドレス検証コントローラークラス
 *
 * このクラスは、メールアドレスの検証に関連する処理を提供します。
 */
class VerifyEmailController extends Controller
{
    /**
     * メールアドレスの検証に関連するビジネスロジックを提供するユースケース
     * 
     * @var EmailVerificationUseCase メールアドレスの検証に使用するUseCaseインスタンス
     */
    private $emailVerificationUseCase;

    /**
     * VerifyEmailControllerのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param EmailVerificationUseCase $emailVerificationUseCase メールアドレスの検証に関連するユースケース
     */
    public function __construct(EmailVerificationUseCase $emailVerificationUseCase)
    {
        $this->emailVerificationUseCase = $emailVerificationUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * メールアドレスの検証を行うメソッド
     *
     * @param EmailVerificationRequest $request メールアドレス検証リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        return $this->emailVerificationUseCase->verify($request);
    }
}
