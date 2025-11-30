<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'DN Auto' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 antialiased font-sans">

    <div class="min-h-screen flex flex-col md:flex-row">
        
        <aside class="hidden md:flex flex-col w-64 bg-slate-900 border-r border-slate-800 h-screen fixed left-0 top-0 z-50">
            <div class="p-6 flex items-center gap-3 border-b border-slate-800">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-bold text-white">DN</div>
                <span class="font-bold text-xl tracking-wide text-white">AUTO</span>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="/" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('/') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span class="font-medium">Beranda</span>
                </a>
                <a href="/pesanan" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('pesanan*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <span class="font-medium">Pesanan Saya</span>
                </a>
                <a href="/akun" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('akun*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span class="font-medium">Akun Pengguna</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <div class="bg-slate-800 p-3 rounded-lg flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-700"></div>
                    <div>
                        <p class="text-xs text-slate-400">Login sebagai</p>
                        <p class="text-sm font-bold text-white">Sultan</p>
                    </div>
                </div>
            </div>
        </aside>


        <header class="md:hidden sticky top-0 z-40 bg-slate-900/90 backdrop-blur-md border-b border-slate-800 px-5 py-4 flex justify-between items-center h-16">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-bold text-white shadow-lg shadow-blue-500/30">DN</div>
                <span class="font-bold text-lg tracking-wide text-white">AUTO</span>
            </div>
            <button class="relative p-2 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.403 4.207A2 2 0 0116.608 22H7.392a2 2 0 01-1.597-1.793L4 17h5m6-4V7a6 6 0 10-12 0v6a4 4 0 00-4 4v1h16v-1a4 4 0 00-4-4z" /></svg>
                <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full"></span>
            </button>
        </header>


        <main class="flex-1 w-full md:ml-64 p-5 md:p-8 pb-24 md:pb-8">
            <div class="max-w-5xl mx-auto">
                {{ $slot }}
            </div>
        </main>


        <footer class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-slate-900/95 backdrop-blur-md border-t border-slate-800 pb-safe">
            <nav class="grid grid-cols-3 h-16 items-center">
                <a href="/" wire:navigate class="flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-blue-500 {{ request()->is('/') ? 'text-blue-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span class="text-[10px] font-medium">Beranda</span>
                </a>
                <a href="/pesanan" wire:navigate class="flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-blue-500 {{ request()->is('pesanan*') ? 'text-blue-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <span class="text-[10px] font-medium">Pesanan</span>
                </a>
                <a href="/akun" wire:navigate class="flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-blue-500 {{ request()->is('akun*') ? 'text-blue-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span class="text-[10px] font-medium">Akun</span>
                </a>
            </nav>
        </footer>

    </div>
</body>
</html>