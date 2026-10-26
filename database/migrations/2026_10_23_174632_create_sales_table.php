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
    Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained(); // Kasir yang melayani
        $table->string('invoice_number')->unique();              // Contoh: INV-20261024-001
        $table->string('customer_name')->default('Umum');        // Nama pembeli
        $table->date('sale_date');                               // Tanggal nota
        $table->decimal('total_amount', 12, 2)->default(0);      // Total belanja
        $table->string('payment_method')->default('cash');       // cash, transfer, qris
        $table->text('notes')->nullable();
        $table->softDeletes();                                   // Soft delete
        $table->timestamps();
    });
} 


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
