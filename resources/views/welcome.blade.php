<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bark & Social - La red social de tus mascotas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-purple-50/40 text-slate-800 font-sans antialiased selection:bg-purple-600 selection:text-white">

    <!-- Navbar Flotante -->
    <header class="fixed top-4 inset-x-0 max-w-6xl mx-auto px-4 z-50">
        <nav class="bg-white/70 backdrop-blur-md rounded-full px-6 py-3 border border-purple-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-2xl">🐾</span>
                <span class="font-extrabold text-xl tracking-tight bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">BarkSocial</span>
            </div>

            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-sm text-slate-700 hover:text-purple-600 transition">Mi Feed</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 px-3 py-2">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-full shadow-lg shadow-purple-600/20 transition hover:scale-105">Únete Gratis</a>
                    @endauth
                @endif
            </div>
        </nav>
    </header>

    <!-- Hero Section con Bento Grid -->
    <main class="pt-32 pb-20 px-4 max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

            <!-- Pitch Principal -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-xs font-bold tracking-wide uppercase">
                    ✨ La comunidad canina #1
                </span>
                <h1 class="text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.15]">
                    El mundo visto a través de los ojos de tu <span class="underline decoration-purple-400 decoration-wavy">mejor amigo</span>.
                </h1>
                <p class="text-lg text-slate-600 max-w-xl mx-auto lg:mx-0">
                    Crea el perfil de tus huellitas, comparte sus mejores ladridos, encuentra compañeros de paseo cerca de ti y descubre un espacio hecho por y para amantes de los animales.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                    <a href="{{ route('register') }}" class="inline-flex justify-center items-center gap-2 text-base font-bold bg-purple-600 hover:bg-purple-700 text-white px-8 py-4 rounded-2xl shadow-xl shadow-purple-600/20 transition-all hover:-translate-y-0.5">
                        Registrar a mi Peludo 🐶
                    </a>
                    <a href="#explorar" class="inline-flex justify-center items-center gap-2 text-base font-semibold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-6 py-4 rounded-2xl transition">
                        Explorar Comunidad
                    </a>
                </div>
            </div>

            <!-- Mosaico / Bento Grid -->
            <div class="lg:col-span-5 grid grid-cols-2 gap-4 relative">
                <div class="space-y-4">
                    <div class="bg-white p-3 rounded-3xl shadow-lg border border-purple-100/50 transform -rotate-2 hover:rotate-0 transition duration-300">
                        <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&q=80&w=400" alt="Dog profile" class="w-full h-48 object-cover rounded-2xl">
                        <div class="mt-3 flex justify-between items-center px-1">
                            <div>
                                <h3 class="font-bold text-slate-900">Max</h3>
                                <p class="text-xs text-slate-500">Golden Retriever • 2 años</p>
                            </div>
                            <span class="bg-pink-50 text-pink-600 text-xs px-2.5 py-1 rounded-full font-medium">❤️ 1.2k</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-4 pt-8">
                    <div class="bg-white p-3 rounded-3xl shadow-lg border border-purple-100/50 transform rotate-3 hover:rotate-0 transition duration-300">
                        <img src="https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?auto=format&fit=crop&q=80&w=400" alt="Dog profile" class="w-full h-48 object-cover rounded-2xl">
                        <div class="mt-3 flex justify-between items-center px-1">
                            <div>
                                <h3 class="font-bold text-slate-900">Luna</h3>
                                <p class="text-xs text-slate-500">Beagle • 4 meses</p>
                            </div>
                            <span class="bg-pink-50 text-pink-600 text-xs px-2.5 py-1 rounded-full font-medium">❤️ 890</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>
