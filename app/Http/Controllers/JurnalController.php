<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJurnalRequest;
use App\Models\Jurnal;
use App\Models\Pengaturan;
use App\Services\AkuntansiService;

class JurnalController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $transactions = $this->service->getTransactions();
        $accounts     = $this->service->getAccountsConfig();
        $totalDebit   = array_sum(array_column($transactions, 'amount'));

        return view('akuntansi.jurnal', compact('transactions', 'accounts', 'totalDebit'));
    }

    public function store(StoreJurnalRequest $request)
    {
        Jurnal::create([
            'tanggal'    => $request->date,
            'keterangan' => $request->desc,
            'akun_debet' => $request->debit_acc,
            'akun_kredit'=> $request->credit_acc,
            'jumlah'     => (int) $request->amount,
            'is_static'  => false,
        ]);

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

    public function reset()
    {
        Jurnal::where('is_static', false)->delete();
        Pengaturan::setValue('adjustments_enabled', '1');

        return redirect()->route('akuntansi.dashboard')
            ->with('success', 'Aplikasi berhasil direset ke modul studi kasus!');
    }
}
