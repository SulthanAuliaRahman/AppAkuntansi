<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            JenisAkunSeeder::class,
            // AkunSeeder::class,
            UserSeeder::class,
            // SaldoAwalSeeder::class,
            // JurnalSeeder::class,
            // PengaturanSeeder::class,
        ]);
    }
}
