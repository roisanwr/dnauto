<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Checkout Pesanan</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Item</h3>
                    <div class="flex gap-4">
                        <img src="{{ $produk->gambar ? asset('storage/'.$produk->gambar) : 'https://via.placeholder.com/150' }}" 
                             class="w-20 h-20 object-cover rounded-md">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $produk->nama_produk }}</h4>
                            <p class="text-gray-500 text-sm">{{ $produk->kategori }}</p>
                            <p class="text-blue-600 font-bold mt-1">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Jumlah Pesanan</label>
                    
                    <div class="flex items-center">
                        <button wire:click="kurangQty" 
                                class="w-8 h-8 rounded-l border border-gray-300 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold flex items-center justify-center transition">
                            -
                        </button>

                        <input type="number" 
                               wire:model.live.debounce.500ms="qty" 
                               class="w-16 h-8 border-t border-b border-gray-300 text-center text-sm focus:ring-blue-500 focus:border-blue-500"
                               min="1">

                        <button wire:click="tambahQty" 
                                class="w-8 h-8 rounded-r border border-gray-300 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold flex items-center justify-center transition">
                            +
                        </button>
                    </div>
                    
                    <div wire:loading wire:target="tambahQty,kurangQty,qty" class="text-xs text-blue-500 mt-1 font-medium animate-pulse">
                        Menghitung total...
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Alamat Pengiriman / Pemasangan</h3>
                    
                    @if($daftarAlamat->isEmpty())
                        <div class="p-4 bg-yellow-50 text-yellow-700 rounded-md">
                            Anda belum memiliki alamat. 
                            <a href="{{ route('profile') }}" class="font-bold underline">Tambah Alamat di Profil</a>
                        </div>
                    @else
                        <select wire:model.live="alamat_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Alamat --</option>
                            @foreach($daftarAlamat as $al)
                                <option value="{{ $al->id }}">
                                    {{ $al->label_alamat }} - {{ $al->kota }} ({{ $al->alamat_lengkap }})
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-2 text-sm text-gray-500">
                            *Ongkos Home Service dihitung berdasarkan Kota pada alamat ini.
                        </div>
                    @endif
                    @error('alamat_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Metode Layanan</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <label class="cursor-pointer border rounded-lg p-4 {{ $layanan == 'bengkel' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                            <input type="radio" wire:model.live="layanan" value="bengkel" class="hidden">
                            <div class="font-semibold">Pasang di Bengkel</div>
                            <div class="text-xs text-gray-500 mt-1">Datang ke workshop DN Auto.</div>
                        </label>

                        <label class="cursor-pointer border rounded-lg p-4 {{ $layanan == 'home_service' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                            <input type="radio" wire:model.live="layanan" value="home_service" class="hidden">
                            <div class="font-semibold">Home Service</div>
                            <div class="text-xs text-gray-500 mt-1">Teknisi datang ke rumah.</div>
                        </label>

                        <label class="cursor-pointer border rounded-lg p-4 {{ $layanan == 'kirim' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                            <input type="radio" wire:model.live="layanan" value="kirim" class="hidden">
                            <div class="font-semibold">Kirim Ekspedisi</div>
                            <div class="text-xs text-gray-500 mt-1">Kirim via JNE/Cargo.</div>
                        </label>
                    </div>

                    @if($layanan == 'home_service' && $error_message)
                        <div class="mt-3 p-3 bg-red-100 text-red-700 text-sm rounded-md">
                            {{ $error_message }}
                        </div>
                    @endif

                    @if($layanan == 'kirim')
                        <div class="mt-3 p-3 bg-blue-100 text-blue-700 text-sm rounded-md">
                            <span class="font-bold">Info:</span> Ongkos kirim akan dihitung oleh Admin setelah barang siap dikirim (masuk tagihan pelunasan).
                        </div>
                    @endif
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Tipe Pembayaran</h3>
                    <div class="flex gap-6">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" wire:model.live="tipe_pembayaran" value="dp" class="form-radio text-blue-600 h-5 w-5">
                            <div>
                                <span class="block font-medium text-gray-900">DP 50% (Pre-Order)</span>
                                <span class="block text-sm text-gray-500">Bayar setengah dulu, lunas saat barang jadi.</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" wire:model.live="tipe_pembayaran" value="lunas" class="form-radio text-blue-600 h-5 w-5">
                            <div>
                                <span class="block font-medium text-gray-900">Bayar Lunas</span>
                                <span class="block text-sm text-gray-500">Bayar penuh di awal.</span>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-lg sticky top-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Ringkasan Biaya</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga Barang (x{{$qty}})</span>
                            <span class="font-medium">Rp {{ number_format($produk->harga * $qty, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Biaya Layanan/Jasa</span>
                            <span class="font-medium">
                                @if($layanan == 'kirim')
                                    Rp 0 (Info Menyusul)
                                @else
                                    Rp {{ number_format($biaya_layanan_total, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>

                        @if($layanan == 'home_service' && !$error_message)
                            <div class="text-xs text-green-600 text-right">
                                (Termasuk Transport & Pasang)
                            </div>
                        @endif

                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between text-base font-bold text-gray-900">
                                <span>Total Project</span>
                                <span>Rp {{ number_format($grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-md mt-4 border border-blue-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-blue-800 font-semibold">Bayar Sekarang:</span>
                                <span class="text-xl font-bold text-blue-600">
                                    Rp {{ number_format($nominal_yang_harus_dibayar, 0, ',', '.') }}
                                </span>
                            </div>
                            @if($tipe_pembayaran == 'dp')
                                <div class="text-xs text-blue-600 text-right">
                                    Sisa Tagihan: Rp {{ number_format($grand_total - $nominal_yang_harus_dibayar, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <button wire:click="buatPesanan" 
                            wire:loading.attr="disabled"
                            class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 disabled:bg-gray-400">
                        <span wire:loading.remove>Proses Pesanan</span>
                        <span wire:loading>Memproses...</span>
                    </button>

                    @if($error_message)
                        <p class="text-red-500 text-xs text-center mt-2">Perbaiki error area di atas sebelum lanjut.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>