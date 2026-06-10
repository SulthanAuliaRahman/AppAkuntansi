<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Akuns;
use Illuminate\Http\Request;

class AksesAkunController extends Controller
{
    public function sync(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'akun_id' => 'nullable|array', // Bisa null jika mengosongkan semua akses
        ]);

        $role = Role::findOrFail($request->role_id);

        // Jika form mengirimkan 'kode_akun' dari checkbox (sesuai UI yang kamu buat),
        // kita perlu mencari ID akuns-nya terlebih dahulu.
        $akunIds = [];
        if ($request->has('akun_id')) {
            $akunIds = Akuns::whereIn('kode_akun', $request->akun_id)->pluck('id')->toArray();
        }

        // Fungsi sync() otomatis menghapus akses lama dan memasukkan akses baru ke tabel pivot
        $role->akunAkses()->sync($akunIds);

        return redirect()->route('admin.users.index')->with('success', 'Pemetaan akses akun berhasil diperbarui.');
    }
}
