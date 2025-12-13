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
    
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden bg-orange-200 rounded-3xl p-8 text-stone-800">
        <div class="relative z-10 max-w-lg">
            <h1 class="text-3xl font-extrabold mb-2 text-stone-900">Selamat Datang! 👋</h1>
            <p class="text-stone-700 font-medium mb-6 leading-relaxed">
                Platform digital DN Auto. Solusi lengkap perawatan mobil dan aksesoris termurah dengan kualitas terbaik.
            </p>
            <div class="flex space-x-3">
                <button class="bg-stone-900 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-orange-200/50 active:scale-95 transition-transform">
                    Mulai Booking
                </button>
            </div>
        </div>
        <div class="absolute -bottom-16 -right-16 w-64 h-64 bg-white/20 rounded-full"></div>
        <div class="absolute top-10 right-10 w-20 h-20 bg-white/30 rounded-full"></div>
    </div>

    {{-- Menu Icon Grid --}}
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
    </div>

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

