<x-layouts.app title="Registrazione | RistoControl">
    <div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow border border-slate-200">
        <h2 class="text-xl font-semibold mb-4">Crea account</h2>
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <input class="w-full border rounded-lg p-2" type="text" name="name" placeholder="Nome" required>
            <input class="w-full border rounded-lg p-2" type="email" name="email" placeholder="Email" required>
            <input class="w-full border rounded-lg p-2" type="password" name="password" placeholder="Password" required>
            <input class="w-full border rounded-lg p-2" type="password" name="password_confirmation" placeholder="Conferma password" required>
            <button class="w-full bg-slate-900 text-white rounded-lg p-2">Registrati</button>
        </form>
    </div>
</x-layouts.app>
