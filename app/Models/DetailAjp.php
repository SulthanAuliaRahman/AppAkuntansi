<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAjp extends Model
{
    use HasFactory;

    protected $table = 'detail_ajp';

    protected $fillable = [
        'ajp_id',
        'akun_id',
        'posisi',
        'nominal'
    ];

    // Relasi ke tabel induk AJP
    public function ajp()
    {
        return $this->belongsTo(Ajp::class, 'ajp_id', 'id');
    }

    // Relasi ke tabel Master Akun (Dipanggil di Controller via $d->akun->nama_akun)
    public function akun()
    {
        return $this->belongsTo(Akuns::class, 'akun_id', 'id'); // Sesuaikan jika nama model akunmu adalah 'Akun'
    }
}