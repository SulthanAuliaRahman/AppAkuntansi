<?php

namespace App\Http\Controllers;

use App\Services\AkuntansiService;
use Illuminate\Http\Request;

class BukuBesarController extends Controller
{
    private const ACCOUNTS_PER_PAGE = 3;

    public function __construct(private AkuntansiService $service) {}

    public function index(Request $request)
    {
        $viewMode     = $request->input('view', 'all'); // 'all' atau 'single'
        $page         = max(1, (int) $request->input('page', 1));
        $selectedCode = $request->input('akun');
        $startDate    = $request->input('start_date');
        $endDate      = $request->input('end_date');

        $accounts     = $this->service->getAccountsConfig();
        $transactions = $this->service->getTransactions();
        $ledgers      = $this->service->calculateLedgers($transactions);

        // Get accounts that have EITHER saldo awal OR transactions
        $accountsWithData = array_filter(array_keys($ledgers), function ($code) use ($ledgers) {
            $entries = $ledgers[$code];
            if (empty($entries)) {
                return false;
            }

            // Check if has opening balance (first entry should always be opening balance)
            $firstEntry = $entries[0];
            $hasOpeningBalance = ($firstEntry['debit'] > 0 || $firstEntry['credit'] > 0);

            // Check if has transactions (more than just opening balance)
            $hasTransactions = count($entries) > 1;

            // Include if has opening balance OR has transactions
            return $hasOpeningBalance || $hasTransactions;
        });

        $accountsWithData = array_values($accountsWithData);
        usort($accountsWithData, function($a, $b) {
            return strcmp($a, $b);
        });

        // Single view mode
        if ($viewMode === 'single') {
            $selectedConfig = null;
            $entries        = [];
            $summary        = ['totalDebit' => 0, 'totalCredit' => 0, 'finalBalance' => 0];

            // Only populate data if valid account is selected and has transactions
            if ($selectedCode && in_array($selectedCode, $accountsWithData)) {
                $entries        = $ledgers[$selectedCode] ?? [];
                $selectedConfig = $accounts[$selectedCode];

                if ($startDate || $endDate) {
                    $entries = $this->filterEntriesByDateRange($entries, $startDate, $endDate);
                }

                $summary = $this->calculateSummary($entries);
            }

            return view('akuntansi.buku-besar-single', compact(
                'accounts', 'accountsWithData', 'selectedCode', 'selectedConfig', 'entries', 'summary', 'startDate', 'endDate'
            ));
        }

        // View all accounts with transactions and pagination
        $totalPages    = ceil(count($accountsWithData) / self::ACCOUNTS_PER_PAGE);
        $page          = min($page, max(1, $totalPages));

        $offset        = ($page - 1) * self::ACCOUNTS_PER_PAGE;
        $pagedCodes    = array_slice($accountsWithData, $offset, self::ACCOUNTS_PER_PAGE);
        $pagedAccounts = [];

        foreach ($pagedCodes as $code) {
            $entries = $ledgers[$code] ?? [];

            if ($startDate || $endDate) {
                $entries = $this->filterEntriesByDateRange($entries, $startDate, $endDate);
            }

            $pagedAccounts[$code] = [
                'config'  => $accounts[$code],
                'entries' => $entries,
                'summary' => $this->calculateSummary($entries),
            ];
        }

        return view('akuntansi.buku-besar', compact(
            'accounts', 'accountsWithData', 'pagedAccounts', 'page', 'totalPages', 'startDate', 'endDate', 'viewMode'
        ));
    }

    private function filterEntriesByDateRange(array $entries, ?string $startDate, ?string $endDate): array
    {
        return array_filter($entries, function ($entry) use ($startDate, $endDate) {
            if (empty($entry['date'])) return true;

            try {
                $entryDate = \Carbon\Carbon::createFromFormat('d M', $entry['date'])->format('m-d');
            } catch (\Exception) {
                return true;
            }

            if ($startDate) {
                $start = \Carbon\Carbon::parse($startDate)->format('m-d');
                if ($entryDate < $start) return false;
            }

            if ($endDate) {
                $end = \Carbon\Carbon::parse($endDate)->format('m-d');
                if ($entryDate > $end) return false;
            }

            return true;
        });
    }

    private function calculateSummary(array $entries): array
    {
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($entries as $entry) {
            $totalDebit += $entry['debit'] ?? 0;
            $totalCredit += $entry['credit'] ?? 0;
        }

        $finalBalance = empty($entries) ? 0 : end($entries)['balance'];

        return [
            'totalDebit'   => $totalDebit,
            'totalCredit'  => $totalCredit,
            'finalBalance' => $finalBalance,
        ];
    }
}
