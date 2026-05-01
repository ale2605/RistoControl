<x-layouts.app title="Login | RistoControl">
    <div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow border border-slate-200">
        <h2 class="text-xl font-semibold mb-4">Accedi</h2>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <input class="w-full border rounded-lg p-2" type="email" name="email" placeholder="Email" required>
            <input class="w-full border rounded-lg p-2" type="password" name="password" placeholder="Password" required>
            <button class="w-full bg-slate-900 text-white rounded-lg p-2">Login</button>
        </form>
    </div>
</x-layouts.app>
