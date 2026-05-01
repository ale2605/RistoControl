<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    public const STATUSES = ['free', 'reserved', 'occupied', 'cleaning', 'disabled'];

    protected $fillable = ['restaurant_id', 'dining_area_id', 'name', 'seats', 'status', 'pos_x', 'pos_y', 'sort_order'];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function diningArea(): BelongsTo
    {
        return $this->belongsTo(DiningArea::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

}
