<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Hash;

/**
 * パスワードユースケースクラス
 *
 * このクラスは、ユーザーのパスワード更新を提供します。
 */
class PasswordUseCase
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
     * ユーザーのパスワードを更新します。
     *
     * @param mixed $user 更新対象のユーザーオブジェクト
     * @param array $validated バリデーション済みの入力データ
     * @return void
     */
    public function updatePassword($user, array $validated): void
    {
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->operationLogUseCase->store([
            'detail' => 'パスワードを更新しました',
            'user_id' => null,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'update-password',
            'ip' => request()->ip(),
        ]);
    }
}
