<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Email perubahan status: dikirim / dibatalkan admin (ke customer), dibatalkan customer (ke toko).
 */
class OrderStatusMail extends OrderMail
{
    public function envelope(): Envelope
    {
        $number = $this->order->order_number;

        return new Envelope(
            subject: match (true) {
                $this->forAdmin                         => "Pesanan {$number} Dibatalkan oleh Customer",
                $this->order->status === Order::DIKIRIM => "Pesanan {$number} Sudah Dikirim - Tatami Indonesia",
                default                                 => "Pesanan {$number} Dibatalkan - Tatami Indonesia",
            },
            replyTo: $this->replyToAddresses(),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-status');
    }
}
