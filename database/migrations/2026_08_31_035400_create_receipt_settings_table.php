<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipt_settings', function (Blueprint $table) {
            $table->id();

            $table->string('business_name')
                ->default('BENGKEL SEMPOELOER');

            $table->string('subtitle')
                ->nullable();

            $table->text('address')
                ->nullable();

            $table->string('phone')
                ->nullable();

            $table->boolean('show_cashier')
                ->default(true);

            $table->boolean('show_customer')
                ->default(true);

            $table->boolean('show_customer_phone')
                ->default(true);

            $table->boolean('show_vehicle')
                ->default(true);

            $table->boolean('show_notes')
                ->default(true);

            $table->text('footer')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipt_settings');
    }
};