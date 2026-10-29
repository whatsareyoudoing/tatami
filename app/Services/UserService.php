<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'username' => $data['username'],
                'password' => $data['password'], // di-hash otomatis oleh cast 'hashed' di model User
            ]);

            if (!empty($data['role'])) {
                $user->roles()->attach($data['role']);
            }

            return $user;
        });
    }

    public function updateUser(array $data, User $user): User
    {
       return DB::transaction(function () use ($data, $user) {
            // 1. Siapkan data yang akan di-update
            $updateData = [
                'name'     => $data['name'],
                'email'    => $data['email'],
                'username' => $data['username'],
            ];


            // 2. Cek password jika diisi (array PHP menggunakan !empty)
            if (!empty($data['password'])) {
                $updateData['password'] = $data['password'];
            }

            // 3. Jalankan query update ke tabel users
            $user->update($updateData);

            // 4. Sync role di tabel pivot (sesuaikan key 'role' / 'role_id')
            $roleId = $data['role_id'] ?? $data['role'] ?? null;
            if ($roleId) {
                $user->roles()->sync([$roleId]);
            }

            // 5. Kembalikan instance $user agar sesuai return type : User
            return $user;
        });
    }

    public function deleteUser(User $user): bool
    {
        $suffix = '_nonaktif_' . time();

        // Ubah status dan "bebaskan" username (serta email)
        return $user->update([
            'status' => 'nonaktif',
            'username' => $user->username . $suffix,
            'email' => $user->email . $suffix,
        ]);
    }
}
