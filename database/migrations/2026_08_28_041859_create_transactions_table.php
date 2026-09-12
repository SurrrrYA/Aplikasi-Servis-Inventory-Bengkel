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

            // Nomor transaksi unik
            $table->string('transaction_code')->unique();

            // User/kasir yang melakukan transaksi
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Total harga seluruh barang
            $table->decimal('total_amount', 15, 2);

            // Uang yang dibayarkan
            $table->decimal('paid_amount', 15, 2);

            // Uang kembalian
            $table->decimal('change_amount', 15, 2)->default(0);

            // Metode pembayaran
            $table->enum('payment_method', [
                'cash',
                'transfer',
                'qris'
            ])->default('cash');

            // Status transaksi
            $table->enum('status', [
                'completed',
                'cancelled'
            ])->default('completed');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};