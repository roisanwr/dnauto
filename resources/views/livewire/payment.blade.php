<div class="max-w-2xl mx-auto bg-white rounded-xl p-6 shadow-sm border border-orange-50">
    <h2 class="text-lg font-bold text-stone-800">Pembayaran Pesanan</h2>

    @if(!isset($pesanan))
        <div class="mt-4 text-sm text-gray-500">Data pesanan tidak ditemukan.</div>
    @else
        <div class="mt-4">
            <div class="text-sm text-gray-500">No. Order</div>
            <div class="text-lg font-semibold text-orange-600">{{ $pesanan->nomor_order }}</div>
            <div class="mt-2 text-sm text-gray-500">Total</div>
            <div class="text-xl font-bold">Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}</div>
        </div>

        @if($snapToken)
            <div class="mt-6">
                <button id="pay-button" class="px-4 py-2 bg-orange-500 text-white rounded-lg shadow-sm hover:bg-orange-600 transition-colors">Bayar Sekarang</button>
            </div>

            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
            <script>
                document.getElementById('pay-button').addEventListener('click', function () {
                    snap.pay('{{ $snapToken }}');
                });
            </script>
        @else
            <div class="mt-6 text-sm text-gray-500">Token pembayaran belum tersedia. Coba refresh halaman.</div>
        @endif
    @endif
</div>