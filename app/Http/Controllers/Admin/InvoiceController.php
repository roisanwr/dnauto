<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function print($id)
    {
        // Ambil data pesanan lengkap
        $pesanan = Pesanan::with(['detailPesanan.produk', 'user', 'pembayaran'])->findOrFail($id);

        return view('admin.invoice', compact('pesanan'));
    }
}