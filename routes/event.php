<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Event\ApprovedUsersAndOrganizerOnlyController;
use App\Http\Controllers\Event\EventCreateController;
use App\Http\Controllers\Event\EventDeleteController;
use App\Http\Controllers\Event\EventDetailController;
use App\Http\Controllers\Event\EventEditController;
use App\Http\Controllers\Event\EventListController;
use App\Http\Controllers\Event\EventStatusController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // プロフィール編集画面
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // イベント関連
    Route::get('/event/create',  [EventCreateController::class, 'create'])->name('event.create');
    Route::patch('/event/create', [EventCreateController::class, 'store'])->name('event.store');

    Route::get('/event/{id}', [EventDetailController::class, 'show'])->name('event.show');
    Route::get('/event/edit/{id}', [EventEditController::class, 'edit'])->name('event.edit');
    Route::patch('/event/update/{id}', [EventEditController::class, 'update'])->name('event.update');

    Route::patch('/event/join-request', [EventStatusController::class, 'joinRequest'])->name('event.join.request');
    Route::patch('/event/cancel-join', [EventStatusController::class, 'cancelJoin'])->name('event.cancel-join');
    Route::delete('/event/{id}',  [EventDeleteController::class, 'destroy'])->name('event.destroy');

    Route::patch('/event/change-status', [EventStatusController::class, 'changeStatus'])->name('event.change.status');
    Route::get('/event/{id}/approved-users-and-organizer-only', [ApprovedUsersAndOrganizerOnlyController::class, 'approvedUsersAndOrganizerOnly'])
        ->name('event.approved.users.and.organizer.only');

    Route::get('/all', [EventListController::class, 'indexAll'])->name('index.all');
    Route::get('/upcoming', [EventListController::class, 'indexUpcoming'])->name('index.upcoming');
});
