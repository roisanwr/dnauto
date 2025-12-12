<div class="max-w-3xl mx-auto">
    
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Produk Baru</h1>
        <p class="text-sm text-gray-500">Isi informasi produk yang ingin dijual.</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <form wire:submit="store" class="space-y-6">
            
            {{-- 1. Upload Gambar --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Produk</label>
                
                <div class="flex items-center gap-6">
                    {{-- Preview Gambar --}}
                    <div class="shrink-0">
                        @if ($gambar)
                            <img src="{{ $gambar->temporaryUrl() }}" class="h-24 w-24 object-cover rounded-lg border border-gray-300">
                        @else
                            <div class="h-24 w-24 rounded-lg bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400">
                                <i data-lucide="image" class="w-8 h-8"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Tombol Upload --}}
                    <label class="block">
                        <span class="sr-only">Choose file</span>
                        <input type="file" wire:model="gambar" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-orange-50 file:text-orange-700
                            hover:file:bg-orange-100
                            cursor-pointer
                        "/>
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, JPEG (Max. 2MB)</p>
                        @error('gambar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- 2. Nama Produk --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Produk</label>
                    <input wire:model="nama_produk" type="text" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="Contoh: Oli Shell Helix">
                    @error('nama_produk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- 3. Kategori --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                    <select wire:model="kategori" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Oli & Cairan">Oli & Cairan</option>
                        <option value="Ban & Velg">Ban & Velg</option>
                        <option value="Towing">Towing</option>
                        <option value="Aksesoris">Aksesoris</option>
                        <option value="Sparepart Mesin">Sparepart Mesin</option>
                        <option value="Jasa Bengkel">Jasa Bengkel</option>
                    </select>
                    @error('kategori') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- 4. Harga --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Harga Jual (Rp)</label>
                    <input wire:model="harga" type="number" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="0">
                    @error('harga') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- 5. Estimasi PO --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Estimasi Pre-Order (Hari)</label>
                    <div class="relative">
                        <input wire:model="estimasi_hari_kerja" type="number" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="2">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Hari</span>
                        </div>
                    </div>
                    @error('estimasi_hari_kerja') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- 6. Deskripsi --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Produk</label>
                <textarea wire:model="deskripsi" rows="3" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="Jelaskan spesifikasi produk..."></textarea>
            </div>

            {{-- Tombol Aksi --}}
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.produk') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-orange-600 rounded-lg hover:bg-orange-700 shadow-sm shadow-orange-200">
                    Simpan Produk
                </button>
            </div>

        </form>
    </div>
</div>