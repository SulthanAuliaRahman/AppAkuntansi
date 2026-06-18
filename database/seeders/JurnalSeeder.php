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
                'tanggal' => '2008-04-01',
                'keterangan' => 'Dibayar sewa 1 tahun di depan',
                'details' => [
                    ['akun_kode' => '114', 'type' => 'debet', 'jumlah' => 1500000],
                    ['akun_kode' => '111', 'type' => 'kredit', 'jumlah' => 1500000],
                ]
            ],
            [
                'tanggal' => '2008-04-02',
                'keterangan' => 'Diterima pelunasan piutang pelanggan',
                'details' => [
                    ['akun_kode' => '1.1.1', 'type' => 'debet', 'jumlah' => 8000000],
                    ['akun_kode' => '1.1.2', 'type' => 'kredit', 'jumlah' => 8000000],
                ]
            ],
            [
                'tanggal' => '2008-04-03',
                'keterangan' => 'Dibeli tunai perlengkapan kantor',
                'details' => [
                    ['akun_kode' => '1.1.3', 'type' => 'debet', 'jumlah' => 2500000],
                    ['akun_kode' => '1.1.1', 'type' => 'kredit', 'jumlah' => 2500000],
                ]
            ],
            [
                'tanggal' => '2008-04-10',
                'keterangan' => 'Dikirim tagihan jasa ke customer (Kredit)',
                'details' => [
                    ['akun_kode' => '1.1.2', 'type' => 'debet', 'jumlah' => 8000000],
                    ['akun_kode' => '4.1.1', 'type' => 'kredit', 'jumlah' => 8000000],
                ]
            ],
            [
                'tanggal' => '2008-04-15',
                'keterangan' => 'Dibayar hutang kepada kreditur',
                'details' => [
                    ['akun_kode' => '2.1.1', 'type' => 'debet', 'jumlah' => 8500000],
                    ['akun_kode' => '1.1.1', 'type' => 'kredit', 'jumlah' => 8500000],
                ]
            ],
            [
                'tanggal' => '2008-04-16',
                'keterangan' => 'Dibeli tunai perlengkapan kantor tambahan',
                'details' => [
                    ['akun_kode' => '1.1.3', 'type' => 'debet', 'jumlah' => 500000],
                    ['akun_kode' => '1.1.1', 'type' => 'kredit', 'jumlah' => 500000],
                ]
            ],
            [
                'tanggal' => '2008-04-25',
                'keterangan' => 'Dibayar gaji karyawan bulanan',
                'details' => [
                    ['akun_kode' => '5.1.1', 'type' => 'debet', 'jumlah' => 2000000],
                    ['akun_kode' => '1.1.1', 'type' => 'kredit', 'jumlah' => 2000000],
                ]
            ],
            [
                'tanggal' => '2008-04-26',
                'keterangan' => 'Dibayar beban iklan komersial',
                'details' => [
                    ['akun_kode' => '5.1.3', 'type' => 'debet', 'jumlah' => 2500000],
                    ['akun_kode' => '1.1.1', 'type' => 'kredit', 'jumlah' => 2500000],
                ]
            ],
            [
                'tanggal' => '2008-04-28',
                'keterangan' => 'Diterima tunai pendapatan jasa service',
                'details' => [
                    ['akun_kode' => '1.1.1', 'type' => 'debet', 'jumlah' => 9000000],
                    ['akun_kode' => '4.1.1', 'type' => 'kredit', 'jumlah' => 9000000],
                ]
            ],
            [
                'tanggal' => '2008-04-29',
                'keterangan' => 'Tuan Sakti mengambil prive pribadi',
                'details' => [
                    ['akun_kode' => '3.1.2', 'type' => 'debet', 'jumlah' => 2000000],
                    ['akun_kode' => '1.1.1', 'type' => 'kredit', 'jumlah' => 2000000],
                ]
            ],
            [
                'tanggal' => '2008-04-30',
                'keterangan' => 'Diterima uang iklan properti dimuka (3 bln)',
                'details' => [
                    ['akun_kode' => '1.1.1', 'type' => 'debet', 'jumlah' => 1350000],
                    ['akun_kode' => '2.1.2', 'type' => 'kredit', 'jumlah' => 1350000],
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