<?php

namespace App\Http\Controllers\Backend;

use App\Exceptions\OrderException;
use App\Http\Controllers\Controller;
use App\Models\Order;
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
    ) {}

    // Isi no. resi + tandai dikirim -> stok berkurang otomatis
    public function ship(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'tracking_number' => ['required', 'string', 'max:100'],
        ], [
            'tracking_number.required' => 'No. resi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return $this->backToOrder($order)->withErrors($validator)->withInput();
        }

        return $this->run($order, fn () => $this->orders->ship($order, trim($request->input('tracking_number'))),
            "Pesanan {$order->order_number} ditandai dikirim. Stok sudah dikurangi.");
    }

    public function complete(Order $order)
    {
        return $this->run($order, fn () => $this->orders->complete($order),
            "Pesanan {$order->order_number} ditandai selesai.");
    }

    public function cancel(Order $order)
    {
        return $this->run($order, fn () => $this->orders->cancel($order),
            "Pesanan {$order->order_number} dibatalkan.");
    }

    private function run(Order $order, callable $action, string $successMessage)
    {
        try {
            $action();
        } catch (OrderException $e) {
            return $this->backToOrder($order)->with('error', $e->getMessage());
        }

        return back()->with('success', $successMessage);
    }

    private function backToOrder(Order $order)
    {
        return back()->with('open_order', $order->id);
    }
}
