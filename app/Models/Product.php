<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'harga_dasar' => 'integer',
            'harga_coret' => 'integer',
            'is_active'   => 'boolean',
        ];
    }

    public function category() { return $this->belongsTo(Category::class); }
    public function variants() { return $this->hasMany(ProductVariant::class)->orderBy('urutan'); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('urutan'); }
}
