<?php

namespace App\Http\Controllers;

use App\Services\AkuntansiService;

class KertasKerjaController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $accounts           = $this->service->getAccountsConfig();
        $transactions       = $this->service->getTransactions();
        $adjustmentsEnabled = $this->service->isAdjustmentsEnabled();
        $adjData            = $this->service->getAdjustedBalances($transactions, $adjustmentsEnabled);

        $rows = [];
        $sums = ['tbD' => 0, 'tbK' => 0, 'ajeD' => 0, 'ajeK' => 0,
                 'nsdD' => 0, 'nsdK' => 0, 'lrD' => 0, 'lrK' => 0, 'nD' => 0, 'nK' => 0];

        foreach ($accounts as $code => $config) {
            $data    = $adjData[$code];
            $isDebit = $config['normal'] === 'debit';
            $type    = $config['type'];

            $tbD = $tbK = $nsdD = $nsdK = $lrD = $lrK = $nD = $nK = 0;

            if ($isDebit) { $tbD = $data['preAdjustment'];    $sums['tbD'] += $tbD; }
            else          { $tbK = $data['preAdjustment'];    $sums['tbK'] += $tbK; }

            $ajeD = $data['debitAdj'];
            $ajeK = $data['creditAdj'];
            $sums['ajeD'] += $ajeD;
            $sums['ajeK'] += $ajeK;

            if ($isDebit) { $nsdD = $data['adjustedBalance']; $sums['nsdD'] += $nsdD; }
            else          { $nsdK = $data['adjustedBalance']; $sums['nsdK'] += $nsdK; }

            if ($type === 'revenue' || $type === 'expense') {
                if ($isDebit) { $lrD = $data['adjustedBalance']; $sums['lrD'] += $lrD; }
                else          { $lrK = $data['adjustedBalance']; $sums['lrK'] += $lrK; }
            } else {
                if ($isDebit) { $nD = $data['adjustedBalance']; $sums['nD'] += $nD; }
                else          { $nK = $data['adjustedBalance']; $sums['nK'] += $nK; }
            }

            $rows[] = compact('code', 'config', 'tbD', 'tbK', 'ajeD', 'ajeK', 'nsdD', 'nsdK', 'lrD', 'lrK', 'nD', 'nK');
        }

        $labaRugiDiff = $sums['lrK'] - $sums['lrD'];
        $neracaDiff   = $sums['nD'] - $sums['nK'];
        $isProfit     = $labaRugiDiff >= 0;

        return view('akuntansi.kertas-kerja', compact('rows', 'sums', 'labaRugiDiff', 'neracaDiff', 'isProfit'));
    }
}
