<?php

namespace Database\Seeders;

use App\Models\SaldoAwal;
use Illuminate\Database\Seeder;

class SaldoAwalSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_akun' => '111', 'debet' => 120000000, 'kredit' => 0],
            ['kode_akun' => '112', 'debet' => 11500000,  'kredit' => 0],
            ['kode_akun' => '113', 'debet' => 0,         'kredit' => 0],
            ['kode_akun' => '114', 'debet' => 0,         'kredit' => 0],
            ['kode_akun' => '115', 'debet' => 81000000,  'kredit' => 0],
            ['kode_akun' => '116', 'debet' => 0,         'kredit' => 0],
            ['kode_akun' => '211', 'debet' => 0,         'kredit' => 12000000],
            ['kode_akun' => '212', 'debet' => 0,         'kredit' => 1000000],
            ['kode_akun' => '213', 'debet' => 0,         'kredit' => 0],
            ['kode_akun' => '311', 'debet' => 0,         'kredit' => 155000000],
            ['kode_akun' => '312', 'debet' => 9000000,   'kredit' => 0],
            ['kode_akun' => '411', 'debet' => 0,         'kredit' => 95000000],
            ['kode_akun' => '412', 'debet' => 0,         'kredit' => 0],
            ['kode_akun' => '511', 'debet' => 20000000,  'kredit' => 0],
            ['kode_akun' => '512', 'debet' => 4000000,   'kredit' => 0],
            ['kode_akun' => '513', 'debet' => 4700000,   'kredit' => 0],
            ['kode_akun' => '514', 'debet' => 1800000,   'kredit' => 0],
            ['kode_akun' => '515', 'debet' => 11000000,  'kredit' => 0],
            ['kode_akun' => '516', 'debet' => 0,         'kredit' => 0],
        ];

        SaldoAwal::insert($data);
    }
}
