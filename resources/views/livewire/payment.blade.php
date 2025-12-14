<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- HEADER --}}
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Pembayaran</h2>
            <p class="mt-2 text-sm text-gray-600">
                Order ID: <span class="font-mono font-bold">{{ $pesanan->nomor_order }}</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            
            {{-- LOGIC HITUNGAN TAMPILAN --}}
            @php
                $tagihanAktif = $pesanan->grand_total;
                $labelTagihan = 'Total Tagihan';
                $keterangan = 'Pembayaran Lunas di Awal';

                // Jika status DP (Awal)
                if($pesanan->status == 'menunggu_pembayaran' && $pesanan->jenis_pembayaran == 'dp') {
                    $tagihanAktif = $pesanan->jumlah_dp;
                    $labelTagihan = 'Uang Muka (DP 50%)';
                    $keterangan = 'Anda memilih metode pembayaran DP. Sisa pelunasan dibayarkan setelah barang jadi.';
                }
                // Jika status Pelunasan (Akhir)
                elseif($pesanan->status == 'menunggu_pelunasan') {
                    $tagihanAktif = $pesanan->sisa_tagihan;
                    $labelTagihan = 'Pelunasan Sisa Tagihan';
                    $keterangan = 'Barang/Jasa sudah siap. Silakan lunasi sisa tagihan ini.';
                }
            @endphp

            <div class="p-8">
                
                {{-- INFO UTAMA (Yang harus dibayar sekarang) --}}
                <div class="text-center py-6 bg-blue-50 rounded-xl border border-blue-100 mb-6">
                    <p class="text-sm font-bold text-blue-600 uppercase tracking-wide mb-1">
                        {{ $labelTagihan }}
                    </p>
                    <h1 class="text-4xl font-extrabold text-gray-900">
                        Rp {{ number_format($tagihanAktif, 0, ',', '.') }}
                    </h1>
                    <p class="text-xs text-gray-500 mt-2 px-4">
                        {{ $keterangan }}
                    </p>
                </div>

                {{-- RINCIAN DETAIL --}}
                <div class="space-y-4 border-t border-gray-100 pt-6">
                    <h3 class="font-bold text-gray-800">Rincian Order</h3>
                    
                    {{-- List Item --}}
                    <div class="space-y-3">
                        @foreach($pesanan->detailPesanan as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $item->produk->nama_produk }} (x{{ $item->jumlah }})</span>
                            <span class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        
                        {{-- Ongkir / Jasa --}}
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Biaya Layanan / Ongkir</span>
                            <span class="font-medium">Rp {{ number_format($pesanan->biaya_layanan, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- TOTAL PROJECT (Referensi) --}}
                    <div class="border-t border-dashed border-gray-200 pt-3 mt-3">
                        <div class="flex justify-between items-center text-gray-500">
                            <span class="text-xs uppercase font-bold">Total Nilai Order</span>
                            <span class="font-semibold line-through decoration-gray-400">Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}</span>
                        </div>
                        
                        {{-- Kalau Pelunasan, kasih tau uang yang udah masuk --}}
                        @if($pesanan->status == 'menunggu_pelunasan')
                            <div class="flex justify-between items-center text-green-600 mt-1">
                                <span class="text-xs uppercase font-bold">Sudah Dibayar (DP)</span>
                                <span class="font-semibold">- Rp {{ number_format($pesanan->grand_total - $pesanan->sisa_tagihan, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- TOMBOL BAYAR --}}
                <div class="mt-8">
                    @if ($snapToken)
                        <button id="pay-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 text-lg flex justify-center items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Bayar Sekarang (Rp {{ number_format($tagihanAktif, 0, ',', '.') }})
                        </button>
                    @else
                        <div class="bg-red-50 text-red-600 p-4 rounded-lg text-center">
                            Token pembayaran tidak valid. Silakan refresh halaman atau hubungi admin.
                        </div>
                    @endif
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('history') }}" class="text-sm text-gray-500 hover:text-gray-900 underline">
                        Kembali ke Riwayat Pesanan
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-6 text-center text-xs text-gray-400">
            Pembayaran diamankan oleh Midtrans Payment Gateway.
        </div>
    </div>

    {{-- MIDTRANS SCRIPT --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    window.location.href = "{{ route('history') }}";
                },
                onPending: function(result){
                    window.location.href = "{{ route('history') }}";
                },
                onError: function(result){
                    alert("Pembayaran Gagal!");
                }
            });
        };
    </script>
</div>