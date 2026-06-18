<?php

namespace App\Http\Controllers;

use App\Models\JenisAkun;
use Illuminate\Http\Request;

class KategoriAkunController extends Controller
{
    public function index()
    {
        $jenisAkuns = JenisAkun::withCount('akuns')->orderBy('kode', 'asc')->get();
        return view('akuntansi.kategori', compact('jenisAkuns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:jenis_akun,kode',
            'nama' => 'required|string|max:100',
        ]);

        JenisAkun::create($request->only('kode', 'nama'));

        return redirect()->route('akuntansi.kategori')
            ->with('success', 'Kategori Akun baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $jenisAkun = JenisAkun::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:10|unique:jenis_akun,kode,' . $id,
            'nama' => 'required|string|max:100',
        ]);

        $jenisAkun->update($request->only('kode', 'nama'));

        return redirect()->route('akuntansi.kategori')
            ->with('success', 'Kategori Akun berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jenisAkun = JenisAkun::findOrFail($id);

        // Cek apakah masih ada akun yang terhubung
        if ($jenisAkun->akuns()->count() > 0) {
            return redirect()->route('akuntansi.kategori')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki akun terdaftar.');
        }

        $jenisAkun->delete();

        return redirect()->route('akuntansi.kategori')
            ->with('success', 'Kategori Akun berhasil dihapus!');
    }
}
