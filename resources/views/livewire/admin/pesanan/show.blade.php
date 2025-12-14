<div class="space-y-6">
    {{-- Header & Tombol Kembali --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.pesanan') }}" wire:navigate class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-500"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan</h1>
                <p class="text-sm text-gray-500">ID: #{{ $this->pesanan->nomor_order }}</p>
            </div>
        </div>

        {{-- FITUR GANTI STATUS --}}
        <div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-gray-200 shadow-sm">
            <span class="text-sm font-medium text-gray-500 pl-2">Status:</span>
            <select wire:model="statusBaru" wire:change="updateStatus" class="border-none text-sm font-bold text-gray-800 focus:ring-0 cursor-pointer bg-gray-50 rounded-md">
                <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                <option value="lunas">Lunas (Siap Proses)</option>
                <option value="sedang_dikerjakan">Sedang Dikerjakan</option>
                <option value="selesai">Selesai</option>
                <option value="batal">Dibatalkan</option>
            </select>
            
            {{-- Indikator Loading saat ganti status --}}
            <div wire:loading wire:target="updateStatus">
                <i data-lucide="loader-2" class="w-4 h-4 animate-spin text-orange-500"></i>
            </div>
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session()->has('success'))
        <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg border border-green-200 text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Kolom Kiri: Daftar Barang --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-900">
                    Item Dibeli
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($this->pesanan->detailPesanan as $detail)
                        <div class="p-6 flex items-center gap-4">
                            <img src="{{ Storage::url($detail->produk->gambar) }}" class="w-16 h-16 rounded-lg object-cover bg-gray-100">
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900">{{ $detail->produk->nama_produk }}</h3>
                                <p class="text-sm text-gray-500">Rp {{ number_format($detail->harga_saat_beli, 0, ',', '.') }} x {{ $detail->jumlah }}</p>
                            </div>
                            <div class="text-right font-bold text-gray-900">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                    
                    {{-- Baris Jasa Pasang --}}
                    @if($this->pesanan->butuh_pemasangan)
                        <div class="p-6 flex items-center gap-4 bg-blue-50">
                            <div class="w-16 h-16 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                <i data-lucide="wrench" class="w-6 h-6"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-blue-900">Jasa Pemasangan & Instalasi</h3>
                                <p class="text-sm text-blue-600">Teknisi datang ke lokasi</p>
                            </div>
                            <div class="text-right font-bold text-blue-900">
                                Rp {{ number_format($this->pesanan->biaya_layanan, 0, ',', '.') }}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    <span class="font-bold text-gray-600">Grand Total</span>
                    <span class="text-xl font-bold text-orange-600">Rp {{ number_format($this->pesanan->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Info Pembeli --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Pelanggan</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-bold">Nama Penerima</label>
                        <p class="text-gray-900 font-medium">{{ $this->pesanan->snap_nama_penerima }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-bold">No. WhatsApp</label>
                        <p class="text-gray-900 font-medium">{{ $this->pesanan->snap_no_hp }}</p>
                        <a href="https://wa.me/{{ $this->pesanan->snap_no_hp }}" target="_blank" class="text-xs text-green-600 hover:underline flex items-center gap-1 mt-1">
                            <i data-lucide="message-circle" class="w-3 h-3"></i> Chat via WA
                        </a>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-bold">Alamat Lengkap</label>
                        <p class="text-gray-700 mt-1 leading-relaxed">{{ $this->pesanan->snap_alamat_lengkap }}</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- CARD BARU: ATUR JADWAL TEKNISI --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i data-lucide="calendar-clock" class="w-5 h-5 text-orange-600"></i>
                Jadwal Pengerjaan
            </h3>

            {{-- Notifikasi Sukses Khusus Jadwal --}}
            @if (session()->has('success_jadwal'))
                <div class="mb-4 bg-green-50 text-green-700 p-3 rounded-lg text-sm border border-green-200">
                    {{ session('success_jadwal') }}
                </div>
            @endif

            <form wire:submit="simpanJadwal" class="space-y-4">
                
                {{-- Pilih Teknisi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Teknisi</label>
                    <select wire:model="pegawai_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($list_pegawai as $pgw)
                            <option value="{{ $pgw->id }}">
                                {{ $pgw->nama_pegawai }} ({{ $pgw->jabatan }})
                            </option>
                        @endforeach
                    </select>
                    @error('pegawai_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" wire:model="tgl_pengerjaan" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm">
                        @error('tgl_pengerjaan') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Jam --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" wire:model="jam_mulai" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm">
                        @error('jam_mulai') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Status Schedule (Opsional) --}}
                @if($this->pesanan->schedule)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pengerjaan</label>
                        <select wire:model="status_schedule" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm">
                            <option value="terjadwal">📅 Terjadwal</option>
                            <option value="reschedule">🔁 Reschedule</option>
                            <option value="selesai">✅ Selesai</option>
                        </select>
                    </div>
                @endif

                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-stone-900 hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-900 transition">
                    Simpan Jadwal
                </button>
            </form>
        </div>
    </div>
</div>