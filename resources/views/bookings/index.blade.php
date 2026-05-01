<x-layouts.app title="Prenotazioni | RistoControl">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold">Prenotazioni</h2>
        <a href="{{ route('bookings.create') }}" class="rounded-lg bg-slate-900 text-white px-4 py-2">Nuova</a>
    </div>
    <form method="GET" class="bg-white rounded-xl border border-slate-200 p-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" class="rounded-lg border-slate-300">
        <select name="meal_shift" class="rounded-lg border-slate-300"><option value="">Tutti i turni</option>@foreach($mealShifts as $shift)<option value="{{ $shift }}" @selected(($filters['meal_shift'] ?? '')===$shift)>{{ ucfirst($shift) }}</option>@endforeach</select>
        <select name="status" class="rounded-lg border-slate-300"><option value="">Tutti gli stati</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '')===$status)>{{ str_replace('_',' ',ucfirst($status)) }}</option>@endforeach</select>
        <input name="search" placeholder="Cerca cliente" value="{{ $filters['search'] ?? '' }}" class="rounded-lg border-slate-300">
        <div class="md:col-span-4"><button class="rounded-lg bg-slate-900 text-white px-4 py-2">Filtra</button></div>
    </form>

    <div class="space-y-3 md:hidden">
        @foreach($bookings as $booking)
            <article class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="font-semibold">{{ $booking->customer_name }} ({{ $booking->guests_count }})</div>
                <div class="text-sm text-slate-500">{{ $booking->booking_date->format('d/m/Y') }} {{ substr($booking->booking_time,0,5) }} · {{ $booking->meal_shift }}</div>
                <div class="text-sm">{{ $booking->status }}</div>
                @include('bookings.partials.quick-actions', ['booking' => $booking])
            </article>
        @endforeach
    </div>
    <div class="hidden md:block bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="min-w-full text-sm"><thead class="bg-slate-50"><tr><th class="p-3 text-left">Cliente</th><th class="p-3">Data/Ora</th><th class="p-3">Turno</th><th class="p-3">Coperti</th><th class="p-3">Stato</th><th class="p-3">Azioni</th></tr></thead><tbody>
            @foreach($bookings as $booking)
            <tr class="border-t"><td class="p-3">{{ $booking->customer_name }}<div class="text-slate-500">{{ $booking->customer_phone }}</div></td><td class="p-3">{{ $booking->booking_date->format('d/m/Y') }} {{ substr($booking->booking_time,0,5) }}</td><td class="p-3">{{ $booking->meal_shift }}</td><td class="p-3">{{ $booking->guests_count }}</td><td class="p-3">{{ $booking->status }}</td><td class="p-3">@include('bookings.partials.quick-actions',['booking'=>$booking])</td></tr>
            @endforeach
        </tbody></table>
    </div>
    <div class="mt-4">{{ $bookings->links() }}</div>
</x-layouts.app>
