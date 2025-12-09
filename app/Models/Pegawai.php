<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    protected $table = 'pegawai'; // Override nama tabel

    protected $fillable = [
        'nama_pegawai',
        'jabatan',
        'kontak',
        'status_ketersediaan',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}