<div class="space-y-6 pb-20">
        {{-- Script tambahan untuk horizontal scroll yang smooth --}}
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    {{-- ========================================= --}}
    {{-- BAGIAN 1: HEADER & MENU (TIDAK DIUBAH)    --}}
    {{-- ========================================= --}}
    
    {{-- Hero Banner "Clean Professional" --}}
    <div class="relative overflow-hidden bg-white rounded-3xl p-8 md:p-12 shadow-xl shadow-orange-100 border border-orange-100 isolate">
        
        {{-- Background Elements (Abstrak Cerah) --}}
        {{-- Lingkaran Oranye Halus di Kanan Atas --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-gradient-to-br from-orange-400 to-amber-300 rounded-full blur-3xl opacity-20"></div>
        {{-- Lingkaran Kuning di Kiri Bawah --}}
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-60 h-60 bg-yellow-300 rounded-full blur-3xl opacity-20"></div>
        
        {{-- Pattern Garis Halus (Tech Feel) --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            
            {{-- KIRI: Teks & Copywriting --}}
            <div class="max-w-xl space-y-6">
                {{-- Badge Status --}}
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-50 border border-orange-100 text-orange-600 text-xs font-bold uppercase tracking-wider shadow-sm">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                    </span>
                    Siap Melayani Anda
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-stone-800 leading-tight tracking-tight">
                    Rawat Mobil <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-500">
                        Tanpa Ribet.
                    </span>
                </h1>

                <p class="text-stone-500 text-lg font-medium leading-relaxed max-w-md">
                    Layanan home service terpercaya dan aksesoris mobil terlengkap. Kualitas bengkel resmi, harga bengkel teman.
                </p>

                <div class="flex flex-wrap gap-3 pt-2">
                    {{-- Tombol Utama (Gradient Orange) --}}
                    <a href="#katalog" class="flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-8 py-3.5 rounded-xl text-sm font-bold shadow-lg shadow-orange-200 transition-all transform hover:-translate-y-1 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Belanja Sekarang
                    </a>
                    
                    {{-- Tombol Sekunder (Minimalis) --}}
                    <a href="{{ route('history') }}" wire:navigate class="flex items-center gap-2 bg-white text-stone-600 border border-stone-200 hover:border-orange-300 hover:text-orange-600 px-6 py-3.5 rounded-xl text-sm font-bold transition-all shadow-sm hover:shadow-md">
                        Cek Pesanan
                    </a>
                </div>
            </div>

            {{-- KANAN: Visual / Ilustrasi (Mobil Abstrak) --}}
            <div class="hidden md:block relative w-full max-w-xs lg:max-w-sm">
                {{-- Card Floating 1 --}}
                <div class="absolute -top-4 -left-4 bg-white p-3 rounded-2xl shadow-xl shadow-orange-100 border border-orange-50 animate-bounce" style="animation-duration: 3s;">
                    <div class="flex items-center gap-2">
                        <div class="bg-green-100 p-1.5 rounded-lg text-green-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-xs font-bold text-stone-700">Terverifikasi</span>
                    </div>
                </div>

                {{-- Card Floating 2 --}}
                <div class="absolute bottom-8 -right-4 bg-white p-3 rounded-2xl shadow-xl shadow-orange-100 border border-orange-50 animate-bounce" style="animation-duration: 4s;">
                     <div class="flex items-center gap-2">
                        <div class="bg-blue-100 p-1.5 rounded-lg text-blue-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-stone-700">Home Service</span>
                    </div>
                </div>

                {{-- Ilustrasi Utama (Vector Style) --}}
                {{-- Menggunakan SVG inline agar tidak perlu upload gambar --}}
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto drop-shadow-2xl">
                    <path fill="#FFEDD5" d="M45.7,-58.9C58.9,-51.9,69.1,-38.9,74.1,-24.1C79.1,-9.3,78.9,7.3,72.9,21.9C66.9,36.5,55.1,49.1,41.9,58.9C28.7,68.7,14.1,75.7,0.3,75.3C-13.5,74.9,-27.3,67.1,-39.1,56.9C-50.9,46.7,-60.7,34.1,-65.7,19.9C-70.7,5.7,-70.9,-10.1,-64.4,-23.4C-57.9,-36.7,-44.7,-47.5,-31.5,-54.5C-18.3,-61.5,-5.1,-64.7,9.6,-64.7C24.3,-64.7,32.5,-65.9,45.7,-58.9Z" transform="translate(100 100) scale(1.1)" />
                    <path d="M40 110 L160 110 L150 70 L50 70 Z" fill="url(#grad1)" stroke="#f97316" stroke-width="2" transform="translate(0, 10)" opacity="0.9"/>
                    <circle cx="60" cy="110" r="15" fill="#44403c" />
                    <circle cx="140" cy="110" r="15" fill="#44403c" />
                    <defs>
                        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#fed7aa;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#fff7ed;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </div>
    </div>

    

    <!-- {{-- Menu Icon Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-stone-100 flex flex-col items-center justify-center text-center cursor-pointer hover:border-orange-200 hover:shadow-pastel transition-all duration-300">
            <div class="w-14 h-14 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <span class="text-stone-800 font-bold text-sm">Servis Berkala</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-100 flex flex-col items-center justify-center text-center cursor-pointer hover:border-orange-200 hover:shadow-pastel transition-all duration-300">
            <div class="w-14 h-14 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <span class="text-stone-800 font-bold text-sm">Aksesoris</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-100 flex flex-col items-center justify-center text-center cursor-pointer hover:border-orange-200 hover:shadow-pastel transition-all duration-300">
            <div class="w-14 h-14 rounded-full bg-red-50 text-red-500 flex items-center justify-center mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-stone-800 font-bold text-sm">Cek Status</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-100 flex flex-col items-center justify-center text-center cursor-pointer hover:border-orange-200 hover:shadow-pastel transition-all duration-300">
            <div class="w-14 h-14 rounded-full bg-stone-50 text-stone-500 flex items-center justify-center mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-stone-800 font-bold text-sm">Info & Bantuan</span>
        </div>
    </div> -->

    {{-- ========================================= --}}
    {{-- BAGIAN 2: KATALOG (SHOPEE STYLE)          --}}
    {{-- ========================================= --}}

    <div class="pt-4" id="katalog">
        
        {{-- Sticky Filter Bar (Nempel pas discroll) --}}
        <div class="sticky top-[4.5rem] z-30 bg-gray-50/95 backdrop-blur-sm py-3 -mx-4 px-4 border-b border-gray-200 shadow-sm">
            <div class="flex flex-col gap-3">
                
                {{-- Baris 1: Judul & Sortir --}}
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-lg text-stone-800">Rekomendasi</h3>
                    
                    {{-- Dropdown Sortir Sederhana --}}
                    <select wire:model.live="urutan" class="text-xs border-none bg-white rounded-lg shadow-sm py-1.5 pl-3 pr-8 focus:ring-1 focus:ring-orange-500 font-medium text-stone-600 cursor-pointer">
                        <option value="terbaru">Terbaru</option>
                        <option value="termurah">Harga Terendah</option>
                        <option value="termahal">Harga Tertinggi</option>
                    </select>
                </div>

                {{-- Baris 2: Kategori Pills (Scroll Samping) --}}
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    {{-- Tombol Semua --}}
                    <button wire:click="setKategori('')" 
                            class="whitespace-nowrap px-4 py-1.5 rounded-full text-xs font-bold transition-all border 
                            {{ $kategori == '' ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-200' : 'bg-white text-stone-500 border-stone-200 hover:border-orange-300' }}">
                        Semua
                    </button>

                    {{-- Tombol Kategori Lain --}}
                    @foreach(['Oli & Cairan', 'Ban & Velg', 'Aksesoris', 'Sparepart Mesin'] as $kat)
                        <button wire:click="setKategori('{{ $kat }}')" 
                                class="whitespace-nowrap px-4 py-1.5 rounded-full text-xs font-bold transition-all border 
                                {{ $kategori == $kat ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-200' : 'bg-white text-stone-500 border-stone-200 hover:border-orange-300' }}">
                            {{ $kat }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 mt-4">
            @forelse($produks as $produk)
                <a href="{{ route('produk.detail', $produk->id) }}" wire:navigate class="group bg-white rounded-xl overflow-hidden border border-stone-100 hover:border-orange-400 hover:shadow-lg transition-all duration-300 relative flex flex-col h-full">
                    
                    {{-- Badge Pre-Order --}}
                    <div class="absolute top-2 left-2 z-10 bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2 py-0.5 rounded-md">
                        PO {{ $produk->estimasi_hari_kerja }} Hari
                    </div>

                    {{-- Gambar (Aspect Square ala Shopee) --}}
                    <div class="aspect-square bg-stone-50 overflow-hidden relative">
                        @if($produk->gambar)
                            <img src="{{ asset('storage/'.$produk->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-stone-300 bg-stone-100">
                                <i data-lucide="image" class="w-8 h-8"></i>
                            </div>
                        @endif
                        
                        {{-- Overlay Gelap saat hover --}}
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>
                    </div>

                    {{-- Info Produk --}}
                    <div class="p-3 flex flex-col flex-1 justify-between">
                        <div>
                            <h4 class="text-xs md:text-sm font-medium text-stone-800 line-clamp-2 leading-relaxed group-hover:text-orange-600 transition-colors">
                                {{ $produk->nama_produk }}
                            </h4>
                            
                            {{-- Label Diskon / Tag (Dummy) --}}
                            <div class="mt-1 flex items-center gap-1">
                                <span class="text-[10px] border border-orange-500 text-orange-500 px-1 rounded-sm">Asli</span>
                                <span class="text-[10px] bg-orange-100 text-orange-600 px-1 rounded-sm">Garansi</span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm md:text-base font-bold text-orange-600">
                                    Rp{{ number_format($produk->harga, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between mt-1">
                                <div class="flex items-center">
                                    <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-yellow-400"></i>
                                    <span class="text-[10px] text-stone-500 ml-1">5.0</span>
                                </div>
                                <span class="text-[10px] text-stone-400">10 Terjual</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-stone-100 mb-4">
                        <i data-lucide="search-x" class="w-8 h-8 text-stone-400"></i>
                    </div>
                    <p class="text-stone-500 font-medium">Produk tidak ditemukan</p>
                    <button wire:click="setKategori('')" class="text-orange-500 text-sm font-bold mt-2 hover:underline">
                        Reset Filter
                    </button>
                </div>
            @endforelse
        </div>

        {{-- Infinite Scroll Trigger --}}
        @if($produks->count() < $totalProduk)
            <div x-intersect="$wire.loadMore()" class="flex justify-center py-8">
                <div class="flex items-center space-x-2 text-orange-500 font-medium animate-pulse">
                    <i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i>
                    <span class="text-sm">Memuat produk lainnya...</span>
                </div>
            </div>
        @else
            <div class="text-center py-8 text-stone-400 text-xs">
                - Anda sudah melihat semua produk -
            </div>
        @endif

    </div>

</div>

