<x-layouts.app title="Piatti e Prodotti | RistoControl">
    <div class="flex items-center justify-between mb-4"><h2 class="text-2xl font-semibold">Piatti / Prodotti</h2><a href="{{ route('menu-items.create') }}" class="rounded-lg bg-slate-900 text-white px-4 py-2">Nuovo prodotto</a></div>
    @if(session('status'))<div class="mb-4 rounded-lg bg-emerald-100 text-emerald-700 px-3 py-2">{{ session('status') }}</div>@endif
    <form class="bg-white rounded-xl border border-slate-200 p-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-3" method="GET">
        <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cerca nome" class="rounded-lg border-slate-300">
        <select name="menu_category_id" class="rounded-lg border-slate-300"><option value="">Tutte le categorie</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(($filters['menu_category_id'] ?? '')==$category->id)>{{ $category->name }}</option>@endforeach</select>
        <select name="is_available" class="rounded-lg border-slate-300"><option value="">Disponibilità</option><option value="1" @selected(($filters['is_available'] ?? '')==='1')>Disponibile</option><option value="0" @selected(($filters['is_available'] ?? '')==='0')>Non disponibile</option></select>
        <button class="rounded-lg bg-slate-900 text-white px-4 py-2">Filtra</button>
    </form>
    <div class="space-y-3">
        @foreach($items as $item)
            <article class="bg-white border border-slate-200 rounded-xl p-4">
                <div class="flex items-start justify-between gap-3">
                    <div><a href="{{ route('menu-items.edit', $item) }}" class="font-semibold">{{ $item->name }}</a><p class="text-sm text-slate-500">{{ $item->category?->name }} · Ordine {{ $item->sort_order }}</p><p class="text-sm mt-1">€ {{ number_format((float) $item->price, 2, ',', '.') }}</p></div>
                    <span class="text-xs {{ $item->is_available ? 'text-emerald-600' : 'text-rose-600' }}">{{ $item->is_available ? 'Disponibile' : 'Non disponibile' }}</span>
                </div>
            </article>
        @endforeach
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
</x-layouts.app>
