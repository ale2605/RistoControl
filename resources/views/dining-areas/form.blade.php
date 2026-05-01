<x-layouts.app :title="($area->exists?'Modifica':'Nuova').' sala'">
<form method="POST" action="{{ $area->exists ? route('dining-areas.update',$area) : route('dining-areas.store') }}" class="bg-white rounded border p-4 space-y-3">@csrf @if($area->exists) @method('PUT') @endif
<input name="name" value="{{ old('name',$area->name) }}" placeholder="Nome sala" class="w-full rounded">
<input type="number" name="sort_order" value="{{ old('sort_order',$area->sort_order) }}" class="w-full rounded">
<label><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$area->is_active))> Attiva</label>
<button class="bg-slate-900 text-white px-3 py-2 rounded">Salva</button></form>
</x-layouts.app>
