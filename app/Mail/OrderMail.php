<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;

/**
 * Dasar email pesanan. $forAdmin = true untuk email ke toko, false untuk email ke customer.
 */
abstract class OrderMail extends Mailable
{
    public function __construct(
        public Order $order,
        public bool $forAdmin = false,
    ) {}

    /**
     * Reply-To: admin cukup tekan "Balas" untuk menjawab customer, dan sebaliknya.
     */
    protected function replyToAddresses(): array
    {
        return $this->forAdmin
            ? [new Address($this->order->user->email, $this->order->user->name)]
            : array_filter([config('shop.order_notify_email')]);
    }
}
