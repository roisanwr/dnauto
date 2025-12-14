<?php

namespace App\Livewire;

use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan; // Note: Ini t_pesanan_produk di DB baru, pastikan modelnya menyesuaikan
use App\Models\Alamat;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class Checkout extends Component
{
    public $produk;
    public $qty = 1;

    // Data Pilihan User
    public $alamat_id; // ID Alamat yang dipilih
    public $layanan = 'bengkel'; // 'bengkel', 'home_service', 'kirim'
    public $tipe_pembayaran = 'dp'; // 'dp', 'lunas'
    
    // Variabel Hitungan
    public $harga_jasa_produk = 0; // Jasa pasang per item (dari DB produk)
    public $biaya_transport = 0;   // Ongkos bensin teknisi
    public $biaya_layanan_total = 0; // Gabungan Jasa + Transport
    
    public $grand_total = 0;
    public $nominal_yang_harus_dibayar = 0; // Angka yang dikirim ke Midtrans

    public $error_message = ''; // Buat nampilin error kalau area gak tercover

    public function mount($produkId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->produk = Produk::findOrFail($produkId);
        
        // Ambil alamat user (kalau ada), set default ke primary
        $primaryAddress = Alamat::where('user_id', Auth::id())->where('is_primary', true)->first();
        if ($primaryAddress) {
            $this->alamat_id = $primaryAddress->id;
        } else {
            // Kalau gak punya alamat primary, ambil sembarang alamat dia
            $firstAddress = Alamat::where('user_id', Auth::id())->first();
            $this->alamat_id = $firstAddress ? $firstAddress->id : null;
        }

        $this->hitungTotal();
    }

    // Setiap user ganti opsi, hitung ulang
    public function updated($propertyName)
    {
        $this->hitungTotal();
    }

    public function hitungTotal()
    {
        $this->error_message = '';
        $hargaBarangTotal = $this->produk->harga * $this->qty;
        $this->harga_jasa_produk = $this->produk->harga_jasa * $this->qty;

        // 1. LOGIC LAYANAN & TRANSPORT
        if ($this->layanan == 'bengkel') {
            // Kalau di bengkel, kena biaya jasa pasang, tapi transport 0
            $this->biaya_transport = 0;
            $this->biaya_layanan_total = $this->harga_jasa_produk;
        
        } elseif ($this->layanan == 'kirim') {
            // Kalau kirim, biaya layanan 0 dulu (Ongkir dihitung Admin manual nanti)
            $this->biaya_transport = 0;
            $this->biaya_layanan_total = 0;
        
        } elseif ($this->layanan == 'home_service') {
            // Logic Home Service Berdasarkan Kota
            $alamat = Alamat::find($this->alamat_id);
            
            if (!$alamat) {
                $this->biaya_transport = 0;
                $this->error_message = 'Silakan pilih alamat terlebih dahulu.';
            } else {
                $kota = strtolower($alamat->kota);
                
                // Tarif Zona (Sesuai Request)
                if (Str::contains($kota, 'bekasi')) {
                    $this->biaya_transport = 50000;
                } elseif (Str::contains($kota, 'jakarta')) {
                    $this->biaya_transport = 100000;
                } elseif (Str::contains($kota, ['cikarang', 'depok', 'tangerang', 'bogor'])) {
                    $this->biaya_transport = 150000;
                } else {
                    $this->biaya_transport = 0;
                    $this->error_message = "Maaf, Home Service belum tersedia untuk area $alamat->kota. Pilih 'Bengkel' atau 'Kirim'.";
                }

                // Kalau area valid, total = Jasa Pasang + Transport
                if ($this->error_message == '') {
                    $this->biaya_layanan_total = $this->harga_jasa_produk + $this->biaya_transport;
                } else {
                    $this->biaya_layanan_total = 0; // Reset kalau error
                }
            }
        }

        // 2. HITUNG GRAND TOTAL (Nilai Proyek)
        $this->grand_total = $hargaBarangTotal + $this->biaya_layanan_total;

        // 3. LOGIC PEMBAYARAN (DP vs LUNAS)
        if ($this->tipe_pembayaran == 'dp') {
            // DP 50% dari Grand Total saat ini
            $this->nominal_yang_harus_dibayar = $this->grand_total * 0.5;
        } else {
            $this->nominal_yang_harus_dibayar = $this->grand_total;
        }
    }

    public function buatPesanan()
    {
        $this->validate([
            'alamat_id' => 'required|exists:alamat,id',
        ]);

        if ($this->error_message) {
            return; // Jangan lanjut kalau ada error area
        }

        // Ambil Data Alamat untuk Snapshot
        $alamat = Alamat::find($this->alamat_id);
        
        // Buat Nomor Order
        $nomorOrder = 'INV-' . date('ymd') . rand(100, 999);

        // Hitung Keuangan Final
        $jumlahDP = 0;
        $sisaTagihan = 0;

        if ($this->tipe_pembayaran == 'dp') {
            $jumlahDP = $this->grand_total * 0.5;
            $sisaTagihan = $this->grand_total - $jumlahDP;
        } else {
            // Kalau lunas, DP dianggap 0, tapi sisa tagihan 0
            $jumlahDP = 0; 
            $sisaTagihan = 0;
        }

        // Simpan ke Pesanan
        $pesanan = Pesanan::create([
            'nomor_order' => $nomorOrder,
            'user_id' => Auth::id(),
            'snap_nama_penerima' => $alamat->nama_penerima,
            'snap_no_hp' => $alamat->no_hp_penerima,
            'snap_alamat_lengkap' => $alamat->alamat_lengkap . ', ' . $alamat->kota,
            
            'jenis_pembayaran' => $this->tipe_pembayaran,
            'status' => 'menunggu_pembayaran',
            
            'total_belanja' => $this->produk->harga * $this->qty,
            'biaya_layanan' => $this->biaya_layanan_total,
            'grand_total' => $this->grand_total,
            
            'jumlah_dp' => $jumlahDP,
            'sisa_tagihan' => $sisaTagihan,
            
            'butuh_pemasangan' => ($this->layanan != 'kirim'), // Kalau kirim, berarti gak dipasangin
        ]);

        // Simpan Detail Item (Tabel t_pesanan_produk)
        // Note: Pastikan kamu punya model 'TPesananProduk' atau sesuaikan nama modelnya
        // Disini saya pakai raw DB insert atau model yang kamu punya. 
        // Asumsi modelnya 'DetailPesanan' mapping ke 't_pesanan_produk'
        \App\Models\DetailPesanan::create([ 
            'pesanan_id' => $pesanan->id,
            'produk_id' => $this->produk->id,
            'jumlah' => $this->qty,
            'harga_saat_beli' => $this->produk->harga,
            'subtotal' => $this->produk->harga * $this->qty,
        ]);

        // --- MIDTRANS ---
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $nomorOrder,
                'gross_amount' => (int) $this->nominal_yang_harus_dibayar, 
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->no_hp,
            ],
            'item_details' => [
                [
                    'id' => 'TAGIHAN-'. $nomorOrder,
                    'price' => (int) $this->nominal_yang_harus_dibayar,
                    'quantity' => 1,
                    'name' => 'Pembayaran ' . strtoupper($this->tipe_pembayaran) . ' Order',
                ]
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            $pesanan->snap_token = $snapToken;
            $pesanan->save();

            return redirect()->route('payment', $pesanan->id);

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout', [
            'daftarAlamat' => Alamat::where('user_id', Auth::id())->get()
        ]);
    }
}