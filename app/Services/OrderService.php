<?php

namespace App\Services;

use App\Exceptions\OrderException;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Alur pesanan: checkout (baru) -> dikirim (stok berkurang) -> selesai.
 * Pesanan "baru" bisa dibatalkan (stok belum dipotong, jadi tidak ada yang dikembalikan).
 */
class OrderService
{
    /**
     * Ubah isi keranjang menjadi pesanan, lalu kosongkan keranjang.
     * Stok hanya dicek, belum dikurangi.
     */
    public function checkout(User $user, array $shipping): Order
    {
        return DB::transaction(function () use ($user, $shipping) {
            $cartItems = $user->cartItems()
                ->with(['variant.product' => fn ($q) => $q->withTrashed()])
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw new OrderException('Keranjang Anda kosong.');
            }

            foreach ($cartItems as $item) {
                $name = $item->variant?->product?->nama ?? 'Produk';

                if (!$item->isAvailable()) {
                    throw new OrderException("{$name} sudah tidak tersedia. Hapus dari keranjang terlebih dahulu.");
                }
                if ($item->exceedsStock()) {
                    throw new OrderException("Stok {$item->variant->fullName()} tinggal {$item->variant->stok}. Kurangi jumlahnya di keranjang.");
                }
            }

            $order = $user->orders()->create([
                'status'         => Order::BARU,
                'recipient_name' => $shipping['recipient_name'],
                'phone'          => $shipping['phone'],
                'address'        => $shipping['address'],
                'note'           => $shipping['note'] ?? null,
                'total'          => $cartItems->sum->subtotal(),
            ]);
            $order->update(['order_number' => sprintf('INV-%s-%06d', now()->format('Ymd'), $order->id)]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_variant_id' => $item->variant->id,
                    'product_name'       => $item->variant->product->nama,
                    // "Warna: Light Blue", atau null untuk produk tanpa varian
                    'variant_name'       => $item->variant->optionLabel(),
                    'price'              => $item->variant->harga,
                    'quantity'           => $item->quantity,
                    'subtotal'           => $item->subtotal(),
                ]);
            }

            $user->cartItems()->delete();

            return $order->load('items');
        });
    }

    /**
     * Tandai dikirim + isi no. resi + kurangi stok setiap warna.
     * Gagal (tanpa perubahan apa pun) kalau ada stok yang tidak cukup.
     */
    public function ship(Order $order, string $trackingNumber): Order
    {
        return DB::transaction(function () use ($order, $trackingNumber) {
            // Ambil ulang dengan kunci baris: dua admin yang menekan "Dikirim" bersamaan tidak memotong stok dua kali
            $order = Order::with('items')->lockForUpdate()->findOrFail($order->id);

            if ($order->status !== Order::BARU) {
                throw new OrderException("Pesanan {$order->order_number} berstatus {$order->status_label}, tidak bisa ditandai dikirim.");
            }

            if (!$order->stock_deducted_at) {
                $variants = ProductVariant::whereKey($order->items->pluck('product_variant_id')->filter())
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($order->items as $item) {
                    $variant = $variants->get($item->product_variant_id);

                    if (!$variant) {
                        throw new OrderException("{$item->displayName()} sudah dihapus dari produk, stok tidak bisa dikurangi.");
                    }
                    if ($variant->stok < $item->quantity) {
                        throw new OrderException("Stok {$item->displayName()} tinggal {$variant->stok}, pesanan ini butuh {$item->quantity}. Perbarui stok produk terlebih dahulu.");
                    }
                }

                foreach ($order->items as $item) {
                    $variants->get($item->product_variant_id)->decrement('stok', $item->quantity);
                }
            }

            $order->update([
                'status'            => Order::DIKIRIM,
                'tracking_number'   => $trackingNumber,
                'shipped_at'        => now(),
                'stock_deducted_at' => $order->stock_deducted_at ?? now(),
            ]);

            return $order;
        });
    }

    public function complete(Order $order): Order
    {
        return $this->changeStatus($order, Order::DIKIRIM, Order::SELESAI, 'completed_at', 'ditandai selesai');
    }

    public function cancel(Order $order): Order
    {
        return $this->changeStatus($order, Order::BARU, Order::DIBATALKAN, 'cancelled_at', 'dibatalkan');
    }

    private function changeStatus(Order $order, string $from, string $to, string $timestampColumn, string $action): Order
    {
        return DB::transaction(function () use ($order, $from, $to, $timestampColumn, $action) {
            $order = Order::lockForUpdate()->findOrFail($order->id);

            if ($order->status !== $from) {
                throw new OrderException("Pesanan {$order->order_number} berstatus {$order->status_label}, tidak bisa {$action}.");
            }

            $order->update(['status' => $to, $timestampColumn => now()]);

            return $order;
        });
    }
}
