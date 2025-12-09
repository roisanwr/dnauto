<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alamat extends Model
{
    protected $table = 'alamat';

    protected $fillable = [
        'user_id',
        'label_alamat',
        'nama_penerima',
        'no_hp_penerima',
        'alamat_lengkap',
        'kota',
        'is_primary',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}