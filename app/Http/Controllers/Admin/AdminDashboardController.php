<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\AdminDashboardUseCase;

class AdminDashboardController extends Controller
{
    private $adminDashboardUseCase;

    public function __construct(AdminDashboardUseCase $adminDashboardUseCase)
    {
        $this->adminDashboardUseCase = $adminDashboardUseCase;
    }

    public function __invoke()
    {
        $users = $this->adminDashboardUseCase->execute();
        return view('admin.dashboard', compact('users'));
    }
}
