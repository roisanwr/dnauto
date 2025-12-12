<?php

namespace App\Livewire\Admin\Produk;

use App\Models\Produk;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    // Fitur pencarian sederhana
    public $search = '';

    public function render()
    {
        $produks = Produk::query()
            ->where('nama_produk', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.produk.index', [
            'produks' => $produks
        ]);
    }

    // FUNGSI BARU: DELETE
    public function delete($id)
    {
        $produk = Produk::find($id);

        if ($produk) {
            // Hapus gambar dari storage dulu (biar bersih)
            if ($produk->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($produk->gambar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($produk->gambar);
            }

            $produk->delete();
            session()->flash('message', 'Produk berhasil dihapus!');
        }
    }
}