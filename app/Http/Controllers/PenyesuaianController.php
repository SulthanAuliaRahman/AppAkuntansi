<?php

namespace App\Http\Controllers;

use App\Models\EntriPenyesuaian;
use App\Models\Pengaturan;
use App\Services\AkuntansiService;

class PenyesuaianController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $accounts           = $this->service->getAccountsConfig();
        $adjustmentsEnabled = $this->service->isAdjustmentsEnabled();
        $ajeRows            = EntriPenyesuaian::all();
        $totalAJE           = $ajeRows->sum('jumlah');

        return view('akuntansi.penyesuaian', compact('accounts', 'adjustmentsEnabled', 'ajeRows', 'totalAJE'));
    }

    public function toggle()
    {
        $current = $this->service->isAdjustmentsEnabled();
        Pengaturan::setValue('adjustments_enabled', $current ? '0' : '1');

        $msg = !$current
            ? 'Jurnal penyesuaian diaktifkan!'
            : 'Jurnal penyesuaian dinonaktifkan!';

        return redirect()->route('akuntansi.penyesuaian')->with('success', $msg);
    }
}
