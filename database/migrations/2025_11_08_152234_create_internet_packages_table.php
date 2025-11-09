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
        Schema::create('internet_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('bandwidth_up'); // kbps
            $table->integer('bandwidth_down'); // kbps
            $table->decimal('price', 10, 2);
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->boolean('is_active')->default(true);
            $table->integer('duration_days')->default(30); // durasi dalam hari
            $table->string('ip_pool')->nullable(); // IP pool untuk PPPoE
            $table->text('features')->nullable(); // JSON features tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internet_packages');
    }
};
