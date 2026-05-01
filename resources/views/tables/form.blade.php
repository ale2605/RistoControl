<x-layouts.app :title="($table->exists?'Modifica':'Nuovo').' tavolo'">
<form method="POST" action="{{ $table->exists ? route('tables.update',$table) : route('tables.store') }}" class="bg-white rounded border p-4 grid md:grid-cols-2 gap-3">@csrf @if($table->exists) @method('PUT') @endif
<input name="name" value="{{ old('name',$table->name) }}" placeholder="Nome tavolo" class="rounded">
<input type="number" name="seats" value="{{ old('seats',$table->seats) }}" placeholder="Coperti" class="rounded">
<select name="dining_area_id" class="rounded">@foreach($areas as $area)<option value="{{ $area->id }}" @selected(old('dining_area_id',$table->dining_area_id)==$area->id)>{{ $area->name }}</option>@endforeach</select>
<select name="status" class="rounded">@foreach($statuses as $status)<option value="{{ $status }}" @selected(old('status',$table->status)===$status)>{{ $status }}</option>@endforeach</select>
<input type="number" step="0.01" name="pos_x" value="{{ old('pos_x',$table->pos_x) }}" placeholder="X" class="rounded">
<input type="number" step="0.01" name="pos_y" value="{{ old('pos_y',$table->pos_y) }}" placeholder="Y" class="rounded">
<button class="bg-slate-900 text-white px-3 py-2 rounded md:col-span-2">Salva</button></form>
</x-layouts.app>
