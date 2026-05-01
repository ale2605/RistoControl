<div class="flex flex-wrap gap-1 mt-2">
    @foreach(['confirmed'=>'Conferma','seated'=>'Arrivato','completed'=>'Completato','cancelled'=>'Annulla','no_show'=>'No-show'] as $status => $label)
        <form method="POST" action="{{ route('bookings.quick-status', $booking) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="{{ $status }}">
            <button class="text-xs rounded border border-slate-300 px-2 py-1">{{ $label }}</button>
        </form>
    @endforeach
    <a href="{{ route('bookings.edit', $booking) }}" class="text-xs rounded border border-slate-300 px-2 py-1">Modifica</a>
</div>
