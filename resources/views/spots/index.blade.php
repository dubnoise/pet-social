<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-white leading-tight flex items-center gap-2">
            <span>🗺️</span> Mapa Pet-Friendly y Parques
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

                <!-- Columna Izquierda: Formulario de Creación y Listado de Spots -->
                <div class="space-y-6 lg:col-span-1">
                    <!-- Formulario -->
                    <div class="bg-white/95 backdrop-blur-md p-5 sm:p-6 rounded-3xl shadow-sm border border-purple-100 space-y-4">
                        <div class="flex items-center justify-between pb-1 border-b border-purple-50">
                            <h3 class="font-black text-sm uppercase tracking-wider text-purple-900">Añadir Nuevo Lugar 📍</h3>
                            <a href="{{ route('spots.my-spots') }}" class="text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 px-3 py-1.5 rounded-xl transition border border-purple-100 shadow-sm flex items-center gap-1">
                                📋 <span>Mis lugares</span>
                            </a>
                        </div>

                        <p class="text-xs text-slate-500">Usa tu ubicación actual con el botón o haz clic en el mapa.</p>

                        <button type="button" onclick="useCurrentLocation()" class="w-full bg-purple-100 hover:bg-purple-200 text-purple-800 font-bold py-2.5 rounded-xl text-xs transition flex items-center justify-center gap-2 border border-purple-200 shadow-sm">
                            <span>📍</span> Usar mi ubicación actual (GPS)
                        </button>

                        <form action="{{ route('spots.store') }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nombre del Lugar</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: Parque Canino del Turia" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 focus:border-purple-500 focus:ring-purple-500" required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Categoría</label>
                                <select name="category" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 focus:border-purple-500 focus:ring-purple-500" required>
                                    <option value="park">🌳 Parque / Zona Verde</option>
                                    <option value="cafe">☕ Cafetería / Restaurante</option>
                                    <option value="vet">🏥 Veterinario / Urgencias</option>
                                    <option value="store">🛒 Tienda de Mascotas</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Dirección orientativa</label>
                                <input type="text" name="address" value="{{ old('address') }}" placeholder="Ej: Calle Principal 12" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 focus:border-purple-500 focus:ring-purple-500" required>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Latitud</label>
                                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" readonly class="w-full text-xs rounded-xl border-slate-200 bg-slate-100 py-2 px-3 text-slate-500 cursor-not-allowed" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Longitud</label>
                                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" readonly class="w-full text-xs rounded-xl border-slate-200 bg-slate-100 py-2 px-3 text-slate-500 cursor-not-allowed" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Descripción / Consejos</label>
                                <textarea name="description" rows="2" placeholder="¿Tiene fuentes de agua? ¿Es obligatorio ir atado?" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 focus:border-purple-500 focus:ring-purple-500">{{ old('description') }}</textarea>
                            </div>

                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-xl text-xs transition shadow-md shadow-purple-600/25">
                                Guardar Ubicación 🐾
                            </button>
                        </form>
                    </div>

                    <!-- Listado Interactivo de Spots de la Comunidad -->
                    <div class="bg-white/95 backdrop-blur-md p-5 sm:p-6 rounded-3xl shadow-sm border border-purple-100 space-y-3">
                        <h3 class="font-black text-sm uppercase tracking-wider text-purple-900">Lugares de la Comunidad 🐾</h3>
                        <p class="text-xs text-slate-500">Haz clic en cualquier lugar para enfocarlo en el mapa.</p>

                        <div class="max-h-72 overflow-y-auto space-y-2 pr-1 divide-y divide-purple-50">
                            @forelse($spots as $spot)
                                <div onclick="focusSpot({{ $spot->latitude }}, {{ $spot->longitude }}, {{ $spot->id }})" class="pt-3 first:pt-0 p-2.5 rounded-2xl hover:bg-purple-50 transition cursor-pointer flex items-center justify-between group">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs">
                                                @if($spot->category === 'park') 🌳
                                                @elseif($spot->category === 'cafe') ☕
                                                @elseif($spot->category === 'vet') 🏥
                                                @else 🛒 @endif
                                            </span>
                                            <h4 class="font-bold text-xs text-slate-800 group-hover:text-purple-700 transition">{{ $spot->name }}</h4>
                                        </div>
                                        <p class="text-[11px] text-slate-500 truncate max-w-[220px] mt-0.5">📍 {{ $spot->address }}</p>
                                    </div>
                                    <span class="text-xs text-purple-400 group-hover:translate-x-1 transition">Ver →</span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 text-center py-4">No hay lugares registrados todavía.</p>
                            @endforelse
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
        let spotMarkers = {}; // Almacenará los marcadores por ID de spot

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
                        alert('No se pudo obtener tu ubicación automática. Haz clic en el mapa.');
                    }
                );
            } else {
                alert('Tu navegador no soporta geolocalización.');
            }
        }

        // Función para hacer zoom y abrir el popup al hacer clic en un elemento de la lista
        function focusSpot(lat, lng, spotId) {
            map.setView([lat, lng], 16); // Hace zoom al lugar seleccionado
            if (spotMarkers[spotId]) {
                spotMarkers[spotId].openPopup(); // Abre su tarjeta informativa
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            let defaultLat = 39.4699;
            let defaultLng = -0.3763;

            initMap(defaultLat, defaultLng);

            const spots = @json($spots);

            if (spots.length > 0) {
                // Centrar el mapa inicialmente en el primer spot si existe
                defaultLat = spots[0].latitude;
                defaultLng = spots[0].longitude;
                map.setView([defaultLat, defaultLng], 13);
            }

            spots.forEach(spot => {
                let iconEmoji = '🐾';
                if(spot.category === 'park') iconEmoji = '🌳';
                if(spot.category === 'cafe') iconEmoji = '☕';
                if(spot.category === 'vet') iconEmoji = '🏥';
                if(spot.category === 'store') iconEmoji = '🛒';

                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: #9333ea; color: white; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.2); border: 2px solid white;">${iconEmoji}</div>`,
                    iconSize: [34, 34],
                    iconAnchor: [17, 17]
                });

                const popupContent = `
                    <div style="font-family: inherit; padding: 6px; min-width: 180px;">
                        <span style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #7e22ce; background: #f3e8ff; padding: 2px 6px; border-radius: 4px;">${spot.category}</span>
                        <h4 style="font-weight: 800; font-size: 14px; margin: 6px 0 2px 0; color: #1e293b;">${spot.name}</h4>
                        <p style="font-size: 11px; color: #475569; margin: 2px 0;">📍 ${spot.address}</p>
                        ${spot.description ? `<p style="font-size: 11px; color: #64748b; margin-top: 4px; font-style: italic;">"${spot.description}"</p>` : ''}
                        <span style="font-size: 9px; color: #94a3b8; display: block; margin-top: 6px; border-top: 1px solid #f1f5f9; pt-1;">Aportado por ${spot.user.name}</span>
                    </div>
                `;

                const spotMarker = L.marker([spot.latitude, spot.longitude], { icon: customIcon })
                    .addTo(map)
                    .bindPopup(popupContent);

                // Guardar referencia del marcador en el objeto
                spotMarkers[spot.id] = spotMarker;
            });
        });
    </script>
</x-app-layout>
