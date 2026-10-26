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
    Schema::create('purchase_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
        $table->foreignId('product_id')->constrained();      // Produk hijab yang dibeli
        $table->integer('quantity');                         // Jumlah pcs
        $table->decimal('unit_cost', 12, 2);                 // Harga beli per pcs
        $table->decimal('subtotal', 12, 2);                  // quantity * unit_cost
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchaseitems');
    }
};
