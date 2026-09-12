<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();

            // Transaksi induk
            $table->foreignId('transaction_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Barang yang dibeli
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Harga barang saat transaksi
            $table->decimal('price', 15, 2);

            // Jumlah barang
            $table->integer('quantity');

            // Total per item
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};