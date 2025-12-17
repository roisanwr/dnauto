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

        // 1. LOGIC CEK BENTROK (SAMA DENGAN RENDER)
        // Estimasi pengerjaan 2 jam
        $startInput = \Carbon\Carbon::parse($this->jam_mulai);
        $endInput   = $startInput->copy()->addHours(2);

        $bentrok = Schedule::where('pegawai_id', $this->pegawai_id)
            ->where('tgl_pengerjaan', $this->tgl_pengerjaan)
            ->where('status', '!=', 'selesai') 
            ->where('status', '!=', 'reschedule')
            ->get()
            ->filter(function ($jadwal) use ($startInput, $endInput) {
                $startDb = \Carbon\Carbon::parse($jadwal->jam_mulai);
                $endDb   = $startDb->copy()->addHours(2);

                // Cek Overlap: (StartA < EndB) && (EndA > StartB)
                return $startInput->lt($endDb) && $endInput->gt($startDb);
            });

        if ($bentrok->isNotEmpty()) {
            $this->addError('pegawai_id', 'Teknisi ini sibuk di jam tersebut (estimasi durasi 2 jam). Pilih jam lain.');
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
        // 2. BELUM punya jadwal yang BERIRISAN/BENTROK dengan jam yang dipilih

        $queryPegawai = Pegawai::where('status_ketersediaan', 'available');

        if ($this->tgl_pengerjaan && $this->jam_mulai) {
            
            // Asumsi durasi pengerjaan standar = 2 jam (bisa disesuaikan)
            $startInput = \Carbon\Carbon::parse($this->jam_mulai);
            $endInput   = $startInput->copy()->addHours(2);

            // Gunakan 'schedules' (pakai s) sesuai nama fungsi di Model Pegawai
            $queryPegawai->whereDoesntHave('schedules', function($q) use ($startInput, $endInput) {
                $q->where('tgl_pengerjaan', $this->tgl_pengerjaan)
                ->whereIn('status', ['terjadwal']) // Hanya cek yang statusnya aktif
                ->where(function($subQ) use ($startInput, $endInput) {
                    // LOGIC CEK BENTROK JAM (Overlap)
                    // Jadwal database (Start DB)
                    // Kita anggap durasi job lama juga 2 jam (End DB)
                    
                    // Rumus Overlap: (StartA < EndB) && (EndA > StartB)
                    // Karena kita pakai SQL Raw/Logic query builder agak ribet menghitung end_time dinamis,
                    // Kita pakai pendekatan sederhana: Jam mulai tidak boleh sama persis ATAU berdekatan
                    
                    // Opsi Paling Aman & Simpel di Query Builder:
                    // Cek apakah ada jadwal di jam yang sama persis
                    // ATAU 1 jam sebelum/sesudah jam input.
                    
                    $subQ->where('jam_mulai', $this->jam_mulai) // Sama persis
                        ->orWhereBetween('jam_mulai', [
                            $startInput->copy()->subHours(2)->format('H:i'),
                            $endInput->format('H:i')
                        ]);
                });
            });
        }

        return view('livewire.admin.pesanan.show', [
            'pegawais' => $queryPegawai->get()
        ]);
    }
}