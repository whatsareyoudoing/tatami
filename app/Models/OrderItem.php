<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'price'    => 'integer',
            'quantity' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    public function order() { return $this->belongsTo(Order::class); }

    // Bisa null kalau variannya sudah dihapus admin (data nama/harga tetap ada di baris ini)
    public function variant() { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }

    // "Pillow Igusa (Warna: Light Blue)" atau "Tatami Table 70" untuk produk tanpa varian
    public function displayName(): string
    {
        return $this->variant_name ? "{$this->product_name} ({$this->variant_name})" : $this->product_name;
    }
}
