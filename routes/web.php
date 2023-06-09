<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// HTTP ステータスコードを引数に、該当するエラーページを表示させる
Route::get('error/{code}', function ($code) {
    abort($code);
});

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth', 'verified'])->group(function () {
    // プロフィール編集画面
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // イベント関連
    Route::get('/event/create',  [EventController::class, 'createView'])->name('event.create');
    Route::patch('/event/create', [EventController::class, 'create'])->name('event.create');

    Route::get('/event/{id}', [EventController::class, 'detail'])->name('event.detail');
    Route::get('/event/edit/{id}', [EventController::class, 'edit'])->name('event.edit');
    Route::patch('/event/update/{id}', [EventController::class, 'update'])->name('event.update');

    Route::patch('/event/join-request', [EventController::class, 'joinRequest'])->name('event.join.request');
    Route::patch('/event/cancel-join', [EventController::class, 'cancelJoin'])->name('event.cancel-join');
    Route::delete('/event/{id}',  [EventController::class, 'delete'])->name('event.delete');

    Route::patch('/event/change-status', [EventController::class, 'changeStatus'])->name('event.change.status');
    Route::get('/event/{id}/approved-users-and-organizer-only', [EventController::class, 'approvedUsersAndOrganizerOnly'])
        ->name('event.approved.users.and.organizer.only');



    Route::get('/all', [EventController::class, 'listAll'])->name('list.all');
    Route::get('/upcoming', [EventController::class, 'listUpcoming'])->name('list.upcoming');





    Route::get('/admin', [AdminController::class, 'dashboard'])->middleware('admin')->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'listUsers'])->middleware('admin')->name('admin.users');
    Route::patch('/admin/users/{user}', [AdminController::class, 'userUpdate'])->middleware('admin')->name('admin.user.update');

    Route::get('/admin/operation-logs', [AdminController::class, 'listOperationLogs'])->middleware('admin')->name('admin.operation-logs');
});



require __DIR__ . '/auth.php';
