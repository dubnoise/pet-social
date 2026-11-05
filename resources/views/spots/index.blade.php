<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-white leading-tight flex items-center gap-2">
            <span>🗺️</span> Mapa de Zonas de Paseo 🐾
        </h2>
    </x-slot>

    <!-- Importar Leaflet CSS y JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-pink-50 border border-pink-200 text-pink-800 px-4 py-3 rounded-2xl text-xs font-semibold shadow-sm">
                    <p class="font-bold mb-1">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Columna Izquierda: Botón de añadir y Listado de Spots -->
                <div class="space-y-6 lg:col-span-1" x-data="{ openAddModal: false, openCommunitySpots: false, visibleLimit: 5 }">

                    <!-- Botón Principal para Desplegar el Formulario de Creación -->
                    <div class="bg-white/95 backdrop-blur-md p-5 rounded-3xl shadow-sm border border-purple-100 flex items-center justify-between gap-2">
                        <div>
                            <h3 class="font-black text-sm uppercase tracking-wider text-purple-900">Zonas de Paseo 🐾</h3>
                            <p class="text-[11px] text-slate-500">Descubre o comparte sitios perrunos.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('spots.my-spots') }}" class="text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 px-3 py-2 rounded-xl transition border border-purple-100 shadow-sm" title="Mis Lugares">
                                📋
                            </a>
                            <button @click="openAddModal = !openAddModal" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-3.5 py-2 rounded-xl text-xs transition shadow-md shadow-purple-600/25 flex items-center gap-1 cursor-pointer">
                                <span x-text="openAddModal ? '✕ Cerrar' : '+ Añadir'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Formulario Desplegable -->
                    <div x-show="openAddModal" x-cloak x-collapse class="bg-white/95 backdrop-blur-md p-5 sm:p-6 rounded-3xl shadow-sm border border-purple-100 space-y-4">
                        <div class="pb-1 border-b border-purple-50">
                            <h3 class="font-black text-sm uppercase tracking-wider text-purple-900">Nueva Zona de Paseo 📍</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Haz clic en el mapa de la derecha para señalar la ubicación exacta o usa tu GPS.</p>
                        </div>

                        <button type="button" onclick="useCurrentLocation()" class="w-full bg-purple-100 hover:bg-purple-200 text-purple-800 font-bold py-2.5 rounded-xl text-xs transition flex items-center justify-center gap-2 border border-purple-200 shadow-sm cursor-pointer">
                            <span>📍</span> Usar mi ubicación actual (GPS)
                        </button>

                        <form action="{{ route('spots.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf

                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}" required>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nombre del Lugar</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: Parque Canino del Turia" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 focus:border-purple-500 focus:ring-purple-500 font-medium" required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Categoría</label>
                                <select name="category" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 focus:border-purple-500 focus:ring-purple-500 font-bold text-purple-900" required>
                                    <option value="" disabled selected>Selecciona una categoría...</option>
                                    <option value="parque">🌳 Parque / Zona ajardinada</option>
                                    <option value="pipican">🐕 Pipican / Área canina</option>
                                    <option value="descampado">🌾 Descampado / Zona abierta</option>
                                    <option value="zona_general">🚶‍♂️ Zona de paseo general</option>
                                    <option value="playa_perros">🏖️ Playa para perros</option>
                                    <option value="montaña">⛰️ Ruta de montaña / Sendero</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Dirección orientativa</label>
                                <input type="text" name="address" value="{{ old('address') }}" placeholder="Ej: Calle Principal 12" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 focus:border-purple-500 focus:ring-purple-500" required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Descripción / Consejos</label>
                                <textarea name="description" rows="2" placeholder="¿Tiene fuentes de agua? ¿Es obligatorio ir atado?" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 focus:border-purple-500 focus:ring-purple-500">{{ old('description') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Foto del lugar (opcional)</label>
                                <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer">
                            </div>

                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-xl text-xs transition shadow-md shadow-purple-600/25 cursor-pointer">
                                Guardar Zona de Paseo 🐾
                            </button>
                        </form>
                    </div>

                    <!-- Zonas de la Comunidad Desplegable -->
                    <div class="bg-white/95 backdrop-blur-md p-5 rounded-3xl shadow-sm border border-purple-100 space-y-3">
                        <div class="flex items-center justify-between cursor-pointer" @click="openCommunitySpots = !openCommunitySpots">
                            <div>
                                <h3 class="font-black text-sm uppercase tracking-wider text-purple-900">Zonas de la Comunidad 🐾</h3>
                                <p class="text-[11px] text-slate-500">Explora los últimos lugares aportados.</p>
                            </div>
                            <button class="text-xs font-bold bg-purple-50 text-purple-700 hover:bg-purple-100 px-3 py-1.5 rounded-xl transition flex items-center gap-1 border border-purple-100 shadow-sm">
                                <span x-text="openCommunitySpots ? 'Ocultar' : 'Ver ({{ count($spots) }})'"></span>
                                <span class="transition-transform duration-200" :class="openCommunitySpots ? 'rotate-180' : ''">▼</span>
                            </button>
                        </div>

                        <!-- Contenido Desplegable (Con tope inicial de 5 y botón ver más) -->
                        <div x-show="openCommunitySpots" x-cloak x-collapse class="space-y-3 pt-2">
                            <div class="max-h-80 overflow-y-auto space-y-2 pr-1 divide-y divide-purple-50">
                                @forelse($spots as $index => $spot)
                                    <div x-show="{{ $index }} < visibleLimit" onclick="focusSpot({{ $spot->latitude }}, {{ $spot->longitude }}, {{ $spot->id }})" class="pt-3 first:pt-0 p-2.5 rounded-2xl hover:bg-purple-50 transition cursor-pointer flex items-center justify-between group">
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-xs">
                                                    @if($spot->category === 'parque') 🌳
                                                    @elseif($spot->category === 'pipican') 🐕
                                                    @elseif($spot->category === 'descampado') 🌾
                                                    @elseif($spot->category === 'playa_perros') 🏖️
                                                    @elseif($spot->category === 'montaña') ⛰️
                                                    @else 🚶‍♂️ @endif
                                                </span>
                                                <h4 class="font-bold text-xs text-slate-800 group-hover:text-purple-700 transition">{{ $spot->name }}</h4>
                                            </div>
                                            <p class="text-[11px] text-slate-500 truncate max-w-[200px] mt-0.5">📍 {{ $spot->address }}</p>
                                            <span class="text-[10px] text-purple-500 font-semibold">Por {{ $spot->user->name }}</span>
                                        </div>
                                        <span class="text-xs text-purple-400 group-hover:translate-x-1 transition">Ver →</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 text-center py-4">No hay zonas de paseo registradas todavía.</p>
                                @endforelse
                            </div>

                            <!-- Botón Ver más -->
                            @if(count($spots) > 5)
                                <div class="text-center pt-2" x-show="visibleLimit < {{ count($spots) }}">
                                    <button @click="visibleLimit += 5" class="text-xs font-bold text-purple-600 hover:text-purple-800 bg-purple-50 hover:bg-purple-100 w-full py-2 rounded-xl transition cursor-pointer">
                                        Ver más zonas 🔽
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: El Mapa Interactivo -->
                <div class="bg-white p-3 sm:p-4 rounded-3xl shadow-sm border border-purple-100 lg:col-span-2 h-[500px] sm:h-[750px] relative overflow-hidden">
                    <div id="map" class="w-full h-full rounded-2xl z-10"></div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script de Inicialización y Interactividad -->
    <script>
        let map, marker;
        let spotMarkers = {};

        function initMap(lat, lng) {
            if (map) {
                map.setView([lat, lng], 15);
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }
            } else {
                map = L.map('map').setView([lat, lng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                map.on('click', function(e) {
                    const clickLat = e.latlng.lat;
                    const clickLng = e.latlng.lng;

                    document.getElementById('latitude').value = clickLat.toFixed(6);
                    document.getElementById('longitude').value = clickLng.toFixed(6);

                    if (marker) {
                        marker.setLatLng([clickLat, clickLng]);
                    } else {
                        marker = L.marker([clickLat, clickLng]).addTo(map);
                    }
                });
            }

            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
        }

        function useCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        initMap(position.coords.latitude, position.coords.longitude);
                    },
                    error => {
                        alert('No se pudo obtener tu ubicación automática. Haz clic directamente en el mapa.');
                    }
                );
            } else {
                alert('Tu navegador no soporta geolocalización.');
            }
        }

        function focusSpot(lat, lng, spotId) {
            map.setView([lat, lng], 16);
            if (spotMarkers[spotId]) {
                spotMarkers[spotId].openPopup();
            }
        }

        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modalImg.src = imageSrc;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.getElementById('modalImage').src = '';
        }

        document.addEventListener("DOMContentLoaded", function () {
            let defaultLat = 39.4699;
            let defaultLng = -0.3763;

            initMap(defaultLat, defaultLng);

            const spots = @json($spots);

            if (spots.length > 0) {
                defaultLat = spots[0].latitude;
                defaultLng = spots[0].longitude;
                map.setView([defaultLat, defaultLng], 13);
            }

            spots.forEach(spot => {
                let iconEmoji = '🐾';
                if(spot.category === 'parque') iconEmoji = '🌳';
                if(spot.category === 'pipican') iconEmoji = '🐕';
                if(spot.category === 'descampado') iconEmoji = '🌾';
                if(spot.category === 'zona_general') iconEmoji = '🚶‍♂️';
                if(spot.category === 'playa_perros') iconEmoji = '🏖️';
                if(spot.category === 'montaña') iconEmoji = '⛰️';

                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: #9333ea; color: white; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.2); border: 2px solid white;">${iconEmoji}</div>`,
                    iconSize: [34, 34],
                    iconAnchor: [17, 17]
                });

                // 📸 Popup enriquecido con imagen ampliable al hacer clic
                const popupContent = `
                    <div style="font-family: inherit; padding: 4px; min-width: 200px; max-width: 240px;">
                        <span style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #7e22ce; background: #f3e8ff; padding: 2px 6px; border-radius: 4px;">${spot.category}</span>
                        <h4 style="font-weight: 800; font-size: 14px; margin: 6px 0 2px 0; color: #1e293b;">${spot.name}</h4>
                        <p style="font-size: 11px; color: #475569; margin: 2px 0;">📍 ${spot.address}</p>

                        ${spot.image ? `
                            <div style="margin-top: 8px; border-radius: 12px; overflow: hidden; border: 1px solid #e9d5ff; position: relative; cursor: pointer;" title="Haz clic para ampliar" onclick="openImageModal('/storage/${spot.image}')">
                                <img src="/storage/${spot.image}" alt="${spot.name}" style="width: 100%; height: 130px; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'" />
                                <div style="position: absolute; bottom: 6px; right: 6px; background: rgba(0,0,0,0.6); color: white; font-size: 9px; padding: 2px 6px; border-radius: 6px; pointer-events: none;">🔍 Ampliar</div>
                            </div>
                        ` : ''}

                        ${spot.description ? `<p style="font-size: 11px; color: #64748b; margin-top: 6px; font-style: italic;">"${spot.description}"</p>` : ''}

                        <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 9px; color: #94a3b8;">Aportado por ${spot.user.name}</span>
                        </div>
                    </div>
                `;

                const spotMarker = L.marker([spot.latitude, spot.longitude], { icon: customIcon })
                    .addTo(map)
                    .bindPopup(popupContent);

                spotMarkers[spot.id] = spotMarker;
            });
        });
    </script>

    <!-- 🖼️ Modal para Ampliar Imagen -->
    <div id="imageModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4 transition-opacity duration-300" onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-[90vh] w-full flex items-center justify-center" onclick="event.stopPropagation()">
            <!-- Botón de cerrar -->
            <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white bg-white/20 hover:bg-white/30 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold transition cursor-pointer">
                ✕
            </button>
            <!-- Imagen ampliada -->
            <img id="modalImage" src="" alt="Imagen ampliada" class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl border border-white/10" />
        </div>
    </div>

</x-app-layout>
