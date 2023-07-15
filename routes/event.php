<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Event\PrivetController;
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
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // イベント関連
    Route::get('/event/create',  [EventCreateController::class, 'create'])->name('event.create');
    Route::patch('/event/store', [EventCreateController::class, 'store'])->name('event.store');

    Route::get('/event/{event_id}', [EventDetailController::class, 'show'])->name('event.show');
    Route::get('/event/{event_id}/edit', [EventEditController::class, 'edit'])->name('event.edit');
    Route::patch('/event/{event_id}/update', [EventEditController::class, 'update'])->name('event.update');

    Route::patch('/event/{event_id}/join-request', [EventStatusController::class, 'joinRequest'])->name('event.join.request');
    Route::patch('/event/{event_id}/cancel-join', [EventStatusController::class, 'cancelJoin'])->name('event.cancel-join');
    Route::delete('/event/{event_id}/delete',  [EventDeleteController::class, 'destroy'])->name('event.destroy');

    Route::patch('/event/{event_id}/change-status', [EventStatusController::class, 'changeStatus'])->name('event.change.status');
    Route::get('/event/{event_id}/members', [PrivetController::class, 'create'])
        ->name('event.members');

    Route::get('/events/all', [EventListController::class, 'indexAll'])->name('index.all');
    Route::get('/upcoming', [EventListController::class, 'indexUpcoming'])->name('index.upcoming');
});
