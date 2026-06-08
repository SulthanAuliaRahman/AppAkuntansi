<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            JenisAkunSeeder::class,
            AkunSeeder::class,
            SaldoAwalSeeder::class,
            JurnalSeeder::class,
            PengaturanSeeder::class,
        ]);
    }
}
