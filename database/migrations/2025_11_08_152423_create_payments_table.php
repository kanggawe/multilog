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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number', 50)->unique()->comment('Unique payment number');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade')->comment('Customer reference');
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade')->comment('Invoice reference');
            $table->decimal('amount', 10, 2)->comment('Payment amount');
            $table->enum('method', ['cash', 'bank_transfer', 'e_wallet', 'credit_card'])->default('cash')->comment('Payment method');
            $table->date('payment_date')->comment('Payment date');
            $table->string('reference_number', 100)->nullable()->comment('Transaction reference number');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null')->comment('User who received payment');
            $table->timestamps();
            
            // Indexes
            $table->index('payment_number');
            $table->index('customer_id');
            $table->index('invoice_id');
            $table->index('method');
            $table->index('payment_date');
            $table->index('received_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
