<?php

namespace App\Livewire\Admin\Pesanan;

use App\Models\Pesanan;
use App\Models\Pegawai;
use App\Models\Schedule;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Show extends Component
{
    public $pesanan;
    public $activeTab = 'info'; // info, bayar, proses

    // Form Input Ongkir (Khusus Kirim)
    public $ongkir_real = 0;
    public $no_resi;
    
    // Form Input Jadwal (Khusus Jasa)
    public $pegawai_id;
    public $tgl_pengerjaan;
    public $jam_mulai;
    public $status_manual;

    public function mount($id)
    {
        $this->pesanan = Pesanan::with(['detailPesanan.produk', 'pembayaran', 'schedule.pegawai', 'user'])->findOrFail($id);
        
        // Load data existing kalau ada
        if ($this->pesanan->schedule) {
            $this->pegawai_id = $this->pesanan->schedule->pegawai_id;
            $this->tgl_pengerjaan = $this->pesanan->schedule->tgl_pengerjaan;
            $this->jam_mulai = $this->pesanan->schedule->jam_mulai;
        }
    }

// --- ACTION 1: UPDATE ONGKIR (Barang Siap Kirim) ---
    public function simpanOngkir()
    {
        $this->validate([
            'ongkir_real' => 'required|numeric|min:0',
        ]);

        // ... logic hitung sisa tagihan sama ...
        // ... bedanya cuma di status ...

        $this->pesanan->update([
            'biaya_layanan' => $this->pesanan->biaya_layanan + $this->ongkir_real,
            'grand_total' => $newGrandTotal,
            'sisa_tagihan' => $sisaBaru,
            
            // Logic: Ongkir diinput = Barang Jadi = Minta Duit
            'status' => 'menunggu_pelunasan' 
        ]);

        session()->flash('message', 'Ongkir diupdate. User diminta melunasi tagihan.');
    }

    // --- ACTION 2: ASSIGN TEKNISI (Order Jasa) ---
    public function assignTeknisi()
    {
        // ... validasi sama ...
        
        // ... logic schedule sama ...

        // PERUBAHAN STATUS DISINI
        if ($this->pesanan->sisa_tagihan > 0) {
            // Kalau masih ada sisa tagihan (DP), minta pelunasan
            $this->pesanan->update(['status' => 'menunggu_pelunasan']);
            session()->flash('message', 'Jadwal dibuat. Menunggu pelunasan user.');
        } else {
            // Kalau sudah lunas (Full Payment), berarti SIAP DIPASANG
            $this->pesanan->update(['status' => 'siap_dipasang']); 
            session()->flash('message', 'Jadwal dibuat. Barang siap dipasang.');
        }
    }
    
    // --- ACTION 3: SELESAIKAN ORDER ---
    public function tandaiSelesai()
    {
        // ... logic sama ...
    }

    // --- ACTION 4: MANUAL STATUS (Update Listnya) ---
    public function updateStatusManual()
    {
        // Update daftar status yang boleh dipilih manual
        $this->validate([
            'status_manual' => 'required|in:menunggu_pembayaran,menunggu_verifikasi,produksi,siap_dipasang,siap_dikirim,menunggu_pelunasan,lunas,selesai,batal'
        ]);

        $this->pesanan->update(['status' => $this->status_manual]);
        // ...
    }
    public function render()
    {
        return view('livewire.admin.pesanan.show', [
            'pegawais' => Pegawai::where('status_ketersediaan', 'available')->get()
        ]);
    }
}