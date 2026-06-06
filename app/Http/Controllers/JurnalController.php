<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJurnalRequest;
use App\Models\DetailJurnal;
use App\Models\Jurnal;
use App\Models\Akuns;
use App\Models\Pengaturan;
use App\Services\AkuntansiService;
use Illuminate\Support\Facades\DB;

class JurnalController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $transactions = $this->service->getTransactions();
        $accounts     = $this->service->getAccountsConfig();
        $akunsList    = Akuns::where('aktif', true)->get(['kode_akun', 'nama_akun', 'jenis_akun_id'])->toArray();

        // Hitung total debet dan kredit secara terpisah dari masing-masing sisi
        $totalDebit  = array_sum(array_column($transactions, 'debitAmount'));
        $totalCredit = array_sum(array_column($transactions, 'creditAmount'));

        return view('akuntansi.jurnal', compact('transactions', 'accounts', 'totalDebit', 'totalCredit', 'akunsList'));
    }

    public function store(StoreJurnalRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $jurnal = Jurnal::create([
                'tanggal'    => $validated['date'],
                'keterangan' => $validated['desc'],
                'is_static'  => false,
            ]);

            foreach ($validated['entries'] as $entry) {
                DetailJurnal::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_kode' => $entry['akun_kode'],
                    'type'      => $entry['type'],
                    'jumlah'    => $entry['jumlah'],
                ]);
            }
        });

        return redirect()->route('akuntansi.jurnal')
            ->with('success', 'Transaksi berhasil diposting ke Jurnal Umum!');
    }

    public function destroy(Jurnal $jurnal)
    {
        abort_if($jurnal->is_static, 403, 'Transaksi studi kasus tidak dapat dihapus.');
        $jurnal->delete();

        return redirect()->route('akuntansi.jurnal')
            ->with('success', 'Transaksi kustom berhasil dihapus!');
    }

    public function details(Jurnal $jurnal)
    {
        abort_if($jurnal->is_static, 403, 'Transaksi studi kasus tidak dapat diubah.');

        return response()->json([
            'id'         => $jurnal->id,
            'tanggal'    => $jurnal->tanggal,
            'keterangan' => $jurnal->keterangan,
            'details'    => $jurnal->details->map(fn($d) => [
                'id'        => $d->id,
                'akun_kode' => $d->akun_kode,
                'type'      => $d->type,
                'jumlah'    => $d->jumlah,
            ])->toArray(),
        ]);
    }

    public function update(Jurnal $jurnal, StoreJurnalRequest $request)
    {
        abort_if($jurnal->is_static, 403, 'Transaksi studi kasus tidak dapat diubah.');

        $validated = $request->validated();

        DB::transaction(function () use ($jurnal, $validated) {
            $jurnal->update([
                'tanggal'    => $validated['date'],
                'keterangan' => $validated['desc'],
            ]);

            $jurnal->details()->delete();

            foreach ($validated['entries'] as $entry) {
                DetailJurnal::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_kode' => $entry['akun_kode'],
                    'type'      => $entry['type'],
                    'jumlah'    => $entry['jumlah'],
                ]);
            }
        });

        return redirect()->route('akuntansi.jurnal')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function reset()
    {
        Jurnal::where('is_static', false)->delete();
        Pengaturan::setValue('adjustments_enabled', '1');

        return redirect()->route('akuntansi.dashboard')
            ->with('success', 'Aplikasi berhasil direset ke modul studi kasus!');
    }
}