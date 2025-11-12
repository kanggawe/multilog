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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 20)->unique()->comment('Unique customer code');
            $table->string('name', 100)->comment('Customer name');
            $table->text('address')->comment('Customer address');
            $table->string('phone', 20)->nullable()->comment('Contact phone');
            $table->string('email', 100)->nullable()->comment('Contact email');
            $table->string('id_card', 20)->nullable()->comment('ID card number');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->comment('Customer status');
            $table->date('join_date')->default(now())->comment('Registration date');
            $table->decimal('deposit', 10, 2)->default(0)->comment('Security deposit amount');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->comment('User who created this customer');
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('customer_code');
            $table->index('status');
            $table->index('join_date');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
