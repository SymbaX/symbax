<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\UserUpdateUseCase;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;

class UserUpdateController extends Controller
{
    private $userUpdateUseCase;

    public function __construct(UserUpdateUseCase $userUpdateUseCase)
    {
        $this->userUpdateUseCase = $userUpdateUseCase;
    }

    public function __invoke(UserUpdateRequest $request, User $user)
    {
        return $this->userUpdateUseCase->execute($request, $user);
    }
}
