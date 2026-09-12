<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            // Customer yang melakukan servis
            $table->foreignId('customer_id')
                ->nullable()
                ->after('user_id')
                ->constrained('customers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Kendaraan milik customer
            $table->foreignId('vehicle_id')
                ->nullable()
                ->after('customer_id')
                ->constrained('vehicles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->dropForeign([
                'customer_id'
            ]);

            $table->dropForeign([
                'vehicle_id'
            ]);

            $table->dropColumn([
                'customer_id',
                'vehicle_id'
            ]);
        });
    }
};