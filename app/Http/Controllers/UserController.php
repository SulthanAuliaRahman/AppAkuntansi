<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Akuns;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $akuns = Akuns::where('aktif', true)->get(['id', 'kode_akun', 'nama_akun'])->toArray();
        return view('users.create', compact('roles', 'akuns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'akun_ids' => 'nullable|array',
            'akun_ids.*' => 'exists:akuns,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $akunIds = $validated['akun_ids'] ?? [];
        unset($validated['akun_ids']);

        $user = User::create($validated);

        // Assign accounts to user
        if (!empty($akunIds)) {
            $user->akuns()->sync($akunIds);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $akuns = Akuns::where('aktif', true)->get(['id', 'kode_akun', 'nama_akun'])->toArray();
        $userAkunIds = $user->akuns()->pluck('akun_id')->toArray();
        return view('users.edit', compact('user', 'roles', 'akuns', 'userAkunIds'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
            'akun_ids' => 'nullable|array',
            'akun_ids.*' => 'exists:akuns,id',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $akunIds = $validated['akun_ids'] ?? [];
        unset($validated['akun_ids']);

        $user->update($validated);

        // Update account assignments
        $user->akuns()->sync($akunIds);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
