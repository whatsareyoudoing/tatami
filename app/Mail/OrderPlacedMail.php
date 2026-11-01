<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Email checkout: notifikasi ke toko, atau konfirmasi ke customer.
 */
class OrderPlacedMail extends OrderMail
{
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->forAdmin
                ? "Pesanan Baru {$this->order->order_number} dari {$this->order->recipient_name}"
                : "Pesanan {$this->order->order_number} Diterima - Tatami Indonesia",
            replyTo: $this->replyToAddresses(),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-placed');
    }
}
