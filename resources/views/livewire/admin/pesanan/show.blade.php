<div class="max-w-7xl mx-auto space-y-6">
    
    {{-- HEADER & STATUS BADGE --}}
    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $pesanan->nomor_order }}</h1>
            <p class="text-gray-500 text-sm">Dibuat: {{ $pesanan->created_at->format('d M Y, H:i') }}</p>
            <a href="{{ route('admin.pesanan.invoice', $pesanan->id) }}" target="_blank" 
               class="inline-flex items-center gap-2 mt-2 px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 border border-gray-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Invoice
            </a>
        </div>
        
        {{-- Logic Warna Badge Status --}}
        @php
            $badgeColor = 'bg-gray-100 text-gray-800';
            switch($pesanan->status) {
                case 'menunggu_pembayaran': $badgeColor = 'bg-yellow-100 text-yellow-800'; break;
                case 'produksi': $badgeColor = 'bg-blue-100 text-blue-800'; break;
                case 'menunggu_pelunasan': $badgeColor = 'bg-orange-100 text-orange-800'; break;
                case 'siap_dipasang': 
                case 'siap_dikirim': $badgeColor = 'bg-purple-100 text-purple-800'; break;
                case 'lunas':
                case 'selesai': $badgeColor = 'bg-green-100 text-green-800'; break;
                case 'batal': $badgeColor = 'bg-red-100 text-red-800'; break;
            }
        @endphp
        <div class="px-4 py-2 rounded-lg font-bold uppercase text-sm {{ $badgeColor }}">
            {{ str_replace('_', ' ', $pesanan->status) }}
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative animate-fade-in-down">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- ========================
             KOLOM KIRI: DATA ORDER
           ======================== --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- 1. INFO ITEM --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Item Dipesan</h3>
                @foreach($pesanan->detailPesanan as $detail)
                    <div class="flex gap-4 mb-4 last:mb-0">
                        <div class="shrink-0">
                            <img src="{{ $detail->produk->gambar ? asset('storage/'.$detail->produk->gambar) : 'https://via.placeholder.com/80' }}" class="w-16 h-16 object-cover rounded bg-gray-100 border">
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">{{ $detail->produk->nama_produk }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_saat_beli) }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-blue-600 font-bold">Rp {{ number_format($detail->subtotal) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 2. INFO PENGIRIMAN & CUSTOMER --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Informasi Pengiriman/Layanan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-gray-500 text-xs uppercase">Nama Penerima</span>
                        <span class="font-semibold text-gray-900">{{ $pesanan->snap_nama_penerima }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs uppercase">Kontak</span>
                        <span class="font-semibold text-gray-900">{{ $pesanan->snap_no_hp }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="block text-gray-500 text-xs uppercase">Alamat Lengkap</span>
                        <span class="font-semibold text-gray-900">{{ $pesanan->snap_alamat_lengkap }}</span>
                    </div>
                    
                    {{-- Jenis Layanan Badge --}}
                    <div class="md:col-span-2 mt-2 pt-2 border-t border-dashed">
                        <span class="block text-gray-500 text-xs uppercase mb-1">Jenis Layanan</span>
                        @if(!$pesanan->butuh_pemasangan)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-50 text-purple-700 rounded text-xs font-bold border border-purple-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" /></svg>
                                KIRIM EKSPEDISI (Tanpa Pasang)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-50 text-orange-700 rounded text-xs font-bold border border-orange-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                                JASA PASANG (Bengkel/Home Service)
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 3. RIWAYAT PEMBAYARAN --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Riwayat Pembayaran</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="p-2">Tipe</th>
                                <th class="p-2">Metode</th>
                                <th class="p-2 text-right">Jumlah</th>
                                <th class="p-2">Waktu</th>
                                <th class="p-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanan->pembayaran as $bayar)
                                <tr class="border-b last:border-0">
                                    <td class="p-2 font-bold text-gray-700">
                                        {{ $bayar->tipe == 'dp' ? 'DP (Uang Muka)' : 'PELUNASAN' }}
                                    </td>
                                    <td class="p-2">{{ strtoupper(str_replace('_', ' ', $bayar->metode_pembayaran)) }}</td>
                                    <td class="p-2 text-right font-mono font-medium">Rp {{ number_format($bayar->jumlah_bayar) }}</td>
                                    <td class="p-2 text-gray-500 text-xs">{{ $bayar->created_at->format('d/m/y H:i') }}</td>
                                    <td class="p-2 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 uppercase">
                                            {{ $bayar->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-4 text-center text-gray-400">Belum ada pembayaran masuk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ========================
             KOLOM KANAN: ACTION & FINANCE
           ======================== --}}
        <div class="space-y-6">
            
            {{-- PANEL KEUANGAN --}}
            <div class="bg-gray-900 text-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-gray-400 mb-4 uppercase text-xs tracking-wider">Ringkasan Tagihan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Belanja</span>
                        <span>Rp {{ number_format($pesanan->total_belanja) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Biaya Layanan/Ongkir</span>
                        <span>Rp {{ number_format($pesanan->biaya_layanan) }}</span>
                    </div>
                    <div class="border-t border-gray-700 my-2"></div>
                    <div class="flex justify-between font-bold text-base">
                        <span>Grand Total</span>
                        <span>Rp {{ number_format($pesanan->grand_total) }}</span>
                    </div>
                    
                    {{-- Info Sisa Tagihan --}}
                    <div class="mt-4 p-3 bg-gray-800 rounded-lg border border-gray-700">
                        <div class="flex justify-between text-yellow-400 font-bold text-lg">
                            <span>Sisa Tagihan</span>
                            <span>Rp {{ number_format($pesanan->sisa_tagihan) }}</span>
                        </div>
                        @if($pesanan->sisa_tagihan == 0)
                            <div class="text-xs text-green-400 text-right mt-1 font-bold">LUNAS</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- PANEL KONTROL (TINDAKAN ADMIN) --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Tindakan Admin</h3>

                {{-- STATUS: PRODUKSI (Barang sedang dibuat) --}}
                @if($pesanan->status == 'produksi')
                    
                    {{-- CASE A: ORDER KIRIM (Input Ongkir) --}}
                    @if(!$pesanan->butuh_pemasangan)
                        <div class="space-y-4 animate-fade-in">
                            <div class="bg-blue-50 p-3 rounded text-sm text-blue-800 border border-blue-100">
                                <span class="font-bold">Barang Sudah Jadi?</span><br>
                                Masukkan ongkir real (JNE/Cargo) untuk menagih pelunasan ke user.
                            </div>
                            
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500">Ongkos Kirim Real (Rp)</label>
                                <input type="number" wire:model="ongkir_real" class="w-full mt-1 border-gray-300 rounded p-2 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 50000">
                                @error('ongkir_real') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <button wire:click="simpanOngkir" wire:loading.attr="disabled" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 text-sm font-bold shadow-sm transition">
                                Update Ongkir & Tagih Pelunasan
                            </button>
                        </div>
                    
                    {{-- CASE B: ORDER JASA (Input Jadwal) --}}
                    @else
                        <div class="space-y-4 animate-fade-in">
                            <div class="bg-orange-50 p-3 rounded text-sm text-orange-800 border border-orange-100">
                                <span class="font-bold">Barang Sudah Jadi?</span><br>
                                Jadwalkan teknisi. Sistem akan otomatis menagih pelunasan (jika DP) atau menandai siap dipasang (jika Full).
                            </div>
                            
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500">Pilih Teknisi</label>
                                <select wire:model="pegawai_id" class="w-full mt-1 border-gray-300 rounded p-2 text-sm focus:border-orange-500 focus:ring-orange-500">
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach($pegawais as $peg)
                                        <option value="{{ $peg->id }}">{{ $peg->nama_pegawai }}</option>
                                    @endforeach
                                </select>
                                @error('pegawai_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-bold uppercase text-gray-500">Tanggal</label>
                                    <input type="date" wire:model.live="tgl_pengerjaan" class="w-full mt-1 border-gray-300 rounded p-2 text-sm">
                                </div>
                                <div>
                                    <label class="text-xs font-bold uppercase text-gray-500">Jam Mulai</label>
                                    <input type="time" wire:model.live="jam_mulai" class="w-full mt-1 border-gray-300 rounded p-2 text-sm">
                                </div>
                            </div>
                            @error('tgl_pengerjaan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @error('jam_mulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                            <button wire:click="assignTeknisi" wire:loading.attr="disabled" class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700 text-sm font-bold shadow-sm transition">
                                Simpan Jadwal & Lanjut
                            </button>
                        </div>
                    @endif

                {{-- STATUS: MENUNGGU PELUNASAN --}}
                @elseif($pesanan->status == 'menunggu_pelunasan')
                    <div class="text-center py-6 bg-yellow-50 rounded-lg text-yellow-800 border border-yellow-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-bold text-lg">Menunggu User</p>
                        <p class="text-sm">User sedang melakukan pelunasan sisa tagihan.</p>
                        <div class="mt-3 font-mono font-bold bg-white inline-block px-3 py-1 rounded border border-yellow-300">
                            Tagihan: Rp {{ number_format($pesanan->sisa_tagihan) }}
                        </div>
                    </div>

                {{-- STATUS: SIAP DIPASANG / SIAP DIKIRIM --}}
                @elseif($pesanan->status == 'siap_dipasang' || $pesanan->status == 'siap_dikirim' || $pesanan->status == 'lunas')
                    <div class="space-y-4 animate-fade-in">
                        <div class="text-center py-3 bg-green-50 rounded-lg text-green-800 text-sm border border-green-200">
                            @if($pesanan->status == 'siap_dipasang')
                                <p class="font-bold text-base">✅ SIAP DIPASANG</p>
                                <p class="text-xs mt-1">Barang ready & Lunas. Teknisi boleh berangkat kerja.</p>
                            @else
                                <p class="font-bold text-base">📦 SIAP DIKIRIM</p>
                                <p class="text-xs mt-1">Barang ready & Lunas. Silakan kirim barang ke ekspedisi.</p>
                            @endif
                        </div>
                        
                        @if(!$pesanan->butuh_pemasangan)
                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500">Input Nomor Resi</label>
                                <input type="text" wire:model="no_resi" class="w-full mt-1 border-gray-300 rounded p-2 text-sm focus:border-green-500 focus:ring-green-500" placeholder="Contoh: JNE-123456789">
                            </div>
                        @endif

                        <button wire:click="tandaiSelesai" onclick="return confirm('Yakin pesanan ini sudah selesai?')" class="w-full bg-gray-800 text-white py-2 rounded hover:bg-gray-900 text-sm font-bold shadow-sm transition">
                            Tandai Order Selesai
                        </button>
                    </div>

                {{-- STATUS LAIN --}}
                @else
                    <div class="text-center py-8 text-gray-400">
                        <p class="text-sm">Tidak ada tindakan yang diperlukan saat ini.</p>
                    </div>
                @endif
            </div>

            {{-- PANEL DARURAT: UBAH STATUS MANUAL --}}
            <div class="bg-gray-50 p-6 rounded-xl shadow-inner border border-gray-200">
                <h3 class="font-bold text-gray-500 mb-4 text-xs uppercase tracking-wider">Kontrol Manual (Override)</h3>
                
                <p class="text-xs text-gray-400 mb-3 leading-relaxed">
                    Gunakan fitur ini hanya jika flow otomatis tidak sesuai atau ingin merevisi status kesalahan input.
                </p>

                <div class="flex gap-2">
                    <select wire:model="status_manual" class="w-full border-gray-300 rounded text-xs focus:ring-gray-500 focus:border-gray-500">
                        <option value="">-- Pilih Status Baru --</option>
                        <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                        <option value="produksi">Produksi (Diproses)</option>
                        <option value="menunggu_pelunasan">Menunggu Pelunasan</option>
                        <option value="siap_dipasang">Siap Dipasang (Jasa)</option>
                        <option value="siap_dikirim">Siap Dikirim (Barang)</option>
                        <option value="selesai">Selesai</option>
                        <option value="batal">Dibatalkan</option>
                    </select>
                    
                    <button wire:click="updateStatusManual" 
                            onclick="return confirm('PERINGATAN: Mengubah status secara paksa dapat mempegaruhi alur sistem. Lanjutkan?') || event.stopImmediatePropagation()"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-xs font-bold transition">
                        Ubah
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>