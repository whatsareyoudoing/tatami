<?php

namespace App\Services;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Verifikasi email dengan kode 6 digit yang dikirim ke email user.
 */
class EmailVerificationService
{
    public const EXPIRES_MINUTES = 15;
    public const MAX_ATTEMPTS = 5;
    public const RESEND_SECONDS = 60;

    // Hasil verify()
    public const VERIFIED = 'verified';
    public const INVALID = 'invalid';
    public const EXPIRED = 'expired';
    public const TOO_MANY_ATTEMPTS = 'too_many_attempts';

    /**
     * Buat kode baru (menggantikan kode lama) lalu kirim ke email user.
     */
    public function send(User $user): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->verificationCode()->updateOrCreate([], [
            'code_hash'  => Hash::make($code),
            'expires_at' => now()->addMinutes(self::EXPIRES_MINUTES),
            'attempts'   => 0,
            'sent_at'    => now(),
        ]);
        $user->unsetRelation('verificationCode');

        Mail::to($user->email)->send(new VerificationCodeMail($user, $code));
    }

    /**
     * Sisa detik sebelum boleh kirim ulang kode (0 = boleh sekarang).
     */
    public function secondsUntilResend(User $user): int
    {
        $sentAt = $user->verificationCode?->sent_at;

        return $sentAt ? max(0, self::RESEND_SECONDS - (int) $sentAt->diffInSeconds(now())) : 0;
    }

    /**
     * Cek kode. Kalau benar, email ditandai terverifikasi dan kode dihapus.
     */
    public function verify(User $user, string $code): string
    {
        $record = $user->verificationCode;

        if (!$record || $record->expires_at->isPast()) {
            return self::EXPIRED;
        }

        if ($record->attempts >= self::MAX_ATTEMPTS) {
            return self::TOO_MANY_ATTEMPTS;
        }

        if (!Hash::check($code, $record->code_hash)) {
            $record->increment('attempts');

            return $record->attempts >= self::MAX_ATTEMPTS ? self::TOO_MANY_ATTEMPTS : self::INVALID;
        }

        $user->markEmailAsVerified();
        $record->delete();

        return self::VERIFIED;
    }

    public function remainingAttempts(User $user): int
    {
        return max(0, self::MAX_ATTEMPTS - (int) $user->verificationCode?->attempts);
    }
}
