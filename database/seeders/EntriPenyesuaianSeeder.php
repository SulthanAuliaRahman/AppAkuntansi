<?php

namespace Database\Seeders;

use App\Models\EntriPenyesuaian;
use Illuminate\Database\Seeder;

class EntriPenyesuaianSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_akun_debet' => '1.1.3', 'kode_akun_kredit' => '5.1.5', 'jumlah' => 2500000, 'keterangan' => 'Mencatat sisa fisik perlengkapan kantor'],
            ['kode_akun_debet' => '5.1.6', 'kode_akun_kredit' => '1.1.6', 'jumlah' => 2250000, 'keterangan' => 'Penyusutan peralatan kantor 1/36 bulan'],
            ['kode_akun_debet' => '5.1.2', 'kode_akun_kredit' => '1.1.4', 'jumlah' => 125000,  'keterangan' => 'Alokasi sewa ruangan bulan April'],
            ['kode_akun_debet' => '1.1.2', 'kode_akun_kredit' => '4.1.1', 'jumlah' => 1000000, 'keterangan' => 'Piutang pendapatan yang belum ditagih'],
            ['kode_akun_debet' => '2.1.2', 'kode_akun_kredit' => '4.1.2', 'jumlah' => 1000000, 'keterangan' => 'Pengakuan pendapatan iklan diterima dimuka'],
            ['kode_akun_debet' => '5.1.1', 'kode_akun_kredit' => '2.1.3', 'jumlah' => 2342400, 'keterangan' => 'Akrual gaji karyawan bulan April'],
        ];

        EntriPenyesuaian::insert($data);
    }
}
