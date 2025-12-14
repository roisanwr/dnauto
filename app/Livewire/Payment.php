<?php

namespace App\Livewire;

use App\Models\Pesanan;
use Livewire\Component;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Payment extends Component
{
    public $pesanan;
    public $snapToken;

    public function mount($id)
    {
        // 1. Ambil Data Pesanan
        $this->pesanan = Pesanan::findOrFail($id);

        // 2. Security Check: Pastikan yang buka adalah pemilik pesanan
        if ($this->pesanan->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        // 3. Kalau sudah lunas & tidak ada tagihan, lempar balik
        // Kecuali statusnya 'siap_dikirim'/'siap_dipasang' user masih boleh lihat resi/info
        if ($this->pesanan->status === 'lunas' || $this->pesanan->status === 'selesai') {
            // return redirect()->route('home'); // Opsional: matikan ini kalau user mau lihat history lunas
        }

        // KONFIGURASI MIDTRANS
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // LOGIC PENENTUAN TOKEN
        // A. Jika Status = Menunggu Pelunasan (User mau lunasin sisa)
        if ($this->pesanan->status == 'menunggu_pelunasan') {
            $this->buatTokenPelunasan();
        } 
        // B. Jika Token Kosong (Kasus DP Awal / Full Payment Awal)
        elseif (empty($this->pesanan->snap_token)) {
            $this->buatTokenBaru();
        }

        // Ambil token terakhir dari DB buat dikirim ke View
        $this->snapToken = $this->pesanan->snap_token;
    }

    public function buatTokenPelunasan()
    {
        // PENTING: Midtrans menolak Order ID yang sama.
        // Jadi kita buat ID Transaksi Baru: INV-XXX-PL-TIMESTAMP
        $transactionId = $this->pesanan->nomor_order . '-PL-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $transactionId, // ID Unik Pelunasan
                'gross_amount' => (int) $this->pesanan->sisa_tagihan, // HANYA SISA TAGIHAN
            ],
            'customer_details' => [
                'first_name' => $this->pesanan->snap_nama_penerima,
                'phone' => $this->pesanan->snap_no_hp,
            ],
            'item_details' => [
                [
                    'id' => 'PELUNASAN',
                    'price' => (int) $this->pesanan->sisa_tagihan,
                    'quantity' => 1,
                    'name' => 'Pelunasan Order ' . $this->pesanan->nomor_order,
                ]
            ]
        ];

        try {
            // Minta Token & Update DB
            $newToken = Snap::getSnapToken($params);
            $this->pesanan->update(['snap_token' => $newToken]);
        } catch (\Exception $e) {
            // Handle error connection midtrans
        }
    }

    public function buatTokenBaru()
    {
        // Ini logic standar buat pembayaran awal (DP/Full)
        // Hitung nominal yang harus dibayar SEKARANG (bukan total project)
        $amount = ($this->pesanan->jenis_pembayaran == 'dp') 
                    ? $this->pesanan->jumlah_dp 
                    : $this->pesanan->grand_total;

        $params = [
            'transaction_details' => [
                'order_id' => $this->pesanan->nomor_order, // Pake No Order Asli
                'gross_amount' => (int) $amount,
            ],
            'customer_details' => [
                'first_name' => $this->pesanan->snap_nama_penerima,
                'phone' => $this->pesanan->snap_no_hp,
            ],
            'item_details' => [
                [
                    'id' => 'TAGIHAN-AWAL',
                    'price' => (int) $amount,
                    'quantity' => 1,
                    'name' => 'Pembayaran Order ' . $this->pesanan->nomor_order,
                ]
            ]
        ];

        try {
            $newToken = Snap::getSnapToken($params);
            $this->pesanan->update(['snap_token' => $newToken]);
        } catch (\Exception $e) {
            // Handle error
        }
    }

    public function render()
    {
        return view('livewire.payment');
    }
}