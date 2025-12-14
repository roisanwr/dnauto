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

        // 3. Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // 4. LOGIC PENENTUAN TOKEN
        
        // KASUS A: Fase PELUNASAN (User mau lunasin sisa)
        if ($this->pesanan->status == 'menunggu_pelunasan') {
            
            // Kita SELALU buat token baru khusus pelunasan agar nominal update
            // (Misal: Admin baru input ongkir, jadi total tagihan berubah)
            $this->buatTokenPelunasan();

        } 
        // KASUS B: Fase AWAL (DP / Full Payment saat checkout)
        elseif ($this->pesanan->status == 'menunggu_pembayaran') {
            
            // Kalau token belum ada, buat baru.
            if (empty($this->pesanan->snap_token)) {
                $this->buatTokenAwal();
            } else {
                // Kalau sudah ada, pakai yang lama
                $this->snapToken = $this->pesanan->snap_token;
            }
        }
        // KASUS C: Status lain (Sudah lunas/selesai)
        else {
            $this->snapToken = $this->pesanan->snap_token;
        }
    }

    public function buatTokenPelunasan()
    {
        // PENTING: Gunakan timestamp agar ID Transaksi UNIK.
        // Midtrans akan menolak jika kita pakai ID yang sama dengan pembayaran DP.
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
            $newToken = Snap::getSnapToken($params);
            
            // Simpan token baru ke database
            $this->pesanan->update(['snap_token' => $newToken]);
            $this->snapToken = $newToken;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal terhubung ke Midtrans: ' . $e->getMessage());
        }
    }

    public function buatTokenAwal()
    {
        // Hitung nominal (DP 50% atau Full 100%)
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
                    'name' => 'Pembayaran Order (DP/Full)',
                ]
            ]
        ];

        try {
            $newToken = Snap::getSnapToken($params);
            $this->pesanan->update(['snap_token' => $newToken]);
            $this->snapToken = $newToken;
        } catch (\Exception $e) {
             session()->flash('error', 'Gagal terhubung ke Midtrans');
        }
    }

    public function render()
    {
        return view('livewire.payment');
    }
}