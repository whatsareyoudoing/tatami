<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend/welcome');
})->name('home');
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
    // Maksimal 5 percobaan login per menit, mencegah tebak-tebakan password
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    // Pendaftaran customer (batas per IP diatur di RegisterController)
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Verifikasi email dengan kode 6 digit
    Route::get('/verifikasi-email', [EmailVerificationController::class, 'index'])->name('verification.notice');
    Route::post('/verifikasi-email', [EmailVerificationController::class, 'verify'])->middleware('throttle:10,1')->name('verification.verify');
    Route::post('/verifikasi-email/kirim-ulang', [EmailVerificationController::class, 'resend'])->name('verification.resend');
    Route::post('/verifikasi-email/batal', [EmailVerificationController::class, 'cancel'])->name('verification.cancel');

    // Akun Saya (customer). 'verified': yang belum memasukkan kode diarahkan ke halaman verifikasi.
    Route::middleware('verified')->group(function () {
        Route::get('/akun', [AccountController::class, 'index'])->name('account.index');
        Route::get('/akun/ubah', [AccountController::class, 'edit'])->name('account.edit');
        Route::put('/akun/profil', [AccountController::class, 'updateProfile'])->name('account.profile');
        Route::put('/akun/password', [AccountController::class, 'updatePassword'])->name('account.password');
    });

    // Panel admin: hanya role yang punya permission access_admin (admin, editor).
    // Customer yang membuka URL di bawah ini mendapat 403.
    Route::middleware('can:access_admin')->group(function () {
        Route::view('/dashboard','backend.dashboard')->name('dashboard');

        // Permission per aksi diatur di method middleware() masing-masing controller
        Route::resource('users', UserController::class)->except('show')->names('user');

        Route::get('customers', [CustomerController::class, 'index'])->name('customer.index');
        Route::patch('customers/{customer}/block', [CustomerController::class, 'block'])->name('customer.block');
        Route::patch('customers/{customer}/unblock', [CustomerController::class, 'unblock'])->name('customer.unblock');

        Route::resource('categories', CategoryController::class)->except('show')->names('category');
        Route::resource('products', ProductController::class)->except('show')->names('product');
    });
});
