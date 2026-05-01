<x-layouts.app title="Prenotazioni | RistoControl">
    <div class="flex flex-col gap-3 mb-4 md:flex-row md:items-center md:justify-between">
        <h2 class="text-2xl font-semibold">Prenotazioni</h2>
        <div class="flex gap-2">
            <a href="{{ route('bookings.index', array_merge(request()->query(), ['view' => 'month'])) }}" class="rounded-lg px-3 py-2 text-sm {{ $activeView === 'month' ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200' }}">Mese</a>
            <a href="{{ route('bookings.index', array_merge(request()->query(), ['view' => 'day', 'date' => $selectedDate->toDateString()])) }}" class="rounded-lg px-3 py-2 text-sm {{ $activeView === 'day' ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200' }}">Giorno</a>
            <a href="{{ route('bookings.index', array_merge(request()->query(), ['view' => 'list'])) }}" class="rounded-lg px-3 py-2 text-sm {{ $activeView === 'list' ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200' }}">Elenco</a>
            <a href="{{ route('bookings.create') }}" class="rounded-lg bg-slate-900 text-white px-4 py-2">Nuova</a>
        </div>
    </div>

    <form method="GET" class="bg-white rounded-xl border border-slate-200 p-4 mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">
        <input type="hidden" name="view" value="{{ $activeView }}">
        <input type="month" name="month" value="{{ $selectedMonth->format('Y-m') }}" class="rounded-lg border-slate-300">
        <input type="date" name="date" value="{{ $filters['date'] ?? $selectedDate->toDateString() }}" class="rounded-lg border-slate-300">
        <select name="meal_shift" class="rounded-lg border-slate-300"><option value="">Tutti i turni</option>@foreach($mealShifts as $shift)<option value="{{ $shift }}" @selected(($filters['meal_shift'] ?? '')===$shift)>{{ ucfirst($shift) }}</option>@endforeach</select>
        <select name="status" class="rounded-lg border-slate-300"><option value="">Tutti gli stati</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '')===$status)>{{ str_replace('_',' ',ucfirst($status)) }}</option>@endforeach</select>
        <input name="search" placeholder="Cerca cliente" value="{{ $filters['search'] ?? '' }}" class="rounded-lg border-slate-300">
        <div class="md:col-span-5"><button class="rounded-lg bg-slate-900 text-white px-4 py-2">Filtra</button></div>
    </form>

    @if($activeView === 'month')
        @php
            $firstCell = $selectedMonth->copy()->startOfMonth()->startOfWeek();
            $lastCell = $selectedMonth->copy()->endOfMonth()->endOfWeek();
        @endphp
        <div class="grid grid-cols-7 gap-2 text-xs text-slate-500 mb-2">
            @foreach(['Lun','Mar','Mer','Gio','Ven','Sab','Dom'] as $day)
                <div class="text-center">{{ $day }}</div>
            @endforeach
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2">
            @for($cursor = $firstCell->copy(); $cursor->lte($lastCell); $cursor->addDay())
                @php
                    $dateKey = $cursor->toDateString();
                    $daily = $coversByDay[$dateKey] ?? ['lunch'=>0,'dinner'=>0,'total'=>0];
                    $isFull = ($maxLunch > 0 && $daily['lunch'] >= $maxLunch) || ($maxDinner > 0 && $daily['dinner'] >= $maxDinner);
                @endphp
                <a href="{{ route('bookings.index', ['view' => 'day', 'date' => $dateKey, 'month' => $selectedMonth->format('Y-m')]) }}"
                   class="rounded-xl border p-3 min-h-28 {{ $cursor->month !== $selectedMonth->month ? 'bg-slate-50 text-slate-400' : 'bg-white' }} {{ $isFull ? 'border-rose-400 bg-rose-50' : 'border-slate-200' }}">
                    <div class="font-semibold">{{ $cursor->format('d') }}</div>
                    <div class="text-xs">Pranzo: {{ $daily['lunch'] }}{{ $maxLunch > 0 ? '/'.$maxLunch : '' }}</div>
                    <div class="text-xs">Cena: {{ $daily['dinner'] }}{{ $maxDinner > 0 ? '/'.$maxDinner : '' }}</div>
                    <div class="text-xs mt-1 font-medium">Totale: {{ $daily['total'] }}</div>
                </a>
            @endfor
        </div>
    @elseif($activeView === 'day')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach(['lunch' => 'Pranzo', 'dinner' => 'Cena'] as $shiftKey => $shiftLabel)
                @php $shiftBookings = $dailyTimeline->where('meal_shift', $shiftKey); @endphp
                <section class="bg-white rounded-xl border border-slate-200 p-4">
                    <h3 class="font-semibold mb-3">{{ $shiftLabel }} · {{ $selectedDate->format('d/m/Y') }}</h3>
                    <div class="space-y-2">
                        @forelse($shiftBookings as $booking)
                            <a href="{{ route('bookings.edit', $booking) }}" class="block rounded-lg border border-slate-200 p-3 hover:bg-slate-50">
                                <div class="flex justify-between gap-2">
                                    <div class="font-medium">{{ substr($booking->booking_time,0,5) }} · {{ $booking->customer_name }}</div>
                                    <div class="text-sm">{{ $booking->guests_count }} coperti</div>
                                </div>
                                <div class="text-sm text-slate-500">{{ $booking->status }} · {{ $booking->customer_phone }}</div>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">Nessuna prenotazione nel turno {{ strtolower($shiftLabel) }}.</p>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>
    @else
        <div class="space-y-3 md:hidden">
            @foreach($bookings as $booking)
                <article class="bg-white rounded-xl border border-slate-200 p-4">
                    <a href="{{ route('bookings.edit', $booking) }}" class="font-semibold block">{{ $booking->customer_name }} ({{ $booking->guests_count }})</a>
                    <div class="text-sm text-slate-500">{{ $booking->booking_date->format('d/m/Y') }} {{ substr($booking->booking_time,0,5) }} · {{ $booking->meal_shift }}</div>
                    <div class="text-sm">{{ $booking->status }}</div>
                    @include('bookings.partials.quick-actions', ['booking' => $booking])
                </article>
            @endforeach
        </div>
        <div class="hidden md:block bg-white rounded-xl border border-slate-200 overflow-x-auto">
            <table class="min-w-full text-sm"><thead class="bg-slate-50"><tr><th class="p-3 text-left">Cliente</th><th class="p-3">Data/Ora</th><th class="p-3">Turno</th><th class="p-3">Coperti</th><th class="p-3">Stato</th><th class="p-3">Azioni</th></tr></thead><tbody>
                @foreach($bookings as $booking)
                <tr class="border-t"><td class="p-3"><a class="font-medium" href="{{ route('bookings.edit', $booking) }}">{{ $booking->customer_name }}</a><div class="text-slate-500">{{ $booking->customer_phone }}</div></td><td class="p-3">{{ $booking->booking_date->format('d/m/Y') }} {{ substr($booking->booking_time,0,5) }}</td><td class="p-3">{{ $booking->meal_shift }}</td><td class="p-3">{{ $booking->guests_count }}</td><td class="p-3">{{ $booking->status }}</td><td class="p-3">@include('bookings.partials.quick-actions',['booking'=>$booking])</td></tr>
                @endforeach
            </tbody></table>
        </div>
        <div class="mt-4">{{ $bookings->links() }}</div>
    @endif
</x-layouts.app>
