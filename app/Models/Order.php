<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Alur status: baru -> dikirim -> selesai. Pesanan "baru" bisa dibatalkan.
    public const BARU = 'baru';
    public const DIKIRIM = 'dikirim';
    public const SELESAI = 'selesai';
    public const DIBATALKAN = 'dibatalkan';

    // Status yang dihitung sebagai pendapatan
    public const PAID = [self::DIKIRIM, self::SELESAI];

    private const LABELS = [
        self::BARU       => ['Baru', 'warning'],
        self::DIKIRIM    => ['Dikirim', 'primary'],
        self::SELESAI    => ['Selesai', 'success'],
        self::DIBATALKAN => ['Dibatalkan', 'secondary'],
    ];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'total'             => 'integer',
            'shipped_at'        => 'datetime',
            'completed_at'      => 'datetime',
            'cancelled_at'      => 'datetime',
            'stock_deducted_at' => 'datetime',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }

    // "Baru", "Dikirim", ...
    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => self::LABELS[$this->status][0] ?? $this->status);
    }

    // Warna badge Bootstrap: warning, primary, success, secondary
    protected function statusColor(): Attribute
    {
        return Attribute::get(fn () => self::LABELS[$this->status][1] ?? 'secondary');
    }

    public function isCancellable(): bool
    {
        return $this->status === self::BARU;
    }
}
