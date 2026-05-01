<x-layouts.app :title="($item->exists ? 'Modifica' : 'Nuovo').' prodotto | RistoControl'">
    <h2 class="text-2xl font-semibold mb-4">{{ $item->exists ? 'Modifica prodotto' : 'Nuovo prodotto' }}</h2>
    <form method="POST" enctype="multipart/form-data" action="{{ $item->exists ? route('menu-items.update', $item) : route('menu-items.store') }}" class="bg-white rounded-xl border border-slate-200 p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf @if($item->exists) @method('PUT') @endif
        <input name="name" value="{{ old('name', $item->name) }}" placeholder="Nome" class="rounded-lg border-slate-300 md:col-span-2" required>
        <select name="menu_category_id" class="rounded-lg border-slate-300" required>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('menu_category_id', $item->menu_category_id)==$category->id)>{{ $category->name }}</option>@endforeach</select>
        <input type="number" step="0.01" name="price" min="0" value="{{ old('price', $item->price) }}" placeholder="Prezzo" class="rounded-lg border-slate-300" required>
        <textarea name="description" placeholder="Descrizione" rows="4" class="rounded-lg border-slate-300 md:col-span-2">{{ old('description', $item->description) }}</textarea>
        <input name="allergens" value="{{ old('allergens', implode(',', $item->allergens ?? [])) }}" placeholder="Allergeni separati da virgola" class="rounded-lg border-slate-300 md:col-span-2">
        <input name="tags" value="{{ old('tags', implode(',', $item->tags ?? [])) }}" placeholder="Tag separati da virgola" class="rounded-lg border-slate-300 md:col-span-2">
        <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}" min="0" class="rounded-lg border-slate-300">
        <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="rounded-lg border-slate-300">
        <label><input type="checkbox" name="is_available" value="1" @checked(old('is_available', $item->is_available))> Disponibile</label>
        <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $item->is_featured))> In evidenza</label>
        <button class="rounded-lg bg-slate-900 text-white px-4 py-2 w-fit md:col-span-2">Salva prodotto</button>
    </form>
</x-layouts.app>
