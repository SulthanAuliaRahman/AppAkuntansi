<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntriPenyesuaian extends Model
{
    protected $table    = 'entri_penyesuaian';
    protected $fillable = ['kode_akun_debet', 'kode_akun_kredit', 'jumlah', 'keterangan'];

    protected $casts = ['jumlah' => 'integer'];

    public function akunDebet()
    {
        return $this->belongsTo(Akun::class, 'kode_akun_debet', 'kode');
    }

    public function akunKredit()
    {
        return $this->belongsTo(Akun::class, 'kode_akun_kredit', 'kode');
    }
}
