<x-layouts.app title="Comande | RistoControl">
<div class="flex justify-between items-center mb-4"><h2 class="text-2xl font-semibold">Comande</h2><a href="{{ route('orders.create') }}" class="rounded bg-slate-900 text-white px-3 py-2">Nuova comanda</a></div>
<div class="space-y-2">@foreach($orders as $order)<a href="{{ route('orders.show',$order) }}" class="block rounded border bg-white p-4"><div class="font-semibold">{{ $order->order_number }} · {{ $order->status }}</div><div class="text-sm">Tavolo: {{ $order->table->name ?? '-' }} · Totale: € {{ number_format($order->total(),2,',','.') }}</div></a>@endforeach</div>
<div class="mt-4">{{ $orders->links() }}</div>
</x-layouts.app>
