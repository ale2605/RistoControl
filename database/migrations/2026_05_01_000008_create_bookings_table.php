<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone', 50);
            $table->string('customer_email')->nullable();
            $table->date('booking_date');
            $table->time('booking_time');
            $table->enum('meal_shift', ['lunch', 'dinner', 'other']);
            $table->unsignedSmallInteger('guests_count');
            $table->enum('status', ['pending', 'confirmed', 'seated', 'completed', 'cancelled', 'no_show'])->default('pending');
            $table->text('notes')->nullable();
            $table->enum('source', ['manual', 'website', 'phone', 'whatsapp'])->default('manual');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['restaurant_id', 'booking_date']);
            $table->index(['restaurant_id', 'status']);
            $table->index(['restaurant_id', 'meal_shift']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
