<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'harga'       => 'integer',
            'harga_coret' => 'integer',
            'stok'        => 'integer',
            'is_active'   => 'boolean',
        ];
    }

    public function product() { return $this->belongsTo(Product::class); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('urutan'); }

    // Persen diskon dari harga coret, misalnya 230.000 -> 193.000 = 16
    protected function diskonPersen(): Attribute
    {
        return Attribute::get(fn () => $this->harga_coret > $this->harga
            ? (int) round(($this->harga_coret - $this->harga) / $this->harga_coret * 100)
            : null);
    }

    // "Warna: Light Blue" untuk ditampilkan/disimpan di pesanan; null untuk produk tanpa varian.
    // Butuh relasi product.
    public function optionLabel(): ?string
    {
        if (!$this->product?->has_variants) {
            return null;
        }

        return ($this->product->variant_label ? "{$this->product->variant_label}: " : '') . $this->nama;
    }

    // "Pillow Igusa (Light Blue)" atau "Tatami Table 70" untuk pesan error stok
    public function fullName(): string
    {
        $name = $this->product?->nama ?? 'Produk';

        return $this->product?->has_variants ? "{$name} ({$this->nama})" : $name;
    }
}
