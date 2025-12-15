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
        // Load data pesanan beserta relasinya
        $this->pesanan = Pesanan::with(['detailPesanan.produk', 'pembayaran', 'schedule.pegawai', 'user'])->findOrFail($id);
        
        // Load data existing schedule kalau ada (untuk form edit jadwal)
        if ($this->pesanan->schedule) {
            $this->pegawai_id = $this->pesanan->schedule->pegawai_id;
            $this->tgl_pengerjaan = $this->pesanan->schedule->tgl_pengerjaan;
            $this->jam_mulai = $this->pesanan->schedule->jam_mulai;
        }

        // Load data ongkir existing kalau ada
        if (!$this->pesanan->butuh_pemasangan && $this->pesanan->biaya_layanan > 0) {
            $this->ongkir_real = $this->pesanan->biaya_layanan;
        }
        
        // Load resi
        if ($this->pesanan->no_resi) {
            $this->no_resi = $this->pesanan->no_resi;
        }
    }

    // --- ACTION 1: UPDATE ONGKIR (Barang Siap Kirim) ---
    public function simpanOngkir()
    {
        $this->validate([
            'ongkir_real' => 'required|numeric|min:0',
        ]);

        // 1. Definisikan Biaya Layanan Baru (Ongkir Real)
        $biayaLayananBaru = $this->ongkir_real; 

        // 2. Hitung Grand Total Baru
        // Rumus: Total Belanja Barang + Ongkir Real
        $newGrandTotal = $this->pesanan->total_belanja + $biayaLayananBaru;

        // 3. Hitung Sisa Tagihan Baru
        // Kita cari tahu dulu berapa uang yang SUDAH masuk (DP)
        // Rumus: Grand Total Lama - Sisa Tagihan Lama = Uang Masuk
        $uangSudahMasuk = $this->pesanan->grand_total - $this->pesanan->sisa_tagihan;
        
        // Sisa Tagihan Baru = Grand Total Baru - Uang Masuk
        $sisaBaru = $newGrandTotal - $uangSudahMasuk;

        // 4. Update Database
        $this->pesanan->update([
            'biaya_layanan' => $biayaLayananBaru,
            'grand_total' => $newGrandTotal,
            'sisa_tagihan' => $sisaBaru,
            
            // Logic: Ongkir diinput = Barang Jadi = Tagih Pelunasan
            'status' => 'menunggu_pelunasan' 
        ]);

        session()->flash('message', 'Ongkir berhasil diupdate. Tagihan pelunasan user telah disesuaikan.');
    }

    // --- ACTION 2: ASSIGN TEKNISI (Order Jasa) ---
    public function assignTeknisi()
    {
        $this->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'tgl_pengerjaan' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
        ]);

        // 1. CEK BENTROK (DOUBLE BOOKING PREVENTION)
        // Cek apakah pegawai ini SUDAH punya jadwal di TANGGAL dan JAM yang sama?
        // Asumsi pengerjaan memakan waktu (misal 2-3 jam), tapi untuk simpelnya kita cek jam mulainya.
        $bentrok = Schedule::where('pegawai_id', $this->pegawai_id)
            ->where('tgl_pengerjaan', $this->tgl_pengerjaan)
            ->where('status', '!=', 'selesai') // Abaikan yang sudah selesai
            ->where('status', '!=', 'reschedule') // Abaikan yang batal/reschedule
            // Logic Cek Jam: Jika jam mulai sama persis (Bisa dikembangkan jadi rentang waktu nanti)
            ->where('jam_mulai', $this->jam_mulai) 
            ->exists();

        if ($bentrok) {
            // Lempar error ke user admin
            $this->addError('pegawai_id', 'Teknisi ini sudah ada jadwal lain di Tanggal & Jam tersebut!');
            return;
        }

        // 2. PROSES UPDATE/CREATE JADWAL
        Schedule::updateOrCreate(
            ['pesanan_id' => $this->pesanan->id],
            [
                'pegawai_id' => $this->pegawai_id,
                'tgl_pengerjaan' => $this->tgl_pengerjaan,
                'jam_mulai' => $this->jam_mulai,
                'status' => 'terjadwal'
            ]
        );

        // 3. UPDATE STATUS PEGAWAI (Opsional, tapi bagus buat visual)
        // Pegawai::find($this->pegawai_id)->update(['status_ketersediaan' => 'busy']); 
        // Note: Logic 'busy' ini agak tricky kalau jadwalnya minggu depan, jadi mending biarkan 'available' 
        // tapi difilter lewat query jadwal seperti di bawah.

        // Logic Status Pesanan
        if ($this->pesanan->sisa_tagihan > 0) {
            $this->pesanan->update(['status' => 'menunggu_pelunasan']);
            session()->flash('message', 'Jadwal dibuat. Menunggu pelunasan user.');
        } else {
            $this->pesanan->update(['status' => 'siap_dipasang']); 
            session()->flash('message', 'Jadwal dibuat. Barang siap dipasang.');
        }
    }
    
    // --- ACTION 3: SELESAIKAN ORDER ---
    public function tandaiSelesai()
    {
        // Validasi Resi jika ini order kiriman
        if (!$this->pesanan->butuh_pemasangan) {
            $this->validate([
                'no_resi' => 'required|string|min:3'
            ], [
                'no_resi.required' => 'Nomor Resi wajib diisi sebelum menyelesaikan order kiriman.'
            ]);
            
            // Simpan resi ke DB (Pastikan tabel pesanan punya kolom no_resi)
            $this->pesanan->update([
                'no_resi' => $this->no_resi,
                'status' => 'selesai'
            ]);

        } else {
            // Jika jasa pasang, langsung selesai
            $this->pesanan->update(['status' => 'selesai']);
        }
        
        // Update status schedule juga jika ada
        if ($this->pesanan->schedule) {
            $this->pesanan->schedule->update(['status' => 'selesai']);
        }
        
        session()->flash('message', 'Order berhasil diselesaikan!');
    }

    // --- ACTION 4: MANUAL STATUS (Update Listnya) ---
    public function updateStatusManual()
    {
        // Update daftar status yang boleh dipilih manual
        $this->validate([
            'status_manual' => 'required|in:menunggu_pembayaran,menunggu_verifikasi,produksi,siap_dipasang,siap_dikirim,menunggu_pelunasan,lunas,selesai,batal'
        ]);

        $this->pesanan->update(['status' => $this->status_manual]);
        
        // Sinkronisasi status schedule jika diubah ke selesai
        if ($this->status_manual == 'selesai' && $this->pesanan->schedule) {
            $this->pesanan->schedule->update(['status' => 'selesai']);
        }

        session()->flash('message', 'Status berhasil diubah secara manual.');
    }

    public function render()
    {
        // LOGIC SMART FILTER PEGAWAI
        // Kita hanya mau menampilkan pegawai yang:
        // 1. Status dasarnya 'available' (Gak cuti/sakit)
        // 2. BELUM punya jadwal di tanggal & jam yang dipilih admin (Kalau admin sudah input tanggal)

        $queryPegawai = Pegawai::where('status_ketersediaan', 'available');

        if ($this->tgl_pengerjaan && $this->jam_mulai) {
            $queryPegawai->whereDoesntHave('schedule', function($q) {
                $q->where('tgl_pengerjaan', $this->tgl_pengerjaan)
                  ->where('jam_mulai', $this->jam_mulai)
                  ->whereIn('status', ['terjadwal']); // Status aktif
            });
        }

        return view('livewire.admin.pesanan.show', [
            'pegawais' => $queryPegawai->get()
        ]);
    }
}