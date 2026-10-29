<?php

namespace App\Models;

use App\Services\ImageOptimizer;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $guarded = ['id'];
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }

    // URL publik gambar, dipakai di view: $image->url
    protected function url(): Attribute
    {
        return Attribute::get(fn () => asset('storage/' . $this->image));
    }

    // URL thumbnail (maks 300px) untuk tampilan kecil: $image->thumb_url
    // Gambar lama (sebelum ada optimizer, bukan .webp) belum punya thumbnail, jadi pakai gambar aslinya.
    protected function thumbUrl(): Attribute
    {
        return Attribute::get(fn () => str_ends_with($this->image, '.webp')
            ? asset('storage/' . ImageOptimizer::thumbnailPath($this->image))
            : $this->url);
    }
}
