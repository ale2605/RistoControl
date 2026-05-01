<x-layouts.app title="Tavoli | RistoControl">
<div class="flex justify-between"><h2 class="text-2xl font-semibold mb-4">Tavoli</h2><a href="{{ route('tables.create') }}" class="rounded bg-slate-900 text-white px-3 py-2">Nuovo tavolo</a></div>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
@php $colors=['free'=>'bg-emerald-100','reserved'=>'bg-amber-100','occupied'=>'bg-rose-100','cleaning'=>'bg-sky-100','disabled'=>'bg-slate-200']; @endphp
@foreach($tables as $table)
<div class="rounded-xl border p-4 {{ $colors[$table->status] ?? 'bg-white' }}">
<div class="font-semibold">{{ $table->name }} · {{ $table->seats }} coperti</div>
<div class="text-sm">Sala: {{ $table->diningArea->name ?? '-' }}</div>
<form method="POST" action="{{ route('tables.quick-status',$table) }}" class="mt-2">@csrf @method('PATCH')<select name="status" onchange="this.form.submit()" class="rounded text-sm">@foreach(\App\Models\Table::STATUSES as $status)<option value="{{ $status }}" @selected($table->status===$status)>{{ $status }}</option>@endforeach</select></form>
</div>
@endforeach</div>
</x-layouts.app>
