<?php

namespace App\Http\Controllers;

use App\Services\AkuntansiService;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $transactions = $this->service->getTransactions();
        $accounts     = $this->service->getAccountsConfig();

        // Filter transactions by date range if provided
        if ($startDate || $endDate) {
            $transactions = $this->filterTransactionsByDateRange($transactions, $startDate, $endDate);
        }

        // Use calculateLedgers to get pre-adjusted balances (not post-adjusted)
        $ledgers = $this->service->calculateLedgers($transactions);
        $balances = $this->extractBalancesFromLedgers($ledgers);

        // Get revenues (4xx range)
        $revenues = $this->getAccountsByRange($balances, $accounts, '4');

        // Get expenses (5xx range)
        $expenses = $this->getAccountsByRange($balances, $accounts, '5');

        // Get assets (1xx range)
        $assets = $this->getAccountsByRange($balances, $accounts, '1');

        // Get liabilities (2xx range)
        $liabilities = $this->getAccountsByRange($balances, $accounts, '2');

        // Get capital accounts (3xx range)
        $capitalAccounts = $this->getAccountsByRange($balances, $accounts, '3');

        // Calculate totals for Laba Rugi
        $totalRev = array_sum(array_map(fn($a) => $a['balance'], $revenues));
        $totalExp = array_sum(array_map(fn($a) => $a['balance'], $expenses));
        $netIncome = $totalRev - $totalExp;

        // Get capital and prive balances
        $capitalValues = array_values($capitalAccounts);
        $initialCap = 0;
        $prive = 0;

        if (count($capitalValues) > 0) {
            $initialCap = $capitalValues[0]['balance'] ?? 0;
        }
        if (count($capitalValues) > 1) {
            $prive = $capitalValues[1]['balance'] ?? 0;
        }

        $capIncrease = $netIncome - $prive;
        $finalCap = $initialCap + $capIncrease;

        // Calculate asset totals
        $assetAccounts = array_values($assets);
        $assetTotals = $this->calculateAssetTotals($assetAccounts);

        // Calculate liability totals
        $totalLiabilities = array_sum(array_map(fn($a) => $a['balance'], $liabilities));
        $totalPassives = $totalLiabilities + $finalCap;

        return view('akuntansi.laporan', compact(
            'revenues', 'expenses', 'totalRev', 'totalExp', 'netIncome',
            'initialCap', 'prive', 'capIncrease', 'finalCap',
            'assets', 'assetTotals', 'liabilities',
            'totalLiabilities', 'totalPassives',
            'startDate', 'endDate'
        ));
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $transactions = $this->service->getTransactions();
        $accounts     = $this->service->getAccountsConfig();

        // Filter transactions by date range if provided
        if ($startDate || $endDate) {
            $transactions = $this->filterTransactionsByDateRange($transactions, $startDate, $endDate);
        }

        // Use calculateLedgers to get pre-adjusted balances
        $ledgers = $this->service->calculateLedgers($transactions);
        $balances = $this->extractBalancesFromLedgers($ledgers);

        // Get revenues (4xx range)
        $revenues = $this->getAccountsByRange($balances, $accounts, '4');

        // Get expenses (5xx range)
        $expenses = $this->getAccountsByRange($balances, $accounts, '5');

        // Get assets (1xx range)
        $assets = $this->getAccountsByRange($balances, $accounts, '1');

        // Get liabilities (2xx range)
        $liabilities = $this->getAccountsByRange($balances, $accounts, '2');

        // Get capital accounts (3xx range)
        $capitalAccounts = $this->getAccountsByRange($balances, $accounts, '3');

        // Calculate totals for Laba Rugi
        $totalRev = array_sum(array_map(fn($a) => $a['balance'], $revenues));
        $totalExp = array_sum(array_map(fn($a) => $a['balance'], $expenses));
        $netIncome = $totalRev - $totalExp;

        // Get capital and prive balances
        $capitalValues = array_values($capitalAccounts);
        $initialCap = 0;
        $prive = 0;

        if (count($capitalValues) > 0) {
            $initialCap = $capitalValues[0]['balance'] ?? 0;
        }
        if (count($capitalValues) > 1) {
            $prive = $capitalValues[1]['balance'] ?? 0;
        }

        $capIncrease = $netIncome - $prive;
        $finalCap = $initialCap + $capIncrease;

        // Calculate asset totals
        $assetAccounts = array_values($assets);
        $assetTotals = $this->calculateAssetTotals($assetAccounts);

        // Calculate liability totals
        $totalLiabilities = array_sum(array_map(fn($a) => $a['balance'], $liabilities));
        $totalPassives = $totalLiabilities + $finalCap;

        return view('akuntansi.laporan-print', compact(
            'revenues', 'expenses', 'totalRev', 'totalExp', 'netIncome',
            'initialCap', 'prive', 'capIncrease', 'finalCap',
            'assets', 'assetTotals', 'liabilities',
            'totalLiabilities', 'totalPassives',
            'startDate', 'endDate'
        ));
    }

    private function extractBalancesFromLedgers(array $ledgers): array
    {
        $balances = [];
        foreach ($ledgers as $code => $entries) {
            $finalBalance = empty($entries) ? 0 : end($entries)['balance'];
            $balances[$code] = ['balance' => $finalBalance];
        }
        return $balances;
    }

    private function getAccountsByRange(array $balances, array $accounts, string $prefix): array
    {
        $filtered = [];

        foreach ($accounts as $code => $config) {
            if (str_starts_with((string) $code, $prefix) && isset($balances[$code])) {
                $filtered[$code] = array_merge($config, $balances[$code]);
            }
        }

        ksort($filtered);
        return $filtered;
    }

    private function calculateAssetTotals(array $assets): array
    {
        $totalAssets = 0;
        $accumulatedDepreciation = 0;

        foreach ($assets as $code => $asset) {
            if (stripos($asset['name'] ?? '', 'akum') !== false || stripos($asset['name'] ?? '', 'penyusutan') !== false) {
                $accumulatedDepreciation += abs($asset['balance'] ?? 0);
            } else {
                $totalAssets += $asset['balance'] ?? 0;
            }
        }

        return [
            'total' => $totalAssets - $accumulatedDepreciation,
            'gross' => $totalAssets,
            'accumulated' => $accumulatedDepreciation,
        ];
    }

    private function filterTransactionsByDateRange(array $transactions, ?string $startDate, ?string $endDate): array
    {
        return array_filter($transactions, function ($transaction) use ($startDate, $endDate) {
            if (empty($transaction['date'])) return true;

            try {
                $txnDate = \Carbon\Carbon::createFromFormat('d M', $transaction['date'])->format('m-d');
            } catch (\Exception) {
                return true;
            }

            if ($startDate) {
                $start = \Carbon\Carbon::parse($startDate)->format('m-d');
                if ($txnDate < $start) return false;
            }

            if ($endDate) {
                $end = \Carbon\Carbon::parse($endDate)->format('m-d');
                if ($txnDate > $end) return false;
            }

            return true;
        });
    }
}
