<?php

namespace App\Models;

use App\Services\EmailVerificationService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// MustVerifyEmail: route dengan middleware 'verified' hanya bisa dibuka setelah email_verified_at terisi
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 🔗 RELATION
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function verificationCode()
    {
        return $this->hasOne(EmailVerificationCode::class);
    }

    // Bawaan Laravel mengirim link verifikasi; aplikasi ini memakai kode 6 digit
    public function sendEmailVerificationNotification(): void
    {
        app(EmailVerificationService::class)->send($this);
    }

    // 🔐 PERMISSION CHECK
    public function hasPermission(string $permission): bool
    {
        return $this->roles
            ->flatMap(fn($role) => $role->permissions)
            ->pluck('name')
            ->contains($permission);
    }

    // 🔐 ROLE CHECK (optional tapi berguna)
    public function hasRole(string $role): bool
    {
        return $this->roles
            ->pluck('name')
            ->contains($role);
    }

    // User::customers() = pembeli, User::staff() = selain pembeli (admin, editor, atau belum punya role)
    public function scopeCustomers(Builder $query): void
    {
        $query->whereHas('roles', fn ($q) => $q->where('name', Role::CUSTOMER));
    }

    public function scopeStaff(Builder $query): void
    {
        $query->whereDoesntHave('roles', fn ($q) => $q->where('name', Role::CUSTOMER));
    }

    public function isCustomer(): bool
    {
        return $this->hasRole(Role::CUSTOMER);
    }

    // 🔐 Admin aktif terakhir tidak boleh dinonaktifkan/diturunkan, supaya sistem tidak terkunci tanpa admin
    public function isLastActiveAdmin(): bool
    {
        return $this->hasRole('admin')
            && static::where('status', 'aktif')
                ->whereHas('roles', fn ($query) => $query->where('name', 'admin'))
                ->count() <= 1;
    }
}
