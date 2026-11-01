<?php

namespace App\Http\Controllers;

use App\Exceptions\OrderException;
use App\Models\Order;
use App\Services\OrderNotifier;
use App\Services\OrderService;
use Illuminate\Http\Request;

/**
 * Checkout: keranjang -> pesanan berstatus "baru" + email ke toko dan ke customer.
 */
class CheckoutController extends Controller
{
    public function __construct(
        protected OrderService $orders,
        protected OrderNotifier $notifier,
    ) {}

    public function create(Request $request)
    {
        $user = $request->user();
        $items = $user->cartItems()
            ->with(['variant.images', 'variant.product' => fn ($q) => $q->withTrashed()->with('cover')])
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        // Ada barang tidak tersedia / melebihi stok -> perbaiki dulu di keranjang
        if ($items->contains(fn ($item) => !$item->isAvailable() || $item->exceedsStock())) {
            return redirect()->route('cart.index')->with('error', 'Ada barang di keranjang yang perlu diperbaiki sebelum checkout.');
        }

        // Isi otomatis dari pesanan terakhir
        $last = $user->orders()->latest()->first(['recipient_name', 'phone', 'address']);

        return view('checkout.create', [
            'items'    => $items,
            'total'    => $items->sum->subtotal(),
            'defaults' => [
                'recipient_name' => $last?->recipient_name ?? $user->name,
                'phone'          => $last?->phone,
                'address'        => $last?->address,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone'          => ['required', 'string', 'regex:/^[0-9+\-\s()]{8,20}$/'],
            'address'        => ['required', 'string', 'max:500'],
            'note'           => ['nullable', 'string', 'max:500'],
        ], [
            'phone.regex' => 'Nomor HP tidak valid.',
        ], [
            'recipient_name' => 'nama penerima',
            'phone'          => 'nomor HP',
            'address'        => 'alamat',
            'note'           => 'catatan',
        ]);

        try {
            $order = $this->orders->checkout($request->user(), $data);
        } catch (OrderException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        // Email gagal tidak membatalkan pesanan: pesanan tetap tersimpan dan terlihat di dashboard admin
        $this->notifier->placed($order);

        return redirect()->route('account.orders.show', $order)
            ->with('success', 'Pesanan telah di-checkout, silakan tunggu balasan admin dari email Anda.');
    }
}
