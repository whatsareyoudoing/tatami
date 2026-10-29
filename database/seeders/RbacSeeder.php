<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create permissions
        $permissions = [
            'create_user',
            'update_user',
            'delete_user',
            'view_user',

            'create_category',
            'update_category',
            'delete_category',
            'view_category',

            'create_product',
            'update_product',
            'delete_product',
            'view_product',


        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 2. Create admin role
        $adminRole = Role::firstOrCreate([
            'name' => 'admin'
        ]);

        // 3. Assign all permissions to admin
        $adminRole->permissions()->sync(
            Permission::pluck('id')->toArray()
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'username' =>'admin',
                'password' => 'admin', // di-hash otomatis oleh cast 'hashed' di model User
                'status' =>'aktif'
            ]
        );

        // 5. Assign role ke user
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
