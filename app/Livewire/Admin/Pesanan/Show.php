<?php

namespace App\Livewire\Admin\Pesanan;

use App\Models\Pesanan;
use App\Models\Pegawai; // <--- Import Pegawai
use App\Models\Schedule; // <--- Import Schedule
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Show extends Component
{
    public $pesanan;
    public $statusBaru;
    
    // Variabel untuk Jadwal
    public $pegawai_id;
    public $tgl_pengerjaan;
    public $jam_mulai;
    public $status_schedule = 'terjadwal';

    public function mount($id)
    {
        // Load pesanan dengan relasi Schedule juga
        $this->pesanan = Pesanan::with(['detailPesanan.produk', 'user', 'schedule'])->findOrFail($id);
        
        $this->statusBaru = $this->pesanan->status;

        // Jika sudah pernah dijadwalkan, isi form dengan data lama
        if ($this->pesanan->schedule) {
            $this->pegawai_id = $this->pesanan->schedule->pegawai_id;
            $this->tgl_pengerjaan = $this->pesanan->schedule->tgl_pengerjaan;
            $this->jam_mulai = $this->pesanan->schedule->jam_mulai;
            $this->status_schedule = $this->pesanan->schedule->status;
        }
    }

    public function updateStatus()
    {
        $this->pesanan->update(['status' => $this->statusBaru]);
        session()->flash('success', 'Status pesanan berhasil diperbarui!');
    }

    // --- FUNGSI BARU: SIMPAN JADWAL ---
    public function simpanJadwal()
    {
        // Validasi input
        $this->validate([
            'pegawai_id' => 'required',
            'tgl_pengerjaan' => 'required|date',
            'jam_mulai' => 'required',
        ]);

        // Simpan atau Update Jadwal (Pakai updateOrCreate)
        Schedule::updateOrCreate(
            ['pesanan_id' => $this->pesanan->id], // Kunci pencarian
            [
                'pegawai_id' => $this->pegawai_id,
                'tgl_pengerjaan' => $this->tgl_pengerjaan,
                'jam_mulai' => $this->jam_mulai,
                'status' => $this->status_schedule
            ]
        );

        session()->flash('success_jadwal', 'Jadwal teknisi berhasil diatur!');
    }

    public function render()
    {
        // Ambil data semua pegawai untuk dropdown
        return view('livewire.admin.pesanan.show', [
            'list_pegawai' => Pegawai::all()
        ]);
    }
}