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
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 h-screen flex overflow-hidden" x-data="{ sidebarOpen: true }">

    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col flex-shrink-0 z-20 transition-all duration-300 ease-in-out"
           :class="sidebarOpen ? 'ml-0' : '-ml-64'">
        
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center text-white">
                    <i data-lucide="wrench" class="w-4 h-4"></i>
                </div>
                <span class="text-lg font-bold tracking-tight text-gray-900">DN Auto</span>
            </div>
        </div>

        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            
            {{-- Menu Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" wire:navigate 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-md group {{ request()->routeIs('admin.dashboard') ? 'text-orange-700 bg-orange-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-orange-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                Dashboard
            </a>

            {{-- Contoh Menu Lain (Statis Dulu) --}}
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors group">
                <i data-lucide="list-video" class="w-4 h-4 text-gray-400 group-hover:text-gray-600"></i>
                Service Queue
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors group">
                <i data-lucide="package" class="w-4 h-4 text-gray-400 group-hover:text-gray-600"></i>
                Spareparts
            </a>

            <div class="pt-4 mt-4 border-t border-gray-100">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">System</p>
                
                {{-- Tombol Logout --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-md transition-colors group">
                        <i data-lucide="log-out" class="w-4 h-4 text-red-400 group-hover:text-red-600"></i>
                        Logout
                    </button>
                </form>
            </div>
        </nav>

        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center gap-3 w-full p-2 rounded-md">
                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" alt="Admin" class="w-8 h-8 rounded-full border border-gray-200 object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li><span class="text-gray-900 font-medium">DN Auto Admin</span></li>
                    </ol>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <button class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors rounded-full hover:bg-gray-100">
                    <span class="absolute top-2 right-2.5 block h-1.5 w-1.5 rounded-full ring-2 ring-white bg-orange-500"></span>
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-8">
            {{ $slot }}
        </main>
    </div>

    <script>
        // Init icon saat halaman pertama kali load
        lucide.createIcons();
        
        // Init icon saat Livewire berpindah halaman (PENTING!)
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>