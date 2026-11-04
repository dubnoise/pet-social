<x-guest-layout>
    <div x-data="{ step: 1, species: 'dog' }" class="w-full max-w-lg mx-auto bg-white p-8 rounded-3xl shadow-xl border border-purple-100">

        <!-- Indicador de Pasos / Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-extrabold uppercase tracking-wider text-purple-600" x-text="step === 1 ? 'Paso 1: Tu Cuenta' : 'Paso 2: Tu Mascota'"></span>
                <span class="text-xs font-semibold text-slate-400" x-text="step + '/2'"></span>
            </div>
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 h-full transition-all duration-500" :style="'width: ' + (step === 1 ? '50%' : '100%')"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- ================= PASO 1: DATOS DEL HUMANO ================= -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">

                <div class="text-center mb-6">
                    <h2 class="text-2xl font-black text-slate-900">¡Crea tu cuenta de Humano! 👤</h2>
                    <p class="text-xs text-slate-500 mt-1">Primero configuramos tus credenciales de acceso</p>
                </div>

                <!-- Nombre del Humano -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700">Tu Nombre Completo</label>
                    <input id="name" class="mt-1 w-full rounded-2xl border-slate-200 focus:border-purple-500 focus:ring-purple-500 text-sm py-3 px-4 bg-slate-50/50" type="text" name="name" :value="old('name')" required autofocus placeholder="Ej. Carlos Mendoza" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">Correo Electrónico</label>
                    <input id="email" class="mt-1 w-full rounded-2xl border-slate-200 focus:border-purple-500 focus:ring-purple-500 text-sm py-3 px-4 bg-slate-50/50" type="email" name="email" :value="old('email')" required placeholder="hola@ejemplo.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">Contraseña</label>
                    <input id="password" class="mt-1 w-full rounded-2xl border-slate-200 focus:border-purple-500 focus:ring-purple-500 text-sm py-3 px-4 bg-slate-50/50" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirmar Contraseña</label>
                    <input id="password_confirmation" class="mt-1 w-full rounded-2xl border-slate-200 focus:border-purple-500 focus:ring-purple-500 text-sm py-3 px-4 bg-slate-50/50" type="password" name="password_confirmation" required placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <a class="text-xs text-slate-500 hover:text-purple-600 font-medium" href="{{ route('login') }}">
                        ¿Ya tienes cuenta?
                    </a>
                    <button type="button" @click="step = 2" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold px-6 py-3 rounded-2xl shadow-lg shadow-purple-600/20 transition">
                        Siguiente: Tu Mascota 🐾
                    </button>
                </div>
            </div>

            <!-- ================= PASO 2: DATOS DE LA MASCOTA ================= -->
            <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">

                <div class="text-center mb-6">
                    <h2 class="text-2xl font-black text-slate-900">Presenta a tu Peludo 🐶</h2>
                    <p class="text-xs text-slate-500 mt-1">Este será el perfil principal de tu mascota</p>
                </div>

                <!-- Selector de Tipo de Mascota -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Especie</label>
                    <div class="grid grid-cols-3 gap-3">
                        <button type="button" @click="species = 'dog'" :class="species === 'dog' ? 'border-purple-500 bg-purple-50 text-purple-700 ring-2 ring-purple-500/20' : 'border-slate-200 hover:bg-slate-50'" class="border rounded-2xl p-3 flex flex-col items-center gap-1 transition">
                            <span class="text-2xl">🐶</span>
                            <span class="text-xs font-bold">Perro</span>
                        </button>
                        <button type="button" @click="species = 'cat'" :class="species === 'cat' ? 'border-purple-500 bg-purple-50 text-purple-700 ring-2 ring-purple-500/20' : 'border-slate-200 hover:bg-slate-50'" class="border rounded-2xl p-3 flex flex-col items-center gap-1 transition">
                            <span class="text-2xl">🐱</span>
                            <span class="text-xs font-bold">Gato</span>
                        </button>
                        <button type="button" @click="species = 'other'" :class="species === 'other' ? 'border-purple-500 bg-purple-50 text-purple-700 ring-2 ring-purple-500/20' : 'border-slate-200 hover:bg-slate-50'" class="border rounded-2xl p-3 flex flex-col items-center gap-1 transition">
                            <span class="text-2xl">🐰</span>
                            <span class="text-xs font-bold">Otro</span>
                        </button>
                    </div>
                    <input type="hidden" name="pet_species" :value="species">
                    <x-input-error :messages="$errors->get('pet_species')" class="mt-1" />
                </div>

                <!-- Nombre de la Mascota -->
                <div>
                    <label for="pet_name" class="block text-sm font-semibold text-slate-700">Nombre de tu Mascota</label>
                    <input id="pet_name" class="mt-1 w-full rounded-2xl border-slate-200 focus:border-purple-500 focus:ring-purple-500 text-sm py-3 px-4 bg-slate-50/50" type="text" name="pet_name" :value="old('pet_name')" placeholder="Ej. Firulais, Max, Coco..." />
                    <x-input-error :messages="$errors->get('pet_name')" class="mt-1" />
                </div>

                <!-- Raza -->
                <div>
                    <label for="pet_breed" class="block text-sm font-semibold text-slate-700">Raza / Mezcla (Opcional)</label>
                    <input id="pet_breed" class="mt-1 w-full rounded-2xl border-slate-200 focus:border-purple-500 focus:ring-purple-500 text-sm py-3 px-4 bg-slate-50/50" type="text" name="pet_breed" :value="old('pet_breed')" placeholder="Ej. Golden Retriever, Mestizo..." />
                    <x-input-error :messages="$errors->get('pet_breed')" class="mt-1" />
                </div>

                <!-- Pequeña Bio -->
                <div>
                    <label for="pet_bio" class="block text-sm font-semibold text-slate-700">¿Qué le gusta hacer? (Bio corta)</label>
                    <textarea id="pet_bio" name="pet_bio" rows="2" class="mt-1 w-full rounded-2xl border-slate-200 focus:border-purple-500 focus:ring-purple-500 text-sm py-3 px-4 bg-slate-50/50" placeholder="Le encanta perseguir pelotas y dormir al sol..."></textarea>
                    <x-input-error :messages="$errors->get('pet_bio')" class="mt-1" />
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <button type="button" @click="step = 1" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                        ← Volver a mis datos
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 py-3 rounded-2xl shadow-lg shadow-indigo-600/20 transition hover:scale-105">
                        ¡Crear Comunidad! 🚀
                    </button>
                </div>
            </div>

        </form>
    </div>
</x-guest-layout>
