<?php

namespace Database\Seeders;

use App\Models\SaldoAwal;
use Illuminate\Database\Seeder;

class SaldoAwalSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_akun' => '1.1.1', 'debet' => 120000000, 'kredit' => 0],
            ['kode_akun' => '1.1.2', 'debet' => 11500000,  'kredit' => 0],
            ['kode_akun' => '1.1.3', 'debet' => 0,         'kredit' => 0],
            ['kode_akun' => '1.1.4', 'debet' => 0,         'kredit' => 0],
            ['kode_akun' => '1.1.5', 'debet' => 81000000,  'kredit' => 0],
            ['kode_akun' => '1.1.6', 'debet' => 0,         'kredit' => 0],
            ['kode_akun' => '2.1.1', 'debet' => 0,         'kredit' => 12000000],
            ['kode_akun' => '2.1.2', 'debet' => 0,         'kredit' => 1000000],
            ['kode_akun' => '2.1.3', 'debet' => 0,         'kredit' => 0],
            ['kode_akun' => '3.1.1', 'debet' => 0,         'kredit' => 155000000],
            ['kode_akun' => '3.1.2', 'debet' => 9000000,   'kredit' => 0],
            ['kode_akun' => '4.1.1', 'debet' => 0,         'kredit' => 95000000],
            ['kode_akun' => '4.1.2', 'debet' => 0,         'kredit' => 0],
            ['kode_akun' => '5.1.1', 'debet' => 20000000,  'kredit' => 0],
            ['kode_akun' => '5.1.2', 'debet' => 4000000,   'kredit' => 0],
            ['kode_akun' => '5.1.3', 'debet' => 4700000,   'kredit' => 0],
            ['kode_akun' => '5.1.4', 'debet' => 1800000,   'kredit' => 0],
            ['kode_akun' => '5.1.5', 'debet' => 11000000,  'kredit' => 0],
            ['kode_akun' => '5.1.6', 'debet' => 0,         'kredit' => 0],
        ];

        SaldoAwal::insert($data);
    }
}
