<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'DN Auto' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-blue-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 antialiased font-sans transition-colors duration-300"
      x-data="{ 
          sidebarOpen: false, 
          darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggleTheme() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('theme', 'light');
              }
          }
      }"
      x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'))">

    <div class="min-h-screen flex flex-col md:flex-row">
        
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/50 z-40 lg:hidden">
        </div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed top-0 left-0 z-50 w-64 h-screen bg-white dark:bg-slate-900 border-r border-blue-100 dark:border-slate-800 transition-transform duration-300 ease-in-out shadow-2xl flex flex-col">
            
            <div class="p-6 flex items-center justify-between border-b border-blue-100 dark:border-slate-800 h-16 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-bold text-white">DN</div>
                    <span class="font-bold text-xl tracking-wide text-slate-800 dark:text-white">AUTO</span>
                </div>
                <button @click="sidebarOpen = false" class="text-slate-500 hover:text-red-500 dark:text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="/" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('/') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span class="font-medium">Beranda</span>
                </a>

                @auth
                    <a href="/pesanan" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('pesanan*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <span class="font-medium">Pesanan Saya</span>
                    </a>
                    <a href="/akun" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('akun*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span class="font-medium">Akun Pengguna</span>
                    </a>
                @endauth
            </nav>

            <div class="p-4 border-t border-blue-100 dark:border-slate-800 shrink-0 bg-white dark:bg-slate-900">
                @auth
                    <div class="bg-blue-50 dark:bg-slate-800 p-3 rounded-lg flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center font-bold text-white text-xs">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs text-slate-500 dark:text-slate-400">Login sebagai</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        </div>
                    </div>
                @else
                    <a href="/login" class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl font-bold transition shadow-lg shadow-blue-900/20">
                        Masuk / Daftar
                    </a>
                @endauth
            </div>
        </aside>

        <header class="fixed top-0 right-0 z-40 bg-white/80 dark:bg-slate-900/90 backdrop-blur-md border-b border-blue-100 dark:border-slate-800 px-5 py-4 flex justify-between items-center h-16 transition-all duration-300"
                :class="sidebarOpen ? 'md:left-64' : 'left-0'">
            
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 text-slate-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-white transition rounded-lg hover:bg-blue-50 dark:hover:bg-slate-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                </button>

                <div class="flex items-center gap-2" x-show="!sidebarOpen" x-transition>
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-bold text-white shadow-md">DN</div>
                    <span class="font-bold text-lg tracking-wide text-slate-800 dark:text-white hidden sm:block">AUTO</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button @click="toggleTheme()" class="p-2 text-slate-500 hover:text-orange-500 dark:text-slate-400 dark:hover:text-yellow-400 transition rounded-full hover:bg-orange-50 dark:hover:bg-slate-800">
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41-1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                </button>
                <button class="relative p-2 text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition rounded-full hover:bg-blue-50 dark:hover:bg-slate-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
                </button>
                @auth
                    <a href="/akun" class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center font-bold text-white text-xs ring-2 ring-blue-100 dark:ring-slate-800">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </a>
                @endauth
            </div>
        </header>

        <main class="flex-1 w-full pt-20 pb-24 md:pb-8 px-5 md:px-8 transition-all duration-300"
              :class="sidebarOpen ? 'md:ml-64' : ''">
            <div class="max-w-5xl mx-auto">
                {{ $slot }}
            </div>
        </main>

        <footer class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/90 dark:bg-slate-900/95 backdrop-blur-md border-t border-blue-100 dark:border-slate-800 pb-safe transition-colors duration-300">
            <nav class="grid grid-cols-3 h-16 items-center">
                <a href="/" wire:navigate class="flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-500 {{ request()->is('/') ? 'text-blue-600 dark:text-blue-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span class="text-[10px] font-medium">Beranda</span>
                </a>
                @auth
                    <a href="/pesanan" wire:navigate class="flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-500 {{ request()->is('pesanan*') ? 'text-blue-600 dark:text-blue-500' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <span class="text-[10px] font-medium">Pesanan</span>
                    </a>
                    <a href="/akun" wire:navigate class="flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-500 {{ request()->is('akun*') ? 'text-blue-600 dark:text-blue-500' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span class="text-[10px] font-medium">Akun</span>
                    </a>
                @else
                    <a href="#katalog" class="flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                        <span class="text-[10px] font-medium">Katalog</span>
                    </a>
                    <a href="/login" class="flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                        <span class="text-[10px] font-medium">Masuk</span>
                    </a>
                @endauth
            </nav>
        </footer>

    </div>
</body>
</html>