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

        $kas          = $adj['111']['adjustedBalance'];
        $piutang      = $adj['112']['adjustedBalance'];
        $perlengkapan = $adj['113']['adjustedBalance'];
        $sewa         = $adj['114']['adjustedBalance'];
        $peralatan    = $adj['115']['adjustedBalance'];
        $akumPeny     = $adj['116']['adjustedBalance'];
        $totalAssets  = $kas + $piutang + $perlengkapan + $sewa + $peralatan - $akumPeny;

        $totalRev        = $adj['411']['adjustedBalance'] + $adj['412']['adjustedBalance'];
        $expGaji         = $adj['511']['adjustedBalance'];
        $expSewa         = $adj['512']['adjustedBalance'];
        $expIklan        = $adj['513']['adjustedBalance'];
        $expAsuransi     = $adj['514']['adjustedBalance'];
        $expPerlengkapan = $adj['515']['adjustedBalance'];
        $expPenyusutan   = $adj['516']['adjustedBalance'];
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
