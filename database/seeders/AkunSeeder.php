<?php

namespace Database\Seeders;

use App\Models\Akun;
use Illuminate\Database\Seeder;

class AkunSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => '111', 'nama' => 'Kas',                              'tipe' => 'asset',     'normal' => 'debit'],
            ['kode' => '112', 'nama' => 'Piutang Dagang',                   'tipe' => 'asset',     'normal' => 'debit'],
            ['kode' => '113', 'nama' => 'Perlengkapan Kantor',              'tipe' => 'asset',     'normal' => 'debit'],
            ['kode' => '114', 'nama' => 'Sewa Dibayar Dimuka',              'tipe' => 'asset',     'normal' => 'debit'],
            ['kode' => '115', 'nama' => 'Peralatan Kantor',                 'tipe' => 'asset',     'normal' => 'debit'],
            ['kode' => '116', 'nama' => 'Akumulasi Penyusutan Peralatan',   'tipe' => 'asset',     'normal' => 'credit'],
            ['kode' => '211', 'nama' => 'Hutang Dagang',                    'tipe' => 'liability', 'normal' => 'credit'],
            ['kode' => '212', 'nama' => 'Iklan Diterima Dimuka',            'tipe' => 'liability', 'normal' => 'credit'],
            ['kode' => '213', 'nama' => 'Hutang Gaji',                      'tipe' => 'liability', 'normal' => 'credit'],
            ['kode' => '311', 'nama' => 'Modal Tuan Sakti',                 'tipe' => 'equity',    'normal' => 'credit'],
            ['kode' => '312', 'nama' => 'Prive Tuan Sakti',                 'tipe' => 'equity',    'normal' => 'debit'],
            ['kode' => '411', 'nama' => 'Pendapatan Jasa',                  'tipe' => 'revenue',   'normal' => 'credit'],
            ['kode' => '412', 'nama' => 'Pendapatan Iklan',                 'tipe' => 'revenue',   'normal' => 'credit'],
            ['kode' => '511', 'nama' => 'Beban Gaji',                       'tipe' => 'expense',   'normal' => 'debit'],
            ['kode' => '512', 'nama' => 'Beban Sewa',                       'tipe' => 'expense',   'normal' => 'debit'],
            ['kode' => '513', 'nama' => 'Beban Iklan',                      'tipe' => 'expense',   'normal' => 'debit'],
            ['kode' => '514', 'nama' => 'Beban Asuransi',                   'tipe' => 'expense',   'normal' => 'debit'],
            ['kode' => '515', 'nama' => 'Beban Perlengkapan',               'tipe' => 'expense',   'normal' => 'debit'],
            ['kode' => '516', 'nama' => 'Beban Penyusutan Peralatan',       'tipe' => 'expense',   'normal' => 'debit'],
        ];

        foreach ($data as $row) {
            Akun::create($row);
        }
    }
}
