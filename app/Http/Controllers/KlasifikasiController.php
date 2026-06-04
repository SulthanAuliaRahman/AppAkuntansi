<?php

namespace App\Http\Controllers;

use App\Models\Akuns;
use App\Models\JenisAkun;
use Illuminate\Http\Request;

class KlasifikasiController extends Controller
{
    public function index()
    {
        $akuns = Akuns::with('jenisAkun')->orderBy('kode_akun', 'asc')->get();
        $jenisAkuns = JenisAkun::orderBy('kode', 'asc')->get();

        return view('akuntansi.klasifikasi', compact('akuns', 'jenisAkuns'));
    }

    public function store(Request $request)
    {
        // 1. Cari data jenis akun terlebih dahulu
        $jenisAkun = JenisAkun::findOrFail($request->jenis_akun_id);

        // 2. Gabungkan kode (Misal: "1.1" + "." + "01" = "1.1.01")
        $kodeAkunLengkap = $jenisAkun->kode . '.' . $request->kode_suffix;

        // 3. Masukkan ke dalam request data sebelum validasi dijalankan
        $request->merge(['kode_akun' => $kodeAkunLengkap]);

        $request->validate([
            'jenis_akun_id' => 'required|exists:jenis_akun,id',
            'kode_akun'     => 'required|string|max:20|unique:akuns,kode_akun',
            'nama_akun'     => 'required|string|max:150',
            'saldo_normal'  => 'required|in:DEBET,KREDIT',
        ]);

        Akuns::create([
            'jenis_akun_id' => $request->jenis_akun_id,
            'kode_akun'     => $request->kode_akun,
            'nama_akun'     => $request->nama_akun,
            'saldo_normal'  => $request->saldo_normal,
            'aktif'         => true,
        ]);

        return redirect()->route('akuntansi.klasifikasi.index')
            ->with('success', 'Akun Klasifikasi baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $akun = Akuns::findOrFail($id);
        $jenisAkun = JenisAkun::findOrFail($request->jenis_akun_id);

        // Gabungkan kode baru saat update
        $kodeAkunLengkap = $jenisAkun->kode . '.' . $request->kode_suffix;
        $request->merge(['kode_akun' => $kodeAkunLengkap]);

        $request->validate([
            'jenis_akun_id' => 'required|exists:jenis_akun,id',
            'kode_akun'     => 'required|string|max:20|unique:akuns,kode_akun,' . $id,
            'nama_akun'     => 'required|string|max:150',
            'saldo_normal'  => 'required|in:DEBET,KREDIT',
            'aktif'         => 'required|boolean'
        ]);

        $akun->update($request->all());

        return redirect()->route('akuntansi.klasifikasi.index')
            ->with('success', 'Data Akun Klasifikasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // ini teh paling harus nya bisa di hapus kalau gak ada transaksi
        // kalau ada transaksi cuman bisa di nonaktifkan aja
        $akun = Akuns::findOrFail($id);
        $akun->delete();

        return redirect()->route('akuntansi.klasifikasi.index')
            ->with('success', 'Akun Klasifikasi berhasil dihapus!');
    }
}
