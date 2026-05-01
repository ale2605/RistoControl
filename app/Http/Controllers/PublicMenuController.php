<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\View\View;

class PublicMenuController extends Controller
{
    public function show(string $publicSlug): View
    {
        $restaurant = Restaurant::query()
            ->where('public_slug', $publicSlug)
            ->with([
                'menuCategories' => fn ($query) => $query
                    ->where('is_active', true)
                    ->with(['items' => fn ($itemQuery) => $itemQuery->orderBy('sort_order')->orderBy('name')])
                    ->orderBy('sort_order')
                    ->orderBy('name'),
            ])
            ->firstOrFail();

        return view('public.menu', [
            'restaurant' => $restaurant,
        ]);
    }
}
