<div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold text-stone-900 mb-6">Konfirmasi Pesanan</h1>

    <div class="md:flex gap-8">
        
        {{-- KOLOM KIRI: FORM PENGIRIMAN --}}
        <div class="md:w-2/3 space-y-6">
            
            {{-- Card Alamat --}}
            <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
                <h3 class="font-bold text-lg mb-4 flex items-center">
                    <i data-lucide="map-pin" class="w-5 h-5 mr-2 text-orange-500"></i>
                    Informasi Pengiriman
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-stone-600 mb-1">Nama Penerima</label>
                        <input wire:model="nama_penerima" type="text" class="w-full rounded-lg border-stone-300 focus:ring-orange-500 focus:border-orange-500">
                        @error('nama_penerima') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-stone-600 mb-1">Nomor HP / WA</label>
                        <input wire:model="no_hp" type="text" class="w-full rounded-lg border-stone-300 focus:ring-orange-500 focus:border-orange-500">
                        @error('no_hp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-stone-600 mb-1">Alamat Lengkap</label>
                    <textarea wire:model="alamat_lengkap" rows="3" class="w-full rounded-lg border-stone-300 focus:ring-orange-500 focus:border-orange-500" placeholder="Nama Jalan, RT/RW, Patokan..."></textarea>
                    @error('alamat_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Opsi Pemasangan --}}
            <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
                <h3 class="font-bold text-lg mb-4 flex items-center">
                    <i data-lucide="wrench" class="w-5 h-5 mr-2 text-orange-500"></i>
                    Layanan Tambahan
                </h3>
                
                <label class="flex items-start cursor-pointer p-4 border rounded-xl hover:bg-stone-50 transition-colors {{ $butuh_pemasangan ? 'border-orange-500 bg-orange-50' : 'border-stone-200' }}">
                    <input type="checkbox" wire:model.live="butuh_pemasangan" class="mt-1 w-4 h-4 text-orange-600 rounded border-gray-300 focus:ring-orange-500">
                    <div class="ml-3">
                        <span class="block font-bold text-stone-800">Saya butuh jasa pemasangan</span>
                        <span class="block text-xs text-stone-500 mt-1">Teknisi kami akan memasang sparepart ini di bengkel atau lokasi Anda (tergantung kesepakatan).</span>
                    </div>
                    @if($butuh_pemasangan)
                        <span class="ml-auto font-bold text-orange-600">+Rp{{ number_format($biaya_pasang, 0, ',', '.') }}</span>
                    @endif
                </label>
            </div>
        </div>

        {{-- KOLOM KANAN: RINGKASAN --}}
        <div class="md:w-1/3 mt-8 md:mt-0">
            <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-lg sticky top-24">
                <h3 class="font-bold text-lg mb-4">Ringkasan Pesanan</h3>

                {{-- Item Produk --}}
                <div class="flex gap-3 mb-6 pb-6 border-b border-stone-100">
                    @if($produk->gambar)
                        <img src="{{ asset('storage/'.$produk->gambar) }}" class="w-16 h-16 rounded-lg object-cover bg-stone-100">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-stone-100 flex items-center justify-center"><i data-lucide="image" class="w-6 h-6 text-stone-400"></i></div>
                    @endif
                    <div>
                        <h4 class="text-sm font-bold text-stone-800 line-clamp-2">{{ $produk->nama_produk }}</h4>
                        <p class="text-xs text-stone-500 mt-1">Pre-Order {{ $produk->estimasi_hari_kerja }} Hari</p>
                    </div>
                </div>

                {{-- Rincian Biaya --}}
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm text-stone-600">
                        <span>Harga Barang (1x)</span>
                        <span>Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($butuh_pemasangan)
                    <div class="flex justify-between text-sm text-stone-600">
                        <span>Jasa Pasang</span>
                        <span>Rp {{ number_format($biaya_pasang, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between text-base font-bold text-stone-900 pt-3 border-t border-stone-100">
                        <span>Total Bayar</span>
                        <span class="text-orange-600">Rp {{ number_format($grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button wire:click="buatPesanan" wire:loading.attr="disabled" class="w-full bg-orange-600 text-white font-bold py-3 rounded-xl hover:bg-orange-700 transition-colors shadow-lg shadow-orange-200">
                    <span wire:loading.remove>Buat Pesanan</span>
                    <span wire:loading><i data-lucide="loader-2" class="w-4 h-4 animate-spin inline mr-2"></i>Memproses...</span>
                </button>
            </div>
        </div>

    </div>
</div>