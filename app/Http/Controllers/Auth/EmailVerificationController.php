<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Halaman "masukkan kode verifikasi" untuk customer yang baru daftar.
 */
class EmailVerificationController extends Controller
{
    public function __construct(
        protected EmailVerificationService $verification,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            // Halaman sukses hanya tampil sekali, tepat setelah verifikasi berhasil
            return session('verified')
                ? view('auth.verify-email-success', compact('user'))
                : redirect()->route('home');
        }

        return view('auth.verify-email', [
            'user'              => $user,
            'secondsUntilResend' => $this->verification->secondsUntilResend($user),
            'expiresMinutes'    => EmailVerificationService::EXPIRES_MINUTES,
        ]);
    }

    public function verify(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $request->validate(
            ['code' => ['required', 'digits:6']],
            ['code.required' => 'Masukkan kode verifikasi.', 'code.digits' => 'Kode verifikasi terdiri dari 6 angka.']
        );

        $result = $this->verification->verify($user, $request->input('code'));

        $message = match ($result) {
            EmailVerificationService::VERIFIED          => null,
            EmailVerificationService::EXPIRED           => 'Kode sudah tidak berlaku. Silakan klik "Kirim ulang kode".',
            EmailVerificationService::TOO_MANY_ATTEMPTS => 'Terlalu banyak percobaan salah. Silakan klik "Kirim ulang kode" untuk mendapatkan kode baru.',
            default => 'Kode salah. Sisa percobaan: ' . $this->verification->remainingAttempts($user->fresh()) . '.',
        };

        if ($message) {
            return back()->withErrors(['code' => $message]);
        }

        return redirect()->route('verification.notice')->with('verified', true);
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $wait = $this->verification->secondsUntilResend($user);
        if ($wait > 0) {
            return back()->with('error', "Tunggu {$wait} detik lagi sebelum meminta kode baru.");
        }

        try {
            $this->verification->send($user);
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim ulang kode verifikasi', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->with('error', 'Kode gagal dikirim. Silakan coba beberapa saat lagi.');
        }

        return back()->with('success', 'Kode baru sudah dikirim ke email Anda.');
    }

    /**
     * "Salah email?": keluar lalu kembali ke halaman daftar.
     * Akun lama yang belum diverifikasi tidak mengunci email/username siapa pun.
     */
    public function cancel(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('register');
    }
}
