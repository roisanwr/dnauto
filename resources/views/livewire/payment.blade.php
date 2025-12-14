<?php

namespace App\Livewire;

use App\Models\Pesanan;
use Livewire\Component;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class Payment extends Component
{
    public $pesanan;
    public $snapToken;

    public function mount($id)
    {
        // Cari pesanan
        $this->pesanan = Pesanan::findOrFail($id);

        // KONFIGURASI MIDTRANS
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // LOGIC PENENTUAN TOKEN
        // 1. Jika Status = Menunggu Pelunasan (User mau lunasin)
        if ($this->pesanan->status == 'menunggu_pelunasan') {
            $this->buatTokenPelunasan();
        } 
        // 2. Jika Token Kosong (Kasus error / token expired)
        elseif (empty($this->pesanan->snap_token)) {
            $this->buatTokenBaru();
        }

        // Ambil token terakhir dari DB
        $this->snapToken = $this->pesanan->snap_token;
    }

    public function buatTokenPelunasan()
    {
        // PENTING: Midtrans menolak Order ID yang sama.
        // Jadi kita buat ID Transaksi Baru: INV-XXX-PL-TIMESTAMP
        $transactionId = $this->pesanan->nomor_order . '-PL-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $transactionId,
                'gross_amount' => (int) $this->pesanan->sisa_tagihan, // Pake Sisa Tagihan!
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

        // Minta Token & Update DB
        $newToken = Snap::getSnapToken($params);
        $this->pesanan->update(['snap_token' => $newToken]);
    }

    public function buatTokenBaru()
    {
        // Ini logic standar buat pembayaran awal (DP/Full) kalau token hilang
        // Pake Order ID asli gapapa kalau belum pernah sukses bayar
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

        $newToken = Snap::getSnapToken($params);
        $this->pesanan->update(['snap_token' => $newToken]);
    }

    public function render()
    {
        return view('livewire.payment');
    }
}