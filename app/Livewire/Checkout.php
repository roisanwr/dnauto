<?php

namespace App\Livewire;

use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Midtrans\Config; // <--- Import Library Midtrans
use Midtrans\Snap;   // <--- Import Library Midtrans

class Checkout extends Component
{
    public $produk;
    public $qty = 1;
    
    public $nama_penerima;
    public $no_hp;
    public $alamat_lengkap;
    public $butuh_pemasangan = false;
    
    public $biaya_pasang = 0;
    public $grand_total = 0;

    public function mount($produkId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->produk = Produk::findOrFail($produkId);
        
        $user = Auth::user();
        $this->nama_penerima = $user->name;
        $this->no_hp = $user->no_hp ?? '';
        
        $this->hitungTotal();
    }

    public function updatedButuhPemasangan()
    {
        $this->hitungTotal();
    }

    public function hitungTotal()
    {
        $hargaBarang = $this->produk->harga * $this->qty;
        
        if ($this->butuh_pemasangan) {
            $this->biaya_pasang = max(50000, $this->produk->harga * 0.1); 
        } else {
            $this->biaya_pasang = 0;
        }

        $this->grand_total = $hargaBarang + $this->biaya_pasang;
    }

    public function buatPesanan()
    {
        $this->validate([
            'nama_penerima' => 'required',
            'no_hp' => 'required',
            'alamat_lengkap' => 'required|min:10',
        ]);

        // 1. Buat Nomor Order Unik
        $nomorOrder = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

        // 2. Simpan Data Pesanan (Status Awal: menunggu_pembayaran)
        $pesanan = Pesanan::create([
            'nomor_order' => $nomorOrder,
            'user_id' => Auth::id(),
            'snap_nama_penerima' => $this->nama_penerima,
            'snap_no_hp' => $this->no_hp,
            'snap_alamat_lengkap' => $this->alamat_lengkap,
            'status' => 'menunggu_pembayaran', // <--- Status kita ubah jadi ini
            'total_belanja' => $this->produk->harga * $this->qty,
            'biaya_layanan' => $this->biaya_pasang,
            'grand_total' => $this->grand_total,
            'sisa_tagihan' => $this->grand_total,
            'butuh_pemasangan' => $this->butuh_pemasangan,
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $this->produk->id,
            'jumlah' => $this->qty,
            'harga_saat_beli' => $this->produk->harga,
            'subtotal' => $this->produk->harga * $this->qty,
        ]);

        // ==========================================
        // INTEGRASI MIDTRANS DIMULAI DI SINI
        // ==========================================
        
        // 3. Set Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // 4. Siapkan Parameter untuk dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $nomorOrder,
                'gross_amount' => (int) $this->grand_total, // Harus Integer
            ],
            'customer_details' => [
                'first_name' => $this->nama_penerima,
                'email' => Auth::user()->email,
                'phone' => $this->no_hp,
            ],
            'item_details' => [
                [
                    'id' => $this->produk->id,
                    'price' => (int) $this->produk->harga,
                    'quantity' => $this->qty,
                    'name' => substr($this->produk->nama_produk, 0, 50), // Nama max 50 char
                ],
                // Tambahkan item Jasa Pasang jika ada
            ]
        ];

        // Hack kecil: Kalau ada biaya pasang, masukkan sebagai item tambahan
        if($this->biaya_pasang > 0){
            $params['item_details'][] = [
                'id' => 'JASA-PASANG',
                'price' => (int) $this->biaya_pasang,
                'quantity' => 1,
                'name' => 'Jasa Pemasangan & Instalasi'
            ];
        }

        try {
            // 5. Minta Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // 6. Simpan Token ke Database
            $pesanan->snap_token = $snapToken;
            $pesanan->save();

            // 7. Redirect ke Halaman Pembayaran (Payment Page)
            return redirect()->route('payment', $pesanan->id);

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}