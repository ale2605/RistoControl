<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'restaurant_id',
        'role_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function bookingsCreated(): HasMany
    {
        return $this->hasMany(Booking::class, 'created_by');
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role?->slug, $roles, true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(Role::SUPER_ADMIN);
    }

    public function canManageRestaurantSettings(): bool
    {
        return $this->hasRole(Role::OWNER, Role::MANAGER, Role::SUPER_ADMIN);
    }
}
