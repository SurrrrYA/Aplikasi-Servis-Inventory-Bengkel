<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_items', function (Blueprint $table) {

            // Menentukan apakah item merupakan barang atau jasa
            $table->enum('type', [
                'product',
                'service',
            ])->after('project_id');

            // Relasi ke produk jika item merupakan barang
            $table->foreignId('product_id')
                ->nullable()
                ->after('type')
                ->constrained('products')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('project_items', function (Blueprint $table) {

            $table->dropForeign([
                'product_id'
            ]);

            $table->dropColumn([
                'product_id',
                'type',
            ]);
        });
    }
};