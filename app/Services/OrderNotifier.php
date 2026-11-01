<?php

namespace App\Services;

use App\Mail\OrderMail;
use App\Mail\OrderPlacedMail;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Semua email pesanan. Dikirim langsung (tanpa queue, cocok untuk shared hosting).
 * Email gagal tidak membatalkan apa pun: pesanan/status tetap tersimpan, errornya dicatat di laravel.log.
 */
class OrderNotifier
{
    // Checkout: notifikasi ke toko + konfirmasi ke customer
    public function placed(Order $order): void
    {
        $this->toShop(new OrderPlacedMail($order, forAdmin: true));
        $this->toCustomer(new OrderPlacedMail($order));
    }

    // Admin menandai dikirim: no. resi ke customer. Hasil: email terkirim atau tidak.
    public function shipped(Order $order): bool
    {
        return $this->toCustomer(new OrderStatusMail($order));
    }

    // Dibatalkan admin -> kabari customer; dibatalkan customer sendiri -> kabari toko
    public function cancelled(Order $order, bool $byCustomer): bool
    {
        return $byCustomer
            ? $this->toShop(new OrderStatusMail($order, forAdmin: true))
            : $this->toCustomer(new OrderStatusMail($order));
    }

    private function toShop(OrderMail $mail): bool
    {
        // ORDER_NOTIFY_EMAIL kosong = notifikasi ke toko dimatikan
        $address = config('shop.order_notify_email');

        return $address ? $this->send($address, $mail, 'toko') : false;
    }

    private function toCustomer(OrderMail $mail): bool
    {
        return $this->send($mail->order->user->email, $mail, 'customer');
    }

    private function send(string $address, OrderMail $mail, string $to): bool
    {
        $mail->order->loadMissing('items', 'user');

        try {
            Mail::to($address)->send($mail);

            return true;
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email pesanan ke {$to}", [
                'order' => $mail->order->order_number,
                'email' => class_basename($mail),
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
