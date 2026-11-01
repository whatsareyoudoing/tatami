<?php

namespace App\Http\Controllers\Backend;

use App\Exceptions\OrderException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderNotifier;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

/**
 * Perubahan status pesanan dari popup di dashboard.
 * Kalau gagal, popup pesanan yang sama dibuka lagi (session 'open_order') beserta pesan errornya.
 */
class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('can:update_order')];
    }

    public function __construct(
        protected OrderService $orders,
        protected OrderNotifier $notifier,
    ) {}

    // Isi kurir + no. resi, tandai dikirim -> stok berkurang otomatis
    public function ship(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'courier'         => ['required', 'string', 'max:50'],
            'tracking_number' => ['required', 'string', 'max:100'],
        ], [
            'courier.required'         => 'Kurir wajib diisi.',
            'tracking_number.required' => 'No. resi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return $this->backToOrder($order)->withErrors($validator)->withInput();
        }

        // Berhasil -> kurir + no. resi dikirim ke email customer
        $data = $validator->validated();

        return $this->run($order, fn () => $this->notifier->shipped($this->orders->ship($order, $data['courier'], $data['tracking_number'])),
            "Pesanan {$order->order_number} ditandai dikirim. Stok sudah dikurangi.");
    }

    public function complete(Order $order)
    {
        // Tanpa email ke customer
        return $this->run($order, function () use ($order) {
            $this->orders->complete($order);
        }, "Pesanan {$order->order_number} ditandai selesai.");
    }

    // Alasan opsional, ikut dikirim ke email customer
    public function cancel(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'cancel_reason' => ['nullable', 'string', 'max:255'],
        ], [], ['cancel_reason' => 'alasan pembatalan']);

        if ($validator->fails()) {
            return $this->backToOrder($order)->withErrors($validator)->withInput();
        }

        $reason = $validator->validated()['cancel_reason'] ?? null;

        return $this->run($order, fn () => $this->notifier->cancelled($this->orders->cancel($order, $reason), byCustomer: false),
            "Pesanan {$order->order_number} dibatalkan.");
    }

    /**
     * $action mengembalikan true/false kalau ada email ke customer (terkirim/gagal), null kalau tidak ada email.
     */
    private function run(Order $order, callable $action, string $successMessage)
    {
        try {
            $mailSent = $action();
        } catch (OrderException $e) {
            return $this->backToOrder($order)->with('error', $e->getMessage());
        }

        $successMessage .= match ($mailSent) {
            true    => ' Customer sudah dikabari lewat email.',
            false   => ' Tetapi email ke customer gagal terkirim (lihat storage/logs/laravel.log), kabari customer secara manual.',
            default => '',
        };

        return back()->with('success', $successMessage);
    }

    private function backToOrder(Order $order)
    {
        return back()->with('open_order', $order->id);
    }
}
