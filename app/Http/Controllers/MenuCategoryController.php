<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Support\ResolvesCurrentRestaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuCategoryController extends Controller
{
    use ResolvesCurrentRestaurant;

    public function index(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant, 404);

        $categories = MenuCategory::query()->where('restaurant_id', $restaurant->id)->orderBy('sort_order')->orderBy('name')->paginate(12);

        return view('menu.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('menu.categories.form', ['category' => new MenuCategory(['is_active' => true, 'sort_order' => 0])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant, 404);
        $data = $this->validateData($request);
        $data['restaurant_id'] = $restaurant->id;
        MenuCategory::create($data);
        return redirect()->route('menu-categories.index')->with('status', 'Categoria creata.');
    }

    public function edit(Request $request, MenuCategory $menuCategory): View
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant && $menuCategory->restaurant_id === $restaurant->id, 404);
        return view('menu.categories.form', ['category' => $menuCategory]);
    }

    public function update(Request $request, MenuCategory $menuCategory): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant && $menuCategory->restaurant_id === $restaurant->id, 404);
        $menuCategory->update($this->validateData($request));
        return redirect()->route('menu-categories.index')->with('status', 'Categoria aggiornata.');
    }

    public function destroy(Request $request, MenuCategory $menuCategory): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant && $menuCategory->restaurant_id === $restaurant->id, 404);
        $menuCategory->delete();
        return back()->with('status', 'Categoria eliminata.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:4000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
