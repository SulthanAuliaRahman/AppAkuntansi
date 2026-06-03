<?php

namespace App\Http\Controllers;

use App\Services\AkuntansiService;
use Illuminate\Http\Request;

class BukuBesarController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index(Request $request)
    {
        $accounts     = $this->service->getAccountsConfig();
        $selectedCode = $request->get('akun', '111');

        if (!array_key_exists($selectedCode, $accounts)) {
            $selectedCode = '111';
        }

        $transactions   = $this->service->getTransactions();
        $ledgers        = $this->service->calculateLedgers($transactions);
        $entries        = $ledgers[$selectedCode] ?? [];
        $finalBalance   = empty($entries) ? 0 : end($entries)['balance'];
        $selectedConfig = $accounts[$selectedCode];

        return view('akuntansi.buku-besar', compact(
            'accounts', 'selectedCode', 'selectedConfig', 'entries', 'finalBalance'
        ));
    }
}
