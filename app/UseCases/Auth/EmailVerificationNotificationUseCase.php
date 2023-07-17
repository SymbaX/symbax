<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;

class EmailVerificationNotificationUseCase
{
    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /**
     * メール検証通知の再送信を行います。
     *
     * @param mixed $user
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
