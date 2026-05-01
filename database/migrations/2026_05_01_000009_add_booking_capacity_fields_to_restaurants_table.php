<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->unsignedInteger('max_covers_lunch')->default(0)->after('opening_hours');
            $table->unsignedInteger('max_covers_dinner')->default(0)->after('max_covers_lunch');
            $table->unsignedInteger('default_booking_duration_minutes')->default(120)->after('max_covers_dinner');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['max_covers_lunch', 'max_covers_dinner', 'default_booking_duration_minutes']);
        });
    }
};
