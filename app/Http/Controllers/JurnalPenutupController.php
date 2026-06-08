<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\DetailJurnal;
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

        $isGenerated = Jurnal::where('keterangan', 'like', 'Jurnal Penutup%')->exists();

        return view('akuntansi.jurnal-penutup', compact(
            'accounts', 'revJasa', 'revIklan', 'totalRev',
            'expGaji', 'expSewa', 'expIklan', 'expAsuransi', 'expPerlengkapan', 'expPenyusutan',
            'totalExp', 'netIncome', 'prive', 'totalDebit', 'isGenerated'
        ));
    }

    public function generate()
    {
        $transactions       = $this->service->getTransactions();
        $adjustmentsEnabled = $this->service->isAdjustmentsEnabled();
        $adj                = $this->service->getAdjustedBalances($transactions, $adjustmentsEnabled);

        $revJasa         = (int) $adj['411']['adjustedBalance'];
        $revIklan        = (int) $adj['412']['adjustedBalance'];
        $totalRev        = $revJasa + $revIklan;
        $expGaji         = (int) $adj['511']['adjustedBalance'];
        $expSewa         = (int) $adj['512']['adjustedBalance'];
        $expIklan        = (int) $adj['513']['adjustedBalance'];
        $expAsuransi     = (int) $adj['514']['adjustedBalance'];
        $expPerlengkapan = (int) $adj['515']['adjustedBalance'];
        $expPenyusutan   = (int) $adj['516']['adjustedBalance'];
        $totalExp        = $expGaji + $expSewa + $expIklan + $expAsuransi + $expPerlengkapan + $expPenyusutan;
        $netIncome       = $totalRev - $totalExp;
        $prive           = (int) $adj['312']['adjustedBalance'];

        DB::transaction(function () use ($revJasa, $revIklan, $totalRev, $expGaji, $expSewa, $expIklan, $expAsuransi, $expPerlengkapan, $expPenyusutan, $totalExp, $netIncome, $prive) {
            // 1. Tutup Pendapatan ke Ikhtisar Laba Rugi
            $j1 = Jurnal::create([
                'tanggal'    => now()->format('Y-m-d'),
                'keterangan' => 'Jurnal Penutup - Pendapatan',
                'is_static'  => false,
            ]);

            DetailJurnal::create(['jurnal_id' => $j1->id, 'akun_kode' => '411', 'type' => 'debit', 'jumlah' => $revJasa]);
            DetailJurnal::create(['jurnal_id' => $j1->id, 'akun_kode' => '412', 'type' => 'debit', 'jumlah' => $revIklan]);
            DetailJurnal::create(['jurnal_id' => $j1->id, 'akun_kode' => '313', 'type' => 'credit', 'jumlah' => $totalRev]);

            // 2. Tutup Beban ke Ikhtisar Laba Rugi
            $j2 = Jurnal::create([
                'tanggal'    => now()->format('Y-m-d'),
                'keterangan' => 'Jurnal Penutup - Beban',
                'is_static'  => false,
            ]);

            DetailJurnal::create(['jurnal_id' => $j2->id, 'akun_kode' => '313', 'type' => 'debit', 'jumlah' => $totalExp]);
            DetailJurnal::create(['jurnal_id' => $j2->id, 'akun_kode' => '511', 'type' => 'credit', 'jumlah' => $expGaji]);
            DetailJurnal::create(['jurnal_id' => $j2->id, 'akun_kode' => '512', 'type' => 'credit', 'jumlah' => $expSewa]);
            DetailJurnal::create(['jurnal_id' => $j2->id, 'akun_kode' => '513', 'type' => 'credit', 'jumlah' => $expIklan]);
            DetailJurnal::create(['jurnal_id' => $j2->id, 'akun_kode' => '514', 'type' => 'credit', 'jumlah' => $expAsuransi]);
            DetailJurnal::create(['jurnal_id' => $j2->id, 'akun_kode' => '515', 'type' => 'credit', 'jumlah' => $expPerlengkapan]);
            DetailJurnal::create(['jurnal_id' => $j2->id, 'akun_kode' => '516', 'type' => 'credit', 'jumlah' => $expPenyusutan]);

            // 3. Tutup Ikhtisar Laba Rugi ke Modal
            $j3 = Jurnal::create([
                'tanggal'    => now()->format('Y-m-d'),
                'keterangan' => 'Jurnal Penutup - Ikhtisar Laba Rugi ke Modal',
                'is_static'  => false,
            ]);

            DetailJurnal::create(['jurnal_id' => $j3->id, 'akun_kode' => '313', 'type' => 'debit', 'jumlah' => abs($netIncome)]);
            DetailJurnal::create(['jurnal_id' => $j3->id, 'akun_kode' => '311', 'type' => $netIncome >= 0 ? 'credit' : 'debit', 'jumlah' => abs($netIncome)]);

            // 4. Tutup Prive ke Modal
            $j4 = Jurnal::create([
                'tanggal'    => now()->format('Y-m-d'),
                'keterangan' => 'Jurnal Penutup - Prive',
                'is_static'  => false,
            ]);

            DetailJurnal::create(['jurnal_id' => $j4->id, 'akun_kode' => '311', 'type' => 'debit', 'jumlah' => abs($prive)]);
            DetailJurnal::create(['jurnal_id' => $j4->id, 'akun_kode' => '312', 'type' => 'credit', 'jumlah' => abs($prive)]);
        });

        return redirect()->route('akuntansi.penutup')
            ->with('success', 'Jurnal Penutup berhasil dibuat dan diposting!');
    }
}
