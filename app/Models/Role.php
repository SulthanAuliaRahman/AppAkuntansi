<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Akuns;

class Role extends Model
{
    protected $fillable = ['nama_role', 'deskripsi', 'is_full_access'];

    protected $casts = [
        'is_full_access' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function akunAkses()
    {
        // Ubah Akun::class menjadi Akuns::class
        return $this->belongsToMany(
            Akuns::class,
            'roles_akses_akun',
            'role_id',
            'akuns_id'
        );
    }
}
