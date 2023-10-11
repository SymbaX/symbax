<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ListUsersController;
use App\Http\Controllers\Admin\UserUpdateController;
use App\Http\Controllers\Admin\ListOperationLogsController;
use App\Http\Controllers\Admin\MailSendController;
use App\Http\Controllers\Admin\TitleImageCreateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin', 'disabled'])->group(function () {

    Route::get('/admin', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [ListUsersController::class, 'listUsers'])->name('admin.users');
    Route::patch('/admin/users/{user}', [UserUpdateController::class, 'updateUser'])->name('admin.user.update');

    Route::get('/admin/operation-logs', [ListOperationLogsController::class, 'showLogs'])->name('admin.operation-logs');

    Route::get('/admin/mail', [MailSendController::class, 'showMailForm'])->name('admin.mail');
    Route::patch('/admin/mail', [MailSendController::class, 'sendMail'])->name('admin.mail.send');

    Route::get('/admin/create/title-image', [TitleImageCreateController::class, 'createImage'])->name('admin.title-image');
});
