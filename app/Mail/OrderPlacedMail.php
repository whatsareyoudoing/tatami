<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Email checkout. $forAdmin = true untuk notifikasi ke toko, false untuk konfirmasi ke customer.
 */
class OrderPlacedMail extends Mailable
{
    public function __construct(
        public Order $order,
        public bool $forAdmin = false,
    ) {}

    public function envelope(): Envelope
    {
        // Reply-To: admin cukup tekan "Balas" untuk menjawab customer, dan sebaliknya
        $replyTo = $this->forAdmin
            ? [new Address($this->order->user->email, $this->order->user->name)]
            : array_filter([config('shop.order_notify_email')]);

        return new Envelope(
            subject: $this->forAdmin
                ? "Pesanan Baru {$this->order->order_number} dari {$this->order->recipient_name}"
                : "Pesanan {$this->order->order_number} Diterima - Tatami Indonesia",
            replyTo: $replyTo,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-placed');
    }
}
