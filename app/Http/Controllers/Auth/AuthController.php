<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    public function login(Request $request){
        // Field "username" bisa diisi username atau email
        $credentials = $request->validate([
                'username' => ['required', 'string'],
                'password' => ['required', 'string']
        ]);

        $field = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Hanya user berstatus aktif yang boleh login
        if (Auth::attempt([$field => $credentials['username'], 'password' => $credentials['password'], 'status' => 'aktif'])) {
            $request->session()->regenerate();

            // Customer yang belum memasukkan kode verifikasi diarahkan ke halaman kode dulu
            if (!$request->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // Admin/editor kembali ke halaman admin yang tadi dituju, customer ke beranda
            return $request->user()->can('access_admin')
                ? redirect()->intended(route('dashboard'))
                : redirect()->route('home');
        }

        return back()->withErrors([
            'username' => 'Username/email atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request){
        // Staf kembali ke halaman login, customer kembali ke beranda website
        $isStaff = $request->user()?->can('access_admin');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $isStaff ? redirect('/login') : redirect()->route('home');
    }
}
