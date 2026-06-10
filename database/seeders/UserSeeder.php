<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Akuns;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nama_role', 'Administrator')->first();
        $userRole = Role::where('nama_role', 'User')->first();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
        ]);

        $john = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $userRole->id,
        ]);

        // Assign default accounts to John
        $johnAccounts = Akuns::whereIn('kode_akun', [
            '111', // Kas
            '112', // Piutang Usaha
            '113', // Perlengkapan Kantor
            '211', // Utang Usaha
            '311', // Modal
            '411', // Pendapatan Jasa
            '511', // Beban Gaji
            '512', // Beban Sewa
        ])->pluck('id')->toArray();

        $john->akuns()->sync($johnAccounts);
    }
}
