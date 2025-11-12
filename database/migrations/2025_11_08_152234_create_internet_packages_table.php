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
            $table->string('name', 100)->comment('Package name');
            $table->text('description')->nullable()->comment('Package description');
            $table->integer('bandwidth_up')->comment('Upload speed in kbps');
            $table->integer('bandwidth_down')->comment('Download speed in kbps');
            $table->decimal('price', 10, 2)->comment('Package price');
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly'])->default('monthly')->comment('Billing frequency');
            $table->boolean('is_active')->default(true)->comment('Package availability status');
            $table->integer('duration_days')->default(30)->comment('Package duration in days');
            $table->string('ip_pool', 50)->nullable()->comment('IP pool for PPPoE');
            $table->text('features')->nullable()->comment('Additional features (JSON)');
            $table->timestamps();
            
            // Indexes
            $table->index('name');
            $table->index('is_active');
            $table->index('billing_cycle');
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
