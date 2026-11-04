<x-app-layout>
    <div style="background-color: #faf5ff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem 1rem;">
        <!-- Contenedor principal adaptado para móviles -->
        <div style="width: 100%; max-width: 36rem; background-color: #ffffff; padding: 2rem 1.5rem; border-radius: 2.5rem; box-shadow: 0 25px 50px -12px rgba(147, 51, 234, 0.12); border: 1px solid #f3e8ff;"
             x-data="{ species: '{{ old('species', 'dog') }}' }">

            <div style="text-align: center; margin-bottom: 2rem;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 4.5rem; height: 4.5rem; background-color: #f3e8ff; border-radius: 9999px; font-size: 2.25rem; box-shadow: inset 0 2px 4px rgba(147, 51, 234, 0.1);">🐶</span>
                <h2 style="font-size: 1.5rem; font-weight: 900; color: #0f172a; margin-top: 0.75rem; letter-spacing: -0.025em;">Añadir un Nuevo Peludo</h2>
                <p style="font-size: 0.8rem; color: #64748b; margin-top: 0.25rem;">Expande tu familia en la red social</p>
            </div>

            <form action="{{ route('pets.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                @csrf

                <!-- Selector de Especie -->
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem;">¿Qué clase de peludo es?</label>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; width: 100%;">

                        <!-- Perro -->
                        <button type="button"
                                @click="species = 'dog'"
                                :style="species === 'dog' ? 'background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%); color: #ffffff; border-color: #7e22ce; box-shadow: 0 8px 16px -4px rgba(147, 51, 234, 0.4); transform: translateY(-2px);' : 'background-color: #faf5ff; color: #475569; border-color: #f3e8ff;'"
                                style="border-width: 2px; border-style: solid; border-radius: 1.5rem; padding: 1.15rem 0.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden;">

                            <div x-show="species === 'dog'" style="position: absolute; top: 6px; right: 6px; background: rgba(255,255,255,0.25); width: 16px; height: 16px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: bold;">✓</div>

                            <span style="font-size: 2rem; line-height: 1;">🐶</span>
                            <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Perro</span>
                        </button>

                        <!-- Gato -->
                        <button type="button"
                                @click="species = 'cat'"
                                :style="species === 'cat' ? 'background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%); color: #ffffff; border-color: #7e22ce; box-shadow: 0 8px 16px -4px rgba(147, 51, 234, 0.4); transform: translateY(-2px);' : 'background-color: #faf5ff; color: #475569; border-color: #f3e8ff;'"
                                style="border-width: 2px; border-style: solid; border-radius: 1.5rem; padding: 1.15rem 0.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden;">

                            <div x-show="species === 'cat'" style="position: absolute; top: 6px; right: 6px; background: rgba(255,255,255,0.25); width: 16px; height: 16px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: bold;">✓</div>

                            <span style="font-size: 2rem; line-height: 1;">🐱</span>
                            <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Gato</span>
                        </button>

                        <!-- Otro -->
                        <button type="button"
                                @click="species = 'other'"
                                :style="species === 'other' ? 'background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%); color: #ffffff; border-color: #7e22ce; box-shadow: 0 8px 16px -4px rgba(147, 51, 234, 0.4); transform: translateY(-2px);' : 'background-color: #faf5ff; color: #475569; border-color: #f3e8ff;'"
                                style="border-width: 2px; border-style: solid; border-radius: 1.5rem; padding: 1.15rem 0.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden;">

                            <div x-show="species === 'other'" style="position: absolute; top: 6px; right: 6px; background: rgba(255,255,255,0.25); width: 16px; height: 16px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: bold;">✓</div>

                            <span style="font-size: 2rem; line-height: 1;">🐰</span>
                            <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Otro</span>
                        </button>

                    </div>

                    <input type="hidden" name="species" x-model="species">
                    <x-input-error :messages="$errors->get('species')" style="margin-top: 0.25rem;" />
                </div>

                <!-- Nombre -->
                <div>
                    <label for="name" style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.3rem;">Nombre de la Mascota</label>
                    <input id="name" name="name" value="{{ old('name') }}" style="width: 100%; border-radius: 9999px; border: 1.5px solid #e9d5ff; padding: 0.75rem 1.25rem; background-color: #faf5ff; font-size: 0.85rem; color: #1e293b; outline: none;" type="text" required placeholder="Ej. Toby, Lola..." />
                    <x-input-error :messages="$errors->get('name')" style="margin-top: 0.25rem;" />
                </div>

                <!-- Raza -->
                <div>
                    <label for="breed" style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.3rem;">Raza / Mezcla (Opcional)</label>
                    <input id="breed" name="breed" value="{{ old('breed') }}" style="width: 100%; border-radius: 9999px; border: 1.5px solid #e9d5ff; padding: 0.75rem 1.25rem; background-color: #faf5ff; font-size: 0.85rem; color: #1e293b; outline: none;" type="text" placeholder="Ej. Pastor Alemán, Siamés..." />
                    <x-input-error :messages="$errors->get('breed')" style="margin-top: 0.25rem;" />
                </div>

                <!-- Biografía -->
                <div>
                    <label for="bio" style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.3rem;">Biografía corta</label>
                    <textarea id="bio" name="bio" rows="2" style="width: 100%; border-radius: 1.25rem; border: 1.5px solid #e9d5ff; padding: 0.75rem 1.25rem; background-color: #faf5ff; font-size: 0.85rem; color: #1e293b; outline: none; resize: none;" placeholder="Cuéntanos algo divertido sobre él/ella...">{{ old('bio') }}</textarea>
                    <x-input-error :messages="$errors->get('bio')" style="margin-top: 0.25rem;" />
                </div>

                <!-- Botones de Acción (Ajustados para responsividad móvil) -->
                <div style="padding-top: 0.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem;">
                    <a href="{{ route('dashboard') }}" style="font-size: 0.8rem; font-weight: 600; color: #64748b; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#9333ea'" onmouseout="this.style.color='#64748b'">
                        ← Cancelar
                    </a>
                    <button type="submit" style="background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%); color: #ffffff; font-weight: 700; font-size: 0.85rem; padding: 0.75rem 1.5rem; border-radius: 9999px; box-shadow: 0 8px 16px -4px rgba(147, 51, 234, 0.35); border: none; cursor: pointer; transition: all 0.2s; flex: 1; max-width: 160px; text-align: center;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        Guardar 🐾
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
