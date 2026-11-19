<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RbacSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RbacSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_admin_gets_a_random_password_shown_once_in_the_terminal(): void
    {
        Artisan::call('db:seed', ['--class' => RbacSeeder::class]);
        preg_match('/Password admin awal: (\S+)/', Artisan::output(), $printed);

        $admin = User::where('username', 'admin')->firstOrFail();

        $this->assertNotEmpty($printed[1] ?? null, 'Password admin awal harus tampil di terminal');
        $this->assertTrue(Hash::check($printed[1], $admin->password));
        $this->assertFalse(Hash::check('admin', $admin->password));
    }
}
