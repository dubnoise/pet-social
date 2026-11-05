<x-app-layout>
    <!-- Contenedor principal con el array de fotos del álbum para el visor de galería -->
    @php
        $albumPhotos = $pet->posts->whereNotNull('image')->pluck('image')->map(fn($img) => asset('storage/' . $img))->values()->toArray();
    @endphp

    <div class="py-8 bg-gradient-to-b from-[#e9d5ff] via-[#f3e8ff] to-[#ede9fe] min-h-screen"
         x-data="{
             activeImage: null,
             activeAvatar: null,
             images: @js($albumPhotos),
             currentIndex: 0,
             openGallery(imgUrl) {
                 this.activeImage = imgUrl;
                 this.currentIndex = this.images.indexOf(imgUrl);
                 if (this.currentIndex === -1) {
                     this.images.push(imgUrl);
                     this.currentIndex = this.images.length - 1;
                 }
             },
             nextImage() {
                 if (this.images.length === 0) return;
                 this.currentIndex = (this.currentIndex + 1) % this.images.length;
                 this.activeImage = this.images[this.currentIndex];
             },
             prevImage() {
                 if (this.images.length === 0) return;
                 this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                 this.activeImage = this.images[this.currentIndex];
             }
         }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Card de Perfil de la Mascota -->
            <div class="bg-white/90 backdrop-blur-md p-6 sm:p-8 rounded-3xl shadow-sm border border-purple-200 space-y-6">
                <div class="flex flex-col sm:flex-row items-center gap-6">

                    <!-- 📸 INICIO: Avatar de la Mascota con soporte para imagen y cambio rápido -->
                    <div class="relative group">
                        <div class="w-24 h-24 bg-gradient-to-tr from-purple-600 to-indigo-500 text-white rounded-3xl flex items-center justify-center text-4xl font-black shadow-lg shadow-purple-500/30 border-2 border-white overflow-hidden">
                            @if($pet->avatar)
                                <img src="{{ asset('storage/' . $pet->avatar) }}" alt="{{ $pet->name }}" class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-300" @click="activeAvatar = '{{ asset('storage/' . $pet->avatar) }}'">
                            @else
                                <span class="cursor-default">{{ $pet->species === 'cat' ? '🐱' : '🐶' }}</span>
                            @endif
                        </div>

                        <!-- Botón para cambiar foto (Visible solo si es el dueño de la mascota) -->
                        @if(Auth::user()->pets->pluck('id')->contains($pet->id))
                            <button @click="$refs.avatarInput.click()" class="absolute -bottom-2 -right-2 bg-purple-600 hover:bg-purple-700 text-white p-2 rounded-xl shadow-md transition text-xs flex items-center justify-center cursor-pointer hover:scale-110" title="Cambiar foto de perfil">
                                📷
                            </button>

                            <!-- Formulario oculto para enviar la foto automáticamente al seleccionarla -->
                            <form action="{{ route('pets.avatar.update', $pet) }}" method="POST" enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="file" x-ref="avatarInput" name="avatar" accept="image/*" @change="$el.form.submit()">
                            </form>
                        @endif
                    </div>
                    <!-- 📸 FIN DEL AVATAR -->

                    <div class="flex-1 text-center sm:text-left space-y-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h1 class="text-2xl font-black text-slate-900 leading-tight">{{ $pet->name }}</h1>
                                <p class="text-xs font-semibold text-purple-700 bg-purple-100 px-3 py-1 rounded-full w-fit mx-auto sm:mx-0 mt-1 border border-purple-200">
                                    {{ ucfirst($pet->species_in_spanish) }} • {{ $pet->breed ?: 'Mestizo' }}
                                </p>
                            </div>

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

                <!-- Estadisticas -->
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-purple-100 text-center">
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

                <!-- Insignias -->
                <div class="pt-4 border-t border-purple-100 space-y-3" x-data="{ activeBadge: null }">
                    <h3 class="font-black text-xs uppercase tracking-wider text-purple-900 flex items-center gap-1.5">
                        <span>🏆</span> Vitrina de Logros e Insignias
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @forelse($pet->badges as $badge)
                            <div @click="activeBadge = { name: '{{ $badge->name }}', description: '{{ $badge->description }}', icon: '{{ $badge->icon }}' }"
                                class="bg-purple-50/70 border border-purple-100 p-3 rounded-2xl text-center space-y-1 hover:scale-105 transition shadow-sm cursor-pointer group">
                                <span class="text-2xl block group-hover:scale-110 transition">{{ $badge->icon }}</span>
                                <h4 class="font-bold text-xs text-purple-900 truncate">{{ $badge->name }}</h4>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 col-span-full text-center py-3">Aún no ha desbloqueado ninguna insignia. ¡Participa más en la comunidad! 🐾</p>
                        @endforelse
                    </div>

                    <template x-if="activeBadge">
                        <div class="fixed inset-0 bg-purple-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="activeBadge = null">
                            <div class="bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl border border-purple-100 text-center space-y-4 relative animate-in fade-in zoom-in duration-200">
                                <button @click="activeBadge = null" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                                <div class="w-16 h-16 bg-purple-100 rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-inner">
                                    <span x-text="activeBadge.icon"></span>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-black text-base text-purple-900" x-text="activeBadge.name"></h4>
                                    <p class="text-xs text-slate-600 leading-relaxed" x-text="activeBadge.description"></p>
                                </div>
                                <button @click="activeBadge = null" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-md shadow-purple-600/20">
                                    ¡Genial! 🐾
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Pestañas -->
            <div x-data="{ tab: 'posts' }" class="space-y-6">
                <div class="flex flex-wrap items-center gap-2 bg-white/90 backdrop-blur-md p-2 rounded-2xl border border-purple-200 shadow-sm w-fit mx-auto sm:mx-0">
                    <button @click="tab = 'posts'" :class="tab === 'posts' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-500/20' : 'text-slate-600 hover:bg-purple-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition">
                        📸 Publicaciones ({{ $pet->posts->count() }})
                    </button>
                    <button @click="tab = 'album'" :class="tab === 'album' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-500/20' : 'text-slate-600 hover:bg-purple-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition">
                        🖼️ Álbum ({{ $pet->posts->whereNotNull('image')->count() }})
                    </button>
                    <button @click="tab = 'followers'" :class="tab === 'followers' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-500/20' : 'text-slate-600 hover:bg-purple-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition">
                        🐾 Seguidores ({{ $pet->followers->count() }})
                    </button>
                    <button @click="tab = 'following'" :class="tab === 'following' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-500/20' : 'text-slate-600 hover:bg-purple-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition">
                        🐕 Seguidos ({{ $pet->following->count() }})
                    </button>
                </div>

                <!-- Publicaciones -->
                <div x-show="tab === 'posts'" class="space-y-4">
                    @forelse($pet->posts as $post)
                        <div class="bg-white/90 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-purple-200 space-y-4">
                            <span class="text-xs text-purple-600 font-semibold">{{ $post->created_at->diffForHumans() }}</span>
                            <p class="text-slate-800 text-sm leading-relaxed">{{ $post->content }}</p>

                            @if($post->image)
                                <div class="rounded-2xl overflow-hidden border border-purple-100 max-h-96 cursor-pointer group relative"
                                     @click="openGallery('{{ asset('storage/' . $post->image) }}')">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="w-full object-cover group-hover:scale-105 transition duration-300">
                                    <div class="absolute inset-0 bg-purple-950/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <span class="bg-white/90 text-purple-900 text-xs font-bold px-3 py-1.5 rounded-xl shadow-md backdrop-blur-sm">🔍 Ampliar imagen</span>
                                    </div>
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

                <!-- Álbum -->
                <div x-show="tab === 'album'" x-cloak class="bg-white/90 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-purple-200 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-black text-sm uppercase tracking-wider text-purple-900 flex items-center gap-2">
                            <span>📸</span> Galería de Recuerdos
                        </h3>
                        <span class="text-xs font-bold bg-purple-100 text-purple-700 px-2.5 py-1 rounded-full">
                            {{ $pet->posts->whereNotNull('image')->count() }} fotos
                        </span>
                    </div>

                    @php $galleryPhotos = $pet->posts->whereNotNull('image'); @endphp

                    @if($galleryPhotos->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($galleryPhotos as $photo)
                                <div class="aspect-square rounded-2xl overflow-hidden border border-purple-100 bg-slate-50 cursor-pointer group relative shadow-sm"
                                     @click="openGallery('{{ asset('storage/' . $photo->image) }}')">
                                    <img src="{{ asset('storage/' . $photo->image) }}" alt="Foto del álbum" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    <div class="absolute inset-0 bg-purple-950/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center backdrop-blur-[2px]">
                                        <span class="bg-white/90 text-purple-900 text-xs font-bold px-3 py-1.5 rounded-xl shadow-md">🔍 Ampliar</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 bg-purple-50/50 rounded-2xl border border-dashed border-purple-200">
                            <span class="text-3xl block mb-2">🐾</span>
                            <p class="text-xs text-slate-500 font-medium">Aún no hay fotos en el álbum de esta mascota.</p>
                        </div>
                    @endif
                </div>

                <!-- Seguidores / Seguidos -->
                <div x-show="tab === 'followers'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($pet->followers as $follower)
                        <a href="{{ route('pets.show', $follower) }}" class="flex items-center gap-4 p-4 rounded-3xl bg-white/90 backdrop-blur-md border border-purple-200 hover:border-purple-400 hover:shadow-md transition group">
                            <div class="w-12 h-12 bg-purple-100 text-purple-700 rounded-2xl flex items-center justify-center text-xl font-bold group-hover:scale-105 transition">
                                {{ $follower->species === 'cat' ? '🐱' : '🐶' }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 group-hover:text-purple-600 transition">{{ $follower->name }}</h4>
                                <p class="text-xs text-purple-600 font-medium">{{ $follower->breed ?: 'Mestizo' }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full bg-white/90 backdrop-blur-md p-8 rounded-3xl text-center border border-purple-200">
                            <p class="text-slate-500 text-sm">Aún no tiene seguidores en su manada 🐕</p>
                        </div>
                    @endforelse
                </div>

                <div x-show="tab === 'following'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($pet->following as $followed)
                        <a href="{{ route('pets.show', $followed) }}" class="flex items-center gap-4 p-4 rounded-3xl bg-white/90 backdrop-blur-md border border-purple-200 hover:border-purple-400 hover:shadow-md transition group">
                            <div class="w-12 h-12 bg-purple-100 text-purple-700 rounded-2xl flex items-center justify-center text-xl font-bold group-hover:scale-105 transition">
                                {{ $followed->species === 'cat' ? '🐱' : '🐶' }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 group-hover:text-purple-600 transition">{{ $followed->name }}</h4>
                                <p class="text-xs text-purple-600 font-medium">{{ $followed->breed ?: 'Mestizo' }}</p>
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

        <!-- 🌟 VISOR DE GALERÍA AVANZADO (Con Siguiente/Anterior, Lista de Miniaturas inferior y Zoom con arrastre) -->
        <template x-if="activeImage">
            <div class="fixed inset-0 w-screen h-screen z-[999999] bg-black/95 backdrop-blur-2xl flex flex-col items-center justify-between overflow-hidden select-none"
                 x-data="{
                     scale: 1,
                     isDragging: false,
                     startX: 0,
                     startY: 0,
                     translateX: 0,
                     translateY: 0,
                     startDrag(e) {
                         if (this.scale > 1) {
                             this.isDragging = true;
                             this.startX = (e.clientX || e.touches[0].clientX) - this.translateX;
                             this.startY = (e.clientY || e.touches[0].clientY) - this.translateY;
                         }
                     },
                     onDrag(e) {
                         if (!this.isDragging) return;
                         const clientX = e.clientX || (e.touches ? e.touches[0].clientX : 0);
                         const clientY = e.clientY || (e.touches ? e.touches[0].clientY : 0);
                         this.translateX = clientX - this.startX;
                         this.translateY = clientY - this.startY;
                     },
                     stopDrag() {
                         this.isDragging = false;
                     },
                     resetZoom() {
                         this.scale = 1;
                         this.translateX = 0;
                         this.translateY = 0;
                     }
                 }"
                 @keydown.escape.window="activeImage = null"
                 @keydown.arrow-right.window="nextImage(); resetZoom();"
                 @keydown.arrow-left.window="prevImage(); resetZoom();"
                 @mousemove.window="onDrag($event)"
                 @mouseup.window="stopDrag()"
                 @touchmove="onDrag($event)"
                 @touchend="stopDrag()">

                <!-- Barra Superior con Indicador de Posición y Cierre -->
                <div class="w-full p-4 sm:p-6 flex items-center justify-between z-[9999999] bg-gradient-to-b from-black/90 to-transparent pointer-events-auto">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/10 text-white text-xs font-medium shadow-lg">
                            <span>📷 <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span></span>
                        </div>
                        <div class="hidden sm:block bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/10 text-white text-xs font-medium shadow-lg">
                            <span>🔍 <span x-text="Math.round(scale * 100)"></span>%</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button @click="resetZoom()" class="bg-white/10 hover:bg-white/20 text-white p-2.5 rounded-full transition backdrop-blur-md border border-white/10 text-xs flex items-center justify-center shadow-lg" title="Restaurar zoom">
                            🔄
                        </button>
                        <button @click="activeImage = null" class="bg-white/20 hover:bg-white/30 text-white font-bold px-4 py-2 rounded-full transition backdrop-blur-md border border-white/20 text-xs shadow-lg flex items-center gap-1.5">
                            ✕ Cerrar
                        </button>
                    </div>
                </div>

                <!-- Botones de Navegación Lateral (Anterior / Siguiente) -->
                <button @click="prevImage(); resetZoom();" class="absolute left-4 top-1/2 -translate-y-1/2 z-50 bg-white/10 hover:bg-white/25 text-white p-3 rounded-full backdrop-blur-md border border-white/15 transition shadow-2xl">
                    ❮
                </button>
                <button @click="nextImage(); resetZoom();" class="absolute right-4 top-1/2 -translate-y-1/2 z-50 bg-white/10 hover:bg-white/25 text-white p-3 rounded-full backdrop-blur-md border border-white/15 transition shadow-2xl">
                    ❯
                </button>

                <!-- Área Central de la Imagen -->
                <div class="flex-1 w-full flex items-center justify-center overflow-hidden touch-none px-12"
                     @click.self="activeImage = null"
                     @wheel.prevent="
                         scale = Math.max(1, Math.min(4, scale + ($event.deltaY > 0 ? -0.2 : 0.2)));
                         if(scale === 1) { translateX = 0; translateY = 0; }
                     ">

                    <img :src="activeImage"
                         alt="Imagen ampliada"
                         class="max-w-[85vw] max-h-[70vh] object-contain rounded-2xl shadow-2xl transition-transform duration-75 ease-out select-none"
                         :class="scale > 1 ? 'cursor-grab active:cursor-grabbing' : 'cursor-zoom-in'"
                         :style="`transform: scale(${scale}) translate(${translateX}px, ${translateY}px);`"
                         @mousedown="startDrag($event)"
                         @touchstart="startDrag($event)"
                         @dblclick="
                             scale = scale > 1 ? 1 : 2.5;
                             translateX = 0;
                             translateY = 0;
                         "
                         draggable="false">
                </div>

                <!-- Tira Inferior de Miniaturas (Lista de Fotos Interactiva) -->
                <div class="w-full p-4 bg-gradient-to-t from-black/90 to-transparent z-50 flex items-center justify-center">
                    <div class="flex items-center gap-2 overflow-x-auto max-w-xl py-2 px-3 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10">
                        <template x-for="(img, idx) in images" :key="idx">
                            <button @click="currentIndex = idx; activeImage = img; resetZoom();"
                                    class="w-12 h-12 rounded-xl overflow-hidden border-2 transition flex-shrink-0 relative"
                                    :class="currentIndex === idx ? 'border-purple-400 scale-110 shadow-lg' : 'border-transparent opacity-60 hover:opacity-100'">
                                <img :src="img" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </div>

            </div>
        </template>

        <!-- 🖼️ VISOR EXCLUSIVO PARA LA FOTO DE PERFIL (Sin flechas ni galería) -->
        <template x-if="activeAvatar">
            <div class="fixed inset-0 w-screen h-screen z-[999999] bg-black/95 backdrop-blur-2xl flex flex-col items-center justify-center overflow-hidden select-none"
                x-data="{
                    scale: 1,
                    isDragging: false,
                    startX: 0,
                    startY: 0,
                    translateX: 0,
                    translateY: 0,
                    startDrag(e) {
                        if (this.scale > 1) {
                            this.isDragging = true;
                            this.startX = (e.clientX || e.touches[0].clientX) - this.translateX;
                            this.startY = (e.clientY || e.touches[0].clientY) - this.translateY;
                        }
                    },
                    onDrag(e) {
                        if (!this.isDragging) return;
                        const clientX = e.clientX || (e.touches ? e.touches[0].clientX : 0);
                        const clientY = e.clientY || (e.touches ? e.touches[0].clientY : 0);
                        this.translateX = clientX - this.startX;
                        this.translateY = clientY - this.startY;
                    },
                    stopDrag() { this.isDragging = false; },
                    resetZoom() { this.scale = 1; this.translateX = 0; this.translateY = 0; }
                }"
                @keydown.escape.window="activeAvatar = null"
                @mousemove.window="onDrag($event)"
                @mouseup.window="stopDrag()"
                @touchmove="onDrag($event)"
                @touchend="stopDrag()">

                <!-- Barra Superior Limpia -->
                <div class="absolute top-0 inset-x-0 p-4 sm:p-6 flex items-center justify-between z-[9999999] bg-gradient-to-b from-black/90 to-transparent pointer-events-auto">
                    <div class="bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/10 text-white text-xs font-medium shadow-lg">
                        <span>👤 Foto de Perfil</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="resetZoom()" class="bg-white/10 hover:bg-white/20 text-white p-2.5 rounded-full transition backdrop-blur-md border border-white/10 text-xs flex items-center justify-center shadow-lg" title="Restaurar zoom">🔄</button>
                        <button @click="activeAvatar = null" class="bg-white/20 hover:bg-white/30 text-white font-bold px-4 py-2 rounded-full transition backdrop-blur-md border border-white/20 text-xs shadow-lg flex items-center gap-1.5">✕ Cerrar</button>
                    </div>
                </div>

                <!-- Área Central con Zoom y Arrastre -->
                <div class="w-full h-full flex items-center justify-center overflow-hidden touch-none px-4"
                    @click.self="activeAvatar = null"
                    @wheel.prevent="
                        scale = Math.max(1, Math.min(4, scale + ($event.deltaY > 0 ? -0.2 : 0.2)));
                        if(scale === 1) { translateX = 0; translateY = 0; }
                    ">
                    <img :src="activeAvatar"
                        alt="Foto de perfil ampliada"
                        class="max-w-[85vw] max-h-[80vh] object-contain rounded-2xl shadow-2xl transition-transform duration-75 ease-out select-none"
                        :class="scale > 1 ? 'cursor-grab active:cursor-grabbing' : 'cursor-zoom-in'"
                        :style="`transform: scale(${scale}) translate(${translateX}px, ${translateY}px);`"
                        @mousedown="startDrag($event)"
                        @touchstart="startDrag($event)"
                        @dblclick="scale = scale > 1 ? 1 : 2.5; translateX = 0; translateY = 0;"
                        draggable="false">
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
