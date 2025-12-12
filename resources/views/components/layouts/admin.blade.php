<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - DN Auto</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: true }">

    <div class="min-h-screen flex">
        
        {{-- SIDEBAR ADMIN --}}
        <aside class="fixed inset-y-0 left-0 z-50 bg-stone-900 text-white transition-all duration-300 ease-in-out"
               :class="sidebarOpen ? 'w-64' : 'w-20'">
            
            {{-- Logo Area --}}
            <div class="h-16 flex items-center justify-center border-b border-stone-800">
                <span class="font-bold text-xl tracking-wider" x-show="sidebarOpen">ADMIN PANEL</span>
                <span class="font-bold text-xl" x-show="!sidebarOpen">DN</span>
            </div>

            {{-- Menu Items --}}
            <nav class="mt-5 px-3 space-y-2">
                
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center px-4 py-3 bg-stone-800 rounded-xl text-white group transition-colors">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="ml-3 font-medium" x-show="sidebarOpen">Dashboard</span>
                </a>

                {{-- Master Data (Contoh Menu Dropdown) --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-stone-400 hover:bg-stone-800 hover:text-white rounded-xl transition-colors">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <span class="ml-3 font-medium" x-show="sidebarOpen">Master Data</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open && sidebarOpen" class="mt-2 pl-11 space-y-1">
                        <a href="#" class="block px-4 py-2 text-sm text-stone-400 hover:text-white rounded-lg">Kategori</a>
                        <a href="#" class="block px-4 py-2 text-sm text-stone-400 hover:text-white rounded-lg">Produk & Jasa</a>
                    </div>
                </div>

                {{-- Pesanan Masuk --}}
                <a href="#" class="flex items-center px-4 py-3 text-stone-400 hover:bg-stone-800 hover:text-white rounded-xl transition-colors">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="ml-3 font-medium" x-show="sidebarOpen">Pesanan</span>
                </a>

            </nav>
            
            {{-- Logout Admin --}}
            <div class="absolute bottom-0 w-full p-4 border-t border-stone-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-red-400 hover:bg-stone-800 hover:text-red-300 rounded-xl transition-colors">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="ml-3 font-medium" x-show="sidebarOpen">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 transition-all duration-300 ease-in-out" 
              :class="sidebarOpen ? 'ml-64' : 'ml-20'">
            
            {{-- Topbar --}}
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 sticky top-0 z-40">
                <button @click="sidebarOpen = !sidebarOpen" class="text-stone-500 hover:text-stone-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-semibold text-stone-700">Hi, Admin {{ Auth::user()->name }}</span>
                    <img src="{{ Auth::user()->avatar }}" class="w-9 h-9 rounded-full object-cover border border-stone-200">
                </div>
            </header>

            {{-- Content Slot --}}
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>