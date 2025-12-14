<?php

namespace App\Livewire;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public $filterStatus = 'semua';

    public function setFilter($status)
    {
        $this->filterStatus = $status;
        $this->resetPage();
    }

    // --- FITUR: BATALKAN PESANAN (BEBAS) ---
    public function batalkanPesanan($id)
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$pesanan) {
            session()->flash('error', 'Pesanan tidak ditemukan.');
            return;
        }

        // ATURAN BARU: Boleh batal KECUALI sudah 'selesai' atau memang sudah 'batal'
        if ($pesanan->status !== 'selesai' && $pesanan->status !== 'batal') {
            
            $pesanan->update([
                'status' => 'batal'
            ]);

            // Hapus jadwal teknisi jika ada, biar teknisi gak kecele
            if ($pesanan->schedule) {
                $pesanan->schedule->delete(); 
            }

            session()->flash('message', 'Pesanan berhasil dibatalkan.');
        } else {
            session()->flash('error', 'Pesanan yang sudah selesai tidak dapat dibatalkan.');
        }
    }

    public function render()
    {
        $user = Auth::user();

        $query = Pesanan::with(['detailPesanan.produk'])
                ->where('user_id', $user->id)
                ->latest();

        if ($this->filterStatus == 'belum_bayar') {
            $query->whereIn('status', ['menunggu_pembayaran', 'menunggu_pelunasan']);
        } elseif ($this->filterStatus == 'proses') {
            $query->whereIn('status', ['produksi', 'siap_dipasang', 'siap_dikirim', 'diproses', 'dikirim']); // Tambah 'dikirim' disini
        } elseif ($this->filterStatus == 'selesai') {
            $query->whereIn('status', ['lunas', 'selesai']); // Lunas masuk sini kalau mau rapi, atau masuk proses
        } elseif ($this->filterStatus == 'batal') {
            $query->where('status', 'batal');
        }

        return view('livewire.history', [
            'orders' => $query->paginate(5)
        ]);
    }
}