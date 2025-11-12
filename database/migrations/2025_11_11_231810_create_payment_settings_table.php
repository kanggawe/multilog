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
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_name', 50)->unique()->comment('Payment gateway name (e.g., tripay)');
            $table->string('environment', 20)->default('sandbox')->comment('sandbox or production');
            $table->text('api_key')->nullable()->comment('Encrypted API key');
            $table->text('private_key')->nullable()->comment('Encrypted private key');
            $table->text('merchant_code')->nullable()->comment('Encrypted merchant code');
            $table->string('callback_url', 255)->nullable()->comment('Callback URL for payment notifications');
            $table->boolean('is_active')->default(false)->comment('Gateway active status');
            $table->text('config')->nullable()->comment('Additional configuration (JSON)');
            $table->timestamps();
            
            // Indexes
            $table->index('gateway_name');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
