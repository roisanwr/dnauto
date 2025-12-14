<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



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

    // 2. INI YANG TADI BIKIN ERROR (Fungsi Relasi ke Produk)
    // Tanpa ini, Laravel gak tau cara ambil nama & gambar produk
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Opsional: Relasi balik ke Pesanan
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}