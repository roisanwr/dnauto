<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'nomor_order',
        'user_id',
        'snap_nama_penerima',
        'snap_no_hp',
        'snap_alamat_lengkap',
        'status',
        
        // --- TAMBAHAN BARU ---
        'jenis_pembayaran', // <--- PENTING: User pilih DP/Full
        'jumlah_dp',        // <--- PENTING: Nilai 50%
        'snap_token',       // <--- PENTING: Buat popup Midtrans
        // ---------------------

        'total_belanja',
        'biaya_layanan',
        'grand_total',
        'sisa_tagihan',
        'butuh_pemasangan',
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Detail Item
    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    // Relasi ke Pembayaran
    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    // Relasi ke Schedule
    public function schedule(): HasOne
    {
        return $this->hasOne(Schedule::class);
    }
}