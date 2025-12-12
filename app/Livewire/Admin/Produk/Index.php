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
}