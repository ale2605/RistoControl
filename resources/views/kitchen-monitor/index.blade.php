<x-layouts.app title="Monitor cucina/bar | RistoControl">
    <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-3xl font-bold">Monitor cucina/bar</h2>
            <p class="text-sm text-slate-600">Aggiornamento automatico ogni 12 secondi.</p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <label for="department" class="text-sm font-medium">Reparto</label>
            <select id="department" name="department" onchange="this.form.submit()" class="rounded-lg border px-3 py-2 text-sm">
                <option value="all" @selected($department === 'all')>Tutto</option>
                <option value="kitchen" @selected($department === 'kitchen')>Cucina</option>
                <option value="bar" @selected($department === 'bar')>Bar</option>
            </select>
        </form>
    </div>

    @php
        $columns = [
            'pending' => 'Nuove',
            'preparing' => 'In preparazione',
            'ready' => 'Pronte',
        ];
    @endphp

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
        @foreach($columns as $status => $title)
            <section class="rounded-xl border border-slate-300 bg-slate-50 p-3 md:p-4">
                <h3 class="mb-3 text-xl font-semibold">{{ $title }}</h3>
                <div class="space-y-3">
                    @forelse($orders as $order)
                        @php $items = $order->items->where('status', $status); @endphp
                        @if($items->isNotEmpty())
                            <article class="rounded-lg border bg-white p-3 shadow-sm">
                                <div class="mb-2 flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-base font-bold">{{ $order->order_number }}</p>
                                        <p class="text-sm text-slate-600">Tavolo: {{ $order->table->name ?? '-' }}</p>
                                    </div>
                                    <p class="text-xs text-slate-500">{{ $order->created_at?->format('H:i') }}</p>
                                </div>
                                <ul class="mb-3 space-y-2 text-sm">
                                    @foreach($items as $item)
                                        <li class="rounded border border-slate-200 p-2">
                                            <div class="font-semibold">{{ $item->name_snapshot }} × {{ $item->quantity }}</div>
                                            <div class="text-slate-600">Reparto: {{ $item->department }} · Note: {{ $item->notes ?: '-' }}</div>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="flex flex-wrap gap-2">
                                    @foreach($items as $item)
                                        @if($status === 'pending')
                                            <form method="POST" action="{{ route('kitchen-monitor.items.transition', $item) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="target_status" value="preparing">
                                                <input type="hidden" name="department" value="{{ $department }}">
                                                <button class="rounded bg-amber-600 px-3 py-2 text-xs font-medium text-white">Prendi in carico</button>
                                            </form>
                                        @elseif($status === 'preparing')
                                            <form method="POST" action="{{ route('kitchen-monitor.items.transition', $item) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="target_status" value="ready">
                                                <input type="hidden" name="department" value="{{ $department }}">
                                                <button class="rounded bg-emerald-600 px-3 py-2 text-xs font-medium text-white">Segna pronto</button>
                                            </form>
                                        @elseif($status === 'ready')
                                            <form method="POST" action="{{ route('kitchen-monitor.items.transition', $item) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="target_status" value="served">
                                                <input type="hidden" name="department" value="{{ $department }}">
                                                <button class="rounded bg-sky-700 px-3 py-2 text-xs font-medium text-white">Segna servito</button>
                                            </form>
                                        @endif
                                    @endforeach
                                </div>
                            </article>
                        @endif
                    @empty
                        <p class="text-sm text-slate-500">Nessuna comanda presente.</p>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>

    <script>
        setInterval(() => {
            window.location.reload();
        }, 12000);
    </script>
</x-layouts.app>
