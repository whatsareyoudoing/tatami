<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

/**
 * Lupa password: link dikirim ke email, lalu user membuat password baru.
 * Token dibuat, disimpan (tabel password_reset_tokens), dan dicek oleh password broker bawaan Laravel.
 */
class PasswordResetController extends Controller
{
    public function index()
    {
        return view('auth.forgot-password');
    }

    public function send(Request $request)
    {
        $request->validate(
            ['email' => ['required', 'email']],
            ['email.required' => 'Masukkan email.', 'email.email' => 'Format email tidak valid.']
        );

        try {
            Password::sendResetLink($request->only('email'));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim link reset password', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Email gagal dikirim. Silakan coba beberapa saat lagi.');
        }

        // Jawaban selalu sama (terdaftar atau tidak), supaya orang lain tidak bisa mengecek email mana yang punya akun
        return back()->with('success', 'Kalau email tersebut terdaftar, link untuk membuat password baru sudah kami kirim. Cek juga folder spam.');
    }

    public function edit(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'token'    => ['required', 'string'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required'     => 'Masukkan email.',
            'email.email'        => 'Format email tidak valid.',
            'password.required'  => 'Masukkan password baru.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        // Di-hash otomatis oleh cast 'hashed' di model User
        $status = Password::reset($data, fn (User $user, string $password) => $user->update(['password' => $password]));

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Link sudah tidak berlaku atau email tidak cocok. Silakan minta link baru.']);
        }

        return redirect()->route('login')->with('success', 'Password berhasil diganti. Silakan masuk dengan password baru.');
    }
}
