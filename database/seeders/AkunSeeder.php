<?php

namespace Database\Seeders;

use App\Models\Akuns;
use App\Models\JenisAkun;
use Illuminate\Database\Seeder;

class AkunSeeder extends Seeder
{
    public function run(): void
    {
        $jenis = [
            '1.1' => JenisAkun::where('kode', '1.1')->first(),
            '1.2' => JenisAkun::where('kode', '1.2')->first(),
            '2.1' => JenisAkun::where('kode', '2.1')->first(),
            '3.1' => JenisAkun::where('kode', '3.1')->first(),
            '4.1' => JenisAkun::where('kode', '4.1')->first(),
            '5.1' => JenisAkun::where('kode', '5.1')->first(),
        ];

        $data = [
            ['jenis_akun_id' => $jenis['1.1']?->id, 'kode_akun' => '111', 'nama_akun' => 'Kas', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['1.1']?->id, 'kode_akun' => '112', 'nama_akun' => 'Piutang Usaha', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['1.1']?->id, 'kode_akun' => '113', 'nama_akun' => 'Perlengkapan Kantor', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['1.1']?->id, 'kode_akun' => '114', 'nama_akun' => 'Biaya Dibayar Dimuka', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['1.1']?->id, 'kode_akun' => '115', 'nama_akun' => 'Persediaan', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['1.1']?->id, 'kode_akun' => '116', 'nama_akun' => 'Piutang Lain-lain', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['2.1']?->id, 'kode_akun' => '211', 'nama_akun' => 'Utang Usaha', 'saldo_normal' => 'KREDIT'],
            ['jenis_akun_id' => $jenis['2.1']?->id, 'kode_akun' => '212', 'nama_akun' => 'Pendapatan Diterima Dimuka', 'saldo_normal' => 'KREDIT'],
            ['jenis_akun_id' => $jenis['2.1']?->id, 'kode_akun' => '213', 'nama_akun' => 'Hutang Lain-lain', 'saldo_normal' => 'KREDIT'],
            ['jenis_akun_id' => $jenis['3.1']?->id, 'kode_akun' => '311', 'nama_akun' => 'Modal Tuan Sakti', 'saldo_normal' => 'KREDIT'],
            ['jenis_akun_id' => $jenis['3.1']?->id, 'kode_akun' => '312', 'nama_akun' => 'Prive / Penarikan Pemilik', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['4.1']?->id, 'kode_akun' => '411', 'nama_akun' => 'Pendapatan Jasa', 'saldo_normal' => 'KREDIT'],
            ['jenis_akun_id' => $jenis['4.1']?->id, 'kode_akun' => '412', 'nama_akun' => 'Pendapatan Lainnya', 'saldo_normal' => 'KREDIT'],
            ['jenis_akun_id' => $jenis['5.1']?->id, 'kode_akun' => '511', 'nama_akun' => 'Beban Gaji', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['5.1']?->id, 'kode_akun' => '512', 'nama_akun' => 'Beban Sewa', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['5.1']?->id, 'kode_akun' => '513', 'nama_akun' => 'Beban Iklan', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['5.1']?->id, 'kode_akun' => '514', 'nama_akun' => 'Beban Listrik', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['5.1']?->id, 'kode_akun' => '515', 'nama_akun' => 'Beban Telepon', 'saldo_normal' => 'DEBET'],
            ['jenis_akun_id' => $jenis['5.1']?->id, 'kode_akun' => '516', 'nama_akun' => 'Beban Lain-lain', 'saldo_normal' => 'DEBET'],
        ];

        foreach ($data as $item) {
            if (!$item['jenis_akun_id']) continue;
            Akuns::updateOrCreate(['kode_akun' => $item['kode_akun']], $item);
        }
    }
}