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
     * @var EmailVerificationUseCase
     */
    private $emailVerificationUseCase;

    /**
     * VerifyEmailControllerの新しいインスタンスを生成します。
     *
     * @param EmailVerificationUseCase $emailVerificationUseCase メールアドレスの検証に関連するユースケースインスタンス
     */
    public function __construct(EmailVerificationUseCase $emailVerificationUseCase)
    {
        $this->emailVerificationUseCase = $emailVerificationUseCase;
    }

    /**
     * メールアドレスの検証を行います。
     *
     * @param EmailVerificationRequest $request メールアドレス検証リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        return $this->emailVerificationUseCase->verify($request);
    }
}
