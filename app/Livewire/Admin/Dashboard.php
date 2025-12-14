<?php

namespace App\Livewire\Admin;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            // 1. Hitung Total Uang (Hanya yang statusnya 'lunas' atau 'selesai')
            'total_pendapatan' => Pesanan::whereIn('status', ['lunas', 'selesai', 'sedang_dikerjakan'])->sum('grand_total'),

            // 2. Hitung Jumlah Pesanan Baru (Perlu diproses)
            'pesanan_baru' => Pesanan::where('status', 'menunggu_pembayaran')->orWhere('status', 'lunas')->count(),

            // 3. Hitung Total Customer
            // Ganti 'usertype' jadi 'role', dan 'user' jadi 'customer'
            'total_user' => User::where('role', 'customer')->count(),

            // 4. Hitung Total Produk
            'total_produk' => Produk::count(),

            // 5. Ambil 5 Pesanan Terakhir buat tabel mini
            'pesanan_terbaru' => Pesanan::with('user')->latest()->take(5)->get()
        ]);
    }
}