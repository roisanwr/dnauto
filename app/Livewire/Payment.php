<?php

namespace App\Livewire;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Payment extends Component
{
    public $pesanan;

    public function mount($id)
    {
        // 1. Ambil Data Pesanan
        $this->pesanan = Pesanan::findOrFail($id);

        // 2. Security Check: Pastikan yang buka adalah pemilik pesanan
        if ($this->pesanan->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        // 3. Kalau sudah lunas, lempar balik ke dashboard/home
        if ($this->pesanan->status === 'lunas') {
            return redirect()->route('home');
        }
    }

    public function render()
    {
        return view('livewire.payment');
    }
}