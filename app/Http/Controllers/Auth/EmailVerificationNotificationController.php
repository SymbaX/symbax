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
     * メールアドレス確認通知のビジネスロジックを提供するユースケース
     * 
     * @var EmailVerificationNotificationUseCase メールアドレス確認通知に使用するUseCaseインスタンス
     */
    private $emailVerificationNotificationUseCase;

    /**
     * EmailVerificationNotificationControllerのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param EmailVerificationNotificationUseCase $emailVerificationNotificationUseCase メールアドレス確認通知のユースケース
     */
    public function __construct(EmailVerificationNotificationUseCase $emailVerificationNotificationUseCase)
    {
        $this->emailVerificationNotificationUseCase = $emailVerificationNotificationUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * メールアドレス確認用通知を再送信するメソッド
     *
     * @param Request $request HTTPリクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function store(Request $request): RedirectResponse
    {
        // メールアドレス確認用通知を再送信する
        $this->emailVerificationNotificationUseCase->resendEmailVerificationNotification($request->user());

        // リダイレクトする
        return back()->with('status', 'verification-link-sent');
    }
}
