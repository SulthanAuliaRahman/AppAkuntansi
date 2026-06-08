<?php

namespace App\Http\Controllers;

use App\Models\JurnalPenutup;
use App\Services\AkuntansiService;
use Illuminate\Support\Facades\DB;

class JurnalPenutupController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        $accounts           = $this->service->getAccountsConfig();
        $transactions       = $this->service->getTransactions();
        $adjustmentsEnabled = $this->service->isAdjustmentsEnabled();
        $adj                = $this->service->getAdjustedBalances($transactions, $adjustmentsEnabled);

        // Akun Pendapatan (classCode '4.x') dengan saldo > 0
        $pendapatanAccounts = [];
        foreach ($accounts as $code => $config) {
            if (str_starts_with($config['classCode'], '4') && isset($adj[$code])) {
                $balance = (int) $adj[$code]['adjustedBalance'];
                if ($balance > 0) {
                    $pendapatanAccounts[$code] = ['name' => $config['name'], 'balance' => $balance];
                }
            }
        }

        // Akun Beban (classCode '5.x') dengan saldo > 0
        $bebanAccounts = [];
        foreach ($accounts as $code => $config) {
            if (str_starts_with($config['classCode'], '5') && isset($adj[$code])) {
                $balance = (int) $adj[$code]['adjustedBalance'];
                if ($balance > 0) {
                    $bebanAccounts[$code] = ['name' => $config['name'], 'balance' => $balance];
                }
            }
        }

        // Akun Modal (classCode '3.x', saldo normal KREDIT)
        $modalCode = null;
        $modalName = 'Modal';
        foreach ($accounts as $code => $config) {
            if (str_starts_with($config['classCode'], '3') && $config['normal'] === 'credit') {
                $modalCode = $code;
                $modalName = $config['name'];
                break;
            }
        }

        // Akun Prive (classCode '3.x', saldo normal DEBET) dengan saldo > 0
        $priveAccounts = [];
        foreach ($accounts as $code => $config) {
            if (str_starts_with($config['classCode'], '3') && $config['normal'] === 'debit' && isset($adj[$code])) {
                $balance = (int) $adj[$code]['adjustedBalance'];
                if ($balance > 0) {
                    $priveAccounts[$code] = ['name' => $config['name'], 'balance' => $balance];
                }
            }
        }

        $totalRev   = array_sum(array_column($pendapatanAccounts, 'balance'));
        $totalExp   = array_sum(array_column($bebanAccounts, 'balance'));
        $netIncome  = $totalRev - $totalExp;
        $totalPrive = array_sum(array_column($priveAccounts, 'balance'));
        $totalDebit = $totalRev + $totalExp + abs($netIncome) + $totalPrive;

        $isGenerated    = JurnalPenutup::exists();
        $savedEntries   = $isGenerated ? JurnalPenutup::orderBy('langkah')->orderBy('id')->get()->groupBy('langkah') : collect();

        return view('akuntansi.jurnal-penutup', compact(
            'pendapatanAccounts', 'bebanAccounts', 'priveAccounts',
            'modalCode', 'modalName',
            'totalRev', 'totalExp', 'netIncome', 'totalPrive', 'totalDebit',
            'isGenerated', 'savedEntries'
        ));
    }

    public function generate()
    {
        $accounts           = $this->service->getAccountsConfig();
        $transactions       = $this->service->getTransactions();
        $adjustmentsEnabled = $this->service->isAdjustmentsEnabled();
        $adj                = $this->service->getAdjustedBalances($transactions, $adjustmentsEnabled);

        $pendapatanAccounts = [];
        foreach ($accounts as $code => $config) {
            if (str_starts_with($config['classCode'], '4') && isset($adj[$code])) {
                $balance = (int) $adj[$code]['adjustedBalance'];
                if ($balance > 0) $pendapatanAccounts[$code] = $balance;
            }
        }

        $bebanAccounts = [];
        foreach ($accounts as $code => $config) {
            if (str_starts_with($config['classCode'], '5') && isset($adj[$code])) {
                $balance = (int) $adj[$code]['adjustedBalance'];
                if ($balance > 0) $bebanAccounts[$code] = $balance;
            }
        }

        $modalCode = null;
        foreach ($accounts as $code => $config) {
            if (str_starts_with($config['classCode'], '3') && $config['normal'] === 'credit') {
                $modalCode = $code;
                break;
            }
        }

        $priveAccounts = [];
        foreach ($accounts as $code => $config) {
            if (str_starts_with($config['classCode'], '3') && $config['normal'] === 'debit' && isset($adj[$code])) {
                $balance = (int) $adj[$code]['adjustedBalance'];
                if ($balance > 0) $priveAccounts[$code] = $balance;
            }
        }

        $totalRev  = array_sum($pendapatanAccounts);
        $totalExp  = array_sum($bebanAccounts);
        $netIncome = $totalRev - $totalExp;

        $modalName = $accounts[$modalCode]['name'] ?? 'Modal';

        DB::transaction(function () use ($accounts, $pendapatanAccounts, $bebanAccounts, $priveAccounts, $modalCode, $modalName, $totalRev, $totalExp, $netIncome) {
            JurnalPenutup::query()->delete();

            // Langkah 1: Tutup Pendapatan ke Ikhtisar L/R
            foreach ($pendapatanAccounts as $code => $balance) {
                JurnalPenutup::create(['langkah' => 1, 'kode_akun' => $code, 'nama_akun' => $accounts[$code]['name'], 'posisi' => 'debet', 'jumlah' => $balance]);
            }
            JurnalPenutup::create(['langkah' => 1, 'kode_akun' => '313', 'nama_akun' => 'Ikhtisar Laba Rugi', 'posisi' => 'kredit', 'jumlah' => $totalRev]);

            // Langkah 2: Tutup Beban ke Ikhtisar L/R
            JurnalPenutup::create(['langkah' => 2, 'kode_akun' => '313', 'nama_akun' => 'Ikhtisar Laba Rugi', 'posisi' => 'debet', 'jumlah' => $totalExp]);
            foreach ($bebanAccounts as $code => $balance) {
                JurnalPenutup::create(['langkah' => 2, 'kode_akun' => $code, 'nama_akun' => $accounts[$code]['name'], 'posisi' => 'kredit', 'jumlah' => $balance]);
            }

            // Langkah 3: Tutup Ikhtisar L/R ke Modal
            if ($netIncome >= 0) {
                JurnalPenutup::create(['langkah' => 3, 'kode_akun' => '313', 'nama_akun' => 'Ikhtisar Laba Rugi', 'posisi' => 'debet', 'jumlah' => $netIncome]);
                JurnalPenutup::create(['langkah' => 3, 'kode_akun' => $modalCode, 'nama_akun' => $modalName, 'posisi' => 'kredit', 'jumlah' => $netIncome]);
            } else {
                JurnalPenutup::create(['langkah' => 3, 'kode_akun' => $modalCode, 'nama_akun' => $modalName, 'posisi' => 'debet', 'jumlah' => abs($netIncome)]);
                JurnalPenutup::create(['langkah' => 3, 'kode_akun' => '313', 'nama_akun' => 'Ikhtisar Laba Rugi', 'posisi' => 'kredit', 'jumlah' => abs($netIncome)]);
            }

            // Langkah 4: Tutup Prive ke Modal
            foreach ($priveAccounts as $code => $balance) {
                JurnalPenutup::create(['langkah' => 4, 'kode_akun' => $modalCode, 'nama_akun' => $modalName, 'posisi' => 'debet', 'jumlah' => $balance]);
                JurnalPenutup::create(['langkah' => 4, 'kode_akun' => $code, 'nama_akun' => $accounts[$code]['name'], 'posisi' => 'kredit', 'jumlah' => $balance]);
            }
        });

        return redirect()->route('akuntansi.penutup')
            ->with('success', 'Jurnal Penutup berhasil di-generate!');
    }
}
