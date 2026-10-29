<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    protected $fillable = ['name'];

    // Role pembeli. Customer dikelola di menu Pelanggan, bukan di menu Pengguna.
    public const CUSTOMER = 'customer';

    // Role::staff() = semua role selain customer (admin, editor, ...)
    public function scopeStaff(Builder $query): void
    {
        $query->where('name', '!=', self::CUSTOMER);
    }

    // Nama untuk tampilan: "editor" -> "Editor"
    protected function label(): Attribute
    {
        return Attribute::get(fn () => Str::headline($this->name));
    }

    // 🔗 RELATION
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}
