<x-layouts.app title="Dettaglio comanda | RistoControl">
<h2 class="text-2xl font-semibold mb-2">{{ $order->order_number }}</h2>
<p class="text-sm mb-4">Stato: {{ $order->status }} · Tavolo: {{ $order->table->name ?? '-' }}</p>
<form method="POST" action="{{ route('orders.send',$order) }}" class="mb-4">@csrf @method('PATCH')<button class="rounded bg-amber-600 text-white px-3 py-2">Invia a cucina/bar</button></form>
<div class="space-y-2">@foreach($order->items as $item)<div class="rounded border bg-white p-3"><div class="font-medium">{{ $item->name_snapshot }} x{{ $item->quantity }} · € {{ number_format($item->price_snapshot,2,',','.') }}</div><div class="text-sm">{{ $item->department }} · Nota: {{ $item->notes ?? '-' }}</div><form method="POST" action="{{ route('orders.items.status',[$order,$item]) }}" class="mt-2">@csrf @method('PATCH')<select name="status" onchange="this.form.submit()" class="rounded border p-1">@foreach(\App\Models\OrderItem::STATUSES as $status)<option value="{{ $status }}" @selected($item->status===$status)>{{ $status }}</option>@endforeach</select></form></div>@endforeach</div>
<div class="mt-4 font-semibold">Totale comanda: € {{ number_format($order->total(),2,',','.') }}</div>
</x-layouts.app>
