<nav x-data="{ open: false }" class="bg-gradient-to-r from-purple-700 via-purple-600 to-indigo-600 border-b border-purple-400/30 sticky top-0 z-50 text-white shadow-lg shadow-purple-900/10 backdrop-blur-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 font-black text-white text-lg">
                        <span class="w-10 h-10 bg-white/20 backdrop-blur-md text-white rounded-2xl flex items-center justify-center text-xl shadow-inner border border-white/30">🐾</span>
                        <span class="tracking-wide">BarkSocial</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-purple-100 font-bold hover:text-white border-white">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown & Notificaciones -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                <!-- Notificaciones Dropdown -->
                <div class="relative" x-data="{ openNotif: false }">
                    <button @click="openNotif = !openNotif" class="relative p-2.5 rounded-xl text-purple-100 hover:text-white bg-white/10 hover:bg-white/20 transition border border-white/20 backdrop-blur-md flex items-center justify-center">
                        🔔
                        @php
                            $authUser = \App\Models\User::find(Auth::id());
                            $unreadCount = $authUser ? $authUser->notifications()->whereNull('read_at')->count() : 0;
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-pink-500 text-white text-[10px] font-black rounded-full flex items-center justify-center shadow-md animate-pulse">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div x-show="openNotif" @click.away="openNotif = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-3xl shadow-2xl border border-purple-100 py-3 z-50 text-slate-800">
                        <div class="px-4 pb-2 border-b border-purple-50 flex items-center justify-between">
                            <h3 class="font-bold text-xs uppercase tracking-wider text-purple-900">Notificaciones 🐾</h3>
                            <form action="{{ route('notifications.readAll') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[10px] font-bold text-purple-600 hover:underline">Marcar leídas</button>
                            </form>
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-purple-50">
                            @forelse(($authUser ? $authUser->notifications()->latest()->take(5)->get() : []) as $notification)
                                <a href="{{ $notification->url ?? '#' }}" class="block px-4 py-3 hover:bg-purple-50/60 transition text-xs {{ is_null($notification->read_at) ? 'bg-purple-50/40 font-semibold' : '' }}">
                                    <p class="text-slate-700">{{ $notification->data }}</p>
                                    <span class="text-[10px] text-purple-400 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                </a>
                            @empty
                                <p class="text-xs text-slate-400 text-center py-6">No tienes notificaciones todavía 📭</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Usuario Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-white/20 text-sm leading-4 font-bold rounded-xl text-white bg-white/10 hover:bg-white/20 focus:outline-none transition ease-in-out duration-150 shadow-sm backdrop-blur-md">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-purple-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-purple-900 hover:bg-purple-50 font-medium">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();" class="text-purple-900 hover:bg-purple-50 font-medium">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Notificaciones y Hamburger para Móvil -->
            <div class="flex items-center sm:hidden gap-2">
                <!-- Notificaciones Móvil -->
                <div class="relative" x-data="{ openNotifMob: false }">
                    <button @click="openNotifMob = !openNotifMob" class="relative p-2 rounded-xl text-purple-100 hover:text-white bg-white/10 hover:bg-white/20 transition border border-white/20 flex items-center justify-center">
                        🔔
                        @php
                            $mobUser = \App\Models\User::find(Auth::id());
                            $unreadCountMob = $mobUser ? $mobUser->notifications()->whereNull('read_at')->count() : 0;
                        @endphp
                        @if($unreadCountMob > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-pink-500 text-white text-[9px] font-black rounded-full flex items-center justify-center shadow">
                                {{ $unreadCountMob }}
                            </span>
                        @endif
                    </button>

                    <div x-show="openNotifMob" @click.away="openNotifMob = false" x-cloak class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-purple-100 py-3 z-50 text-slate-800">
                        <div class="px-3 pb-2 border-b border-purple-50 flex items-center justify-between">
                            <h3 class="font-bold text-xs uppercase tracking-wider text-purple-900">Notificaciones 🐾</h3>
                            <form action="{{ route('notifications.readAll') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[10px] font-bold text-purple-600 hover:underline">Marcar leídas</button>
                            </form>
                        </div>

                        <div class="max-h-56 overflow-y-auto divide-y divide-purple-50">
                            @forelse(($mobUser ? $mobUser->notifications()->latest()->take(5)->get() : []) as $notification)
                                <a href="{{ $notification->url ?? '#' }}" class="block px-3 py-2.5 hover:bg-purple-50/60 transition text-xs {{ is_null($notification->read_at) ? 'bg-purple-50/40 font-semibold' : '' }}">
                                    <p class="text-slate-700">{{ $notification->data }}</p>
                                    <span class="text-[9px] text-purple-400 mt-0.5 block">{{ $notification->created_at->diffForHumans() }}</span>
                                </a>
                            @empty
                                <p class="text-xs text-slate-400 text-center py-4">No hay notificaciones 📭</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Hamburger Button -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-purple-100 hover:text-white hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-purple-800 border-b border-purple-700">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white font-bold">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-4 pb-1 border-t border-purple-700 px-4">
            <div class="font-medium text-base text-white flex items-center justify-between">
                <span>{{ Auth::user()->name }}</span>
                @php
                    $respUser = \App\Models\User::find(Auth::id());
                    $unreadCountResp = $respUser ? $respUser->notifications()->whereNull('read_at')->count() : 0;
                @endphp
                @if($unreadCountResp > 0)
                    <span class="bg-pink-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">
                        {{ $unreadCountResp }} nuevas
                    </span>
                @endif
            </div>
            <div class="font-medium text-sm text-purple-200">{{ Auth::user()->email }}</div>
            <div class="mt-3 space-y-1 pb-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();" class="text-purple-200 font-medium">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
