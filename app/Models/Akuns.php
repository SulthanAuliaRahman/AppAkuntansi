<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Akuns extends Model
{
    protected $table = 'akuns';
    protected $fillable = ['jenis_akun_id', 'kode_akun', 'nama_akun', 'saldo_normal', 'aktif'];

    public function jenisAkun(): BelongsTo
    {
        return $this->belongsTo(JenisAkun::class, 'jenis_akun_id');
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'roles_akses_akun',
            'akuns_id',
            'role_id'
        );
    }
}
