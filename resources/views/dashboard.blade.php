<x-app-layout>
    <div class="py-8 bg-[#f8f5fc] min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-6">

            <!-- ================= COLUMNA IZQUIERDA: PERFIL Y MASCOTAS ================= -->
            <div class="md:col-span-4 space-y-6">
                <div class="bg-white/90 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-purple-100/80">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-tr from-purple-600 to-indigo-500 text-white font-black rounded-2xl flex items-center justify-center text-xl shadow-md shadow-purple-500/20">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-900 leading-tight">{{ Auth::user()->name }}</h2>
                            <span class="text-xs text-purple-700 font-semibold bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-100">Humano Verificado 👤</span>
                        </div>
                    </div>

                    <hr class="border-purple-50 my-4">

                    <!-- Mis Mascotas -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-purple-900/60">Mis Peludos 🐾</h3>
                            <a href="{{ route('pets.create') }}" class="text-xs font-bold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 px-3.5 py-1.5 rounded-xl shadow-sm transition hover:scale-105">
                                + Añadir
                            </a>
                        </div>

                        <div class="space-y-3">
                            @forelse($userPets as $pet)
                                <a href="{{ route('pets.show', $pet) }}" class="flex items-center gap-3 p-3 rounded-2xl bg-purple-50/40 border border-purple-100 hover:bg-purple-100/50 transition block shadow-sm">
                                    <div class="w-10 h-10 bg-purple-100 text-purple-700 rounded-xl flex items-center justify-center text-lg font-bold">
                                        {{ $pet->species === 'cat' ? '🐱' : '🐶' }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800">{{ $pet->name }}</h4>
                                        <p class="text-xs text-purple-600/80 font-medium">{{ $pet->breed ?: 'Mestizo' }}</p>
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-slate-400 italic text-center py-2">Aún no tienes peludos registrados 🐾</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- ✨ Widget: Sugerencias de la Manada (Peludos que quizás conozcas) -->
                <div class="bg-white/90 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-purple-100/80 space-y-4">
                    <div class="flex items-center justify-between border-b border-purple-50 pb-3">
                        <h3 class="font-black text-xs uppercase tracking-wider text-purple-900 flex items-center gap-1.5">
                            <span>✨</span> Sugerencias para tu Manada
                        </h3>
                    </div>

                    <div class="space-y-3">
                        @forelse($suggestedPets as $suggested)
                            <div class="flex items-center justify-between gap-3 p-2 rounded-2xl hover:bg-purple-50/50 transition">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('pets.show', $suggested) }}" class="w-10 h-10 bg-purple-100 text-purple-700 rounded-xl flex items-center justify-center font-bold text-base hover:scale-105 transition shadow-inner">
                                        {{ $suggested->species === 'cat' ? '🐱' : '🐶' }}
                                    </a>
                                    <div>
                                        <a href="{{ route('pets.show', $suggested) }}" class="font-bold text-xs text-slate-900 hover:text-purple-600 transition block">
                                            {{ $suggested->name }}
                                        </a>
                                        <span class="text-[10px] text-purple-600/80 font-medium">{{ $suggested->breed ?: 'Mestizo' }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('pets.show', $suggested) }}" class="bg-purple-50 hover:bg-purple-600 text-purple-700 hover:text-white text-[10px] font-bold px-3 py-1.5 rounded-xl transition shadow-sm">
                                    Ver perfil
                                </a>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-3">No hay más peludos por descubrir ahora mismo 📭</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- ================= COLUMNA CENTRAL: FEED Y CREAR POST ================= -->
            <div class="md:col-span-8 space-y-6">

                <!-- Filtro de Feed -->
                <div class="flex items-center gap-2 bg-white/90 backdrop-blur-md p-2 rounded-2xl border border-purple-100/80 shadow-sm w-fit">
                    <a href="{{ route('dashboard', ['feed' => 'all']) }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('feed', 'all') === 'all' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-500/20' : 'text-slate-600 hover:bg-purple-50' }}">
                        🌍 Descubrir Todo
                    </a>
                    <a href="{{ route('dashboard', ['feed' => 'manada']) }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('feed') === 'manada' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-500/20' : 'text-slate-600 hover:bg-purple-50' }}">
                        🐾 Mi Manada (Seguidos)
                    </a>
                </div>

                <!-- Formulario para publicar -->
                <div class="bg-white/90 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-purple-100/80">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-purple-900/60 uppercase tracking-wider mb-2">Publicar como:</label>
                            <select name="pet_id" class="w-full rounded-2xl border-purple-100 focus:border-purple-500 focus:ring-purple-500 text-sm bg-purple-50/30 py-2.5 px-3 text-slate-800 font-medium">
                                @foreach($userPets as $pet)
                                    <option value="{{ $pet->id }}">{{ $pet->name }} ({{ ucfirst($pet->species_in_spanish) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <textarea name="content" rows="3" class="w-full rounded-2xl border-purple-100 focus:border-purple-500 focus:ring-purple-500 text-sm p-4 bg-purple-50/30 resize-none text-slate-800 placeholder-black-400" placeholder="¿Qué travesura hizo hoy tu mascota? ¡Cuéntaselo a la manada! 🐕"></textarea>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-purple-50">
                            <label class="cursor-pointer inline-flex items-center gap-2 text-xs font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100/80 px-4 py-2.5 rounded-xl transition">
                                <span>📷 Añadir Foto</span>
                                <input type="file" name="image" class="hidden" accept="image/*">
                            </label>

                            <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-md shadow-purple-500/20 transition hover:scale-105">
                                Publicar Ladrido 🚀
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Feed de Publicaciones -->
                <div class="space-y-4">
                    @forelse($posts as $post)
                        <div x-data="{ openComments: false }" class="bg-white p-6 rounded-3xl shadow-sm border border-purple-100 space-y-4">
                            <!-- Cabecera del Post -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <!-- Enlace en el Avatar -->
                                    <a href="{{ route('pets.show', $post->pet) }}" class="w-10 h-10 bg-purple-100 text-purple-700 rounded-full flex items-center justify-center font-bold text-lg hover:scale-105 transition">
                                        {{ substr($post->pet->name, 0, 1) }}
                                    </a>
                                    <div>
                                        <h4 class="font-bold text-slate-900 leading-tight flex items-center gap-1.5">
                                            <!-- Enlace en el Nombre -->
                                            <a href="{{ route('pets.show', $post->pet) }}" class="font-bold text-slate-900 leading-tight hover:text-purple-600 transition">
                                                {{ $post->pet->name }}
                                            </a>
                                            <span class="text-xs font-normal text-slate-400">por {{ $post->user->name }}</span>
                                        </h4>
                                        <span class="text-xs text-purple-600 font-medium">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <!-- 🗑️ BOTÓN DE BORRAR PUBLICACIÓN -->
                                @if($post->user_id === Auth::id())
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar este ladrido? 🐾');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-pink-600 p-1.5 rounded-xl hover:bg-pink-50 transition text-xs font-bold flex items-center gap-1">
                                            🗑️ <span class="hidden sm:inline">Borrar</span>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Contenido -->
                            <p class="text-slate-700 text-sm leading-relaxed">{{ $post->content }}</p>

                            @if($post->image)
                                <div class="rounded-2xl overflow-hidden border border-slate-100 max-h-96">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="w-full object-cover">
                                </div>
                            @endif

                            <!-- Acciones -->
                            <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                                <!-- Botón de Like (Me Guau) -->
                                <form action="{{ route('posts.like', $post) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-bold px-3.5 py-2 rounded-xl transition {{ $post->isLikedByUser(Auth::id()) ? 'bg-pink-500 text-white shadow-md shadow-pink-500/20' : 'bg-pink-50 text-pink-600 hover:bg-pink-100' }}">
                                        <span>❤️ Me Guau</span>
                                        <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $post->isLikedByUser(Auth::id()) ? 'bg-white/20 text-white' : 'bg-white text-pink-600 shadow-sm' }}">{{ $post->likes_count }}</span>
                                    </button>
                                </form>

                                <!-- Botón Desplegar Comentarios -->
                                <button @click="openComments = !openComments" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 px-3.5 py-2 rounded-xl transition">
                                    <span>💬 Comentarios</span>
                                    <span class="bg-white px-1.5 py-0.5 rounded-full text-[10px] shadow-sm">{{ $post->comments->count() }}</span>
                                </button>
                            </div>

                            <!-- Sección de Comentarios Desplegable -->
                            <div x-show="openComments" x-cloak class="pt-4 space-y-3 border-t border-slate-100">
                                <!-- Lista de Comentarios -->
                                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                                    @forelse($post->comments as $comment)
                                        <div class="bg-slate-50 p-3 rounded-2xl flex items-center justify-between text-xs">
                                            <div class="flex gap-2 items-center">
                                                <span class="font-extrabold text-purple-600">{{ $comment->pet->name }}:</span>
                                                <span class="text-slate-700">{{ $comment->content }}</span>
                                            </div>

                                            <!-- 🗑️ BOTÓN DE ELIMINAR COMENTARIO -->
                                            @if($comment->user_id === Auth::id())
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[10px] text-slate-400 hover:text-pink-600 font-semibold transition ml-2">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 italic text-center py-2">Sé el primero en ladrar un comentario 🐾</p>
                                    @endforelse
                                </div>

                                <!-- Formulario de nuevo comentario -->
                                <form action="{{ route('comments.store', $post) }}" method="POST" class="flex gap-2 pt-2">
                                    @csrf
                                    <select name="pet_id" class="text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-2.5">
                                        @foreach($userPets as $pet)
                                            <option value="{{ $pet->id }}">{{ $pet->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="content" placeholder="Escribe un comentario..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-3 focus:border-purple-500 focus:ring-purple-500" required>
                                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
                                        Responder
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-8 rounded-3xl text-center border border-purple-100">
                            <p class="text-slate-500 text-sm">No hay publicaciones todavía en esta sección 🐾</p>
                        </div>
                    @endforelse
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
