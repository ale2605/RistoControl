<x-layouts.app :title="($booking->exists ? 'Modifica' : 'Nuova').' prenotazione | RistoControl'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold">{{ $booking->exists ? 'Modifica prenotazione' : 'Nuova prenotazione' }}</h2>
        <a href="{{ route('bookings.index') }}" class="text-sm text-slate-600">Torna elenco</a>
    </div>

    <form method="POST" action="{{ $booking->exists ? route('bookings.update', $booking) : route('bookings.store') }}" class="bg-white rounded-xl border border-slate-200 p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @if($booking->exists) @method('PUT') @endif

        @foreach ([
            ['customer_name','Nome cliente','text'],
            ['customer_phone','Telefono','text'],
            ['customer_email','Email','email'],
            ['booking_date','Data','date'],
            ['booking_time','Orario','time'],
            ['guests_count','Coperti','number'],
        ] as [$name,$label,$type])
            <label class="block">
                <span class="text-sm text-slate-600">{{ $label }}</span>
                <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $booking->{$name}) }}" class="mt-1 w-full rounded-lg border-slate-300" {{ in_array($name,['customer_name','customer_phone','booking_date','booking_time','guests_count']) ? 'required' : '' }}>
            </label>
        @endforeach

        <label class="block">
            <span class="text-sm text-slate-600">Turno</span>
            <select name="meal_shift" class="mt-1 w-full rounded-lg border-slate-300" required>
                @foreach($mealShifts as $shift)
                    <option value="{{ $shift }}" @selected(old('meal_shift', $booking->meal_shift) === $shift)>{{ ucfirst($shift) }}</option>
                @endforeach
            </select>
        </label>

        <label class="block">
            <span class="text-sm text-slate-600">Stato</span>
            <select name="status" class="mt-1 w-full rounded-lg border-slate-300" required>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $booking->status) === $status)>{{ str_replace('_',' ', ucfirst($status)) }}</option>
                @endforeach
            </select>
        </label>

        <label class="block md:col-span-2">
            <span class="text-sm text-slate-600">Fonte</span>
            <select name="source" class="mt-1 w-full rounded-lg border-slate-300" required>
                @foreach($sources as $source)
                    <option value="{{ $source }}" @selected(old('source', $booking->source) === $source)>{{ ucfirst($source) }}</option>
                @endforeach
            </select>
        </label>

        <label class="block md:col-span-2">
            <span class="text-sm text-slate-600">Note</span>
            <textarea name="notes" rows="4" class="mt-1 w-full rounded-lg border-slate-300">{{ old('notes', $booking->notes) }}</textarea>
        </label>

        <div class="md:col-span-2">
            <button class="rounded-lg bg-slate-900 px-4 py-2 text-white">Salva prenotazione</button>
        </div>
    </form>
</x-layouts.app>
