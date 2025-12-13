<div class="max-w-4xl mx-auto py-10 px-4">
    
    <div class="bg-white rounded-3xl shadow-pastel border border-stone-100 overflow-hidden">
        <div class="md:flex">
            
            {{-- BAGIAN KIRI: FOTO PRODUK --}}
            <div class="md:w-1/2 bg-stone-50 p-8 flex items-center justify-center border-r border-stone-100">
                @if($produk->gambar)
                    <img src="{{ asset('storage/'.$produk->gambar) }}" class="rounded-2xl shadow-lg max-h-96 object-cover">
                @else
                    <div class="w-64 h-64 bg-stone-200 rounded-2xl flex items-center justify-center text-stone-400">
                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
            </div>

            {{-- BAGIAN KANAN: INFO PRODUK --}}
            <div class="md:w-1/2 p-8 flex flex-col justify-between">
                <div>
                    {{-- Kategori Badge --}}
                    <span class="inline-block px-3 py-1 rounded-full bg-orange-100 text-orange-600 text-xs font-bold uppercase tracking-wider mb-4">
                        {{ $produk->kategori }}
                    </span>

                    <h1 class="text-3xl font-extrabold text-stone-900 mb-2">{{ $produk->nama_produk }}</h1>
                    
                    {{-- Harga --}}
                    <div class="text-3xl font-bold text-orange-500 mb-6">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </div>

                    {{-- Info Estimasi --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <p class="text-sm font-bold text-blue-800">Sistem Pre-Order</p>
                                <p class="text-xs text-blue-600 mt-1">
                                    Produk ini memerlukan waktu persiapan sekitar <strong>{{ $produk->estimasi_hari_kerja }} hari kerja</strong> sebelum siap dipasang/dikirim.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <h3 class="font-bold text-stone-800 mb-2">Deskripsi Produk</h3>
                        <p class="text-stone-600 text-sm leading-relaxed">
                            {{ $produk->deskripsi ?? 'Tidak ada deskripsi detail untuk produk ini.' }}
                        </p>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-8 pt-6 border-t border-stone-100">
                    <button class="w-full bg-stone-900 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-200/50 hover:bg-stone-800 transition-all active:scale-95 flex items-center justify-center">
                        <span>Lanjut Pemesanan</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    <p class="text-center text-xs text-stone-400 mt-3">
                        *Anda bisa memilih opsi jasa pasang di halaman selanjutnya.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>