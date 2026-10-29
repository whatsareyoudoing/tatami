<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\CustomerRegistrationService;
use App\Services\EmailVerificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Pendaftaran customer dari website.
 */
class RegisterController extends Controller
{
    // Maksimal pendaftaran berhasil per IP per jam (salah isi form tidak dihitung)
    public const MAX_PER_HOUR = 5;

    public function __construct(
        protected CustomerRegistrationService $registration,
        protected EmailVerificationService $verification,
    ) {}

    public function index()
    {
        // Waktu form dibuka, dienkripsi supaya tidak bisa dipalsukan (lihat RegisterRequest)
        $formToken = Crypt::encryptString((string) now()->timestamp);

        return view('auth.register', compact('formToken'));
    }

    public function store(RegisterRequest $request)
    {
        $limiterKey = 'register:' . $request->ip();

        if (RateLimiter::tooManyAttempts($limiterKey, self::MAX_PER_HOUR)) {
            $minutes = (int) ceil(RateLimiter::availableIn($limiterKey) / 60);

            return back()->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['form' => "Terlalu banyak pendaftaran dari jaringan Anda. Coba lagi dalam {$minutes} menit."]);
        }

        $user = $this->registration->register($request->validated());
        RateLimiter::hit($limiterKey, 3600);

        Auth::login($user);
        $request->session()->regenerate();

        // Kalau email gagal terkirim, akun tetap dibuat; customer bisa klik "Kirim ulang kode"
        try {
            $this->verification->send($user);
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim kode verifikasi', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return redirect()->route('verification.notice')
                ->with('error', 'Akun berhasil dibuat, tetapi kode verifikasi gagal dikirim. Silakan klik "Kirim ulang kode".');
        }

        return redirect()->route('verification.notice');
    }
}
