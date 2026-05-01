<x-layouts.app title="Dashboard | RistoControl">
    <header class="mb-8">
        <h2 class="text-2xl md:text-3xl font-semibold">Dashboard</h2>
        <p class="text-slate-500">Panoramica rapida del ristorante.</p>
    </header>

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <article class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-sm font-medium text-slate-500">Prenotazioni oggi</h3>
            <p class="mt-4 text-3xl font-semibold text-slate-800">{{ $bookingsToday }}</p>
        </article>
        <article class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-sm font-medium text-slate-500">Coperti oggi</h3>
            <p class="mt-4 text-3xl font-semibold text-slate-800">{{ $coversToday }}</p>
        </article>
        <article class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-sm font-medium text-slate-500">No-show del mese</h3>
            <p class="mt-4 text-3xl font-semibold text-slate-800">{{ $noShowMonth }}</p>
        </article>
    </section>
</x-layouts.app>
