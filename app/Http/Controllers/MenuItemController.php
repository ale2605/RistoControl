<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Support\ResolvesCurrentRestaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    use ResolvesCurrentRestaurant;

    public function index(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant, 404);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'menu_category_id' => ['nullable', 'integer'],
            'is_available' => ['nullable', Rule::in(['0', '1'])],
        ]);

        $items = MenuItem::query()->with('category')
            ->where('restaurant_id', $restaurant->id)
            ->when($filters['search'] ?? null, fn($q,$s)=>$q->where('name','like',"%{$s}%"))
            ->when($filters['menu_category_id'] ?? null, fn($q,$c)=>$q->where('menu_category_id',$c))
            ->when(isset($filters['is_available']), fn($q)=>$q->where('is_available',$filters['is_available']))
            ->orderBy('sort_order')->orderBy('name')->paginate(12)->withQueryString();

        $categories = MenuCategory::query()->where('restaurant_id', $restaurant->id)->orderBy('sort_order')->get();

        return view('menu.items.index', compact('items', 'categories', 'filters'));
    }

    public function create(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant,404);
        $categories = MenuCategory::query()->where('restaurant_id',$restaurant->id)->orderBy('sort_order')->get();
        return view('menu.items.form', ['item' => new MenuItem(['is_available'=>true,'is_featured'=>false,'sort_order'=>0]), 'categories'=>$categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant,404);
        $data = $this->validateData($request, $restaurant->id);
        $data['restaurant_id'] = $restaurant->id;
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('menu-items', 'public');
        }
        MenuItem::create($data);
        return redirect()->route('menu-items.index')->with('status','Prodotto creato.');
    }

    public function edit(Request $request, MenuItem $menuItem): View
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant && $menuItem->restaurant_id === $restaurant->id,404);
        $categories = MenuCategory::query()->where('restaurant_id',$restaurant->id)->orderBy('sort_order')->get();
        return view('menu.items.form', ['item' => $menuItem, 'categories'=>$categories]);
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant && $menuItem->restaurant_id === $restaurant->id,404);
        $data = $this->validateData($request, $restaurant->id);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('menu-items', 'public');
        }
        $menuItem->update($data);
        return redirect()->route('menu-items.index')->with('status','Prodotto aggiornato.');
    }

    public function destroy(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant && $menuItem->restaurant_id === $restaurant->id,404);
        $menuItem->delete();
        return back()->with('status','Prodotto eliminato.');
    }

    private function validateData(Request $request, int $restaurantId): array
    {
        $data = $request->validate([
            'menu_category_id' => ['required', Rule::exists('menu_categories', 'id')->where('restaurant_id', $restaurantId)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:4000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'allergens' => ['nullable', 'string', 'max:1000'],
            'tags' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_available' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:3072', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $data['is_available'] = $request->boolean('is_available');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['allergens'] = $data['allergens'] ? array_values(array_filter(array_map('trim', explode(',', $data['allergens'])))) : null;
        $data['tags'] = $data['tags'] ? array_values(array_filter(array_map('trim', explode(',', $data['tags'])))) : null;

        unset($data['image']);

        return $data;
    }
}
