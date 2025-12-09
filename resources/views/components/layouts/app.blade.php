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
        /* Hide Scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Soft Shadow Custom */
        .shadow-pastel { box-shadow: 0 4px 20px -2px rgba(226, 232, 240, 0.8); }
    </style>
</head>
<body class="font-sans antialiased bg-[#F8FAFC] text-slate-600 selection:bg-indigo-100 selection:text-indigo-600"
      x-data="{ sidebarOpen: true }">

    <div class="min-h-screen flex flex-col md:flex-row">

        <aside class="hidden md:flex flex-col border-r border-slate-100 bg-white transition-all duration-300 fixed inset-y-0 z-40 shadow-sm"
               :class="sidebarOpen ? 'w-64' : 'w-20'">
            
            <div @click="sidebarOpen = !sidebarOpen" 
                 class="h-20 flex items-center justify-center cursor-pointer hover:bg-slate-50 transition-colors group border-b border-slate-50">
                <div x-show="sidebarOpen" class="flex items-center space-x-2">
                     <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                     </div>
                     <span class="text-xl font-bold text-slate-800 tracking-tight">DN Auto</span>
                </div>
                <div x-show="!sidebarOpen">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 p-4 space-y-2 mt-2 no-scrollbar" :class="sidebarOpen ? 'overflow-y-auto' : 'overflow-hidden'">
                @php
                    $menuClass = "flex items-center px-3 py-3 text-sm font-medium rounded-2xl transition-all duration-200 group relative";
                    $activeClass = "bg-indigo-50 text-indigo-600"; 
                    $inactiveClass = "text-slate-500 hover:bg-slate-50 hover:text-slate-700";
                @endphp

                <a href="/" wire:navigate class="{{ $menuClass }} {{ request()->is('/') ? $activeClass : $inactiveClass }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->is('/') ? 'text-indigo-500' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="ml-3 whitespace-nowrap font-semibold" x-show="sidebarOpen">Beranda</span>
                </a>
                
                <a href="#" class="{{ $menuClass }} {{ $inactiveClass }}">
                    <svg class="w-6 h-6 shrink-0 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen">Produk</span>
                </a>

                <a href="#" class="{{ $menuClass }} {{ $inactiveClass }}">
                    <svg class="w-6 h-6 shrink-0 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen">Pesanan</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-50">
                 <button class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-rose-500 bg-rose-50 rounded-2xl hover:bg-rose-100 transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span class="ml-2 whitespace-nowrap" x-show="sidebarOpen">Keluar</span>
                </button>
            </div>
        </aside>

        <main class="flex-1 min-h-screen relative flex flex-col transition-all duration-300"
              :class="sidebarOpen ? 'md:ml-64' : 'md:ml-20'">
            
            <header class="bg-white/80 backdrop-blur-md h-20 flex items-center justify-between px-6 sticky top-0 z-30 border-b border-slate-100/50">
                <div class="md:hidden flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="font-bold text-lg text-slate-800">DN Auto</span>
                </div>

                <div class="hidden md:block"></div>

                <div class="flex items-center space-x-4">
                    <button class="w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-indigo-500 hover:border-indigo-100 transition-all shadow-sm relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-rose-400 rounded-full border border-white"></span>
                    </button>
                    
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm shadow-sm border border-indigo-200">
                        RO
                    </div>
                </div>
            </header>

            <div class="p-4 pb-28 md:p-8">
                {{ $slot }}
            </div>
        </main>

        <nav class="md:hidden fixed bottom-6 inset-x-4 h-16 bg-white/90 backdrop-blur-md rounded-2xl shadow-pastel z-50 border border-white/20">
            <div class="grid grid-cols-4 h-full items-center justify-items-center px-2">
                
                <a href="/" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1">
                    <div class="{{ request()->is('/') ? 'text-indigo-500 bg-indigo-50' : 'text-slate-300' }} p-2 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                </a>

                <a href="#" class="flex flex-col items-center justify-center w-full h-full space-y-1">
                     <div class="text-slate-300 p-2 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </a>

                <a href="#" class="flex flex-col items-center justify-center w-full h-full space-y-1">
                     <div class="text-slate-300 p-2 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                </a>

                <a href="#" class="flex flex-col items-center justify-center w-full h-full space-y-1">
                     <div class="text-slate-300 p-2 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                </a>

            </div>
        </nav>

    </div>
    @livewireScripts
</body>
</html>