<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table      = 'pengaturan';
    protected $primaryKey = 'kunci';
    protected $keyType    = 'string';
    public    $incrementing = false;

    protected $fillable = ['kunci', 'nilai'];

    public static function getValue(string $kunci, string $default = ''): string
    {
        return static::where('kunci', $kunci)->value('nilai') ?? $default;
    }

    public static function setValue(string $kunci, string $nilai): void
    {
        static::updateOrCreate(['kunci' => $kunci], ['nilai' => $nilai]);
    }
}
