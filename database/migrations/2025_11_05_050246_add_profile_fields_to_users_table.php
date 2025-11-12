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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email')->comment('User phone number');
            $table->string('company', 100)->nullable()->after('phone')->comment('Company name');
            $table->text('address')->nullable()->after('company')->comment('User address');
            $table->text('bio')->nullable()->after('address')->comment('User biography');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'company', 'address', 'bio']);
        });
    }
};
