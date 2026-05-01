<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'business_name',
        'slug',
        'public_slug',
        'show_unavailable_items',
        'vat_number',
        'email',
        'phone',
        'address',
        'logo',
        'opening_hours',
        'max_covers_lunch',
        'max_covers_dinner',
        'default_booking_duration_minutes',
    ];

    protected $casts = [
        'show_unavailable_items' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function menuCategories(): HasMany
    {
        return $this->hasMany(MenuCategory::class);
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}
