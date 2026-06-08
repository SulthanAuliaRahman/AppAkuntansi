<?php

namespace Database\Seeders;

use App\Models\JenisAkun;
use Illuminate\Database\Seeder;

class JenisAkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kode' => '1.1', 'nama' => 'Asset Lancar'],
            ['kode' => '1.2', 'nama' => 'Asset Tetap'],
            ['kode' => '1.3', 'nama' => 'Asset Tidak Berwujud'],
            ['kode' => '2.1', 'nama' => 'Liabilitas Jangka Pendek'],
            ['kode' => '2.2', 'nama' => 'Hutang Jangka Panjang'],
            ['kode' => '3.1', 'nama' => 'Modal'],
            ['kode' => '4.1', 'nama' => 'Pendapatan'],
            ['kode' => '5.1', 'nama' => 'Beban'],
        ];

        foreach ($data as $item) {
            // Menggunakan updateOrCreate agar aman jika seeder dijalankan berkali-kali
            JenisAkun::updateOrCreate(
                ['kode' => $item['kode']],
                ['nama' => $item['nama']]
            );
        }
    }
}