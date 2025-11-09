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
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('internet_package_id')->constrained()->onDelete('cascade');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('static_ip')->nullable();
            $table->string('profile_name')->nullable(); // Mikrotik profile name
            $table->enum('status', ['active', 'inactive', 'suspended', 'expired'])->default('active');
            $table->datetime('last_login')->nullable();
            $table->string('last_ip')->nullable();
            $table->bigInteger('bytes_in')->default(0);
            $table->bigInteger('bytes_out')->default(0);
            $table->datetime('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
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
