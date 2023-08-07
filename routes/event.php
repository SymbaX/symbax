<?php

use App\Http\Controllers\Event\EventCreateController;
use App\Http\Controllers\Event\EventDeleteController;
use App\Http\Controllers\Event\EventDetailController;
use App\Http\Controllers\Event\EventEditController;
use App\Http\Controllers\Event\EventListController;
use App\Http\Controllers\Event\EventStatusController;
use App\Http\Controllers\Event\EventCommunityController;
use App\Http\Controllers\Event\ReactionController;
use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'disabled'])->group(function () {
    // イベント関連
    Route::get('/event/create',  [EventCreateController::class, 'create'])->name('event.create');
    Route::patch('/event/store', [EventCreateController::class, 'store'])->name('event.store');

    Route::get('/event/{event_id}', [EventDetailController::class, 'show'])->where('event_id', '[0-9]+')->name('event.show');
    Route::get('/event/{event_id}/edit', [EventEditController::class, 'edit'])->where('event_id', '[0-9]+')->name('event.edit');
    Route::patch('/event/{event_id}/update', [EventEditController::class, 'update'])->where('event_id', '[0-9]+')->name('event.update');

    Route::patch('/event/{event_id}/join-request', [EventStatusController::class, 'joinRequest'])->where('event_id', '[0-9]+')->name('event.join.request');
    Route::patch('/event/{event_id}/cancel-join', [EventStatusController::class, 'cancelJoin'])->where('event_id', '[0-9]+')->name('event.cancel-join');
    Route::delete('/event/{event_id}/delete',  [EventDeleteController::class, 'deleteEvent'])->where('event_id', '[0-9]+')->name('event.destroy');

    Route::patch('/event/{event_id}/change-status', [EventStatusController::class, 'changeStatus'])->where('event_id', '[0-9]+')->name('event.change.status');
    Route::get('/event/{event_id}/community', [EventCommunityController::class, 'create'])->where('event_id', '[0-9]+')->name('event.community');
    Route::post('/event/{event_id}/community', [EventCommunityController::class, 'save'])->where('event_id', '[0-9]+')->name('event.save');
    Route::delete('/event/{event_id}/community/{topic_id}', [EventCommunityController::class, 'deleteTopic'])
        ->where(['event_id' => '[0-9]+', 'topic_id' => '[0-9]+'])
        ->name('topic.delete');

    Route::post('/topic/{topic}/reaction', [ReactionController::class, 'store'])->name('reactions.store');


    Route::get('/events/all', [EventListController::class, 'indexAll'])->name('index.all');
    Route::get('/events/join', [EventListController::class, 'indexJoin'])->name('index.join');
    Route::get('/events/organizer', [EventListController::class, 'indexOrganizer'])->name('index.organizer');
    Route::get('/home', [EventListController::class, 'indexHome'])->name('index.home');
});
