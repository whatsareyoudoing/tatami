<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['quantity' => 'integer'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }

    /**
     * Masih bisa dibeli? Produk bisa saja dinonaktifkan/dihapus admin setelah masuk keranjang.
     * Butuh relasi variant.product dimuat dengan withTrashed().
     */
    public function isAvailable(): bool
    {
        $product = $this->variant?->product;

        return $this->variant?->is_active
            && $product
            && !$product->trashed()
            && $product->is_active;
    }

    public function exceedsStock(): bool
    {
        return $this->quantity > (int) $this->variant?->stok;
    }

    public function subtotal(): int
    {
        return $this->quantity * (int) $this->variant?->harga;
    }
}
