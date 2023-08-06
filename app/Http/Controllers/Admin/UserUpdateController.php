<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\UserUpdateUseCase;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;

/**
 * Class UserUpdateController
 * 
 * 管理者向けユーザー更新のコントローラーです。
 */
class UserUpdateController extends Controller
{
    /**
     * @var UserUpdateUseCase
     * 
     * ユーザー更新のためのユースケース
     */
    private $userUpdateUseCase;

    /**
     * UserUpdateController constructor.
     *
     * @param UserUpdateUseCase $userUpdateUseCase
     * 
     * ユーザー更新のユースケースを注入します。
     */
    public function __construct(UserUpdateUseCase $userUpdateUseCase)
    {
        $this->userUpdateUseCase = $userUpdateUseCase;
    }

    /**
     * Handle the incoming request.
     * 
     * @param UserUpdateRequest $request
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     * 
     * ユーザー情報を更新します。更新情報と対象ユーザーはリクエストから取得します。
     */
    public function __invoke(UserUpdateRequest $request, User $user)
    {
        return $this->userUpdateUseCase->execute($request, $user);
    }
}
