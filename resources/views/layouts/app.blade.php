<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'RistoControl' }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 min-h-screen text-slate-900">
    <div class="min-h-screen md:flex">
        <aside class="w-full md:w-64 bg-slate-900 text-white p-4">
            <h1 class="text-2xl font-bold">RistoControl</h1>
            <p class="text-slate-300 text-sm mt-1">Gestionale ristorante</p>
            <nav class="mt-6 space-y-2">
                <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 bg-slate-800">Dashboard</a>
            </nav>
        </aside>
        <main class="flex-1 p-4 md:p-8">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
