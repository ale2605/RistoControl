<x-layouts.app title="Sale | RistoControl">
<h2 class="text-2xl font-semibold mb-4">Sale</h2>
<a href="{{ route('dining-areas.create') }}" class="rounded bg-slate-900 text-white px-3 py-2">Nuova sala</a>
<div class="mt-4 bg-white rounded border">
@foreach($areas as $area)
<div class="p-3 border-b flex justify-between"><div>{{ $area->name }} <span class="text-xs">({{ $area->is_active ? 'attiva':'disattiva' }})</span></div><div class="space-x-2"><a href="{{ route('dining-areas.edit',$area) }}">Modifica</a></div></div>
@endforeach
</div>
</x-layouts.app>
