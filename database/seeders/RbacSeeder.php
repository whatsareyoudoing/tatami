<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    /**
     * Semua permission di aplikasi.
     */
    private const PERMISSIONS = [
        // Boleh masuk panel admin (/dashboard dan seluruh menu admin)
        'access_admin',

        'view_user', 'create_user', 'update_user', 'delete_user',
        // Menu Pelanggan: lihat daftar customer, blokir/buka blokir
        'view_customer', 'update_customer',
        'view_category', 'create_category', 'update_category', 'delete_category',
        'view_product', 'create_product', 'update_product', 'delete_product',
        'view_order', 'update_order',

        // Fitur customer di website
        'manage_cart', 'checkout',
    ];

    /**
     * Peta role -> permission. '*' = semua permission.
     * Untuk mengubah hak akses: edit di sini lalu jalankan
     * php artisan db:seed --class=RbacSeeder
     */
    private const ROLES = [
        'admin' => '*',

        // Hanya bisa menambah dan mengedit produk (view diperlukan untuk membuka daftar produk)
        'editor' => ['access_admin', 'view_product', 'create_product', 'update_product'],

        // Tidak bisa masuk admin, hanya belanja
        'customer' => ['manage_cart', 'checkout'],
    ];

    /**
     * Run the database seeds. Aman dijalankan berulang kali.
     */
    public function run(): void
    {
        foreach (self::PERMISSIONS as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach (self::ROLES as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            $role->permissions()->sync(
                $permissions === '*'
                    ? Permission::pluck('id')
                    : Permission::whereIn('name', $permissions)->pluck('id')
            );
        }

        // Akun admin awal supaya bisa login setelah instalasi baru
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'password' => 'admin', // di-hash otomatis oleh cast 'hashed' di model User
                'status' => 'aktif',
            ]
        );

        $admin->roles()->syncWithoutDetaching([Role::where('name', 'admin')->value('id')]);

        if (!$admin->hasVerifiedEmail()) {
            $admin->markEmailAsVerified();
        }
    }
}
