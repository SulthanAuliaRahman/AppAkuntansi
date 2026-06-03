<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AkunSeeder::class,
            SaldoAwalSeeder::class,
            JurnalSeeder::class,
            EntriPenyesuaianSeeder::class,
            PengaturanSeeder::class,
        ]);
    }
}
