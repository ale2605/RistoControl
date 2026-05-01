<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Support\ResolvesCurrentRestaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantSettingsController extends Controller
{
    use ResolvesCurrentRestaurant;

    public function edit(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request);

        abort_unless($restaurant, 404);
        abort_unless($request->user()?->canManageRestaurantSettings() && ($request->user()->isSuperAdmin() || $request->user()->restaurant_id === $restaurant->id), 403);

        return view('settings.restaurant', compact('restaurant'));
    }

    public function update(Request $request): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request);

        abort_unless($restaurant, 404);
        abort_unless($request->user()?->canManageRestaurantSettings() && ($request->user()->isSuperAdmin() || $request->user()->restaurant_id === $restaurant->id), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'url', 'max:2048'],
            'opening_hours' => ['nullable', 'string', 'max:2000'],
        ]);

        $restaurant->update($validated);

        return back()->with('status', 'Impostazioni aggiornate.');
    }
}
