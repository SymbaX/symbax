<?php

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['verified'])->name('dashboard');

Route::get('/list', [EventController::class, 'list'])->middleware(['verified'])->name('list');

Route::get('/details/{id}', function ($id) {
    $event = Event::findOrFail($id);
    return view('event/details', ['event' => $event]);
})->middleware(['verified'])->name('details');

Route::get('/new', function () {
    return view('event/new');
})->middleware(['verified'])->name('new');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/event/create', [EventController::class, 'create'])->name('event.create');
});

require __DIR__ . '/auth.php';
