<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $pesanan->nomor_order }} - DN Auto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-8 text-gray-800">

    {{-- Kertas A4 --}}
    <div class="max-w-3xl mx-auto bg-white p-8 shadow-lg print:shadow-none print:p-0">
        
        {{-- Header Invoice --}}
        <div class="flex justify-between items-start border-b-2 border-gray-800 pb-6 mb-6">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">DN AUTO</h1>
                <p class="text-sm text-gray-500 mt-1">Bodykit Custom & Modifikasi Mobil</p>
                <p class="text-sm text-gray-500">Jl. Contoh Bengkel No. 123, Bekasi, Jawa Barat</p>
                <p class="text-sm text-gray-500">WA: 0812-3456-7890</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-gray-600 uppercase">Invoice</h2>
                <p class="font-mono font-bold text-lg text-gray-900 mt-2">#{{ $pesanan->nomor_order }}</p>
                <p class="text-sm text-gray-500">Tanggal: {{ $pesanan->created_at->format('d M Y') }}</p>
            </div>
        </div>

        {{-- Info Customer --}}
        <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-100 print:bg-transparent print:border-gray-200">
            <h3 class="font-bold text-gray-700 uppercase text-xs mb-2">Ditagihkan Kepada:</h3>
            <p class="font-bold text-lg text-gray-900">{{ $pesanan->snap_nama_penerima }}</p>
            <p class="text-gray-600">{{ $pesanan->snap_no_hp }}</p>
            <p class="text-gray-600 text-sm w-2/3 mt-1">{{ $pesanan->snap_alamat_lengkap }}</p>
        </div>

        {{-- Tabel Item --}}
        <table class="w-full mb-8 text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase">
                    <th class="py-3 px-4 rounded-l-lg">Deskripsi Item</th>
                    <th class="py-3 px-4 text-center">Qty</th>
                    <th class="py-3 px-4 text-right">Harga Satuan</th>
                    <th class="py-3 px-4 text-right rounded-r-lg">Total</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @foreach($pesanan->detailPesanan as $item)
                <tr class="border-b border-gray-100">
                    <td class="py-3 px-4 font-medium">{{ $item->produk->nama_produk }}</td>
                    <td class="py-3 px-4 text-center">{{ $item->jumlah }}</td>
                    <td class="py-3 px-4 text-right">Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 text-right font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                
                {{-- Baris Biaya Layanan/Ongkir --}}
                @if($pesanan->biaya_layanan > 0)
                <tr class="border-b border-gray-100">
                    <td class="py-3 px-4 text-gray-500 italic">Biaya Layanan / Ongkos Kirim</td>
                    <td class="py-3 px-4 text-center text-gray-500">-</td>
                    <td class="py-3 px-4 text-right text-gray-500">-</td>
                    <td class="py-3 px-4 text-right font-bold">Rp {{ number_format($pesanan->biaya_layanan, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        {{-- Total & Status --}}
        <div class="flex justify-end mb-12">
            <div class="w-1/2 space-y-2">
                <div class="flex justify-between text-gray-600 text-sm">
                    <span>Total Tagihan</span>
                    <span class="font-medium">Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}</span>
                </div>
                
                @php
                    $totalBayar = $pesanan->grand_total - $pesanan->sisa_tagihan;
                @endphp

                <div class="flex justify-between text-green-600 text-sm">
                    <span>Sudah Dibayar</span>
                    <span class="font-medium">- Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                </div>
                
                <div class="border-t border-gray-800 pt-2 flex justify-between items-center mt-2">
                    <span class="font-bold text-gray-800 uppercase">Sisa Pembayaran</span>
                    <span class="font-extrabold text-2xl {{ $pesanan->sisa_tagihan > 0 ? 'text-red-600' : 'text-gray-900' }}">
                        Rp {{ number_format($pesanan->sisa_tagihan, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Stempel Status --}}
                <div class="mt-4 text-right">
                    @if($pesanan->sisa_tagihan <= 0)
                        <span class="inline-block border-2 border-green-600 text-green-600 font-black text-xl px-4 py-1 transform -rotate-6 rounded opacity-80 uppercase tracking-widest">
                            LUNAS
                        </span>
                    @else
                        <span class="inline-block border-2 border-red-600 text-red-600 font-black text-xl px-4 py-1 transform -rotate-6 rounded opacity-80 uppercase tracking-widest">
                            BELUM LUNAS
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Footer Tanda Tangan --}}
        <div class="flex justify-between mt-16 pt-8 border-t border-gray-200 text-xs text-gray-500 text-center">
            <div class="w-1/3">
                <p class="mb-16">Penerima</p>
                <p class="font-bold border-t border-gray-300 pt-1 inline-block px-8">({{ $pesanan->snap_nama_penerima }})</p>
            </div>
            <div class="w-1/3">
                <p class="mb-16">Hormat Kami,</p>
                <p class="font-bold border-t border-gray-300 pt-1 inline-block px-8">( Admin DN Auto )</p>
            </div>
        </div>

        {{-- Tombol Print (Hilang pas diprint) --}}
        <div class="fixed bottom-8 right-8 no-print">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-xl flex items-center gap-2 transition transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Invoice
            </button>
        </div>

    </div>

</body>
</html>