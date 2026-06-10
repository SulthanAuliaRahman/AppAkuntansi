<?php

namespace App\Http\Controllers;

use App\Services\AkuntansiService;

class DashboardController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $transactions       = $this->service->getTransactions();
        $adjustmentsEnabled = $this->service->isAdjustmentsEnabled();
        $adj                = $this->service->getAdjustedBalances($transactions, $adjustmentsEnabled);

        $kas          = $adj['111']['adjustedBalance'] ?? 0;
        $piutang      = $adj['112']['adjustedBalance'] ?? 0;
        $perlengkapan = $adj['113']['adjustedBalance'] ?? 0;
        $sewa         = $adj['114']['adjustedBalance'] ?? 0;
        $peralatan    = $adj['115']['adjustedBalance'] ?? 0;
        $akumPeny     = $adj['116']['adjustedBalance'] ?? 0;
        $totalAssets  = $kas + $piutang + $perlengkapan + $sewa + $peralatan - $akumPeny;

        $totalRev = ($adj['411']['adjustedBalance'] ?? 0) + ($adj['412']['adjustedBalance'] ?? 0);
        $expGaji         = $adj['511']['adjustedBalance'] ?? 0;
        $expSewa         = $adj['512']['adjustedBalance'] ?? 0;
        $expIklan        = $adj['513']['adjustedBalance'] ?? 0;
        $expAsuransi     = $adj['514']['adjustedBalance'] ?? 0;
        $expPerlengkapan = $adj['515']['adjustedBalance'] ?? 0;
        $expPenyusutan   = $adj['516']['adjustedBalance'] ?? 0;
        $totalExp        = $expGaji + $expSewa + $expIklan + $expAsuransi + $expPerlengkapan + $expPenyusutan;
        $netIncome     = $totalRev - $totalExp;
        $peralatanNeto = $peralatan - $akumPeny;

        $chartData = [
            'assets'      => [$kas, $piutang, $perlengkapan, $sewa, $peralatanNeto],
            'revenue'     => $totalRev,
            'expenses'    => $totalExp,
            'netIncome'   => $netIncome,
            'expBreakdown'=> [$expGaji, $expSewa, $expIklan, $expAsuransi, $expPerlengkapan, $expPenyusutan],
        ];

        return view('akuntansi.dashboard', compact(
            'totalAssets', 'totalRev', 'totalExp', 'netIncome', 'chartData'
        ));
    }
}
