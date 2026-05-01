<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Support\ResolvesCurrentRestaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KitchenMonitorController extends Controller
{
    use ResolvesCurrentRestaurant;

    public function index(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant, 404);
        abort_unless($request->user()?->hasRole('kitchen', 'manager', 'owner', 'super_admin'), 403);

        $department = $request->string('department')->toString() ?: 'all';
        abort_unless(in_array($department, ['all', 'kitchen', 'bar'], true), 404);

        $orders = Order::query()
            ->with(['table', 'items' => function ($query) use ($department) {
                $query->whereIn('status', ['pending', 'preparing', 'ready'])
                    ->when($department !== 'all', fn ($q) => $q->where('department', $department))
                    ->orderBy('created_at');
            }])
            ->where('restaurant_id', $restaurant->id)
            ->whereHas('items', function ($query) use ($department) {
                $query->whereIn('status', ['pending', 'preparing', 'ready'])
                    ->when($department !== 'all', fn ($q) => $q->where('department', $department));
            })
            ->latest()
            ->get();

        return view('kitchen-monitor.index', [
            'orders' => $orders,
            'department' => $department,
        ]);
    }

    public function transition(Request $request, OrderItem $item): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant && $item->restaurant_id === $restaurant->id, 404);
        abort_unless($request->user()?->hasRole('kitchen', 'manager', 'owner', 'super_admin'), 403);

        $data = $request->validate([
            'target_status' => ['required', Rule::in(['preparing', 'ready', 'served'])],
            'department' => ['nullable', Rule::in(['all', 'kitchen', 'bar'])],
        ]);

        $item->update(['status' => $data['target_status']]);

        $order = $item->order()->with('items')->first();
        if ($order) {
            if ($order->items->every(fn (OrderItem $orderItem) => $orderItem->status === 'served')) {
                $order->update(['status' => 'served']);
            } elseif ($order->items->contains(fn (OrderItem $orderItem) => $orderItem->status === 'preparing')) {
                $order->update(['status' => 'preparing']);
            } elseif ($order->items->contains(fn (OrderItem $orderItem) => $orderItem->status === 'ready')) {
                $order->update(['status' => 'ready']);
            } else {
                $order->update(['status' => 'sent_to_kitchen']);
            }
        }

        return redirect()->route('kitchen-monitor.index', [
            'department' => $data['department'] ?? 'all',
        ])->with('status', 'Stato aggiornato.');
    }
}
