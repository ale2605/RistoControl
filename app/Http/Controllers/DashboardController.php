<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Support\ResolvesCurrentRestaurant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use ResolvesCurrentRestaurant;

    public function index(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant, 404);

        $today = now()->toDateString();

        $bookingsToday = Booking::where('restaurant_id', $restaurant->id)
            ->whereDate('booking_date', $today)
            ->count();

        $coversToday = Booking::where('restaurant_id', $restaurant->id)
            ->whereDate('booking_date', $today)
            ->sum('guests_count');

        $noShowMonth = Booking::where('restaurant_id', $restaurant->id)
            ->where('status', 'no_show')
            ->whereBetween('booking_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->count();

        return view('dashboard.index', compact('bookingsToday', 'coversToday', 'noShowMonth'));
    }
}
