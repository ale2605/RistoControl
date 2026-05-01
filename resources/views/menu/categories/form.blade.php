<x-layouts.app :title="($category->exists ? 'Modifica' : 'Nuova').' categoria | RistoControl'">
    <h2 class="text-2xl font-semibold mb-4">{{ $category->exists ? 'Modifica categoria' : 'Nuova categoria' }}</h2>
    <form method="POST" action="{{ $category->exists ? route('menu-categories.update', $category) : route('menu-categories.store') }}" class="bg-white rounded-xl border border-slate-200 p-4 grid gap-4">
        @csrf
        @if($category->exists) @method('PUT') @endif
        <input name="name" value="{{ old('name', $category->name) }}" placeholder="Nome" class="rounded-lg border-slate-300" required>
        <textarea name="description" rows="4" placeholder="Descrizione" class="rounded-lg border-slate-300">{{ old('description', $category->description) }}</textarea>
        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="rounded-lg border-slate-300" min="0" required>
        <label class="text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))> Attiva</label>
        <button class="rounded-lg bg-slate-900 text-white px-4 py-2 w-fit">Salva</button>
    </form>
</x-layouts.app>
