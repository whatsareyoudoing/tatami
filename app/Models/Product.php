<?php

namespace App\Models;

use App\Support\YouTube;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Harga, stok, dan SKU ada di varian. Produk tanpa varian (has_variants = false)
 * tetap punya 1 varian "Default" yang tidak ditampilkan ke pembeli.
 */
class Product extends Model
{
    use SoftDeletes;

    public const DEFAULT_VARIANT = 'Default';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_active'    => 'boolean',
            'has_variants' => 'boolean',
        ];
    }

    public function category() { return $this->belongsTo(Category::class); }
    public function variants() { return $this->hasMany(ProductVariant::class)->orderBy('urutan'); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('urutan'); }

    // Varian yang dijual di website (yang tidak dicentang "Aktif" di admin disembunyikan)
    public function activeVariants() { return $this->variants()->where('is_active', true); }

    // Foto utama saja (urutan terkecil), supaya kartu produk tidak memuat semua foto
    public function cover() { return $this->hasOne(ProductImage::class)->ofMany('urutan', 'min'); }

    // Product::active() = boleh tampil di website
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    // Data yang dibutuhkan <x-product-card>: kategori, foto utama, varian aktif (harga & stok),
    // plus harga_min untuk urutan termurah/termahal
    public function scopeForCard(Builder $query): void
    {
        $query->with(['category:id,name,slug', 'cover', 'activeVariants:id,product_id,harga,harga_coret,stok,urutan,is_active'])
            ->withMin(['variants as harga_min' => fn ($q) => $q->where('is_active', true)], 'harga');
    }

    // Varian pertama: untuk produk tanpa varian, inilah tempat harga/stok/SKU-nya
    public function defaultVariant(): ?ProductVariant
    {
        return $this->variants->first();
    }

    // ---- Dihitung dari varian aktif (relasi activeVariants harus dimuat) ----

    protected function hargaMin(): Attribute
    {
        return Attribute::get(fn ($value) => $value !== null ? (int) $value : $this->activeVariants->min('harga'));
    }

    protected function hargaMax(): Attribute
    {
        return Attribute::get(fn () => $this->activeVariants->max('harga'));
    }

    // Harga coret ditampilkan di kartu hanya kalau semua varian aktif harganya sama
    protected function hargaCoret(): Attribute
    {
        return Attribute::get(function () {
            $variants = $this->activeVariants;
            $coret = $variants->pluck('harga_coret')->unique();

            return $this->harga_min === $this->harga_max && $coret->count() === 1 ? $coret->first() : null;
        });
    }

    // Diskon terbesar di antara varian aktif, misalnya 230.000 -> 193.000 = 16
    protected function diskonPersen(): Attribute
    {
        return Attribute::get(fn () => $this->activeVariants->map->diskon_persen->filter()->max());
    }

    protected function stokTersedia(): Attribute
    {
        return Attribute::get(fn () => (int) $this->activeVariants->sum('stok'));
    }

    // ---- Video & gambar ----

    protected function youtubeThumbnail(): Attribute
    {
        return Attribute::get(fn () => $this->youtube_id ? YouTube::thumbnailUrl($this->youtube_id) : null);
    }

    // Gambar kartu: foto pertama, atau thumbnail video kalau produk hanya punya video
    protected function coverUrl(): Attribute
    {
        return Attribute::get(fn () => $this->cover?->thumb_url ?? $this->youtube_thumbnail);
    }
}
