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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique()->comment('Unique invoice number');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade')->comment('Customer reference');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null')->comment('Subscription reference');
            $table->date('invoice_date')->comment('Invoice issue date');
            $table->date('due_date')->comment('Payment due date');
            $table->decimal('amount', 10, 2)->comment('Total invoice amount');
            $table->decimal('paid_amount', 10, 2)->default(0)->comment('Amount paid');
            $table->enum('status', ['unpaid', 'partial', 'paid', 'overdue'])->default('unpaid')->comment('Payment status');
            $table->text('description')->comment('Invoice description');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamps();
            
            // Indexes
            $table->index('invoice_number');
            $table->index('customer_id');
            $table->index('subscription_id');
            $table->index('status');
            $table->index(['invoice_date', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
