<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'sku', 'name', 'slug', 'material',
        'color', 'cost_price', 'price', 'stock', 'minimum_stock',
        'image', 'description'
    ];

    // Relasi: Produk dimiliki oleh 1 Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Helper untuk mengecek apakah stok menipis
    public function isLowStock(): bool
    {
        return $this->stock <= $this->minimum_stock;
    }
}