<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoAwal extends Model
{
    protected $table    = 'saldo_awal';
    protected $fillable = ['kode_akun', 'debet', 'kredit'];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'kode_akun', 'kode');
    }
}
