<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer' || $this->role === 'pelanggan';
    }

    public function isPelanggan(): bool
    {
        return $this->isCustomer();
    }

    // Penjualan yang diproses sebagai staf/kasir
    public function sales()
    {
        return $this->hasMany(Sale::class, 'user_id');
    }

    // Transaksi belanja sebagai pelanggan (Customer)
    public function salesAsCustomer()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
