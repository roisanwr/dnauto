<?php

namespace App\Livewire;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class History extends Component
{
    public function render()
    {
        // Ambil pesanan punya user yang login aja
        $pesanan = Pesanan::where('user_id', Auth::id())
                            ->latest() // Urutkan dari yang terbaru
                            ->get();

        return view('livewire.history', [
            'orders' => $pesanan
        ]);
    }
}