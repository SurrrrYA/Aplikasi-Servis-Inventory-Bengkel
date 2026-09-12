<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();

            $table->string('name');

            $table->string('customer_name')->nullable();

            $table->decimal('total', 15, 2)->default(0);

            $table->enum('status', [
                'draft',
                'process',
                'completed',
                'cancelled'
            ])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};