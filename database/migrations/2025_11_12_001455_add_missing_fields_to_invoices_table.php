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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('tax_amount', 10, 2)->default(0)->after('amount')->comment('Tax amount');
            $table->decimal('total_amount', 10, 2)->after('tax_amount')->comment('Total with tax');
            $table->date('issued_date')->after('invoice_date')->comment('Date invoice was issued');
            $table->date('period_start')->nullable()->after('due_date')->comment('Billing period start');
            $table->date('period_end')->nullable()->after('period_start')->comment('Billing period end');
            $table->foreignId('created_by')->nullable()->after('notes')->constrained('users')->onDelete('set null')->comment('User who created invoice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['tax_amount', 'total_amount', 'issued_date', 'period_start', 'period_end', 'created_by']);
        });
    }
};
