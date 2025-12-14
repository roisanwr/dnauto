<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <h2 class="text-2xl font-bold text-gray-900">Riwayat Pesanan</h2>
            
            {{-- Filter Tabs --}}
            <div class="flex space-x-1 bg-white p-1 rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
                @php
                    $tabs = [
                        'semua' => 'Semua',
                        'belum_bayar' => 'Tagihan',
                        'proses' => 'Berjalan',
                        'selesai' => 'Selesai',
                        'batal' => 'Dibatalkan'
                    ];
                @endphp
                
                @foreach($tabs as $key => $label)
                    <button wire:click="setFilter('{{ $key }}')"
                        class="px-4 py-2 text-sm font-medium rounded-md whitespace-nowrap transition
                        {{ $filterStatus === $key 
                            ? 'bg-gray-900 text-white shadow' 
                            : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            @forelse ($orders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-200">
                    
                    {{-- Header Card --}}
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                        <div class="text-sm">
                            <span class="font-bold text-gray-700">{{ $order->nomor_order }}</span>
                            <span class="text-gray-400 mx-2">|</span>
                            <span class="text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        
                        {{-- BADGE STATUS --}}
                        @php
                            $statusColor = 'bg-gray-100 text-gray-800';
                            $statusLabel = str_replace('_', ' ', $order->status);

                            switch($order->status) {
                                case 'menunggu_pembayaran': 
                                case 'menunggu_pelunasan':
                                    $statusColor = 'bg-yellow-100 text-yellow-800'; break;
                                case 'produksi': 
                                    $statusColor = 'bg-blue-100 text-blue-800'; break;
                                case 'siap_dipasang': 
                                case 'siap_dikirim':
                                    $statusColor = 'bg-purple-100 text-purple-800'; break;
                                case 'dikirim': 
                                case 'lunas':
                                case 'selesai':
                                    $statusColor = 'bg-green-100 text-green-800'; break;
                                case 'batal':
                                    $statusColor = 'bg-red-100 text-red-800'; break;
                            }
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row gap-6">
                            
                            {{-- Kolom Produk --}}
                            <div class="flex-1 space-y-4">
                                @foreach ($order->detailPesanan as $detail)
                                    <div class="flex gap-4">
                                        <div class="shrink-0">
                                            <img src="{{ $detail->produk->gambar ? asset('storage/'.$detail->produk->gambar) : 'https://via.placeholder.com/80' }}" 
                                                 class="w-20 h-20 object-cover rounded-lg bg-gray-100 border border-gray-200">
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900">{{ $detail->produk->nama_produk }}</h4>
                                            <p class="text-sm text-gray-500">{{ $detail->produk->kategori }}</p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_saat_beli, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Kolom Info Tagihan & Aksi --}}
                            <div class="lg:w-1/3 border-t lg:border-t-0 lg:border-l border-gray-100 lg:pl-6 pt-4 lg:pt-0 flex flex-col justify-between">
                                
                                <div class="space-y-2 text-sm mb-4">
                                    <div class="flex justify-between text-gray-500">
                                        <span>Total Project</span>
                                        <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    @if($order->jenis_pembayaran == 'dp')
                                        <div class="flex justify-between text-blue-600">
                                            <span>Sudah Dibayar (DP)</span>
                                            <span>- Rp {{ number_format($order->grand_total - $order->sisa_tagihan, 0, ',', '.') }}</span>
                                        </div>
                                    @endif

                                    <div class="border-t border-dashed my-2"></div>
                                    
                                    <div class="flex justify-between font-bold text-lg {{ $order->sisa_tagihan > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                        <span>
                                            {{ $order->sisa_tagihan > 0 ? 'Sisa Tagihan' : 'Lunas' }}
                                        </span>
                                        <span>Rp {{ number_format($order->sisa_tagihan, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                {{-- ACTION BUTTONS --}}
                                <div class="space-y-2">
                                    
                                    {{-- 1. Tombol Bayar (Awal / Pelunasan) --}}
                                    @if($order->sisa_tagihan > 0 && ($order->status == 'menunggu_pembayaran' || $order->status == 'menunggu_pelunasan'))
                                        <a href="{{ route('payment', $order->id) }}" class="block w-full text-center bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition">
                                            {{ $order->status == 'menunggu_pelunasan' ? 'Lunasi Sekarang' : 'Bayar Sekarang' }}
                                        </a>
                                    
                                    {{-- 2. Info Produksi --}}
                                    @elseif($order->status == 'produksi')
                                        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-xs text-center border border-blue-100">
                                            Pesanan sedang dikerjakan. Kami akan kabari jika sudah siap.
                                        </div>

                                    {{-- 3. Info Siap (Jasa) --}}
                                    @elseif($order->status == 'siap_dipasang')
                                        <div class="bg-purple-50 text-purple-700 px-4 py-2 rounded-lg text-xs border border-purple-100">
                                            <strong>Jadwal Terkonfirmasi!</strong><br>
                                            Teknisi akan datang/siap di bengkel sesuai jadwal.
                                            <div class="mt-1 font-mono text-sm">
                                                {{ $order->schedule ? \Carbon\Carbon::parse($order->schedule->tgl_pengerjaan)->format('d M Y') : '-' }} 
                                                ({{ $order->schedule->jam_mulai ?? '-' }})
                                            </div>
                                        </div>

                                    {{-- 4. Info Resi (Kirim) --}}
                                    @elseif(($order->status == 'dikirim' || $order->status == 'selesai') && !$order->butuh_pemasangan)
                                        <div class="bg-green-50 text-green-700 px-4 py-2 rounded-lg text-xs text-center border border-green-100">
                                            @if($order->no_resi)
                                                No Resi: <span class="font-bold font-mono select-all">{{ $order->no_resi }}</span>
                                            @else
                                                Paket sedang dalam pengiriman.
                                            @endif
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada pesanan</h3>
                    <p class="mt-1 text-gray-500">Yuk mulai belanja kebutuhan mobilmu!</p>
                    <a href="{{ route('home') }}" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-900 hover:bg-gray-800">
                        Cari Produk
                    </a>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>