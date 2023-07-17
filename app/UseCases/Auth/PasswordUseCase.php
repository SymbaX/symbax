<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Hash;

class PasswordUseCase
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
     * ユーザーのパスワードを更新します。
     *
     * @param mixed $user
     * @param array $validated
     * @return void
     */
    public function updatePassword($user, array $validated): void
    {
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->operationLogUseCase->store('パスワードを更新しました');
    }
}
