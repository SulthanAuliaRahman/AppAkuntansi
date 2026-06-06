<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailJurnal extends Model
{
    protected $table = 'detail_jurnal';
    protected $fillable = ['jurnal_id', 'akun_kode', 'type', 'jumlah'];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function jurnal(): BelongsTo
    {
        return $this->belongsTo(Jurnal::class);
    }

    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akuns::class, 'akun_kode', 'kode_akun');
    }
}
