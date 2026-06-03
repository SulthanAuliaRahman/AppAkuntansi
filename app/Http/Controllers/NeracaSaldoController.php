<?php

namespace App\Http\Controllers;

use App\Services\AkuntansiService;

class NeracaSaldoController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $accounts     = $this->service->getAccountsConfig();
        $transactions = $this->service->getTransactions();
        $ledgers      = $this->service->calculateLedgers($transactions);

        $rows        = [];
        $totalDebit  = 0;
        $totalCredit = 0;

        foreach ($accounts as $code => $config) {
            $bal    = end($ledgers[$code])['balance'];
            $debit  = 0;
            $credit = 0;

            if ($config['normal'] === 'debit' && $bal > 0) {
                $debit      = $bal;
                $totalDebit += $bal;
            } elseif ($config['normal'] === 'credit' && $bal > 0) {
                $credit      = $bal;
                $totalCredit += $bal;
            }

            $rows[] = compact('code', 'config', 'debit', 'credit');
        }

        return view('akuntansi.neraca-saldo', compact('rows', 'totalDebit', 'totalCredit'));
    }
}
