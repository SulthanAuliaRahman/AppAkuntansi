<?php

namespace Database\Seeders;

use App\Models\EntriPenyesuaian;
use Illuminate\Database\Seeder;

class EntriPenyesuaianSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_akun_debet' => '113', 'kode_akun_kredit' => '515', 'jumlah' => 2500000, 'keterangan' => 'Mencatat sisa fisik perlengkapan kantor'],
            ['kode_akun_debet' => '516', 'kode_akun_kredit' => '116', 'jumlah' => 2250000, 'keterangan' => 'Penyusutan peralatan kantor 1/36 bulan'],
            ['kode_akun_debet' => '512', 'kode_akun_kredit' => '114', 'jumlah' => 125000,  'keterangan' => 'Alokasi sewa ruangan bulan April'],
            ['kode_akun_debet' => '112', 'kode_akun_kredit' => '411', 'jumlah' => 1000000, 'keterangan' => 'Piutang pendapatan yang belum ditagih'],
            ['kode_akun_debet' => '212', 'kode_akun_kredit' => '412', 'jumlah' => 1000000, 'keterangan' => 'Pengakuan pendapatan iklan diterima dimuka'],
            ['kode_akun_debet' => '511', 'kode_akun_kredit' => '213', 'jumlah' => 2342400, 'keterangan' => 'Akrual gaji karyawan bulan April'],
        ];

        EntriPenyesuaian::insert($data);
    }
}
