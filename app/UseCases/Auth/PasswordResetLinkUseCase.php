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
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * PasswordResetLinkUseCaseのコンストラクタ
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
                'detail' => "email: {$requestData['email']}\n",
                'user_id' => null,
                'target_event_id' => null,
                'target_user_id' => $user->id,
                'target_topic_id' => null,
                'action' => 'send-reset-link',
                'ip' => request()->ip(),
            ]);
        } else {
            $this->operationLogUseCase->store([
                'detail' => "email: {$requestData['email']}\n",
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
