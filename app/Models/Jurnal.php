<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $table    = 'jurnal';
    protected $fillable = ['tanggal', 'keterangan', 'akun_debet', 'akun_kredit', 'jumlah', 'is_static'];

    protected $casts = [
        'is_static' => 'boolean',
        'jumlah'    => 'integer',
    ];

    public function akunDebet()
    {
        return $this->belongsTo(Akun::class, 'akun_debet', 'kode');
    }

    public function akunKredit()
    {
        return $this->belongsTo(Akun::class, 'akun_kredit', 'kode');
    }
}
