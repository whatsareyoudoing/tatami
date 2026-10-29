<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Halaman "Akun Saya" untuk customer di website.
 * Email belum bisa diganti (butuh verifikasi ulang ke email baru).
 */
class AccountController extends Controller
{
    public function index(Request $request)
    {
        return view('account.index', ['user' => $request->user()]);
    }

    public function edit(Request $request)
    {
        return view('account.edit', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validateWithBag('profile', [
            'name' => ['required', 'string', 'max:100'],
        ], [], ['name' => 'nama']);

        $request->user()->update($data);

        return redirect()->route('account.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'Password lama salah.',
            'password.confirmed'                => 'Konfirmasi password baru tidak sama.',
            'password.min'                      => 'Password baru minimal 8 karakter.',
            'password.different'                => 'Password baru harus berbeda dari password lama.',
        ], [
            'current_password' => 'password lama',
            'password'         => 'password baru',
        ]);

        // Di-hash otomatis oleh cast 'hashed' di model User
        $request->user()->update(['password' => $request->input('password')]);

        return redirect()->route('account.edit')->with('success', 'Password berhasil diganti.');
    }
}
