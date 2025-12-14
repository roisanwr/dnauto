<?php

namespace App\Livewire;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public $filterStatus = 'semua'; // Filter tab: semua, belum_bayar, proses, selesai

    public function setFilter($status)
    {
        $this->filterStatus = $status;
        $this->resetPage(); // Reset pagination kalau ganti filter
    }

    public function render()
    {
        $user = Auth::user();

        // Query Dasar
        $query = Pesanan::with(['detailPesanan.produk']) // Eager load produk
                ->where('user_id', $user->id)
                ->latest(); // Urutkan terbaru

        // Logic Filter Tab
        if ($this->filterStatus == 'belum_bayar') {
            // Termasuk yang menunggu DP atau Menunggu Pelunasan
            $query->whereIn('status', ['menunggu_pembayaran', 'menunggu_pelunasan']);
        
        } elseif ($this->filterStatus == 'proses') {
            // Sedang produksi atau siap dieksekusi
            $query->whereIn('status', ['produksi', 'siap_dipasang', 'siap_dikirim', 'diproses']);
        
        } elseif ($this->filterStatus == 'selesai') {
            $query->whereIn('status', ['lunas', 'selesai', 'dikirim']);
        
        } elseif ($this->filterStatus == 'batal') {
            $query->where('status', 'batal');
        }

        return view('livewire.history', [
            'orders' => $query->paginate(5)
        ]);
    }
}