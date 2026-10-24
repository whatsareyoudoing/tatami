<?php

use App\Http\Controllers\Auth\AuthController;
// use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend/welcome');
});
Route::get('/produk', function () {
    return view('frontend/produk');
});
Route::get('/tentang', function () {
    return view('frontend/tentang');
});
Route::get('/artikel', function () {
    return view('frontend/artikel');
});
Route::get('/kontak', function () {
    return view('frontend/kontak');
});
Route::get('/custom-tatami', function () {
    return view('frontend/custom');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Route::get('/user', [UserController::class, 'index'])->middleware('can:view_user')->name('user');

    Route::view('/dashboard','dashboard')->middleware('can:view_user');
});
