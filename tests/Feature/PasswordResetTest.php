<?php

namespace Tests\Feature;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_link_from_email_lets_the_user_set_a_new_password(): void
    {
        Mail::fake();
        $user = User::factory()->create(['email' => 'budi@example.com']);

        $this->post('/lupa-password', ['email' => 'budi@example.com']);

        $sent = null;
        Mail::assertSent(PasswordResetMail::class, function (PasswordResetMail $mail) use (&$sent) {
            $sent = $mail;

            return $mail->hasTo('budi@example.com');
        });
        $sent->assertSeeInHtml($sent->url); // email benar-benar berisi link reset
        $this->get($sent->url)->assertOk();
        preg_match('#/atur-ulang-password/([^?]+)#', $sent->url, $token);

        $this->followingRedirects()
            ->post('/atur-ulang-password', [
                'token'                 => $token[1],
                'email'                 => 'budi@example.com',
                'password'              => 'passwordbaru1',
                'password_confirmation' => 'passwordbaru1',
            ])
            ->assertSee('Password berhasil diganti');

        $this->assertTrue(Hash::check('passwordbaru1', $user->fresh()->password));
    }

    public function test_unknown_email_gets_the_same_answer_and_no_email(): void
    {
        Mail::fake();
        User::factory()->create(['email' => 'budi@example.com']);

        $this->post('/lupa-password', ['email' => 'budi@example.com']);
        $answerForRegistered = session('success');
        $this->post('/lupa-password', ['email' => 'tidakada@example.com']);

        // Jawaban sama, jadi orang lain tidak bisa mengecek email mana yang terdaftar
        $this->assertNotEmpty($answerForRegistered);
        $this->assertSame($answerForRegistered, session('success'));
        Mail::assertSent(PasswordResetMail::class, 1);
    }

    public function test_wrong_token_does_not_change_the_password(): void
    {
        $user = User::factory()->create(['email' => 'budi@example.com']);

        $this->post('/atur-ulang-password', [
            'token'                 => 'token-palsu',
            'email'                 => 'budi@example.com',
            'password'              => 'passwordbaru1',
            'password_confirmation' => 'passwordbaru1',
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('password', $user->fresh()->password)); // password bawaan UserFactory
    }
}
