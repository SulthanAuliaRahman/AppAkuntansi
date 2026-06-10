<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Akuns;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        // Menarik data untuk semua tab di UI
        $users = User::with('role')->get();
        $roles = Role::with('akunAkses')->get(); // Load relasi akses akun

        // Mengelompokkan akun berdasarkan jenis_akun_id (Bisa disesuaikan dengan relasi jenisAkun)
        // Di sini saya asumsikan dikelompokkan manual untuk UI, atau bisa di-group query
        $akuns = Akuns::orderBy('kode_akun')->get();
        $akunGroups = $akuns->groupBy(function($item) {
            // Logika grouping sementara berdasarkan digit pertama kode akun
            $prefix = substr($item->kode_akun, 0, 1);
            return match($prefix) {
                '1' => '1 — Harta / Assets',
                '2' => '2 — Kewajiban / Liabilities',
                '3' => '3 — Modal / Ekuitas',
                '4' => '4 — Pendapatan / Revenue',
                '5' => '5 — Beban / Expenses',
                default => 'Lainnya'
            };
        });

        return view('admin.users.index', compact('users', 'roles', 'akunGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create($validated); // Password otomatis ter-hash karena ada casting di Model User

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8', // Password opsional saat update
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
