<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerRegistrationService
{
    private const USERNAME_MAX = 30;

    /**
     * Buat akun customer (belum terverifikasi).
     * Kode verifikasi dikirim terpisah oleh controller, setelah transaksi selesai.
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Email belum diverifikasi = belum terbukti milik siapa pun, jadi akun lamanya diganti
            User::customers()
                ->where('email', $data['email'])
                ->whereNull('email_verified_at')
                ->delete();

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'username' => $this->generateUsername($data['email']),
                'password' => $data['password'], // di-hash otomatis oleh cast 'hashed' di model User
                'status'   => 'aktif',
            ]);

            $user->roles()->attach(Role::where('name', Role::CUSTOMER)->value('id'));

            return $user;
        });
    }

    /**
     * Username dari bagian depan email: "Budi.Santoso+toko@gmail.com" -> "budi.santosotoko".
     * Kalau sudah dipakai, diberi angka: budi2, budi3, ...
     */
    public function generateUsername(string $email): string
    {
        $base = Str::lower(Str::before($email, '@'));
        $base = trim(preg_replace('/[^a-z0-9._]/', '', $base), '._');
        $base = Str::limit($base, self::USERNAME_MAX - 4, '');

        if (strlen($base) < 3) {
            $base = 'user' . random_int(1000, 9999);
        }

        $username = $base;
        for ($i = 2; User::where('username', $username)->exists(); $i++) {
            $username = Str::limit($base, self::USERNAME_MAX - strlen((string) $i), '') . $i;
        }

        return $username;
    }
}
