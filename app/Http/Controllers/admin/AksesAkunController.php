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
            'akun_id' => 'nullable|array',
        ]);

        $role = Role::findOrFail($request->role_id);

        $akunIds = [];
        if ($request->has('akun_id')) {
            $akunIds = Akuns::whereIn('kode_akun', $request->akun_id)->pluck('id')->toArray();
        }

        $role->akunAkses()->sync($akunIds);

        $tab = $request->input('redirect_tab', 'akses');

        return redirect()->route('admin.users.index', ['tab' => $tab])->with('success', 'Pemetaan akses akun berhasil diperbarui.');
    }
}
