<?php

namespace App\Livewire;

use App\Models\Produk;
use Livewire\Component;

class Home extends Component
{
    // Konfigurasi Infinite Scroll
    public $amount = 8; // Awal muncul 8 produk
    public $totalProduk; // Total semua produk di database
    
    // Filter & Sorting
    public $kategori = ''; // Filter kategori aktif
    public $urutan = 'terbaru'; // Opsi: terbaru, termurah, termahal

    public function mount()
    {
        // Hitung total produk biar kita tau kapan harus berhenti loading
        $this->totalProduk = Produk::count();
    }

    // Fungsi ini dipanggil otomatis saat user scroll ke bawah
    public function loadMore()
    {
        $this->amount += 8; // Tambah 8 produk lagi
    }

    // Fungsi ganti kategori
    public function setKategori($namaKategori)
    {
        $this->kategori = $namaKategori;
        $this->amount = 8; // Reset scroll ke atas
    }

    public function render()
    {
        // Query Dasar
        $query = Produk::query();

        // 1. Filter Kategori (Jika ada yang dipilih)
        if ($this->kategori) {
            $query->where('kategori', $this->kategori);
        }

        // 2. Sorting
        switch ($this->urutan) {
            case 'termurah':
                $query->orderBy('harga', 'asc');
                break;
            case 'termahal':
                $query->orderBy('harga', 'desc');
                break;
            default: // Terbaru
                $query->latest();
                break;
        }

        // Ambil data sesuai jumlah scroll saat ini
        $produks = $query->take($this->amount)->get();

        return view('livewire.home', [
            'produks' => $produks
        ]);
    }
}