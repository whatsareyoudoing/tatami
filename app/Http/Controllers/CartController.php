<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Keranjang customer. Disimpan di database, wajib login + email terverifikasi.
 * Stok hanya dicek (tidak dikurangi): stok dikurangi manual oleh admin setelah pembayaran jelas.
 */
class CartController extends Controller
{
    public const MAX_QUANTITY = 99;

    public function index(Request $request)
    {
        $items = $request->user()->cartItems()
            ->with(['variant.images', 'variant.product' => fn ($q) => $q->withTrashed()->with('cover')])
            ->latest()
            ->get();

        $available = $items->filter->isAvailable();

        return view('cart.index', [
            'items'      => $items,
            'total'      => $available->sum->subtotal(),
            'totalItems' => $available->sum('quantity'),
            'hasProblem' => $items->contains(fn ($item) => !$item->isAvailable() || $item->exceedsStock()),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity'           => ['required', 'integer', 'min:1', 'max:' . self::MAX_QUANTITY],
        ], [
            'product_variant_id.required' => 'Pilih varian terlebih dahulu.',
        ]);

        $variant = ProductVariant::with('product')->findOrFail($data['product_variant_id']);
        $product = $variant->product;

        if (!$product || !$product->is_active || !$variant->is_active) {
            throw ValidationException::withMessages(['product_variant_id' => 'Produk ini sedang tidak tersedia.']);
        }

        $item = $request->user()->cartItems()->firstOrNew(['product_variant_id' => $variant->id]);
        $newQuantity = (int) $item->quantity + $data['quantity'];

        $this->ensureStock($variant, $newQuantity, (int) $item->quantity);

        $item->quantity = $newQuantity;
        $item->save();

        return redirect()->to(route('shop.show', $product->slug) . '#beli')
            ->with('success', "{$variant->fullName()} ditambahkan ke keranjang.");
    }

    public function update(Request $request, CartItem $item)
    {
        $this->ensureOwner($request, $item);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . self::MAX_QUANTITY],
        ]);

        $this->ensureStock($item->variant, $data['quantity']);
        $item->update(['quantity' => $data['quantity']]);

        return redirect()->route('cart.index')->with('success', 'Jumlah diperbarui.');
    }

    public function destroy(Request $request, CartItem $item)
    {
        $this->ensureOwner($request, $item);
        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    private function ensureOwner(Request $request, CartItem $item): void
    {
        // Barang milik user lain dianggap tidak ada
        abort_unless($item->user_id === $request->user()->id, 404);
    }

    private function ensureStock(ProductVariant $variant, int $quantity, int $alreadyInCart = 0): void
    {
        $variant->loadMissing('product');
        $name = $variant->fullName();

        if ($variant->stok < 1) {
            throw ValidationException::withMessages(['quantity' => "Stok {$name} sedang habis."]);
        }

        if ($quantity > $variant->stok) {
            $message = "Stok {$name} tinggal {$variant->stok}.";
            if ($alreadyInCart > 0) {
                $message .= " Di keranjang Anda sudah ada {$alreadyInCart}.";
            }
            throw ValidationException::withMessages(['quantity' => $message]);
        }
    }
}
