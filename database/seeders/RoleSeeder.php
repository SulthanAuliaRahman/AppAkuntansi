<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'nama_role' => 'Administrator',
            'deskripsi' => 'Full access to all features',
            'is_full_access' => true,
        ]);

        Role::create([
            'nama_role' => 'User',
            'deskripsi' => 'Regular user access',
            'is_full_access' => false,
        ]);
    }
}
