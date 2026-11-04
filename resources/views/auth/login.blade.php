<x-guest-layout>
    <div class="w-full max-w-md mx-auto bg-white p-8 rounded-3xl shadow-xl border border-purple-100">

        <div class="text-center mb-8">
            <div class="inline-block p-4 bg-purple-50 rounded-full mb-3 text-3xl">🐶</div>
            <h2 class="text-2xl font-black text-slate-900">¡Hola de nuevo!</h2>
            <p class="text-xs text-slate-500 mt-1">Ingresa para ver las novedades de la manada</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">Correo Electrónico</label>
                <input id="email" class="mt-1 w-full rounded-2xl border-slate-200 focus:border-purple-500 focus:ring-purple-500 text-sm py-3 px-4 bg-slate-50/50" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center">
                    <label for="password" class="block text-sm font-semibold text-slate-700">Contraseña</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs text-purple-600 hover:text-purple-700 font-semibold" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>
                <input id="password" class="mt-1 w-full rounded-2xl border-slate-200 focus:border-purple-500 focus:ring-purple-500 text-sm py-3 px-4 bg-slate-50/50" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center pt-2">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-purple-600 shadow-sm focus:ring-purple-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">Recordar sesión</span>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full justify-center inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-purple-600/20 transition hover:scale-[1.02]">
                    Entrar a la Red 🐾
                </button>
            </div>

            <div class="text-center pt-4 border-t border-slate-100">
                <span class="text-xs text-slate-500">¿Aún no eres parte?</span>
                <a href="{{ route('register') }}" class="text-xs font-bold text-purple-600 hover:underline ms-1">Regístrate aquí</a>
            </div>
        </form>
    </div>
</x-guest-layout>
