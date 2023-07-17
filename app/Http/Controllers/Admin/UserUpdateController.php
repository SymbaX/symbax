<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\UserUpdateUseCase;
use Illuminate\Http\Request;
use App\Models\User;

class UserUpdateController extends Controller
{
    private $userUpdateUseCase;

    public function __construct(UserUpdateUseCase $userUpdateUseCase)
    {
        $this->userUpdateUseCase = $userUpdateUseCase;
    }

    public function __invoke(Request $request, User $user)
    {
        return $this->userUpdateUseCase->execute($request, $user);
    }
}
