<?php


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
})->name('welcome');

require __DIR__ . '/auth.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/event.php';
require __DIR__ . '/admin.php';

// 他のルート定義...

Route::post('/self_introduction', 'UserController@updateIntroduction')->name('self_introduction');

// 他のルート定義...