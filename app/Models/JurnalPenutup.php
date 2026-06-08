<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalPenutup extends Model
{
    protected $table = 'jurnal_penutup';

    protected $fillable = ['langkah', 'kode_akun', 'nama_akun', 'posisi', 'jumlah'];
}
