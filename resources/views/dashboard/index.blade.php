<x-layouts.app title="Dashboard | RistoControl">
    <header class="mb-8">
        <h2 class="text-2xl md:text-3xl font-semibold">Dashboard</h2>
        <p class="text-slate-500">Panoramica rapida del ristorante.</p>
    </header>

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
        @foreach (['Prenotazioni oggi', 'Coperti oggi', 'Entrate oggi', 'Scadenze', 'Magazzino'] as $card)
            <article class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="text-sm font-medium text-slate-500">{{ $card }}</h3>
                <p class="mt-4 text-3xl font-semibold text-slate-800">--</p>
            </article>
        @endforeach
    </section>
</x-layouts.app>
