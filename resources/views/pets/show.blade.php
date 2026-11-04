<x-app-layout>
    <div class="py-8 bg-purple-50/30 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Cabecera de Perfil de Mascota -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-purple-100 flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
                <div class="w-24 h-24 bg-purple-100 text-purple-700 rounded-full flex items-center justify-center font-black text-4xl shadow-inner">
                    {{ $pet->species === 'cat' ? '🐱' : '🐶' }}
                </div>
                <div class="space-y-2 flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h1 class="text-3xl font-black text-slate-900">{{ $pet->name }}</h1>
                            <p class="text-xs font-bold text-purple-600 mt-0.5">
                                {{ $pet->species_in_spanish ?? ucfirst($pet->species) }} • {{ $pet->breed ?: 'Mestizo' }}
                                <span class="text-slate-400 font-normal">({!! $pet->followers->count() !!} seguidores)</span>
                            </p>
                        </div>

                        <a href="{{ route('dashboard') }}" class="text-xs font-bold text-purple-600 bg-purple-50 hover:bg-purple-100 px-4 py-2.5 rounded-xl transition">
                            ← Volver al Feed
                        </a>
                    </div>

                    <p class="text-xs text-slate-600 max-w-lg">{{ $pet->bio ?: 'Aún no hay biografía escrita para este peludo.' }}</p>

                    <!-- Formulario para Seguir (Si el usuario tiene mascotas y no es su propia mascota) -->
                    @auth
                        @if($userPets->count() > 0 && !$userPets->contains('id', $pet->id))
                            <div class="pt-2">
                                <form action="{{ route('pets.follow', $pet) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <select name="follower_pet_id" class="text-xs rounded-xl border-purple-100 bg-purple-50/50 py-2 px-3 text-slate-700 font-semibold focus:border-purple-500 focus:ring-purple-500">
                                        @foreach($userPets as $myPet)
                                            <option value="{{ $myPet->id }}">{{ $myPet->name }}</option>
                                        @endforeach
                                    </select>

                                    @php
                                        // Comprobamos si la primera mascota seleccionada ya lo sigue (o puedes hacerlo dinámico con Alpine)
                                        $selectedPet = $userPets->first();
                                        $isFollowing = $selectedPet ? $selectedPet->isFollowing($pet) : false;
                                    @endphp

                                    <button type="submit" class="text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-md {{ $isFollowing ? 'bg-slate-200 text-slate-700 hover:bg-slate-300' : 'bg-purple-600 hover:bg-purple-700 text-white shadow-purple-600/25' }}">
                                        {{ $isFollowing ? 'Siguiendo 🐾' : '+ Seguir Manada' }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Historial de Posts de la Mascota -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 px-2">Ladridos de {{ $pet->name }} 🐾</h3>

                @forelse($pet->posts as $post)
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-purple-100 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 text-purple-700 rounded-full flex items-center justify-center font-bold text-lg">
                                    {{ substr($pet->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 leading-tight">{{ $pet->name }}</h4>
                                    <span class="text-xs text-purple-600 font-medium">{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-slate-700 text-sm leading-relaxed">{{ $post->content }}</p>

                        @if($post->image)
                            <div class="rounded-2xl overflow-hidden border border-slate-100 max-h-96">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="w-full object-cover">
                            </div>
                        @endif

                        <div class="flex items-center gap-4 pt-2 border-t border-slate-100">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold bg-pink-50 text-pink-600 px-3.5 py-2 rounded-xl">
                                ❤️ Me Guau ({{ $post->likes_count }})
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold bg-slate-100 text-slate-600 px-3.5 py-2 rounded-xl">
                                💬 Comentarios ({{ $post->comments->count() }})
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-3xl border border-purple-100 p-8">
                        <div class="text-4xl mb-2">🐾</div>
                        <h3 class="font-bold text-slate-800">{{ $pet->name }} todavía no ha publicado ningún ladrido</h3>
                        <p class="text-xs text-slate-500 mt-1">¡Ve al feed principal para publicar algo en su nombre!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
