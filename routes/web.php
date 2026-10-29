<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\UserController;
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

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/dashboard','backend.dashboard')->name('dashboard');

    // Permission per aksi diatur di method middleware() masing-masing controller
    Route::resource('users', UserController::class)->except('show')->names('user');
    Route::resource('categories', CategoryController::class)->except('show')->names('category');
    Route::resource('products', ProductController::class)->except('show')->names('product');
});
