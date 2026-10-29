<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\Backend\UserController;
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

    Route::view('/dashboard','backend.dashboard')->name('dashboard');

    Route::get('/user',[UserController::class,'index'])->middleware('can:view_user')->name('user');
    Route::get('/user-create',[UserController::class,'create'])->middleware('can:create_user')->name('user.create');
    Route::post('/user-store',[UserController::class,'store'])->middleware('can:create_user')->name('user.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('can:update_user')->name('user.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('can:update_user')->name('user.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('can:delete_user')->name('user.destory');

    Route::get('/category',[CategoryController::class,'index'])->middleware('can:view_category')->name('category');
    Route::get('/category-create',[CategoryController::class,'create'])->middleware('can:create_category')->name('category.create');
    Route::post('/category-store',[CategoryController::class,'store'])->middleware('can:create_category')->name('category.store');
    Route::get('/categorys/{category}/edit', [CategoryController::class, 'edit'])->middleware('can:update_category')->name('category.edit');
    Route::put('/categorys/{category}', [CategoryController::class, 'update'])->middleware('can:update_category')->name('category.update');
    Route::delete('/categorys/{category}', [CategoryController::class, 'destroy'])->middleware('can:delete_category')->name('category.destory');


});
