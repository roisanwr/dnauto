<div class="max-w-5xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-stone-900 mb-8">Riwayat Pesanan Saya</h1>

    @if($orders->isEmpty())
        <div class="bg-white rounded-xl shadow p-8 text-center border border-stone-100">
            <p class="text-stone-500 mb-4">Kamu belum pernah belanja nih.</p>
            <a href="{{ route('home') }}" class="inline-block bg-stone-900 text-white px-6 py-3 rounded-lg font-bold hover:bg-stone-800 transition">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden hover:shadow-md transition">
                    {{-- Header Card --}}
                    <div class="bg-stone-50 px-6 py-4 border-b border-stone-100 flex justify-between items-center flex-wrap gap-2">
                        <div>
                            <span class="text-xs text-stone-500 font-medium uppercase tracking-wider">No. Order</span>
                            <p class="font-bold text-stone-800">{{ $order->nomor_order }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-stone-500 font-medium uppercase tracking-wider">Tanggal</span>
                            <p class="text-sm text-stone-700">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    {{-- Body Card --}}
                    <div class="px-6 py-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-sm text-stone-500">Total Belanja</p>
                                <p class="text-xl font-bold text-orange-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                            </div>
                            
                            {{-- Badge Status --}}
                            @php
                                $statusColor = match($order->status) {
                                    'lunas' => 'bg-green-100 text-green-700 border-green-200',
                                    'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    // Tambahan warna Merah untuk status gagal/batal
                                    'batal', 'dibatalkan', 'kadaluarsa', 'gagal' => 'bg-red-100 text-red-700 border-red-200',
                                    default => 'bg-gray-100 text-gray-700 border-gray-200'
                                };
                                
                                $statusLabel = ucwords(str_replace('_', ' ', $order->status));
                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="flex gap-3 justify-end mt-4 pt-4 border-t border-stone-100">
                            {{-- Kalau belum bayar, munculkan tombol Bayar --}}
                            @if($order->status == 'menunggu_pembayaran')
                                <a href="{{ route('payment', $order->id) }}" class="px-4 py-2 bg-stone-900 text-white text-sm font-bold rounded-lg hover:bg-stone-800 transition">
                                    Bayar Sekarang
                                </a>
                            @endif
                            
                            {{-- Tombol Detail (Optional, nanti bisa kita buat) --}}
                            {{-- <button class="px-4 py-2 bg-white border border-stone-300 text-stone-700 text-sm font-bold rounded-lg hover:bg-stone-50 transition">
                                Detail
                            </button> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>