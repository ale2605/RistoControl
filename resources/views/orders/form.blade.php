<x-layouts.app title="Nuova comanda | RistoControl">
<h2 class="text-2xl font-semibold mb-4">Nuova comanda</h2>
<form method="POST" action="{{ route('orders.store') }}" class="space-y-4">@csrf
<div class="grid md:grid-cols-2 gap-3"><select name="table_id" class="rounded border p-2"><option value="">Tavolo (opzionale)</option>@foreach($tables as $table)<option value="{{ $table->id }}">{{ $table->name }}</option>@endforeach</select>
<select name="booking_id" class="rounded border p-2"><option value="">Prenotazione (opzionale)</option>@foreach($bookings as $booking)<option value="{{ $booking->id }}">#{{ $booking->id }} - {{ $booking->customer_name }}</option>@endforeach</select></div>
<textarea name="notes" class="w-full rounded border p-2" placeholder="Note comanda"></textarea>
<h3 class="font-semibold">Prodotti</h3>
@for($i=0;$i<3;$i++)
<div class="grid md:grid-cols-4 gap-2">
<select name="items[{{ $i }}][menu_item_id]" class="rounded border p-2" @if($i===0) required @endif><option value="">Prodotto</option>@foreach($menuItems as $item)<option value="{{ $item->id }}">{{ $item->name }} (€ {{ number_format($item->price,2,',','.') }})</option>@endforeach</select>
<input type="number" min="1" name="items[{{ $i }}][quantity]" value="1" class="rounded border p-2" @if($i===0) required @endif>
<select name="items[{{ $i }}][department]" class="rounded border p-2"><option value="kitchen">kitchen</option><option value="bar">bar</option><option value="other">other</option></select>
<input type="text" name="items[{{ $i }}][notes]" class="rounded border p-2" placeholder="Nota singolo prodotto">
</div>
@endfor
<button class="rounded bg-slate-900 text-white px-4 py-2">Salva comanda</button>
</form>
</x-layouts.app>
