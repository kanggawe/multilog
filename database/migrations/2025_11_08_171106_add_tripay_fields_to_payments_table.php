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
        Schema::table('payments', function (Blueprint $table) {
            // Tripay transaction fields
            $table->string('merchant_ref')->nullable()->after('payment_number');
            $table->string('tripay_reference')->nullable()->after('merchant_ref');
            $table->string('payment_gateway')->default('manual')->after('method');
            $table->string('gateway_status')->nullable()->after('payment_gateway');
            $table->text('gateway_response')->nullable()->after('gateway_status');
            $table->decimal('fee_merchant', 10, 2)->default(0)->after('amount');
            $table->decimal('fee_customer', 10, 2)->default(0)->after('fee_merchant');
            $table->string('payment_url')->nullable()->after('gateway_response');
            $table->timestamp('paid_at')->nullable()->after('payment_date');
            $table->timestamp('expired_at')->nullable()->after('paid_at');
            
            // Add index for faster lookups
            $table->index(['merchant_ref']);
            $table->index(['tripay_reference']);
            $table->index(['gateway_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['gateway_status']);
            $table->dropIndex(['tripay_reference']);
            $table->dropIndex(['merchant_ref']);
            
            $table->dropColumn([
                'merchant_ref',
                'tripay_reference', 
                'payment_gateway',
                'gateway_status',
                'gateway_response',
                'fee_merchant',
                'fee_customer',
                'payment_url',
                'paid_at',
                'expired_at'
            ]);
        });
    }
};
