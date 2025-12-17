<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Antrean Servis</h1>
            <p class="text-sm text-gray-500 mt-1">
                @if($filterTanggal)
                    Menampilkan data tanggal: <span class="font-bold text-orange-600">{{ \Carbon\Carbon::parse($filterTanggal)->format('d M Y') }}</span>
                @else
                    Menampilkan jadwal: <span class="font-bold text-orange-600">1 Minggu Lalu - 2 Minggu Kedepan</span>
                @endif
            </p>
        </div>
        
        {{-- Filter Tanggal --}}
        <div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-gray-200 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase px-1">Filter Tgl:</span>
            <input type="date" wire:model.live="filterTanggal" class="border-none text-sm focus:ring-0 text-gray-800 font-bold p-0 cursor-pointer">
            
            {{-- Tombol Reset Filter --}}
            @if($filterTanggal)
                <button wire:click="$set('filterTanggal', null)" class="text-xs text-red-500 hover:text-red-700 font-bold px-2 border-l border-gray-200">
                    Reset
                </button>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Tgl & Jam</th> {{-- Digabung biar hemat tempat --}}
                    <th class="px-6 py-4">Teknisi</th>
                    <th class="px-6 py-4">Customer Info</th>
                    <th class="px-6 py-4">Pekerjaan</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($jadwals as $jadwal)
                <tr class="hover:bg-gray-50 transition group">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            {{-- TANGGAL --}}
                            <span class="font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($jadwal->tgl_pengerjaan)->format('d M Y') }}
                            </span>
                            {{-- JAM --}}
                            <span class="font-mono text-orange-600 text-lg font-bold">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                            </span>
                            {{-- Penanda Hari Ini --}}
                            @if(\Carbon\Carbon::parse($jadwal->tgl_pengerjaan)->isToday())
                                <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-600 text-[10px] font-bold rounded-full w-fit">HARI INI</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-stone-100 border border-stone-200 flex items-center justify-center text-xs font-bold text-stone-600">
                                {{ substr($jadwal->pegawai->nama_pegawai ?? 'X', 0, 1) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $jadwal->pegawai->nama_pegawai ?? 'Dihapus' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-900">{{ $jadwal->pesanan->snap_nama_penerima }}</p>
                        <p class="text-xs text-gray-500 line-clamp-1">{{ $jadwal->pesanan->snap_alamat_lengkap }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @if($jadwal->pesanan->detailPesanan->isNotEmpty())
                            <span class="text-xs font-medium bg-blue-50 text-blue-700 px-2 py-1 rounded">
                                {{ $jadwal->pesanan->detailPesanan->first()->produk->nama_produk }}
                            </span>
                            @if($jadwal->pesanan->detailPesanan->count() > 1)
                                <span class="text-xs text-gray-400">+{{ $jadwal->pesanan->detailPesanan->count() - 1 }} lainnya</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($jadwal->status == 'selesai')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Selesai</span>
                        @elseif($jadwal->status == 'terjadwal')
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold">Terjadwal</span>
                        @else
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold">{{ $jadwal->status }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.pesanan.show', $jadwal->pesanan_id) }}" class="text-gray-400 hover:text-orange-600 font-bold text-xs flex items-center justify-center gap-1 group-hover:underline">
                            Detail <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 bg-white">
                        <div class="flex flex-col items-center">
                            <svg class="w-10 h-10 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="font-medium">Tidak ada jadwal servis dalam rentang waktu ini.</p>
                            @if($filterTanggal)
                                <button wire:click="$set('filterTanggal', null)" class="mt-2 text-sm text-orange-600 hover:underline">Tampilkan Semua Jadwal</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $jadwals->links() }}
</div>