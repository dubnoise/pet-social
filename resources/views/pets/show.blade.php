<x-app-layout>
    <div class="py-8 bg-gradient-to-b from-[#e9d5ff] via-[#f3e8ff] to-[#ede9fe] min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Card de Perfil de la Mascota -->
            <div class="bg-white/90 backdrop-blur-md p-6 sm:p-8 rounded-3xl shadow-sm border border-purple-200">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <!-- Avatar de la Mascota -->
                    <div class="w-24 h-24 bg-gradient-to-tr from-purple-600 to-indigo-500 text-white rounded-3xl flex items-center justify-center text-4xl font-black shadow-lg shadow-purple-500/30 border-2 border-white">
                        {{ $pet->species === 'cat' ? '🐱' : '🐶' }}
                    </div>

                    <!-- Informacion principal -->
                    <div class="flex-1 text-center sm:text-left space-y-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h1 class="text-2xl font-black text-slate-900 leading-tight">{{ $pet->name }}</h1>
                                <p class="text-xs font-semibold text-purple-700 bg-purple-100 px-3 py-1 rounded-full w-fit mx-auto sm:mx-0 mt-1 border border-purple-200">
                                    {{ ucfirst($pet->species_in_spanish) }} • {{ $pet->breed ?: 'Mestizo' }}
                                </p>
                            </div>

                            <!-- Botón de Seguir / Dejar de seguir dinámico -->
                            @if(Auth::user()->pets->pluck('id')->contains($pet->id))
                                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-4 py-2 rounded-xl border border-purple-200 self-center">
                                    ¡Es tu peludo! 🐾
                                </span>
                            @else
                                <form action="{{ route('pets.follow', $pet) }}" method="POST" class="flex flex-col sm:flex-row items-center gap-2 justify-center sm:justify-start">
                                    @csrf
                                    @if(Auth::user()->pets->isNotEmpty())
                                        <select name="follower_pet_id" class="text-xs rounded-xl border-purple-200 bg-purple-50 py-2 px-2.5 focus:ring-purple-500 focus:border-purple-500 font-medium text-purple-900">
                                            @foreach(Auth::user()->pets as $myPet)
                                                <option value="{{ $myPet->id }}">Publicar como {{ $myPet->name }}</option>
                                            @endforeach
                                        </select>

                                        @php
                                            // Comprobamos si alguna de las mascotas del usuario ya sigue a este perfil
                                            $currentPet = Auth::user()->pets->first();
                                            $isFollowing = $currentPet ? $currentPet->isFollowing($pet) : false;
                                        @endphp

                                        <button type="submit" class="text-xs font-bold px-5 py-2.5 rounded-xl shadow-md transition hover:scale-105 {{ $isFollowing ? 'bg-purple-100 text-purple-700 border border-purple-300 hover:bg-purple-200' : 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white shadow-purple-500/20' }}">
                                            {{ $isFollowing ? '✓ Siguiendo Manada' : '+ Seguir Manada' }}
                                        </button>
                                    @endif
                                </form>
                            @endif
                        </div>

                        <p class="text-xs text-slate-500 pt-1">Humano responsable: <span class="font-bold text-slate-700">{{ $pet->user->name }}</span></p>
                    </div>
                </div>

                <!-- Estadisticas de Manada -->
                <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-purple-100 text-center">
                    <div class="bg-purple-50/50 p-3 rounded-2xl border border-purple-100">
                        <span class="block text-xl font-black text-purple-700">{{ $pet->posts->count() }}</span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Publicaciones</span>
                    </div>
                    <div class="bg-purple-50/50 p-3 rounded-2xl border border-purple-100">
                        <span class="block text-xl font-black text-purple-700">{{ $pet->followers->count() }}</span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Seguidores</span>
                    </div>
                    <div class="bg-purple-50/50 p-3 rounded-2xl border border-purple-100">
                        <span class="block text-xl font-black text-purple-700">{{ $pet->following->count() }}</span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Seguidos</span>
                    </div>
                </div>
            </div>

            <!-- Pestañas e Interfaz con Alpine.js -->
            <div x-data="{ tab: 'posts' }" class="space-y-6">

                <!-- Botones de Pestañas -->
                <div class="flex items-center gap-2 bg-white/90 backdrop-blur-md p-2 rounded-2xl border border-purple-200 shadow-sm w-fit mx-auto sm:mx-0">
                    <button @click="tab = 'posts'"
                            :class="tab === 'posts' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-500/20' : 'text-slate-600 hover:bg-purple-50'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition">
                        📸 Publicaciones ({{ $pet->posts->count() }})
                    </button>
                    <button @click="tab = 'followers'"
                            :class="tab === 'followers' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-500/20' : 'text-slate-600 hover:bg-purple-50'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition">
                        🐾 Seguidores ({{ $pet->followers->count() }})
                    </button>
                    <button @click="tab = 'following'"
                            :class="tab === 'following' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-500/20' : 'text-slate-600 hover:bg-purple-50'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition">
                        🐕 Seguidos ({{ $pet->following->count() }})
                    </button>
                </div>

                <!-- Contenido 1: PUBLICACIONES -->
                <div x-show="tab === 'posts'" class="space-y-4">
                    @forelse($pet->posts as $post)
                        <div class="bg-white/90 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-purple-200 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-purple-600 font-semibold">{{ $post->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-slate-800 text-sm leading-relaxed">{{ $post->content }}</p>

                            @if($post->image)
                                <div class="rounded-2xl overflow-hidden border border-purple-100 max-h-96">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="w-full object-cover">
                                </div>
                            @endif

                            <div class="flex items-center gap-3 pt-2 border-t border-purple-100">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-pink-600 bg-pink-50 px-3.5 py-2 rounded-xl border border-pink-100">
                                    ❤️ {{ $post->likes->count() }} Me Guau
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-slate-100 px-3.5 py-2 rounded-xl">
                                    💬 {{ $post->comments->count() }} Comentarios
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white/90 backdrop-blur-md p-8 rounded-3xl text-center border border-purple-200">
                            <p class="text-slate-500 text-sm">Esta mascota aún no ha compartido ninguna travesura 🐾</p>
                        </div>
                    @endforelse
                </div>

                <!-- Contenido 2: SEGUIDORES -->
                <div x-show="tab === 'followers'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($pet->followers as $follower)
                        <a href="{{ route('pets.show', $follower) }}" class="flex items-center gap-4 p-4 rounded-3xl bg-white/90 backdrop-blur-md border border-purple-200 hover:border-purple-400 hover:shadow-md transition group">
                            <div class="w-12 h-12 bg-purple-100 text-purple-700 rounded-2xl flex items-center justify-center text-xl font-bold group-hover:scale-105 transition">
                                {{ $follower->species === 'cat' ? '🐱' : '🐶' }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 group-hover:text-purple-600 transition">{{ $follower->name }}</h4>
                                <p class="text-xs text-purple-600 font-medium">{{ $follower->breed ?: 'Mestizo' }}</p>
                                <span class="text-[10px] text-slate-400">Humano: {{ $follower->user->name }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full bg-white/90 backdrop-blur-md p-8 rounded-3xl text-center border border-purple-200">
                            <p class="text-slate-500 text-sm">Aún no tiene seguidores en su manada 🐕</p>
                        </div>
                    @endforelse
                </div>

                <!-- Contenido 3: SEGUIDOS -->
                <div x-show="tab === 'following'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($pet->following as $followed)
                        <a href="{{ route('pets.show', $followed) }}" class="flex items-center gap-4 p-4 rounded-3xl bg-white/90 backdrop-blur-md border border-purple-200 hover:border-purple-400 hover:shadow-md transition group">
                            <div class="w-12 h-12 bg-purple-100 text-purple-700 rounded-2xl flex items-center justify-center text-xl font-bold group-hover:scale-105 transition">
                                {{ $followed->species === 'cat' ? '🐱' : '🐶' }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 group-hover:text-purple-600 transition">{{ $followed->name }}</h4>
                                <p class="text-xs text-purple-600 font-medium">{{ $followed->breed ?: 'Mestizo' }}</p>
                                <span class="text-[10px] text-slate-400">Humano: {{ $followed->user->name }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full bg-white/90 backdrop-blur-md p-8 rounded-3xl text-center border border-purple-200">
                            <p class="text-slate-500 text-sm">No sigue a ninguna mascota todavía 🐾</p>
                        </div>
                    @endforelse
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
