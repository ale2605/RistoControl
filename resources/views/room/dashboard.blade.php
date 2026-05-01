<x-layouts.app title="Dashboard Sala | RistoControl">
<h2 class="text-2xl font-semibold mb-4">Dashboard sala giornaliera</h2>
<p class="text-slate-600 mb-4">{{ $today }} · Tavoli occupati: {{ $occupied }}/{{ $total }}</p>
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3">
@php $colors=['free'=>'bg-emerald-100','reserved'=>'bg-amber-100','occupied'=>'bg-rose-100','cleaning'=>'bg-sky-100','disabled'=>'bg-slate-200']; @endphp
@foreach($tables as $table)
<div class="rounded-xl border p-3 {{ $colors[$table->status] ?? 'bg-white' }}"><div class="font-semibold">{{ $table->name }}</div><div class="text-xs">{{ $table->status }} · {{ $table->seats }} coperti</div></div>
@endforeach
</div>
</x-layouts.app>
