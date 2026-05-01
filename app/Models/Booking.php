<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    public const MEAL_SHIFTS = ['lunch', 'dinner', 'other'];
    public const STATUSES = ['pending', 'confirmed', 'seated', 'completed', 'cancelled', 'no_show'];
    public const SOURCES = ['manual', 'website', 'phone', 'whatsapp'];

    protected $fillable = [
        'restaurant_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'booking_date',
        'booking_time',
        'meal_shift',
        'guests_count',
        'status',
        'notes',
        'source',
        'created_by',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
