<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ListUsersController;
use App\Http\Controllers\Admin\UserUpdateController;
use App\Http\Controllers\Admin\ListOperationLogsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('/admin', AdminDashboardController::class)->name('admin.dashboard');
    Route::get('/admin/users', ListUsersController::class)->name('admin.users');
    Route::patch('/admin/users/{user}', UserUpdateController::class)->name('admin.user.update');

    Route::get('/admin/operation-logs', ListOperationLogsController::class)->name('admin.operation-logs');
});
