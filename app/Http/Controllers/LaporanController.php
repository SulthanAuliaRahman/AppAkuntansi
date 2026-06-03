<?php

namespace App\Http\Controllers;

use App\Services\AkuntansiService;

class LaporanController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $transactions       = $this->service->getTransactions();
        $adjustmentsEnabled = $this->service->isAdjustmentsEnabled();
        $adj                = $this->service->getAdjustedBalances($transactions, $adjustmentsEnabled);

        // Laba Rugi
        $revJasa         = $adj['411']['adjustedBalance'];
        $revIklan        = $adj['412']['adjustedBalance'];
        $totalRev        = $revJasa + $revIklan;
        $expGaji         = $adj['511']['adjustedBalance'];
        $expSewa         = $adj['512']['adjustedBalance'];
        $expIklan        = $adj['513']['adjustedBalance'];
        $expAsuransi     = $adj['514']['adjustedBalance'];
        $expPerlengkapan = $adj['515']['adjustedBalance'];
        $expPenyusutan   = $adj['516']['adjustedBalance'];
        $totalExp        = $expGaji + $expSewa + $expIklan + $expAsuransi + $expPerlengkapan + $expPenyusutan;
        $netIncome       = $totalRev - $totalExp;

        // Perubahan Modal
        $initialCap  = $adj['311']['preAdjustment'];
        $prive       = $adj['312']['adjustedBalance'];
        $capIncrease = $netIncome - $prive;
        $finalCap    = $initialCap + $capIncrease;

        // Neraca
        $kas          = $adj['111']['adjustedBalance'];
        $piutang      = $adj['112']['adjustedBalance'];
        $perlengkapan = $adj['113']['adjustedBalance'];
        $sewa         = $adj['114']['adjustedBalance'];
        $peralatan    = $adj['115']['adjustedBalance'];
        $akumPeny     = $adj['116']['adjustedBalance'];
        $totalAssets  = $kas + $piutang + $perlengkapan + $sewa + $peralatan - $akumPeny;
        $hutang       = $adj['211']['adjustedBalance'];
        $iklanDimuka  = $adj['212']['adjustedBalance'];
        $hutangGaji   = $adj['213']['adjustedBalance'];
        $totalPassives = $hutang + $iklanDimuka + $hutangGaji + $finalCap;

        return view('akuntansi.laporan', compact(
            'revJasa', 'revIklan', 'totalRev',
            'expGaji', 'expSewa', 'expIklan', 'expAsuransi', 'expPerlengkapan', 'expPenyusutan', 'totalExp',
            'netIncome',
            'initialCap', 'prive', 'capIncrease', 'finalCap',
            'kas', 'piutang', 'perlengkapan', 'sewa', 'peralatan', 'akumPeny', 'totalAssets',
            'hutang', 'iklanDimuka', 'hutangGaji', 'totalPassives'
        ));
    }
}
