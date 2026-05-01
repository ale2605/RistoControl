<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Support\ResolvesCurrentRestaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
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
            'public_slug' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:restaurants,public_slug,'.$restaurant->id],
            'show_unavailable_items' => ['nullable', 'boolean'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'url', 'max:2048'],
            'opening_hours' => ['nullable', 'string', 'max:2000'],
            'max_covers_lunch' => ['required', 'integer', 'min:0', 'max:5000'],
            'max_covers_dinner' => ['required', 'integer', 'min:0', 'max:5000'],
            'default_booking_duration_minutes' => ['required', 'integer', 'min:15', 'max:480'],
        ]);

        $validated['public_slug'] = Str::lower($validated['public_slug']);
        $validated['show_unavailable_items'] = $request->boolean('show_unavailable_items');

        $restaurant->update($validated);

        return back()->with('status', 'Impostazioni aggiornate.');
    }
}
