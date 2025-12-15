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
        // STATUS BARU: produksi, menunggu_pelunasan, siap_dipasang, siap_dikirim, selesai
        
        return view('livewire.admin.dashboard', [
            // 1. Hitung Estimasi Omzet (Semua order valid yang tidak batal/belum bayar)
            'total_pendapatan' => Pesanan::whereNotIn('status', ['menunggu_pembayaran', 'menunggu_verifikasi', 'batal'])
                                        ->sum('grand_total'),

            // 2. Hitung Order Aktif (Yang perlu perhatian admin/teknisi)
            'pesanan_baru' => Pesanan::whereIn('status', [
                                    'produksi', 
                                    'menunggu_pelunasan', 
                                    'siap_dipasang', 
                                    'siap_dikirim'
                                ])->count(),

            // 3. Hitung Total Customer
            'total_user' => User::where('role', 'customer')->count(),

            // 4. Hitung Total Produk
            'total_produk' => Produk::count(),

            // 5. Ambil 5 Pesanan Terakhir
            'pesanan_terbaru' => Pesanan::with('user')->latest()->take(5)->get()
        ]);
    }
}