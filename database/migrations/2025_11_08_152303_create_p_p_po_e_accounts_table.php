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
        Schema::create('p_p_po_e_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade')->comment('Customer reference');
            $table->foreignId('internet_package_id')->constrained()->onDelete('cascade')->comment('Internet package reference');
            $table->string('username', 50)->unique()->comment('PPPoE username');
            $table->string('password', 100)->comment('PPPoE password');
            $table->string('static_ip', 15)->nullable()->comment('Static IP address');
            $table->string('profile_name', 50)->nullable()->comment('Mikrotik profile name');
            $table->enum('status', ['active', 'inactive', 'suspended', 'expired'])->default('active')->comment('Account status');
            $table->datetime('last_login')->nullable()->comment('Last login timestamp');
            $table->string('last_ip', 15)->nullable()->comment('Last IP address used');
            $table->bigInteger('bytes_in')->default(0)->comment('Total bytes downloaded');
            $table->bigInteger('bytes_out')->default(0)->comment('Total bytes uploaded');
            $table->date('expires_at')->nullable()->comment('Expiration date');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamps();
            
            // Indexes
            $table->index('customer_id');
            $table->index('internet_package_id');
            $table->index('username');
            $table->index('status');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_p_po_e_accounts');
    }
};
