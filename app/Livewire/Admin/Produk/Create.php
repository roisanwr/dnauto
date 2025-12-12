<?php

namespace App\Livewire\Admin\Produk;

use App\Models\Produk;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads; // <--- Wajib buat upload

#[Layout('components.layouts.admin')]
class Create extends Component
{
    use WithFileUploads;

    // Variabel Form
    public $nama_produk;
    public $harga;
    public $kategori;
    public $estimasi_hari_kerja = 2; // Default 2 hari
    public $deskripsi;
    public $gambar; // File gambar sementara

    // Aturan Validasi
    protected $rules = [
        'nama_produk' => 'required|min:3',
        'harga' => 'required|numeric|min:0',
        'kategori' => 'required',
        'estimasi_hari_kerja' => 'required|numeric|min:1',
        'gambar' => 'nullable|image|max:2048', // Max 2MB
        'deskripsi' => 'nullable',
    ];

    public function store()
    {
        $this->validate();

        // 1. Upload Gambar (Jika ada)
        $pathGambar = null;
        if ($this->gambar) {
            // Simpan ke folder: storage/app/public/produk
            $pathGambar = $this->gambar->store('produk', 'public');
        }

        // 2. Simpan ke Database
        Produk::create([
            'nama_produk' => $this->nama_produk,
            'harga' => $this->harga,
            'kategori' => $this->kategori,
            'estimasi_hari_kerja' => $this->estimasi_hari_kerja,
            'deskripsi' => $this->deskripsi,
            'gambar' => $pathGambar, // Simpan path-nya
        ]);

        // 3. Balik ke Daftar Produk
        session()->flash('message', 'Produk berhasil ditambahkan!');
        return redirect()->route('admin.produk');
    }

    public function render()
    {
        return view('livewire.admin.produk.create');
    }
}