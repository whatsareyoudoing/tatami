<?php

namespace App\Http\Controllers;

use App\Exceptions\OrderException;
use App\Models\Order;
use App\Services\OrderNotifier;
use App\Services\OrderService;
use Illuminate\Http\Request;

/**
 * "Pesanan Saya" di halaman akun customer.
 */
class AccountOrderController extends Controller
{
    public function __construct(
        protected OrderService $orders,
        protected OrderNotifier $notifier,
    ) {}

    public function index(Request $request)
    {
        $orders = $request->user()->orders()->withCount('items')->latest()->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        $this->ensureOwner($request, $order);
        $order->load('items.variant.images');

        return view('account.order-show', compact('order'));
    }

    // Customer hanya bisa membatalkan selama status masih "baru"
    public function cancel(Request $request, Order $order)
    {
        $this->ensureOwner($request, $order);

        try {
            $cancelled = $this->orders->cancel($order);
        } catch (OrderException $e) {
            return back()->with('error', $e->getMessage());
        }

        // Admin dikabari supaya pesanan ini tidak diproses
        $this->notifier->cancelled($cancelled, byCustomer: true);

        return redirect()->route('account.orders.show', $order)->with('success', 'Pesanan berhasil dibatalkan.');
    }

    private function ensureOwner(Request $request, Order $order): void
    {
        // Pesanan orang lain dianggap tidak ada
        abort_unless($order->user_id === $request->user()->id, 404);
    }
}
