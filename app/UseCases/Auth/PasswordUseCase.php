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
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * PasswordUseCaseのコンストラクタ
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
            'detail' => null,
            'user_id' => null,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'update-password',
            'ip' => request()->ip(),
        ]);
    }
}
