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
            $table->decimal('avatar_focus_x', 5, 2)->default(50)->after('profile_photo');
            $table->decimal('avatar_focus_y', 5, 2)->default(50)->after('avatar_focus_x');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_focus_x', 'avatar_focus_y']);
        });
    }
};
