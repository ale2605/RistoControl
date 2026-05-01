<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Support\ResolvesCurrentRestaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    use ResolvesCurrentRestaurant;

    public function index(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant, 404);
        $orders = Order::with(['table', 'items'])->where('restaurant_id', $restaurant->id)->latest()->paginate(20);
        return view('orders.index', ['orders' => $orders]);
    }

    public function create(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant, 404);
        return view('orders.form', ['tables' => Table::where('restaurant_id', $restaurant->id)->orderBy('name')->get(), 'bookings' => Booking::where('restaurant_id', $restaurant->id)->latest()->limit(20)->get(), 'menuItems' => MenuItem::where('restaurant_id', $restaurant->id)->where('is_available', true)->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant, 404);
        $request->merge(['items' => collect($request->input('items', []))->filter(fn ($item) => ! empty($item['menu_item_id'] ?? null))->values()->all()]);

        $data = $request->validate([
            'table_id' => ['nullable', Rule::exists('tables', 'id')->where('restaurant_id', $restaurant->id)],
            'booking_id' => ['nullable', Rule::exists('bookings', 'id')->where('restaurant_id', $restaurant->id)],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => ['required', Rule::exists('menu_items', 'id')->where('restaurant_id', $restaurant->id)],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.notes' => ['nullable', 'string'],
            'items.*.department' => ['required', Rule::in(OrderItem::DEPARTMENTS)],
        ]);

        $order = DB::transaction(function () use ($data, $restaurant, $request) {
            $sequence = Order::where('restaurant_id', $restaurant->id)->count() + 1;
            $order = Order::create([
                'restaurant_id' => $restaurant->id,
                'table_id' => $data['table_id'] ?? null,
                'booking_id' => $data['booking_id'] ?? null,
                'order_number' => sprintf('ORD-%s-%04d', now()->format('Ymd'), $sequence),
                'status' => 'open',
                'notes' => $data['notes'] ?? null,
                'opened_by' => $request->user()->id,
            ]);

            $menuItems = MenuItem::where('restaurant_id', $restaurant->id)->whereIn('id', collect($data['items'])->pluck('menu_item_id'))->get()->keyBy('id');
            foreach ($data['items'] as $item) {
                $menuItem = $menuItems[$item['menu_item_id']];
                $order->items()->create([
                    'restaurant_id' => $restaurant->id,
                    'menu_item_id' => $menuItem->id,
                    'name_snapshot' => $menuItem->name,
                    'price_snapshot' => $menuItem->price,
                    'quantity' => $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                    'department' => $item['department'],
                    'status' => 'pending',
                ]);
            }
            return $order;
        });

        return redirect()->route('orders.show', $order)->with('status', 'Comanda creata.');
    }

    public function show(Request $request, Order $order): View
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant && $order->restaurant_id === $restaurant->id, 404);
        $order->load(['table', 'booking', 'items.menuItem']);
        return view('orders.show', ['order' => $order]);
    }

    public function send(Request $request, Order $order): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant && $order->restaurant_id === $restaurant->id, 404);
        $order->update(['status' => 'sent_to_kitchen']);
        return back()->with('status', 'Comanda inviata a cucina/bar.');
    }

    public function updateItemStatus(Request $request, Order $order, OrderItem $item): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request); abort_unless($restaurant && $order->restaurant_id === $restaurant->id && $item->order_id === $order->id && $item->restaurant_id === $restaurant->id, 404);
        $request->validate(['status' => ['required', Rule::in(OrderItem::STATUSES)]]);
        $item->update(['status' => $request->string('status')]);
        return back()->with('status', 'Stato prodotto aggiornato.');
    }
}
