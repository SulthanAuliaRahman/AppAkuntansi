<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurnal extends Model
{
    protected $table    = 'jurnal';
    protected $fillable = ['tanggal', 'keterangan', 'is_static'];

    protected $casts = [
        'is_static' => 'boolean',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(DetailJurnal::class);
    }
}
