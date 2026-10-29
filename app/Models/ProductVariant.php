<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'stok'      => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function product() { return $this->belongsTo(Product::class); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('urutan'); }
}
