<x-layouts.app title="Impostazioni ristorante | RistoControl">
    <header class="mb-6">
        <h2 class="text-2xl md:text-3xl font-semibold">Impostazioni ristorante</h2>
        <p class="text-slate-500">Gestisci i dati del tuo locale.</p>
    </header>

    @if (session('status'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-emerald-700">{{ session('status') }}</div>
    @endif

    @php
        $publicMenuUrl = $restaurant->public_slug ? route('public.menu.show', ['publicSlug' => $restaurant->public_slug]) : null;
        $qrUrl = $publicMenuUrl ? 'https://quickchart.io/qr?text='.urlencode($publicMenuUrl).'&size=280' : null;
    @endphp

    <form method="POST" action="{{ route('settings.restaurant.update') }}" class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @method('PUT')
        <label class="block">
            <span class="text-sm text-slate-600">Nome ristorante</span>
            <input name="name" value="{{ old('name', $restaurant->name) }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </label>
        <label class="block">
            <span class="text-sm text-slate-600">Slug pubblico menù QR</span>
            <input name="public_slug" value="{{ old('public_slug', $restaurant->public_slug) }}" class="mt-1 w-full rounded-lg border-slate-300" placeholder="es. ristorante-roma" required>
        </label>
        <label class="block md:col-span-2 rounded-lg border border-slate-200 p-3">
            <span class="text-sm text-slate-600">Prodotti non disponibili</span>
            <div class="mt-2 flex items-start gap-2">
                <input type="checkbox" name="show_unavailable_items" value="1" @checked(old('show_unavailable_items', $restaurant->show_unavailable_items)) class="mt-1">
                <span class="text-sm text-slate-700">Mostra comunque i prodotti non disponibili con etichetta <strong>Non disponibile</strong>. Se disattivato, saranno nascosti.</span>
            </div>
        </label>
        <label class="block">
            <span class="text-sm text-slate-600">Ragione sociale</span>
            <input name="business_name" value="{{ old('business_name', $restaurant->business_name) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </label>
        <label class="block">
            <span class="text-sm text-slate-600">Email</span>
            <input type="email" name="email" value="{{ old('email', $restaurant->email) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </label>
        <label class="block">
            <span class="text-sm text-slate-600">Telefono</span>
            <input name="phone" value="{{ old('phone', $restaurant->phone) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </label>
        <label class="block md:col-span-2">
            <span class="text-sm text-slate-600">Indirizzo</span>
            <input name="address" value="{{ old('address', $restaurant->address) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </label>
        <label class="block md:col-span-2">
            <span class="text-sm text-slate-600">Logo (URL)</span>
            <input name="logo" value="{{ old('logo', $restaurant->logo) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </label>
        <label class="block md:col-span-2">
            <span class="text-sm text-slate-600">Orari apertura</span>
            <textarea name="opening_hours" rows="4" class="mt-1 w-full rounded-lg border-slate-300">{{ old('opening_hours', $restaurant->opening_hours) }}</textarea>
        </label>
        <label class="block">
            <span class="text-sm text-slate-600">Capienza massima pranzo</span>
            <input type="number" min="0" name="max_covers_lunch" value="{{ old('max_covers_lunch', $restaurant->max_covers_lunch ?? 0) }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </label>
        <label class="block">
            <span class="text-sm text-slate-600">Capienza massima cena</span>
            <input type="number" min="0" name="max_covers_dinner" value="{{ old('max_covers_dinner', $restaurant->max_covers_dinner ?? 0) }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </label>
        <label class="block md:col-span-2">
            <span class="text-sm text-slate-600">Durata prenotazione predefinita (minuti)</span>
            <input type="number" min="15" step="15" name="default_booking_duration_minutes" value="{{ old('default_booking_duration_minutes', $restaurant->default_booking_duration_minutes ?? 120) }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </label>
        <div class="md:col-span-2 flex flex-wrap items-center gap-3">
            <button class="rounded-lg bg-slate-900 text-white px-4 py-2">Salva</button>
            @if ($publicMenuUrl)
                <button type="button" class="rounded-lg border border-slate-300 px-4 py-2" onclick="navigator.clipboard.writeText('{{ $publicMenuUrl }}')">Copia link pubblico</button>
                <a href="{{ $publicMenuUrl }}" target="_blank" class="rounded-lg border border-slate-300 px-4 py-2">Apri menù pubblico</a>
            @endif
        </div>
    </form>

    @if ($qrUrl)
        <section class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-6">
            <h3 class="font-semibold text-lg">QR code del menù online</h3>
            <p class="text-sm text-slate-500">Scansionando il codice si apre il menù pubblico del ristorante.</p>
            <img src="{{ $qrUrl }}" alt="QR code menù pubblico" class="mt-4 w-44 h-44 rounded-lg border border-slate-200">
            <a href="{{ $qrUrl }}" download="qr-menu-{{ $restaurant->public_slug }}.png" class="mt-4 inline-block rounded-lg bg-slate-900 text-white px-4 py-2">Scarica QR</a>
        </section>
    @endif
</x-layouts.app>
