<?php

use App\Http\Controllers\Auth\AuthController;
// use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend/welcome');
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
