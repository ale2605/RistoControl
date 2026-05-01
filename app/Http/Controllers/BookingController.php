<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Support\ResolvesCurrentRestaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    use ResolvesCurrentRestaurant;

    public function index(Request $request): View
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant, 404);

        $filters = $request->validate([
            'date' => ['nullable', 'date'],
            'meal_shift' => ['nullable', Rule::in(Booking::MEAL_SHIFTS)],
            'status' => ['nullable', Rule::in(Booking::STATUSES)],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $bookings = Booking::query()
            ->where('restaurant_id', $restaurant->id)
            ->when($filters['date'] ?? null, fn ($q, $date) => $q->whereDate('booking_date', $date))
            ->when($filters['meal_shift'] ?? null, fn ($q, $shift) => $q->where('meal_shift', $shift))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                });
            })
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->paginate(15)
            ->withQueryString();

        return view('bookings.index', [
            'bookings' => $bookings,
            'filters' => $filters,
            'mealShifts' => Booking::MEAL_SHIFTS,
            'statuses' => Booking::STATUSES,
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($this->currentRestaurant($request), 404);

        return view('bookings.form', [
            'booking' => new Booking([
                'booking_date' => now()->toDateString(),
                'booking_time' => now()->format('H:i'),
                'meal_shift' => 'lunch',
                'status' => 'pending',
                'source' => 'manual',
                'guests_count' => 2,
            ]),
            'mealShifts' => Booking::MEAL_SHIFTS,
            'statuses' => Booking::STATUSES,
            'sources' => Booking::SOURCES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant, 404);

        $validated = $this->validateBooking($request);
        $validated['restaurant_id'] = $restaurant->id;
        $validated['created_by'] = $request->user()?->id;

        Booking::create($validated);

        return redirect()->route('bookings.index')->with('status', 'Prenotazione creata.');
    }

    public function edit(Request $request, Booking $booking): View
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant && $booking->restaurant_id === $restaurant->id, 404);

        return view('bookings.form', [
            'booking' => $booking,
            'mealShifts' => Booking::MEAL_SHIFTS,
            'statuses' => Booking::STATUSES,
            'sources' => Booking::SOURCES,
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant && $booking->restaurant_id === $restaurant->id, 404);

        $booking->update($this->validateBooking($request));

        return redirect()->route('bookings.index')->with('status', 'Prenotazione aggiornata.');
    }

    public function destroy(Request $request, Booking $booking): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant && $booking->restaurant_id === $restaurant->id, 404);

        $booking->delete();

        return back()->with('status', 'Prenotazione eliminata.');
    }

    public function quickStatus(Request $request, Booking $booking): RedirectResponse
    {
        $restaurant = $this->currentRestaurant($request);
        abort_unless($restaurant && $booking->restaurant_id === $restaurant->id, 404);

        $validated = $request->validate([
            'status' => ['required', Rule::in(Booking::STATUSES)],
        ]);

        $booking->update(['status' => $validated['status']]);

        return back()->with('status', 'Stato prenotazione aggiornato.');
    }

    private function validateBooking(Request $request): array
    {
        return $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'booking_date' => ['required', 'date'],
            'booking_time' => ['required', 'date_format:H:i'],
            'meal_shift' => ['required', Rule::in(Booking::MEAL_SHIFTS)],
            'guests_count' => ['required', 'integer', 'min:1', 'max:1000'],
            'status' => ['required', Rule::in(Booking::STATUSES)],
            'notes' => ['nullable', 'string', 'max:5000'],
            'source' => ['required', Rule::in(Booking::SOURCES)],
        ]);
    }
}
