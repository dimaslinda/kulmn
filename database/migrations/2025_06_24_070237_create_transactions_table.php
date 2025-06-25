<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('payment_method')->nullable(); // 'QRIS'
            $table->string('payment_status')->default('pending'); // pending, success, failed, expire
            $table->string('transaction_status')->nullable(); // pending, success, failed, expired
            $table->string('midtrans_transaction_id')->nullable(); // ID dari Midtrans
            $table->string('midtrans_order_id')->nullable(); // Order ID yang kita kirim ke Midtrans
            $table->string('midtrans_qr_code_url')->nullable(); // URL QRIS
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
