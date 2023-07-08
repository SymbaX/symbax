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
    Route::patch('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::patch('/event/join', [EventController::class, 'join'])->name('event.join');
    Route::post('/cancel-join', [EventController::class, 'cancelJoin'])->name('cancel-join');
    Route::delete('/event/{id}',  [EventController::class, 'delete'])->name('event.delete');

    Route::get('/all', [EventController::class, 'listAll'])->name('list.all');
    Route::get('/list', [EventController::class, 'list'])->name('list');

    Route::get('/details/{id}', [EventController::class, 'details'])->name('details');

    Route::get('/new', function () {
        return view('event/new');
    })->name('new');

    Route::get('/event/edit/{id}', [EventController::class, 'edit'])->name('event.edit');
    Route::patch('/event/update/{id}', [EventController::class, 'update'])->name('event.update');

    Route::get('/admin', [AdminController::class, 'dashboard'])->middleware('admin')->name('admin.dashboard');
});

require __DIR__ . '/auth.php';
