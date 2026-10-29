<?php

namespace App\Mail;

use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class VerificationCodeMail extends Mailable
{
    public function __construct(
        public User $user,
        public string $code,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Email - Tatami Indonesia',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verification-code',
            with: ['expiresMinutes' => EmailVerificationService::EXPIRES_MINUTES],
        );
    }
}
