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
            $table->unsignedInteger('ban_duration_hours')->nullable()->after('banned_until');
            $table->timestamp('ban_started_at')->nullable()->after('ban_duration_hours');
            $table->text('ban_reason')->nullable()->after('ban_started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ban_duration_hours', 'ban_started_at', 'ban_reason']);
        });
    }
};
