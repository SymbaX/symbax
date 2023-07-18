<?php

namespace App\UseCases\Auth;

use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * 登録ユースケースクラス
 *
 * このクラスは、ユーザーの登録とログイン処理を提供します。
 */
class RegistrationUseCase
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
     * ユーザーの登録を行います。
     *
     * @param array $requestData ユーザー登録に必要な入力データ
     * @return User 登録されたユーザーオブジェクト
     */
    public function register(array $requestData): User
    {
        $user = User::create([
            'name' => $requestData['name'],
            'email' => $requestData['email'],
            'password' => Hash::make($requestData['password']),
            'college_id' => $requestData['college'],
            'department_id' => $requestData['department'],
        ]);

        $user->sendEmailVerificationNotification();
        $this->operationLogUseCase->store('ID:' . $user->id . 'のユーザーを登録しました');

        return $user;
    }

    /**
     * ユーザーをログインさせます。
     *
     * @param User $user ログインさせるユーザーオブジェクト
     * @return void
     */
    public function login(User $user): void
    {
        Auth::login($user);
    }
}
