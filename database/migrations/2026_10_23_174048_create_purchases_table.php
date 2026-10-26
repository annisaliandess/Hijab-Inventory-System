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
    Schema::create('purchases', function (Blueprint $table) {
        $table->id();
        $table->foreignId('supplier_id')->constrained();     // Supplier asal barang
        $table->foreignId('user_id')->constrained();         // Admin/Staf yang mencatat
        $table->string('invoice_number')->unique();          // Contoh: PUR-20261024-001
        $table->date('purchase_date');                       // Tanggal pembelian
        $table->decimal('total_amount', 12, 2)->default(0);  // Total tagihan
        $table->text('notes')->nullable();
        $table->softDeletes();                               // deleted_at (data riwayat aman)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
