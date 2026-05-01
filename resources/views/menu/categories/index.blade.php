<x-layouts.app title="Categorie Menù | RistoControl">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold">Categorie Menù</h2>
        <a href="{{ route('menu-categories.create') }}" class="rounded-lg bg-slate-900 text-white px-4 py-2">Nuova categoria</a>
    </div>
    @if(session('status'))<div class="mb-4 rounded-lg bg-emerald-100 text-emerald-700 px-3 py-2">{{ session('status') }}</div>@endif
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($categories as $category)
            <article class="bg-white border border-slate-200 rounded-xl p-4">
                <div class="flex justify-between gap-2"><a href="{{ route('menu-categories.edit', $category) }}" class="font-semibold">{{ $category->name }}</a><span class="text-xs {{ $category->is_active ? 'text-emerald-600' : 'text-slate-500' }}">{{ $category->is_active ? 'Attiva' : 'Disattiva' }}</span></div>
                <p class="text-sm text-slate-500">Ordine: {{ $category->sort_order }}</p>
                <p class="text-sm mt-2">{{ $category->description }}</p>
            </article>
        @endforeach
    </div>
    <div class="mt-4">{{ $categories->links() }}</div>
</x-layouts.app>
