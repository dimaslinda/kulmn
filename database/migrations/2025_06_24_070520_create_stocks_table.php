<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();

            $table->unique(['product_id', 'branch_id']); // Stok unik per produk per cabang
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};