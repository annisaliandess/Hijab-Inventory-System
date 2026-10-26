<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_id',
        'invoice_number',
        'customer_name',
        'sale_date',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    // Kasir / Staf yang memproses transaksi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Pelanggan (Customer) yang memesan
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Rincian item produk yang dibeli
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
