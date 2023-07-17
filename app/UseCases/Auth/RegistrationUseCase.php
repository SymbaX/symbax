<?php

namespace App\UseCases\Auth;

use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistrationUseCase
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
     * ユーザーの登録を行います。
     *
     * @param array $requestData
     * @return User
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

        $this->operationLogUseCase->store('ID:' . $user->id . 'のユーザーを登録しました');

        return $user;
    }

    /**
     * ユーザーをログインさせます。
     *
     * @param User $user
     * @return void
     */
    public function login(User $user): void
    {
        Auth::login($user);
    }
}
