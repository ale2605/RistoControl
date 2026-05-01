<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('business_name')->nullable()->after('name');
            $table->string('address')->nullable()->after('phone');
            $table->string('logo')->nullable()->after('address');
            $table->text('opening_hours')->nullable()->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['business_name', 'address', 'logo', 'opening_hours']);
        });
    }
};
