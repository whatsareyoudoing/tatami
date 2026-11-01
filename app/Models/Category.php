<?php

namespace App\Models;

use App\Services\ImageOptimizer;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];
    public function products() { return $this->hasMany(Product::class); }

    // URL gambar kategori ukuran kecil (maks 300px): $category->thumb_url, null kalau belum ada gambar
    protected function thumbUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? asset('storage/' . ImageOptimizer::thumbnailPath($this->image)) : null);
    }
}
