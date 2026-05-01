<?php

namespace App\Support;

use App\Models\Restaurant;
use Illuminate\Http\Request;

trait ResolvesCurrentRestaurant
{
    protected function currentRestaurant(Request $request): ?Restaurant
    {
        $user = $request->user();

        if (! $user) {
            return null;
        }

        if ($user->isSuperAdmin() && $request->route('restaurant')) {
            return Restaurant::find($request->integer('restaurant'));
        }

        return $user->restaurant;
    }
}
