<?php

namespace App\Livewire\Admin\Produk;

use App\Models\Produk;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class Edit extends Component
{
    use WithFileUploads;

    public $produkId; // ID Produk yang sedang diedit
    public $nama_produk;
    public $harga;
    public $kategori;
    public $estimasi_hari_kerja;
    public $deskripsi;
    public $gambar; // File baru (jika ada upload)
    public $gambar_lama; // Path gambar lama (buat preview)

    protected $rules = [
        'nama_produk' => 'required|min:3',
        'harga' => 'required|numeric|min:0',
        'kategori' => 'required',
        'estimasi_hari_kerja' => 'required|numeric|min:1',
        'gambar' => 'nullable|image|max:2048', 
        'deskripsi' => 'nullable',
    ];

    // Mengambil data produk saat halaman dibuka
    public function mount($id)
    {
        $produk = Produk::findOrFail($id);

        $this->produkId = $produk->id;
        $this->nama_produk = $produk->nama_produk;
        $this->harga = $produk->harga;
        $this->kategori = $produk->kategori;
        $this->estimasi_hari_kerja = $produk->estimasi_hari_kerja;
        $this->deskripsi = $produk->deskripsi;
        $this->gambar_lama = $produk->gambar;
    }

    public function update()
    {
        $this->validate();

        $produk = Produk::findOrFail($this->produkId);
        $data = [
            'nama_produk' => $this->nama_produk,
            'harga' => $this->harga,
            'kategori' => $this->kategori,
            'estimasi_hari_kerja' => $this->estimasi_hari_kerja,
            'deskripsi' => $this->deskripsi,
        ];

        // Cek apakah ada upload gambar baru?
        if ($this->gambar) {
            // 1. Hapus gambar lama dari storage biar gak numpuk
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }
            
            // 2. Upload gambar baru
            $data['gambar'] = $this->gambar->store('produk', 'public');
        }

        $produk->update($data);

        session()->flash('message', 'Produk berhasil diperbarui!');
        return redirect()->route('admin.produk');
    }

    public function render()
    {
        return view('livewire.admin.produk.edit');
    }
}