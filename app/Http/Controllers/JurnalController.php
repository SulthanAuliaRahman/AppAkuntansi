<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJurnalRequest;
use App\Models\DetailJurnal;
use App\Models\Jurnal;
use App\Models\Akuns;
use App\Models\Pengaturan;
use App\Services\AkuntansiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', session('global_start_date'));
        $endDate   = $request->input('end_date', session('global_end_date'));

        $transactions = $this->service->getTransactions();
        $accounts     = $this->service->getAccountsConfig();

        // Filter berdasarkan tanggal (local filter dari request atau session)
        if ($startDate || $endDate) {
            $transactions = array_filter($transactions, function ($t) use ($startDate, $endDate) {
                if (!isset($t['rawDate'])) return true;
                $tDate = \Carbon\Carbon::parse($t['rawDate'])->format('Y-m-d');
                
                if ($startDate && $tDate < $startDate) return false;
                if ($endDate && $tDate > $endDate) return false;
                return true;
            });
            $transactions = array_values($transactions);
        }

        // Filter akun berdasarkan akses user
        $user = auth()->user();
        $akunsList = $user->getAccessibleAkuns()->toArray();
        $accessibleAkunCodes = array_column($akunsList, 'kode_akun');

        // Filter transaksi berdasarkan akun yang bisa diakses (hanya tampilkan jika ada akun yang bisa diakses)
        if (!$user->role->is_full_access) {
            $transactions = array_filter($transactions, function ($t) use ($accessibleAkunCodes) {
                foreach ($t['entries'] as $entry) {
                    if (in_array($entry['account'], $accessibleAkunCodes)) {
                        return true;
                    }
                }
                return false;
            });
            $transactions = array_values($transactions);
        }

        // Hitung total debet dan kredit secara terpisah dari masing-masing sisi
        $totalDebit  = array_sum(array_column($transactions, 'debitAmount'));
        $totalCredit = array_sum(array_column($transactions, 'creditAmount'));

        return view('akuntansi.jurnal', compact('transactions', 'accounts', 'totalDebit', 'totalCredit', 'akunsList', 'startDate', 'endDate'));
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

        $user = auth()->user();
        if (!$user->role->is_full_access) {
            foreach ($jurnal->details as $detail) {
                if (!$user->canAccessAkun($detail->akun_kode)) {
                    return redirect()->route('akuntansi.jurnal')
                        ->withErrors('Anda tidak memiliki akses untuk menghapus transaksi yang melibatkan akun: ' . $detail->akun_kode);
                }
            }
        }

        $jurnal->delete();

        return redirect()->route('akuntansi.jurnal')
            ->with('success', 'Transaksi kustom berhasil dihapus!');
    }

    public function details(Jurnal $jurnal)
    {
        abort_if($jurnal->is_static, 403, 'Transaksi studi kasus tidak dapat diubah.');

        $user = auth()->user();
        if (!$user->role->is_full_access) {
            foreach ($jurnal->details as $detail) {
                abort_if(!$user->canAccessAkun($detail->akun_kode), 403, 'Anda tidak memiliki akses ke beberapa akun dalam transaksi ini.');
            }
        }

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