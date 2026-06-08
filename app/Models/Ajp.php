<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajp extends Model
{
    use HasFactory;

    // Menentukan nama tabel murni di MySQL
    protected $table = 'ajp';

    protected $fillable = [
        'tanggal',
        'jenis_transaksi',
        'keterangan'
    ];

    // Relasi One-to-Many: Satu induk AJP memiliki banyak baris detail (Debet & Kredit)
    public function details()
    {
        return $this->hasMany(DetailAjp::class, 'ajp_id', 'id');
    }
}