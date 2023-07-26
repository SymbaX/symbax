<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;
use App\Models\User;
use Illuminate\Support\Facades\Password;

/**
 * パスワードリセットリンクユースケースクラス
 *
 * このクラスは、パスワードリセットリンクの送信処理を提供します。
 */
class PasswordResetLinkUseCase
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
     * パスワードリセットリンクのリクエストを処理します。
     *
     * @param array $requestData パスワードリセットリンクのリクエストデータ
     * @return string パスワードリセットリンクの送信結果
     */
    public function sendResetLink(array $requestData): string
    {
        $status = Password::sendResetLink(
            $requestData
        );

        $user = User::where('email', $requestData['email'])->first();

        // ユーザーが存在する場合のみ、ログにユーザーIDを記録
        if ($user) {
            $this->operationLogUseCase->store([
                'detail' => 'Email: ' . $requestData['email'] . ' (USER-ID: ' . $user->id . ') にパスワードリセットリンクを送信しました',
                'user_id' => null,
                'target_event_id' => null,
                'target_user_id' => $user->id,
                'target_topic_id' => null,
                'action' => 'send-reset-link',
                'ip' => request()->ip(),
            ]);
        } else {
            $this->operationLogUseCase->store([
                'detail' => 'Email: ' . $requestData['email'] . '  へのパスワードリセットリンクの送信に失敗しました',
                'user_id' => null,
                'target_event_id' => null,
                'target_user_id' => null,
                'target_topic_id' => null,
                'action' => 'send-not-reset-link',
                'ip' => request()->ip(),
            ]);
        }

        return $status;
    }
}
