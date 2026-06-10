<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_role' => 'required|string|max:50|unique:roles,nama_role',
            'deskripsi' => 'nullable|string',
            'is_full_access' => 'boolean'
        ]);

        $validated['nama_role'] = strtoupper($validated['nama_role']);

        Role::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Role berhasil dibuat.');
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'nama_role' => 'required|string|max:50|unique:roles,nama_role,' . $role->id,
            'deskripsi' => 'nullable|string',
            'is_full_access' => 'boolean'
        ]);

        $validated['nama_role'] = strtoupper($validated['nama_role']);

        $role->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.users.index')->with('success', 'Role berhasil dihapus.');
    }
}
