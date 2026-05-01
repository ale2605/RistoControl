<?php

namespace App\Policies;

use App\Models\Restaurant;
use App\Models\User;

class RestaurantPolicy
{
    public function view(User $user, Restaurant $restaurant): bool
    {
        return $user->isSuperAdmin() || $user->restaurant_id === $restaurant->id;
    }

    public function update(User $user, Restaurant $restaurant): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->restaurant_id === $restaurant->id && $user->hasRole('owner', 'manager');
    }
}
