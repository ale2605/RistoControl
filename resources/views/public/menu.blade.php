<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menù | {{ $restaurant->name }}</title>
    <meta name="description" content="Menù online di {{ $restaurant->name }}">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-4xl px-4 py-6 md:py-10">
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 md:p-6">
            <div class="flex items-center gap-4">
                @if ($restaurant->logo)
                    <img src="{{ $restaurant->logo }}" alt="Logo {{ $restaurant->name }}" class="h-16 w-16 rounded-xl object-cover border border-slate-200">
                @endif
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">{{ $restaurant->name }}</h1>
                    @if ($restaurant->address)
                        <p class="text-slate-500 text-sm">{{ $restaurant->address }}</p>
                    @endif
                </div>
            </div>
        </section>

        <div class="mt-6 space-y-4">
            @forelse ($restaurant->menuCategories as $category)
                @php
                    $items = $restaurant->show_unavailable_items
                        ? $category->items
                        : $category->items->where('is_available', true);
                @endphp

                @if($items->isNotEmpty())
                    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 md:p-6">
                        <h2 class="text-xl font-semibold">{{ $category->name }}</h2>
                        @if ($category->description)
                            <p class="text-slate-500 mt-1">{{ $category->description }}</p>
                        @endif

                        <div class="mt-4 space-y-3">
                            @foreach ($items as $item)
                                <article class="border border-slate-200 rounded-xl p-3 md:p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h3 class="font-semibold text-lg">{{ $item->name }}</h3>
                                            @if (!$item->is_available)
                                                <p class="text-xs font-medium text-amber-700">Non disponibile</p>
                                            @endif
                                        </div>
                                        <p class="font-semibold whitespace-nowrap">€ {{ number_format((float) $item->price, 2, ',', '.') }}</p>
                                    </div>
                                    @if ($item->description)
                                        <p class="text-slate-600 mt-2 text-sm">{{ $item->description }}</p>
                                    @endif
                                    @if (!empty($item->allergens))
                                        <p class="mt-2 text-xs text-rose-700">Allergeni: {{ implode(', ', $item->allergens) }}</p>
                                    @endif
                                    @if ($item->image_path)
                                        <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->name }}" class="mt-3 h-40 w-full object-cover rounded-lg border border-slate-200">
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            @empty
                <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 md:p-6 text-slate-500">
                    Nessuna categoria disponibile al momento.
                </section>
            @endforelse
        </div>
    </main>
</body>
</html>
