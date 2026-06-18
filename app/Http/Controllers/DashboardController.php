<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Services\AkuntansiService;

class DashboardController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $transactions       = $this->service->getTransactions();
        $adjustmentsEnabled = $this->service->isAdjustmentsEnabled();
        $adj                = $this->service->getAdjustedBalances($transactions, $adjustmentsEnabled);

        $kas          = $adj['1.1.1']['adjustedBalance'] ?? 0;
        $piutang      = $adj['1.1.2']['adjustedBalance'] ?? 0;
        $perlengkapan = $adj['1.1.3']['adjustedBalance'] ?? 0;
        $sewa         = $adj['1.1.4']['adjustedBalance'] ?? 0;
        $peralatan    = $adj['1.1.5']['adjustedBalance'] ?? 0;
        $akumPeny     = $adj['1.1.6']['adjustedBalance'] ?? 0;
        $totalAssets  = $kas + $piutang + $perlengkapan + $sewa + $peralatan - $akumPeny;

        $totalRev = ($adj['4.1.1']['adjustedBalance'] ?? 0) + ($adj['4.1.2']['adjustedBalance'] ?? 0);
        $expGaji         = $adj['5.1.1']['adjustedBalance'] ?? 0;
        $expSewa         = $adj['5.1.2']['adjustedBalance'] ?? 0;
        $expIklan        = $adj['5.1.3']['adjustedBalance'] ?? 0;
        $expAsuransi     = $adj['5.1.4']['adjustedBalance'] ?? 0;
        $expPerlengkapan = $adj['5.1.5']['adjustedBalance'] ?? 0;
        $expPenyusutan   = $adj['5.1.6']['adjustedBalance'] ?? 0;
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

        // Ambil list tahun yang ada di database secara spesifik, db-agnostic (MySQL/PostgreSQL/SQLite)
        $availableYears = Jurnal::select('tanggal')->get()
            ->map(fn($j) => \Carbon\Carbon::parse($j->tanggal)->year)
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        // $period = session('global_period', 'all');
        // $periodLabel = 'Semua Waktu';
        // if ($period === '1_month') {
        //     $periodLabel = 'Bulan Ini';
        // } elseif ($period === '3_months') {
        //     $periodLabel = '3 Bulan Terakhir';
        // } elseif ($period === '1_year') {
        //     $periodLabel = 'Tahun Ini';
        // } elseif ($period === 'custom_month') {
        //     $m = session('global_custom_month', now()->month);
        //     $y = session('global_custom_year', now()->year);
        //     $periodLabel = \Carbon\Carbon::create()->month($m)->translatedFormat('F') . ' ' . $y;
        // }

        return view('akuntansi.dashboard', compact(
            'totalAssets', 'totalRev', 'totalExp', 'netIncome', 'chartData', 'availableYears'
        ));
    }
}
