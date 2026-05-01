<x-layouts.app title="Impostazioni ristorante | RistoControl">
    <header class="mb-6">
        <h2 class="text-2xl md:text-3xl font-semibold">Impostazioni ristorante</h2>
        <p class="text-slate-500">Gestisci i dati del tuo locale.</p>
    </header>

    @if (session('status'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-emerald-700">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('settings.restaurant.update') }}" class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @method('PUT')
        <label class="block">
            <span class="text-sm text-slate-600">Nome ristorante</span>
            <input name="name" value="{{ old('name', $restaurant->name) }}" class="mt-1 w-full rounded-lg border-slate-300" required>
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
        <div class="md:col-span-2">
            <button class="rounded-lg bg-slate-900 text-white px-4 py-2">Salva</button>
        </div>
    </form>
</x-layouts.app>
