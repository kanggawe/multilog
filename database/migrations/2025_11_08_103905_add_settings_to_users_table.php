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
            $table->string('language', 10)->default('en')->after('bio')->comment('User interface language');
            $table->string('timezone', 50)->default('UTC')->after('language')->comment('User timezone');
            $table->string('date_format', 20)->default('Y-m-d')->after('timezone')->comment('Date display format');
            $table->string('theme', 10)->default('auto')->after('date_format')->comment('UI theme preference');
            $table->boolean('email_notifications')->default(true)->after('theme')->comment('Email notifications enabled');
            $table->boolean('push_notifications')->default(false)->after('email_notifications')->comment('Push notifications enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'language',
                'timezone',
                'date_format',
                'theme',
                'email_notifications',
                'push_notifications'
            ]);
        });
    }
};
