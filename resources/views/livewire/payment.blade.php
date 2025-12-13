<div class="max-w-3xl mx-auto py-12 px-4">
    
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-stone-100">
        
        {{-- Header --}}
        <div class="bg-stone-900 px-8 py-6 text-center">
            <h1 class="text-white font-bold text-xl">Selesaikan Pembayaran</h1>
            <p class="text-stone-400 text-sm mt-1">Order ID: {{ $this->pesanan->nomor_order }}</p>
        </div>

        {{-- Body --}}
        <div class="p-8 text-center space-y-6">
            
            <div class="py-4">
                <p class="text-stone-500 mb-2">Total Tagihan</p>
                <h2 class="text-4xl font-extrabold text-orange-600">
                    Rp {{ number_format($this->pesanan->grand_total, 0, ',', '.') }}
                </h2>
            </div>

            <div class="bg-orange-50 rounded-xl p-4 text-sm text-stone-600 border border-orange-100">
                Silakan selesaikan pembayaran agar pesanan Anda segera diproses oleh teknisi kami.
            </div>

            {{-- TOMBOL BAYAR (Pemicu Pop-up) --}}
            <button id="pay-button" class="w-full bg-stone-900 text-white font-bold py-4 rounded-xl hover:bg-stone-800 transition-transform active:scale-95 shadow-lg shadow-stone-200">
                Bayar Sekarang
            </button>
            
            <a href="{{ route('home') }}" class="block text-stone-400 text-sm font-medium hover:text-stone-600 mt-4">
                Kembali ke Beranda
            </a>
        </div>
    </div>

    {{-- SCRIPT MIDTRANS (WAJIB ADA) --}}
    {{-- Kita ambil Client Key dari Config --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // SnapToken dari Database dipanggil di sini
            snap.pay('{{ $this->pesanan->snap_token }}', {
                // Callback jika berhasil
                onSuccess: function(result){
                    alert("Pembayaran Berhasil!");
                    window.location.href = "/"; // Nanti kita arahkan ke halaman sukses
                },
                // Callback jika pending (tutup pop-up tapi belum bayar)
                onPending: function(result){
                    alert("Menunggu pembayaran...");
                },
                // Callback jika error
                onError: function(result){
                    alert("Pembayaran gagal!");
                }
            });
        };
    </script>
</div>