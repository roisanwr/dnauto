<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Data Booking Masuk</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">ID Order</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Total & Layanan</th>
                        <th class="px-6 py-4">Status Pembayaran</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $order->nomor_order }}
                                <div class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('d M Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-700">{{ $order->snap_nama_penerima }}</div>
                                <div class="text-xs text-gray-500">{{ $order->snap_no_hp }}</div>
                                @if($order->butuh_pemasangan)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                        + Jasa Pasang
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-orange-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColor = match($order->status) {
                                        'lunas' => 'bg-green-100 text-green-700',
                                        'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-700',
                                        'batal', 'kadaluarsa', 'gagal' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                {{-- Tombol Detail (Nanti kita buat) --}}
                                {{-- UBAH JADI INI: --}}
                                <a href="{{ route('admin.pesanan.show', $order->id) }}" wire:navigate class="text-gray-400 hover:text-orange-600 transition">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                Belum ada pesanan masuk nih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>