<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisAkun extends Model
{
    protected $table = 'jenis_akun'; // Sesuai nama tabel di migration Anda
    protected $fillable = ['kode', 'nama'];

    public function akuns(): HasMany
    {
        return $this->hasMany(Akuns::class, 'jenis_akun_id');
    }
}
