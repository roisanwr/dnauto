<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    // Pastikan nama tabel benar (singular sesuai migrasi kamu)
    protected $table = 'schedule'; 

    protected $fillable = [
        'pesanan_id',
        'pegawai_id',
        'tgl_pengerjaan',
        'jam_mulai',
        'status', // terjadwal, selesai, reschedule
    ];

    // Relasi ke Pesanan
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    // Relasi ke Pegawai (Teknisi)
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}