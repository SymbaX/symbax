<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\UseCases\Auth\EmailVerificationNotificationUseCase;

/**
 * メールアドレス確認通知コントローラークラス
 *
 * このクラスは、メールアドレスの確認用通知を再送信する処理を提供します。
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * @var EmailVerificationNotificationUseCase
     */
    private $emailVerificationNotificationUseCase;

    /**
     * EmailVerificationNotificationControllerの新しいインスタンスを生成します。
     *
     * @param EmailVerificationNotificationUseCase $emailVerificationNotificationUseCase メールアドレス確認通知のユースケースインスタンス
     */
    public function __construct(EmailVerificationNotificationUseCase $emailVerificationNotificationUseCase)
    {
        $this->emailVerificationNotificationUseCase = $emailVerificationNotificationUseCase;
    }

    /**
     * メールアドレス確認用通知を再送信します。
     *
     * @param Request $request HTTPリクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function store(Request $request): RedirectResponse
    {
        $this->emailVerificationNotificationUseCase->resendEmailVerificationNotification($request->user());

        return back()->with('status', 'verification-link-sent');
    }
}
