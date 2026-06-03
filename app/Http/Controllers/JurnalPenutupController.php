<?php

namespace App\Http\Controllers;

use App\Services\AkuntansiService;

class JurnalPenutupController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $accounts           = $this->service->getAccountsConfig();
        $transactions       = $this->service->getTransactions();
        $adjustmentsEnabled = $this->service->isAdjustmentsEnabled();
        $adj                = $this->service->getAdjustedBalances($transactions, $adjustmentsEnabled);

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
        $prive           = $adj['312']['adjustedBalance'];
        $totalDebit      = $totalRev + $totalExp + $netIncome + $prive;

        return view('akuntansi.jurnal-penutup', compact(
            'accounts', 'revJasa', 'revIklan', 'totalRev',
            'expGaji', 'expSewa', 'expIklan', 'expAsuransi', 'expPerlengkapan', 'expPenyusutan',
            'totalExp', 'netIncome', 'prive', 'totalDebit'
        ));
    }
}
