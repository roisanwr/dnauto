<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // Override nama tabel karena migrasi menggunakan 'produk' (singular)
    protected $table = 'produk'; 

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'harga_jasa', // <--- TAMBAHKAN INI
        'kategori',
        'estimasi_hari_kerja',
        'gambar',
    ];
}