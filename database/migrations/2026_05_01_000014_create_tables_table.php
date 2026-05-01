<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dining_area_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedSmallInteger('seats');
            $table->enum('status', ['free', 'reserved', 'occupied', 'cleaning', 'disabled'])->default('free');
            $table->decimal('pos_x', 8, 2)->nullable();
            $table->decimal('pos_y', 8, 2)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['restaurant_id', 'status']);
            $table->unique(['restaurant_id', 'name']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('table_id')->nullable()->after('restaurant_id')->constrained('tables')->nullOnDelete();
            $table->index(['restaurant_id', 'table_id']);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('table_id');
        });

        Schema::dropIfExists('tables');
    }
};
