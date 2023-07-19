<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\ListUsersUseCase;

class ListUsersController extends Controller
{
    private $listUsersUseCase;

    public function __construct(ListUsersUseCase $listUsersUseCase)
    {
        $this->listUsersUseCase = $listUsersUseCase;
    }

    public function __invoke()
    {
        $data = $this->listUsersUseCase->execute();
        return view('admin.users-list', $data);
    }
}
