<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'DN Auto' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Warm Shadow untuk tema Orange */
        .shadow-pastel { box-shadow: 0 4px 20px -2px rgba(251, 146, 60, 0.15); }
    </style>
</head>
<body class="font-sans antialiased bg-[#FFF8F0] text-stone-600 selection:bg-orange-100 selection:text-orange-600"
      x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row">

        <aside class="hidden md:flex flex-col border-r border-orange-100 bg-white transition-all duration-300 fixed inset-y-0 z-40 shadow-sm"
               :class="sidebarOpen ? 'w-64' : 'w-20'">
            
            <div @click="sidebarOpen = !sidebarOpen" 
                 class="h-20 flex items-center justify-center cursor-pointer hover:bg-orange-50 transition-colors group border-b border-orange-50">
                <div x-show="sidebarOpen" class="flex items-center space-x-2">
                     <div class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center text-orange-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                     </div>
                     <span class="text-xl font-bold text-stone-800 tracking-tight">DN Auto</span>
                </div>
                <div x-show="!sidebarOpen">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 p-4 space-y-2 mt-2 no-scrollbar" :class="sidebarOpen ? 'overflow-y-auto' : 'overflow-hidden'">
                @php
                    $menuClass = "flex items-center px-3 py-3 text-sm font-medium rounded-2xl transition-all duration-200 group relative";
                    // Active state: Orange background
                    $activeClass = "bg-orange-100 text-orange-600"; 
                    $inactiveClass = "text-stone-500 hover:bg-orange-50 hover:text-stone-700";
                @endphp

                <a href="/" wire:navigate class="{{ $menuClass }} {{ request()->is('/') ? $activeClass : $inactiveClass }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->is('/') ? 'text-orange-500' : 'text-stone-400 group-hover:text-stone-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="ml-3 whitespace-nowrap font-semibold" x-show="sidebarOpen">Beranda</span>
                </a>
                
                <a href="#" class="{{ $menuClass }} {{ $inactiveClass }}">
                    <svg class="w-6 h-6 shrink-0 text-stone-400 group-hover:text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen">Produk</span>
                </a>

                <a href="#" class="{{ $menuClass }} {{ $inactiveClass }}">
                    <svg class="w-6 h-6 shrink-0 text-stone-400 group-hover:text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen">Cek Pesanan</span>
                </a>
                {{-- Contoh Link Riwayat --}}
                <a href="{{ route('history') }}" class="{{ $menuClass }} {{ request()->routeIs('history') ? $activeClass : $inactiveClass }}">
                    {{-- Ikon Jam / History (Opsional) --}}
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('history') ? 'text-orange-500' : 'text-stone-400 group-hover:text-stone-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen">Riwayat Pesanan</span>
                </a>
            </nav>

            <div class="p-4 border-t border-orange-50">
                @guest
                    <a href="{{ route('login') }}" wire:navigate class="flex items-center justify-center w-full px-4 py-3 text-sm font-bold text-white bg-orange-400 rounded-2xl hover:bg-orange-500 transition-colors shadow-sm shadow-orange-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <span class="ml-2 whitespace-nowrap" x-show="sidebarOpen">Masuk Akun</span>
                    </a>
                @endguest

                @auth
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" 
                            class="flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-red-500 hover:bg-red-50 hover:text-red-600 rounded-xl transition-colors group">
                        
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        
                        <span class="ml-2 whitespace-nowrap" x-show="sidebarOpen">Keluar</span>
                    </button>
                </form>
                @endauth
            </div>
        </aside>

        <main class="flex-1 min-h-screen relative flex flex-col transition-all duration-300"
              :class="sidebarOpen ? 'md:ml-64' : 'md:ml-20'">
            
            <header class="bg-white/80 backdrop-blur-md h-20 flex items-center justify-between px-6 sticky top-0 z-30 border-b border-orange-50">
                
                <div class="md:hidden flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-orange-400 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="font-bold text-lg text-stone-800">DN Auto</span>
                </div>

                <div class="hidden md:block"></div>

                <div class="flex items-center space-x-4">
                    @guest
                        {{-- TAMPILAN UNTUK TAMU (BELUM LOGIN) --}}
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-stone-900 rounded-lg hover:bg-stone-800">
                            Daftar
                        </a>
                    @endguest

                    @auth
                        {{-- TAMPILAN UNTUK USER (SUDAH LOGIN) --}}
                        <div class="relative group" x-data="{ open: false }">
                            {{-- Foto Profil & Nama (Bisa Diklik) --}}
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <img src="{{ Auth::user()->avatar }}" 
                                    alt="{{ Auth::user()->name }}" 
                                    class="w-8 h-8 rounded-full border border-gray-200 object-cover">
                                <span class="text-sm font-medium text-gray-700 hidden md:block">
                                    {{ Auth::user()->name }}
                                </span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            {{-- Dropdown Menu (Muncul saat diklik) --}}
                            <div x-show="open" 
                                @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-100 z-50"
                                style="display: none;">
                                
                                {{-- Menu Item --}}
                                <div class="px-4 py-2 text-xs text-gray-500 border-b">
                                    Halo, {{ Auth::user()->name }}
                                </div>
                                
                                <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    Profile Saya
                                </a>
                                
                                {{-- Tombol Logout --}}
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        Keluar / Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            <div class="p-4 pb-28 md:p-8">
                {{ $slot }}
            </div>
        </main>

        <nav class="md:hidden fixed bottom-6 inset-x-4 h-16 bg-white/95 backdrop-blur-md rounded-2xl shadow-pastel z-50 border border-white/40">
            <div class="grid grid-cols-4 h-full items-center justify-items-center px-2">
                
                <a href="/" wire:navigate class="group flex flex-col items-center justify-center w-full h-full space-y-1">
                    <div class="p-2 rounded-xl transition-all {{ request()->is('/') ? 'text-orange-500 bg-orange-50' : 'text-stone-300 group-hover:text-stone-500 group-hover:bg-orange-50' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                </a>

                <a href="#" class="group flex flex-col items-center justify-center w-full h-full space-y-1">
                     <div class="p-2 rounded-xl transition-all text-stone-300 group-hover:text-stone-500 group-hover:bg-orange-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </a>

                <a href="#" class="group flex flex-col items-center justify-center w-full h-full space-y-1">
                     <div class="p-2 rounded-xl transition-all text-stone-300 group-hover:text-stone-500 group-hover:bg-orange-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                </a>

                <a href="#" class="group flex flex-col items-center justify-center w-full h-full space-y-1">
                     <div class="p-2 rounded-xl transition-all text-stone-300 group-hover:text-stone-500 group-hover:bg-orange-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </div>
                </a>

            </div>
        </nav>

    </div>
    @livewireScripts
</body>
</html>