<?php

namespace App\Http\Controllers;

use App\Models\Akuns;
use App\Services\AkuntansiService;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $rows = $this->buildWorksheetRows();

        $revenues = $this->getReportAccounts($rows, '4');
        $expenses = $this->getReportAccounts($rows, '5');
        $assets = $this->getReportAccounts($rows, '1');
        $liabilities = $this->getReportAccounts($rows, '2');
        $equities = $this->getReportAccounts($rows, '3');

        $totalRev = array_sum(array_column($revenues, 'amount'));
        $totalExp = array_sum(array_column($expenses, 'amount'));
        $netIncome = $totalRev - $totalExp;

        $initialCap = 0;
        $prive = 0;

        foreach ($equities as $equity) {
            if (stripos($equity['name'], 'prive') !== false || stripos($equity['name'], 'pengambilan') !== false) {
                $prive += $equity['amount'];
                continue;
            }

            $initialCap += $equity['amount'];
        }

        $capIncrease = $netIncome - $prive;
        $finalCap = $initialCap + $capIncrease;

        $assetTotals = $this->calculateAssetTotals($assets);
        $totalLiabilities = array_sum(array_column($liabilities, 'amount'));
        $totalPassives = $totalLiabilities + $finalCap;

        return view('akuntansi.laporan-keuangan', compact(
            'revenues',
            'expenses',
            'totalRev',
            'totalExp',
            'netIncome',
            'initialCap',
            'prive',
            'capIncrease',
            'finalCap',
            'assets',
            'assetTotals',
            'liabilities',
            'totalLiabilities',
            'totalPassives'
        ));
    }

    private function buildWorksheetRows(): array
    {
        $accounts = $this->service->getAccountsConfig();
        $transactions = $this->service->getTransactions();
        $ledgers = $this->service->calculateLedgers($transactions);

        $ajpMutations = DB::table('detail_ajp')
            ->select(
                'akun_id',
                DB::raw("SUM(CASE WHEN posisi = 'DEBET' THEN nominal ELSE 0 END) as total_debit"),
                DB::raw("SUM(CASE WHEN posisi = 'KREDIT' THEN nominal ELSE 0 END) as total_kredit")
            )
            ->groupBy('akun_id')
            ->get()
            ->keyBy('akun_id');

        $masterAkuns = Akuns::get()->keyBy('id');
        $rows = [];

        foreach ($accounts as $code => $config) {
            $entries = $ledgers[$code] ?? [];
            $finalBalance = empty($entries) ? 0 : end($entries)['balance'];

            $isDebit = $config['normal'] === 'debit';
            $classCode = (int) ($config['classCode'] ?? 0);

            $tbD = $tbK = $ajeD = $ajeK = $nsdD = $nsdK = $lrD = $lrK = $nD = $nK = 0;

            if ($isDebit) {
                $tbD = $finalBalance;
            } else {
                $tbK = $finalBalance;
            }

            $matchedMaster = $masterAkuns->where('kode_akun', $code)->first();
            $ajpData = $matchedMaster ? ($ajpMutations[$matchedMaster->id] ?? null) : null;

            if ($ajpData) {
                $ajeD = (float) $ajpData->total_debit;
                $ajeK = (float) $ajpData->total_kredit;
            }

            if ($isDebit) {
                $nsdD = $tbD + $ajeD - $ajeK;
            } else {
                $nsdK = $tbK + $ajeK - $ajeD;
            }

            if ($classCode >= 4) {
                if ($isDebit) {
                    $lrD = $nsdD;
                } else {
                    $lrK = $nsdK;
                }
            } else {
                if ($isDebit) {
                    $nD = $nsdD;
                } else {
                    $nK = $nsdK;
                }
            }

            $rows[$code] = compact('code', 'config', 'lrD', 'lrK', 'nD', 'nK');
        }

        return $rows;
    }

    private function getReportAccounts(array $rows, string $prefix): array
    {
        $accounts = [];

        foreach ($rows as $code => $row) {
            if (!str_starts_with((string) $code, $prefix)) {
                continue;
            }

            $amount = (int) max($row['lrD'], $row['lrK'], $row['nD'], $row['nK']);

            if ($amount <= 0) {
                continue;
            }

            $accounts[$code] = [
                'code' => $code,
                'name' => $row['config']['name'] ?? 'Akun '.$code,
                'normal' => $row['config']['normal'] ?? 'debit',
                'amount' => $amount,
            ];
        }

        ksort($accounts);
        return $accounts;
    }

    private function calculateAssetTotals(array $assets): array
    {
        $totalAssets = 0;
        $accumulatedDepreciation = 0;

        foreach ($assets as $asset) {
            $isContraAsset = stripos($asset['name'], 'akum') !== false
                || stripos($asset['name'], 'penyusutan') !== false
                || ($asset['normal'] ?? 'debit') === 'credit';

            if ($isContraAsset) {
                $accumulatedDepreciation += abs($asset['amount']);
            } else {
                $totalAssets += $asset['amount'];
            }
        }

        return [
            'total' => $totalAssets - $accumulatedDepreciation,
            'gross' => $totalAssets,
            'accumulated' => $accumulatedDepreciation,
        ];
    }
}
