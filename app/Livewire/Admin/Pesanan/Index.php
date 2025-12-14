<?php

namespace App\Livewire\Admin\Pesanan;

use App\Models\Pesanan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')] // <--- 2. Tambahkan Baris Ini

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        // Ambil data pesanan, urutkan dari yang terbaru
        $pesanan = Pesanan::with('user') // Ambil data user pembeli juga
                    ->latest()
                    ->paginate(10);

        return view('livewire.admin.pesanan.index', [
            'orders' => $pesanan
        ]);
    }
}