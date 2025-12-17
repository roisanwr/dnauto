<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - DN Auto</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 3px; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 h-screen flex overflow-hidden" x-data="{ sidebarOpen: true }">

    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col flex-shrink-0 z-20 transition-all duration-300 ease-in-out"
           :class="sidebarOpen ? 'ml-0' : '-ml-64'">
        
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center text-white shadow-sm shadow-orange-200">
                    <i data-lucide="wrench" class="w-4 h-4"></i>
                </div>
                <span class="text-lg font-bold tracking-tight text-gray-900">DN Auto</span>
            </div>
        </div>

        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            
            <a href="{{ route('admin.dashboard') }}" wire:navigate 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-md group {{ request()->routeIs('admin.dashboard') ? 'text-orange-700 bg-orange-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-orange-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                Dashboard
            </a>

            <div class="pt-4 pb-2 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Operasional Bengkel
            </div>

            <a href="{{ route('admin.pesanan') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-md transition-colors group {{ request()->routeIs('admin.pesanan') ? 'text-orange-700 bg-orange-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                <i data-lucide="inbox" class="w-4 h-4 {{ request()->routeIs('admin.pesanan') ? 'text-orange-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                Booking Masuk
                
                {{-- Indikator Jumlah Pesanan Baru (Opsional, nanti kita bikin dinamis) --}}
                {{-- <span class="ml-auto bg-orange-100 text-orange-600 py-0.5 px-2 rounded-full text-xs font-bold">3</span> --}}
            </a>

            <a href="{{ route('admin.schedule') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-md transition-colors group {{ request()->routeIs('admin.schedule') ? 'text-orange-700 bg-orange-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                <i data-lucide="clipboard-list" class="w-4 h-4 {{ request()->routeIs('admin.schedule') ? 'text-orange-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                Antrean Servis
            </a>

            <div class="pt-4 pb-2 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Master Data
            </div>
            

            <a href="{{ route('admin.produk') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors group">
                <i data-lucide="package" class="w-4 h-4 text-gray-400 group-hover:text-gray-600"></i>
                Produk & Jasa
            </a>
            <!-- <div x-data="{ open: {{ request()->routeIs('admin.produk*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors group">
                    <div class="flex items-center gap-3">
                        <i data-lucide="package" class="w-4 h-4 text-gray-400 group-hover:text-gray-600"></i>
                        Produk & Jasa
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" class="pl-10 space-y-1 mt-1" x-collapse>
                    <a href="{{ route('admin.produk') }}" wire:navigate class="block py-2 text-sm {{ request()->routeIs('admin.produk') ? 'text-orange-600 font-bold' : 'text-gray-500 hover:text-orange-600' }}">
                        Daftar Produk
                    </a>
                    <a href="#" class="block py-2 text-sm text-gray-500 hover:text-orange-600">Kategori</a>
                </div>
            </div> -->

            <div x-data="{ open: {{ request()->routeIs('admin.pegawai*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors group">
                    <div class="flex items-center gap-3">
                        <i data-lucide="users" class="w-4 h-4 text-gray-400 group-hover:text-gray-600"></i>
                        Pengguna
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" class="pl-10 space-y-1 mt-1" x-collapse>
                    <a href="{{ route('admin.pelanggan') }}" wire:navigate class="block py-2 text-sm {{ request()->routeIs('admin.pelanggan') ? 'text-orange-600 font-bold' : 'text-gray-500 hover:text-orange-600' }}">
                        Data Pelanggan
                    </a>
                    
                    <a href="{{ route('admin.pegawai') }}" wire:navigate class="block py-2 text-sm {{ request()->routeIs('admin.pegawai*') ? 'text-orange-600 font-bold' : 'text-gray-500 hover:text-orange-600' }}">
                        Data Pegawai
                    </a>
                </div>
            </div>

            <!-- <div class="pt-4 mt-4 border-t border-gray-100">
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors group">
                    <i data-lucide="file-bar-chart" class="w-4 h-4 text-gray-400 group-hover:text-gray-600"></i>
                    Laporan Keuangan
                </a>
            </div> -->
        </nav>

        <div class="p-4 border-t border-gray-200 relative" x-data="{ userMenuOpen: false }">
            
            <div x-show="userMenuOpen" 
                 @click.away="userMenuOpen = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                 class="absolute bottom-full left-0 w-full mb-2 px-4 z-50"
                 style="display: none;">
                
                <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden ring-1 ring-black ring-opacity-5">
                    <a href="{{ route('profile') }}" wire:navigate class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors border-b border-gray-50">
                        <i data-lucide="user" class="w-4 h-4 mr-3 text-gray-400"></i>
                        Profile Saya
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                            <i data-lucide="log-out" class="w-4 h-4 mr-3 text-red-400"></i>
                            Keluar Aplikasi
                        </button>
                    </form>
                </div>
            </div>

            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-3 w-full p-2 hover:bg-gray-50 rounded-lg transition-colors text-left group">
                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" 
                     alt="Admin" 
                     class="w-9 h-9 rounded-full border border-gray-200 object-cover group-hover:border-orange-300 transition-colors">
                
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">Administrator</p>
                </div>
                
                <i data-lucide="chevron-up" class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="userMenuOpen ? 'rotate-180' : ''"></i>
            </button>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                
            </div>

            <div class="flex items-center gap-4">
                <button class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                    <span class="absolute top-2 right-2.5 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500"></span>
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-8">
            {{ $slot }}
        </main>
    </div>

    <script>
        lucide.createIcons();
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>