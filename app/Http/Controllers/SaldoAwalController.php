<?php

namespace App\Http\Controllers;

use App\Models\Akuns;
use App\Models\SaldoAwal;
use Illuminate\Http\Request;

class SaldoAwalController extends Controller
{
    public function index()
    {
        $saldoAwals = SaldoAwal::join('akuns', 'saldo_awal.kode_akun', '=', 'akuns.kode_akun')
            ->join('jenis_akun', 'akuns.jenis_akun_id', '=', 'jenis_akun.id')
            ->select('saldo_awal.*', 'akuns.nama_akun', 'akuns.saldo_normal', 'jenis_akun.nama as jenis_nama')
            ->orderBy('saldo_awal.kode_akun')
            ->get();

        return view('akuntansi.saldo-awal.index', compact('saldoAwals'));
    }

    public function edit($kodeAkun)
    {
        $saldoAwal = SaldoAwal::where('kode_akun', $kodeAkun)->firstOrFail();
        $akun = Akuns::where('kode_akun', $kodeAkun)
            ->join('jenis_akun', 'akuns.jenis_akun_id', '=', 'jenis_akun.id')
            ->select('akuns.*', 'jenis_akun.nama as jenis_nama')
            ->first();

        return view('akuntansi.saldo-awal.edit', compact('saldoAwal', 'akun'));
    }

    public function update(Request $request, $kodeAkun)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'tipe'    => 'required|in:debit,kredit',
        ], [
            'nominal.required' => 'Nominal harus diisi',
            'nominal.min'      => 'Nominal harus lebih dari 0',
            'tipe.required'    => 'Tipe saldo harus dipilih',
            'tipe.in'          => 'Tipe saldo tidak valid',
        ]);

        $nominal = (int) $request->nominal;
        $debet = $request->tipe === 'debit' ? $nominal : 0;
        $kredit = $request->tipe === 'kredit' ? $nominal : 0;

        SaldoAwal::where('kode_akun', $kodeAkun)->update([
            'debet'  => $debet,
            'kredit' => $kredit,
        ]);

        return redirect()
            ->route('akuntansi.saldoawal')
            ->with('success', "Saldo awal akun $kodeAkun berhasil diupdate");
    }

    public function updateBulk(Request $request)
    {
        $request->validate([
            'saldo_awal' => 'required|array',
            'saldo_awal.*.debet'  => 'required|numeric|min:0',
            'saldo_awal.*.kredit' => 'required|numeric|min:0',
        ]);

        $updatedCount = 0;
        $errors = [];

        foreach ($request->saldo_awal as $kodeAkun => $data) {
            $debet = (int) ($data['debet'] ?? 0);
            $kredit = (int) ($data['kredit'] ?? 0);

            if ($debet > 0 && $kredit > 0) {
                $errors[] = "Akun $kodeAkun: hanya boleh Debet ATAU Kredit, tidak keduanya";
                continue;
            }

            if ($debet === 0 && $kredit === 0) {
                $errors[] = "Akun $kodeAkun: minimal salah satu dari Debet/Kredit harus diisi";
                continue;
            }

            SaldoAwal::where('kode_akun', $kodeAkun)->update([
                'debet'  => $debet,
                'kredit' => $kredit,
            ]);

            $updatedCount++;
        }

        $message = "$updatedCount akun berhasil diupdate";
        if (!empty($errors)) {
            return back()->with('errors', $errors)->with('partial_success', $message)->withInput();
        }

        return redirect()
            ->route('akuntansi.saldoawal')
            ->with('success', $message);
    }

    public function destroy($kodeAkun)
    {
        SaldoAwal::where('kode_akun', $kodeAkun)->delete();

        return redirect()
            ->route('akuntansi.saldoawal')
            ->with('success', "Saldo awal akun $kodeAkun berhasil dihapus");
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_akun' => 'required|exists:akuns,kode_akun|unique:saldo_awal,kode_akun',
            'nominal'   => 'required|numeric|min:1',
            'tipe'      => 'required|in:debit,kredit',
        ], [
            'kode_akun.required' => 'Akun harus dipilih',
            'kode_akun.exists'   => 'Akun tidak valid',
            'kode_akun.unique'   => 'Saldo awal akun ini sudah ada',
            'nominal.required'   => 'Nominal harus diisi',
            'nominal.min'        => 'Nominal harus lebih dari 0',
            'tipe.required'      => 'Tipe saldo harus dipilih',
            'tipe.in'            => 'Tipe saldo tidak valid',
        ]);

        $nominal = (int) $request->nominal;
        $debet = $request->tipe === 'debit' ? $nominal : 0;
        $kredit = $request->tipe === 'kredit' ? $nominal : 0;

        SaldoAwal::create([
            'kode_akun' => $request->kode_akun,
            'debet'     => $debet,
            'kredit'    => $kredit,
        ]);

        return redirect()
            ->route('akuntansi.saldoawal')
            ->with('success', "Saldo awal akun {$request->kode_akun} berhasil ditambahkan");
    }
}
