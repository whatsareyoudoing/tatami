<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                'password' => Hash::make('admin')
            ]
        );

        // 5. Assign role ke user
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
