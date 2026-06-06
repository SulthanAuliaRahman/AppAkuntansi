<?php

namespace Database\Seeders;

use App\Models\Jurnal;
use App\Models\DetailJurnal;
use Illuminate\Database\Seeder;

class JurnalSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'tanggal' => '01 Apr',
                'keterangan' => 'Dibayar sewa 1 tahun di depan',
                'details' => [
                    ['akun_kode' => '114', 'type' => 'debet', 'jumlah' => 1500000],
                    ['akun_kode' => '111', 'type' => 'kredit', 'jumlah' => 1500000],
                ]
            ],
            [
                'tanggal' => '02 Apr',
                'keterangan' => 'Diterima pelunasan piutang pelanggan',
                'details' => [
                    ['akun_kode' => '111', 'type' => 'debet', 'jumlah' => 8000000],
                    ['akun_kode' => '112', 'type' => 'kredit', 'jumlah' => 8000000],
                ]
            ],
            [
                'tanggal' => '03 Apr',
                'keterangan' => 'Dibeli tunai perlengkapan kantor',
                'details' => [
                    ['akun_kode' => '113', 'type' => 'debet', 'jumlah' => 2500000],
                    ['akun_kode' => '111', 'type' => 'kredit', 'jumlah' => 2500000],
                ]
            ],
            [
                'tanggal' => '10 Apr',
                'keterangan' => 'Dikirim tagihan jasa ke customer (Kredit)',
                'details' => [
                    ['akun_kode' => '112', 'type' => 'debet', 'jumlah' => 8000000],
                    ['akun_kode' => '411', 'type' => 'kredit', 'jumlah' => 8000000],
                ]
            ],
            [
                'tanggal' => '15 Apr',
                'keterangan' => 'Dibayar hutang kepada kreditur',
                'details' => [
                    ['akun_kode' => '211', 'type' => 'debet', 'jumlah' => 8500000],
                    ['akun_kode' => '111', 'type' => 'kredit', 'jumlah' => 8500000],
                ]
            ],
            [
                'tanggal' => '16 Apr',
                'keterangan' => 'Dibeli tunai perlengkapan kantor tambahan',
                'details' => [
                    ['akun_kode' => '113', 'type' => 'debet', 'jumlah' => 500000],
                    ['akun_kode' => '111', 'type' => 'kredit', 'jumlah' => 500000],
                ]
            ],
            [
                'tanggal' => '25 Apr',
                'keterangan' => 'Dibayar gaji karyawan bulanan',
                'details' => [
                    ['akun_kode' => '511', 'type' => 'debet', 'jumlah' => 2000000],
                    ['akun_kode' => '111', 'type' => 'kredit', 'jumlah' => 2000000],
                ]
            ],
            [
                'tanggal' => '26 Apr',
                'keterangan' => 'Dibayar beban iklan komersial',
                'details' => [
                    ['akun_kode' => '513', 'type' => 'debet', 'jumlah' => 2500000],
                    ['akun_kode' => '111', 'type' => 'kredit', 'jumlah' => 2500000],
                ]
            ],
            [
                'tanggal' => '28 Apr',
                'keterangan' => 'Diterima tunai pendapatan jasa service',
                'details' => [
                    ['akun_kode' => '111', 'type' => 'debet', 'jumlah' => 9000000],
                    ['akun_kode' => '411', 'type' => 'kredit', 'jumlah' => 9000000],
                ]
            ],
            [
                'tanggal' => '29 Apr',
                'keterangan' => 'Tuan Sakti mengambil prive pribadi',
                'details' => [
                    ['akun_kode' => '312', 'type' => 'debet', 'jumlah' => 2000000],
                    ['akun_kode' => '111', 'type' => 'kredit', 'jumlah' => 2000000],
                ]
            ],
            [
                'tanggal' => '30 Apr',
                'keterangan' => 'Diterima uang iklan properti dimuka (3 bln)',
                'details' => [
                    ['akun_kode' => '111', 'type' => 'debet', 'jumlah' => 1350000],
                    ['akun_kode' => '212', 'type' => 'kredit', 'jumlah' => 1350000],
                ]
            ],
        ];

        foreach ($data as $journalData) {
            $jurnal = Jurnal::create([
                'tanggal' => $journalData['tanggal'],
                'keterangan' => $journalData['keterangan'],
                'is_static' => false,
            ]);

            foreach ($journalData['details'] as $detail) {
                DetailJurnal::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_kode' => $detail['akun_kode'],
                    'type' => $detail['type'],
                    'jumlah' => $detail['jumlah'],
                ]);
            }
        }
    }
}