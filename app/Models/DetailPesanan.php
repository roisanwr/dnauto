<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    // INI KUNCINYA:
    // Kita kasih tahu Laravel: "Eh, tabel buat model ini namanya 't_pesanan_produk' ya!"
    protected $table = 't_pesanan_produk'; 

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'jumlah',
        'harga_saat_beli',
        'subtotal',
        'catatan_custom',
    ];

    // ... relasi ke Produk dan Pesanan tetap sama ...
}