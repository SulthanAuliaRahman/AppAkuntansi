<?php

namespace App\Http\Controllers;

use App\Models\Ajp;
use App\Models\DetailAjp;
use App\Models\Akuns;
use App\Services\AkuntansiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyesuaianController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $accounts = $this->service->getAccountsConfig();
        $akunsList = Akuns::where('aktif', true)->get(['id', 'kode_akun', 'nama_akun', 'jenis_akun_id'])->toArray();

        // Ambil data AJP murni beserta detail baris dan master akunya sekaligus
        $ajps = Ajp::with('details.akun')->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();

        $ajeRows = [];
        foreach ($ajps as $ajp) {
            // Pisahkan pecahan baris debet dan kredit
            $debits  = $ajp->details->where('posisi', 'DEBET');
            $credits = $ajp->details->where('posisi', 'KREDIT');

            // Hitung total akumulasi nominal per sisi
            $totalDebit  = (int) $debits->sum('nominal');
            $totalCredit = (int) $credits->sum('nominal');

            // Masukkan baris data utama dengan membawa seluruh array pecahan barisnya
            $ajeRows[] = [
                'id'           => $ajp->id,
                'date'         => \Carbon\Carbon::parse($ajp->tanggal)->translatedFormat('d M'),
                'desc'         => $ajp->keterangan,
                'is_static'    => false,
                'debits'       => $debits,
                'credits'      => $credits,
                'debitAmount'  => $totalDebit,
                'creditAmount' => $totalCredit,
            ];
        }

        $totalDebitAJE  = array_sum(array_column($ajeRows, 'debitAmount'));
        $totalCreditAJE = array_sum(array_column($ajeRows, 'creditAmount'));

        return view('akuntansi.penyesuaian', compact(
            'ajeRows', 'accounts', 'totalDebitAJE', 'totalCreditAJE', 'akunsList'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'             => 'required|date',
            'desc'             => 'required|string|max:255',
            'entries'          => 'required|array|min:2',
            'entries.*.posisi'  => 'required|string|in:DEBET,KREDIT',
            'entries.*.akun_id' => 'required|exists:akuns,id',
            'entries.*.nominal' => 'required|numeric|min:1000',
        ]);

        DB::transaction(function () use ($validated) {
            $ajp = Ajp::create([
                'tanggal'         => $validated['date'],
                'keterangan'      => $validated['desc'],
                'jenis_transaksi' => 'PENYESUAIAN',
            ]);

            foreach ($validated['entries'] as $entry) {
                DetailAjp::create([
                    'ajp_id'  => $ajp->id,
                    'akun_id' => $entry['akun_id'],
                    'posisi'  => $entry['posisi'],
                    'nominal' => $entry['nominal'],
                ]);
            }
        });

        return redirect()->route('akuntansi.penyesuaian')
            ->with('success', 'Ayat Jurnal Penyesuaian manual berhasil diposting!');
    }

    // Nama method disamakan menjadi getDetails agar sinkron dengan route web.php
    public function getDetails($id)
    {
        $ajp = Ajp::with('details')->find($id);
        
        if (!$ajp) {
            return response()->json(['message' => 'Data penyesuaian tidak ditemukan'], 404);
        }

        return response()->json([
            'id'         => $ajp->id,
            'tanggal'    => $ajp->tanggal,
            'keterangan' => $ajp->keterangan,
            'details'    => $ajp->details->map(fn($d) => [
                'id'      => $d->id,
                'akun_id' => $d->akun_id,
                'posisi'  => $d->posisi,
                'nominal' => $d->nominal,
            ])->toArray(),
        ]);
    }

    public function update($id, Request $request)
    {
        $ajp = Ajp::findOrFail($id);

        $validated = $request->validate([
            'date'             => 'required|date',
            'desc'             => 'required|string|max:255',
            'entries'          => 'required|array|min:2',
            'entries.*.posisi'  => 'required|string|in:DEBET,KREDIT',
            'entries.*.akun_id' => 'required|exists:akuns,id',
            'entries.*.nominal' => 'required|numeric|min:1000',
        ]);

        DB::transaction(function () use ($ajp, $validated) {
            $ajp->update([
                'tanggal'    => $validated['date'],
                'keterangan' => $validated['desc'],
            ]);

            $ajp->details()->delete();

            foreach ($validated['entries'] as $entry) {
                DetailAjp::create([
                    'ajp_id'  => $ajp->id,
                    'akun_id' => $entry['akun_id'],
                    'posisi'  => $entry['posisi'],
                    'nominal' => $entry['nominal'],
                ]);
            }
        });

        return redirect()->route('akuntansi.penyesuaian')
            ->with('with', 'Transaksi penyesuaian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ajp = Ajp::findOrFail($id);
        $ajp->delete();

        return redirect()->route('akuntansi.penyesuaian')
            ->with('success', 'Entri Jurnal Penyesuaian berhasil dihapus!');
    }
}