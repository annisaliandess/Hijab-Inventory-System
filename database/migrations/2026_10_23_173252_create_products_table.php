<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        // Foreign Key yang menghubungkan produk ke kategori
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->string('sku')->unique();             // Contoh: HJB-PAS-001
        $table->string('name');                      // Nama: Pashmina Silk Cradenza
        $table->string('slug');                      // URL: pashmina-silk-cradenza
        $table->string('material')->nullable();      // Bahan: Ceruty, Voal, Silk
        $table->string('color')->nullable();         // Warna: Sage Green, Dusty Rose
        $table->decimal('cost_price', 12, 2);        // Harga modal dari supplier
        $table->decimal('price', 12, 2);             // Harga jual ke pelanggan
        $table->integer('stock')->default(0);        // Stok saat ini
        $table->integer('minimum_stock')->default(5);// Batas stok kritis/menipis
        $table->string('image')->nullable();         // Foto produk
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
