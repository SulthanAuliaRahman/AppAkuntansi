<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table      = 'akun';
    protected $primaryKey = 'kode';
    protected $keyType    = 'string';
    public    $incrementing = false;

    protected $fillable = ['kode', 'nama', 'tipe', 'normal'];

    public function saldoAwal()
    {
        return $this->hasOne(SaldoAwal::class, 'kode_akun', 'kode');
    }

    public function jurnalDebet()
    {
        return $this->hasMany(Jurnal::class, 'akun_debet', 'kode');
    }

    public function jurnalKredit()
    {
        return $this->hasMany(Jurnal::class, 'akun_kredit', 'kode');
    }
}
