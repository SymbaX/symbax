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
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * OperationLogUseCaseの新しいインスタンスを生成します。
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関連するユースケースインスタンス
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

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
        $this->operationLogUseCase->store('検証メールを再送信しました');
    }
}
