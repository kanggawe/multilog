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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade')->comment('Customer reference');
            $table->foreignId('internet_package_id')->constrained()->onDelete('cascade')->comment('Internet package reference');
            $table->date('start_date')->comment('Subscription start date');
            $table->date('end_date')->comment('Subscription end date');
            $table->decimal('monthly_fee', 10, 2)->comment('Monthly subscription fee');
            $table->enum('status', ['active', 'inactive', 'suspended', 'expired'])->default('active')->comment('Subscription status');
            $table->boolean('auto_renew')->default(true)->comment('Auto renewal enabled');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamps();
            
            // Indexes
            $table->index('customer_id');
            $table->index('internet_package_id');
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
