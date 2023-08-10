<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * メール検証通知ユースケースクラス
 *
 * このクラスは、メール検証通知の再送信に関連する処理を提供します。
 */
class EmailVerificationNotificationUseCase
{
    /**
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * EmailVerificationNotificationUseCaseのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関するユースケース
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * メール検証通知の再送信を行います。
     *
     * @param mixed $user 再送信対象のユーザーオブジェクト
     */
    public function resendEmailVerificationNotification($user): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->sendEmailVerificationNotification();
        $this->operationLogUseCase->store([
            'detail' => "email: {$user->email}\n",
            'user_id' => auth()->user()->id,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'resend-email-verification-notification',
            'ip' => request()->ip(),
        ]);
    }
}
