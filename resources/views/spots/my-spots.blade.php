<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-white leading-tight flex items-center gap-2">
            <span>📋</span> Mis Lugares Pet-Friendly Guardados
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center">
                <a href="{{ route('spots.index') }}" class="text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 px-4 py-2 rounded-xl transition flex items-center gap-1.5">
                    <span>←</span> Volver al Mapa General
                </a>
            </div>

            <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-purple-100 space-y-4">
                <div class="divide-y divide-purple-50">
                    @forelse($spots as $spot)
                        <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold uppercase text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md">
                                        @if($spot->category === 'park') 🌳 Parque
                                        @elseif($spot->category === 'cafe') ☕ Cafetería
                                        @elseif($spot->category === 'vet') 🏥 Veterinario
                                        @else 🛒 Tienda @endif
                                    </span>
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $spot->name }}</h4>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">📍 {{ $spot->address }}</p>
                                @if($spot->description)
                                    <p class="text-xs text-slate-600 italic mt-1">"{{ $spot->description }}"</p>
                                @endif
                                <span class="text-[10px] text-purple-400 mt-1 block">Añadido el {{ $spot->created_at->format('d/m/Y') }}</span>
                            </div>

                            <!-- Botón para eliminar el spot -->
                            <form action="{{ route('spots.destroy', $spot) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este lugar?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-pink-50 hover:bg-pink-100 text-pink-600 font-bold text-xs px-3 py-2 rounded-xl transition">
                                    🗑️ Borrar
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-slate-400 text-xs">Aún no has añadido ningún lugar pet-friendly 🗺️</p>
                            <a href="{{ route('spots.index') }}" class="inline-block mt-3 bg-purple-600 text-white font-bold text-xs px-4 py-2 rounded-xl shadow-sm hover:bg-purple-700 transition">
                                Añadir mi primer lugar
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
