<?php

namespace Database\Seeders;

use App\Models\Jurnal;
use Illuminate\Database\Seeder;

class JurnalSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['tanggal' => '01 Apr', 'keterangan' => 'Dibayar sewa 1 tahun di depan',              'akun_debet' => '114', 'akun_kredit' => '111', 'jumlah' => 1500000,  'is_static' => true],
            ['tanggal' => '02 Apr', 'keterangan' => 'Diterima pelunasan piutang pelanggan',        'akun_debet' => '111', 'akun_kredit' => '112', 'jumlah' => 8000000,  'is_static' => true],
            ['tanggal' => '03 Apr', 'keterangan' => 'Dibeli tunai perlengkapan kantor',            'akun_debet' => '113', 'akun_kredit' => '111', 'jumlah' => 2500000,  'is_static' => true],
            ['tanggal' => '10 Apr', 'keterangan' => 'Dikirim tagihan jasa ke customer (Kredit)',   'akun_debet' => '112', 'akun_kredit' => '411', 'jumlah' => 8000000,  'is_static' => true],
            ['tanggal' => '15 Apr', 'keterangan' => 'Dibayar hutang kepada kreditur',              'akun_debet' => '211', 'akun_kredit' => '111', 'jumlah' => 8500000,  'is_static' => true],
            ['tanggal' => '16 Apr', 'keterangan' => 'Dibeli tunai perlengkapan kantor tambahan',   'akun_debet' => '113', 'akun_kredit' => '111', 'jumlah' => 500000,   'is_static' => true],
            ['tanggal' => '25 Apr', 'keterangan' => 'Dibayar gaji karyawan bulanan',               'akun_debet' => '511', 'akun_kredit' => '111', 'jumlah' => 2000000,  'is_static' => true],
            ['tanggal' => '26 Apr', 'keterangan' => 'Dibayar beban iklan komersial',               'akun_debet' => '513', 'akun_kredit' => '111', 'jumlah' => 2500000,  'is_static' => true],
            ['tanggal' => '28 Apr', 'keterangan' => 'Diterima tunai pendapatan jasa service',      'akun_debet' => '111', 'akun_kredit' => '411', 'jumlah' => 9000000,  'is_static' => true],
            ['tanggal' => '29 Apr', 'keterangan' => 'Tuan Sakti mengambil prive pribadi',          'akun_debet' => '312', 'akun_kredit' => '111', 'jumlah' => 2000000,  'is_static' => true],
            ['tanggal' => '30 Apr', 'keterangan' => 'Diterima uang iklan properti dimuka (3 bln)', 'akun_debet' => '111', 'akun_kredit' => '212', 'jumlah' => 1350000,  'is_static' => true],
        ];

        Jurnal::insert($data);
    }
}
