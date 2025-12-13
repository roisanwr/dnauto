<?php

namespace App\Livewire;

use App\Models\Produk;
use Livewire\Component;

class ProdukDetail extends Component
{
    public $produk;

    public function mount($id)
    {
        // Cari produk berdasarkan ID, kalau gak ada tampilkan 404
        $this->produk = Produk::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.produk-detail');
    }
}