<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', $title ?? config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-slate-50">
<div class="min-h-screen flex flex-col">

    {{-- Header --}}
 <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="h-16 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('landing') }}"
               class="flex items-center gap-3 hover:opacity-90 transition">
                <img src="{{ asset('logo.png') }}"
                     alt="logo"
                     class="w-8 h-8 rounded-full"
                     onerror="this.style.display='none'">
                <span class="font-semibold text-sky-700 text-lg tracking-tight">
                    Country Houses
                </span>
            </a>

            {{-- Right side --}}
            <div class="flex items-center gap-4">

                @auth
                    {{-- User dropdown --}}
                    <div class="relative group">

                        <button type="button"
                                class="flex items-center gap-2 text-sm font-medium text-slate-700
                                       hover:text-sky-600 transition focus:outline-none">
                            <span>{{ Auth::user()->name }}</span>

                            <svg class="w-4 h-4 text-slate-400"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown --}}
                        <div class="absolute right-0 mt-2 w-56 bg-white border border-slate-200
                                    rounded-xl shadow-lg opacity-0 invisible
                                    group-hover:opacity-100 group-hover:visible
                                    transition-all duration-150">

                            {{-- Домики --}}
                            <a href="{{ route('houses.index') }}"
                               class="block px-4 py-2 text-sm text-slate-700
                                      hover:bg-slate-100 rounded-t-xl">
                                Список домов
                            </a>

                            {{-- Мои бронирования --}}
                            <a href="{{ route('bookings.my') }}"
                               class="block px-4 py-2 text-sm text-slate-700
                                      hover:bg-slate-100">
                                Мои бронирования
                            </a>

                            {{-- Профиль --}}
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-2 text-sm text-slate-700
                                      hover:bg-slate-100">
                                Профиль
                            </a>

                            @role('admin')
                                <div class="border-t border-slate-200 my-1"></div>

                                <a href="{{ route('admin.houses') }}"
                                   class="block px-4 py-2 text-sm text-slate-700
                                          hover:bg-slate-100">
                                    Управление домами
                                </a>

                                <a href="{{ route('admin.statistics') }}"
                                   class="block px-4 py-2 text-sm text-slate-700
                                          hover:bg-slate-100">
                                    Статистика
                                </a>
                            @endrole

                            <div class="border-t border-slate-200 my-1"></div>

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                            <button
                                type="submit"
                                class="w-full text-left px-4 py-2 text-sm font-medium
                                    text-white bg-red-600
                                    hover:bg-red-700
                                    rounded-b-xl transition">
                                Выйти
                            </button>
                            </form>
                        </div>
                    </div>

                @else
                    <a href="{{ route('houses.index') }}"
                       class="text-sm text-sky-600 hover:text-sky-700 font-medium flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Список домов
                    </a>

                    <a href="{{ route('login') }}"
                       class="text-sm text-sky-600 hover:underline">
                        Войти
                    </a>

                    <a href="{{ route('register') }}"
                       class="text-sm bg-sky-600 text-white px-4 py-2 rounded-xl
                              hover:bg-sky-700 transition">
                        Регистрация
                    </a>
                @endauth
            </div>

        </div>
    </div>
</header>

    {{-- Content --}}
    <main class="flex-1">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t">
        <div class="max-w-6xl mx-auto px-4 py-4
                    text-xs text-slate-500 flex justify-between">
            <span>© {{ date('Y') }} Country Houses Booking</span>
            <span>Laravel · Livewire · Spatie</span>
        </div>
    </footer>

</div>

@livewireScripts
</body>
</html>
