<?php

namespace App\Http\Controllers;

use App\Services\AkuntansiService;
use Dompdf\Dompdf;
use Dompdf\Options;
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
        $startDate    = $request->input('start_date', session('global_start_date'));
        $endDate      = $request->input('end_date', session('global_end_date'));

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

    public function exportPdf(Request $request)
    {
        $html = view('akuntansi.exports.buku-besar', $this->getExportData($request))->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);

        $pdf = new Dompdf($options);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="buku-besar.pdf"',
        ]);
    }

    public function exportExcel(Request $request)
    {
        $html = view('akuntansi.exports.buku-besar', $this->getExportData($request))->render();

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="buku-besar.xls"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    private function getExportData(Request $request): array
    {
        $accounts     = $this->service->getAccountsConfig();
        $transactions = $this->service->getTransactions();
        $ledgers      = $this->service->calculateLedgers($transactions);
        $startDate    = $request->input('start_date');
        $endDate      = $request->input('end_date');

        $codes = array_filter(array_keys($ledgers), function ($code) use ($ledgers) {
            $entries = $ledgers[$code];
            if (empty($entries)) return false;
            $first = $entries[0];
            return ($first['debit'] > 0 || $first['credit'] > 0) || count($entries) > 1;
        });

        $codes = array_values($codes);
        usort($codes, fn($a, $b) => strcmp($a, $b));

        $allAccounts = [];
        foreach ($codes as $code) {
            $entries = $ledgers[$code] ?? [];
            if ($startDate || $endDate) {
                $entries = $this->filterEntriesByDateRange($entries, $startDate, $endDate);
            }
            $allAccounts[$code] = [
                'config'  => $accounts[$code],
                'entries' => $entries,
                'summary' => $this->calculateSummary($entries),
            ];
        }

        return compact('allAccounts', 'startDate', 'endDate');
    }

    private function filterEntriesByDateRange(array $entries, ?string $startDate, ?string $endDate): array
    {
        return array_filter($entries, function ($entry) use ($startDate, $endDate) {
            $rawDate = $entry['rawDate'] ?? null;
            if (!$rawDate) return true;

            $entryDate = \Carbon\Carbon::parse($rawDate)->format('Y-m-d');

            if ($startDate && $entryDate < $startDate) return false;
            if ($endDate   && $entryDate > $endDate)   return false;

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
