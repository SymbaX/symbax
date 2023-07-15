<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'listUsers'])->name('admin.users');
    Route::patch('/admin/users/{user}', [AdminController::class, 'userUpdate'])->name('admin.user.update');

    Route::get('/admin/operation-logs', [AdminController::class, 'listOperationLogs'])->name('admin.operation-logs');
});
