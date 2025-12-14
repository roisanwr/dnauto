<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Profil Saya</h2>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 mb-8">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Data Akun</h3>
            
            @if (session()->has('message'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm font-medium">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="updateProfile" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email (Tidak dapat diubah)</label>
                    <input type="email" wire:model="email" disabled class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed shadow-sm text-gray-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor HP (WhatsApp)</label>
                    <input type="text" wire:model="no_hp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('no_hp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 text-right">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-medium transition">
                        Simpan Perubahan Akun
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-800">Buku Alamat</h3>
                
                @if(!$showAlamatForm)
                <button wire:click="toggleForm" class="bg-gray-800 text-white px-3 py-2 rounded-md text-sm hover:bg-gray-900 flex items-center gap-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Alamat
                </button>
                @endif
            </div>

            @if (session()->has('alamat_message'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm font-medium">
                    {{ session('alamat_message') }}
                </div>
            @endif

            @if($showAlamatForm)
                <div class="mb-8 p-5 bg-blue-50 border border-blue-100 rounded-lg animate-fade-in-down">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-blue-800">Input Alamat Baru</h4>
                        <button wire:click="toggleForm" class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="simpanAlamat" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Label Alamat</label>
                                <input type="text" wire:model="label_alamat" placeholder="Contoh: Rumah, Kantor, Kost" class="w-full mt-1 border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('label_alamat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Kota / Area</label>
                                <select wire:model.live="kota" class="w-full mt-1 border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Pilih Area --</option>
                                    @foreach($pilihanKota as $k)
                                        <option value="{{ $k }}">{{ $k }}</option>
                                    @endforeach
                                </select>
                                
                                @if($kota == 'Lainnya (Luar Jabodetabek)')
                                    <div class="mt-2 p-2 bg-white rounded border border-blue-200">
                                        <label class="text-xs text-blue-600 font-bold block mb-1">Tulis Nama Kota / Kabupaten:</label>
                                        <input type="text" 
                                               wire:model="kota_manual" 
                                               placeholder="Misal: Surabaya, Medan, Makassar" 
                                               class="w-full border-gray-300 rounded text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    @error('kota_manual') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                @else
                                    <p class="text-xs text-gray-400 mt-1">*Penting untuk penentuan ongkos Home Service.</p>
                                @endif
                                
                                @error('kota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Nama Penerima</label>
                                <input type="text" wire:model="nama_penerima" class="w-full mt-1 border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('nama_penerima') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">No HP Penerima</label>
                                <input type="text" wire:model="no_hp_penerima" class="w-full mt-1 border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('no_hp_penerima') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Alamat Lengkap</label>
                            <textarea wire:model="alamat_lengkap" rows="2" placeholder="Jalan, Gang, Nomor Rumah, RT/RW, Patokan" class="w-full mt-1 border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @error('alamat_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model="is_primary" id="utama" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="utama" class="text-sm text-gray-700 cursor-pointer">Jadikan Alamat Utama</label>
                        </div>

                        <div class="flex justify-end gap-3 pt-2 border-t mt-4">
                            <button type="button" wire:click="toggleForm" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 bg-gray-100 rounded-md">Batal</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 font-medium shadow-sm">Simpan Alamat</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="space-y-4">
                @forelse($daftarAlamat as $alamat)
                    <div class="border {{ $alamat->is_primary ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white' }} rounded-lg p-5 relative transition hover:shadow-md">
                        
                        @if($alamat->is_primary)
                            <span class="absolute top-4 right-4 bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide">Utama</span>
                        @endif

                        <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4">
                            <div class="pr-0 sm:pr-20">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-bold text-gray-900 text-lg">{{ $alamat->label_alamat }}</span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-gray-600 font-medium">{{ $alamat->nama_penerima }}</span>
                                </div>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p class="font-medium text-gray-800">{{ $alamat->no_hp_penerima }}</p>
                                    <p>{{ $alamat->alamat_lengkap }}</p>
                                    <p class="font-semibold text-gray-800 uppercase">{{ $alamat->kota }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mt-2 sm:mt-0">
                                @if(!$alamat->is_primary)
                                    <button wire:click="setUtama({{ $alamat->id }})" class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                        Set Utama
                                    </button>
                                    <span class="text-gray-300">|</span>
                                @endif
                                
                                <button wire:click="hapusAlamat({{ $alamat->id }})" 
                                        onclick="return confirm('Yakin ingin menghapus alamat ini?') || event.stopImmediatePropagation()"
                                        class="text-xs font-medium text-red-600 hover:text-red-800 hover:underline">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-500 border-2 border-dashed border-gray-200 rounded-lg bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mx-auto mb-2 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <p>Belum ada alamat tersimpan.</p>
                        <p class="text-sm">Tambahkan alamat untuk memudahkan pengiriman & layanan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>