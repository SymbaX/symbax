<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkUseCase
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
     * パスワードリセットリンクのリクエストを処理します。
     *
     * @param array $requestData
     * @return string
     */
    public function sendResetLink(array $requestData): string
    {
        $status = Password::sendResetLink(
            $requestData
        );

        $user = User::where('email', $requestData['email'])->first();

        // ユーザーが存在する場合のみ、ログにユーザーIDを記録
        if ($user) {
            $this->operationLogUseCase->store('Email: ' . $requestData['email'] . ' (USER-ID: ' . $user->id . ') にパスワードリセットリンクを送信しました', '不明');
        } else {
            $this->operationLogUseCase->store('Email: ' . $requestData['email'] . ' (USER-ID: 不明) にパスワードリセットリンクの送信を試みました', '不明');
        }

        return $status;
    }
}
