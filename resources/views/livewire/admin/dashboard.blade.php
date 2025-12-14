<div class="space-y-8">
    
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
        <p class="text-gray-500">Pantau performa toko kamu hari ini.</p>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Card 1: Omzet --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                <i data-lucide="banknote" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Card 2: Pesanan Baru --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                <i data-lucide="shopping-bag" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Order Aktif</p>
                <p class="text-2xl font-bold text-gray-900">{{ $pesanan_baru }}</p>
            </div>
        </div>

        {{-- Card 3: Produk --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-orange-100 text-orange-600 rounded-lg">
                <i data-lucide="package" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Produk</p>
                <p class="text-2xl font-bold text-gray-900">{{ $total_produk }}</p>
            </div>
        </div>

        {{-- Card 4: Customer --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Pelanggan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $total_user }}</p>
            </div>
        </div>
    </div>

    {{-- TABEL PESANAN TERBARU --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">Transksi Terbaru</h3>
            <a href="{{ route('admin.pesanan') }}" class="text-sm text-orange-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-6 py-3">No. Order</th>
                        <th class="px-6 py-3">Nama Customer</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pesanan_terbaru as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">
                                #{{ $order->nomor_order }}
                            </td>
                            <td class="px-6 py-3">
                                {{ $order->snap_nama_penerima }}
                            </td>
                            <td class="px-6 py-3 font-bold text-gray-700">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-3">
                                @php
                                    $color = match($order->status) {
                                        'lunas' => 'text-green-600 bg-green-50 border-green-200',
                                        'menunggu_pembayaran' => 'text-yellow-600 bg-yellow-50 border-yellow-200',
                                        'sedang_dikerjakan' => 'text-blue-600 bg-blue-50 border-blue-200',
                                        'selesai' => 'text-gray-600 bg-gray-50 border-gray-200',
                                        default => 'text-red-600 bg-red-50 border-red-200'
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-bold border {{ $color }}">
                                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>