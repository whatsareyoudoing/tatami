<?php

namespace App\Models;

use App\Services\ImageOptimizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function author() { return $this->belongsTo(User::class, 'user_id'); }

    // Produk yang dicantumkan, sesuai urutan yang dipilih admin
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('urutan')->orderByPivot('urutan');
    }

    // Yang tampil di website: sudah diterbitkan dan tanggal terbitnya sudah lewat
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true)->where('published_at', '<=', now());
    }

    // URL sampul ukuran kecil (maks 300px) untuk daftar: $article->sampul_thumb_url
    protected function sampulThumbUrl(): Attribute
    {
        return Attribute::get(fn () => $this->sampul ? asset('storage/' . ImageOptimizer::thumbnailPath($this->sampul)) : null);
    }

    // URL sampul ukuran penuh untuk halaman artikel: $article->sampul_url
    protected function sampulUrl(): Attribute
    {
        return Attribute::get(fn () => $this->sampul ? asset('storage/' . $this->sampul) : null);
    }
}
