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
            $table->string('merchant_ref', 100)->nullable()->after('payment_number')->comment('Merchant reference number');
            $table->string('tripay_reference', 100)->nullable()->after('merchant_ref')->comment('Tripay transaction reference');
            $table->string('payment_gateway', 50)->default('manual')->after('method')->comment('Payment gateway used');
            $table->string('gateway_status', 50)->nullable()->after('payment_gateway')->comment('Gateway transaction status');
            $table->text('gateway_response')->nullable()->after('gateway_status')->comment('Gateway response data (JSON)');
            $table->decimal('fee_merchant', 10, 2)->default(0)->after('amount')->comment('Merchant fee');
            $table->decimal('fee_customer', 10, 2)->default(0)->after('fee_merchant')->comment('Customer fee');
            $table->string('payment_url', 255)->nullable()->after('gateway_response')->comment('Payment page URL');
            $table->timestamp('paid_at')->nullable()->after('payment_date')->comment('Actual payment timestamp');
            $table->timestamp('expired_at')->nullable()->after('paid_at')->comment('Payment expiration timestamp');
            
            // Add indexes for faster lookups
            $table->index('merchant_ref');
            $table->index('tripay_reference');
            $table->index('gateway_status');
            $table->index('payment_gateway');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['payment_gateway']);
            $table->dropIndex(['gateway_status']);
            $table->dropIndex(['tripay_reference']);
            $table->dropIndex(['merchant_ref']);
            
            // Drop columns
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
