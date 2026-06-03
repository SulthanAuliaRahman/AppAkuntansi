@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">6. Laporan Keuangan (Financial Statements)</h2>
            <p class="text-sm text-slate-500">Laporan Keuangan formal Perusahaan Jasa "Anugerah Sakti" per 30 April 2008</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Laporan Laba Rugi -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3 text-center">
                    <span class="text-xs text-indigo-600 font-semibold tracking-widest uppercase">Perusahaan Jasa "Anugerah Sakti"</span>
                    <h3 class="text-base font-bold text-slate-800 mt-1">Laporan Laba Rugi</h3>
                    <p class="text-[11px] text-slate-400">Untuk Periode yang Berakhir 30 April 2008</p>
                </div>
                <div class="space-y-4 text-xs">
                    <div class="space-y-2">
                        <h4 class="font-bold text-slate-700 uppercase tracking-wider text-[10px]">Pendapatan</h4>
                        <div class="flex justify-between pl-3"><span>Pendapatan Jasa</span><span>@rupiah($revJasa)</span></div>
                        <div class="flex justify-between pl-3"><span>Pendapatan Iklan</span><span>@rupiah($revIklan)</span></div>
                        <div class="flex justify-between font-bold border-t border-slate-100 pt-2 text-slate-800">
                            <span>Total Pendapatan</span><span>@rupiah($totalRev)</span>
                        </div>
                    </div>
                    <div class="space-y-2 pt-2">
                        <h4 class="font-bold text-slate-700 uppercase tracking-wider text-[10px]">Beban-Beban Operasional</h4>
                        <div class="flex justify-between pl-3"><span>Beban Gaji</span><span>@rupiah($expGaji)</span></div>
                        <div class="flex justify-between pl-3"><span>Beban Sewa</span><span>@rupiah($expSewa)</span></div>
                        <div class="flex justify-between pl-3"><span>Beban Iklan</span><span>@rupiah($expIklan)</span></div>
                        <div class="flex justify-between pl-3"><span>Beban Asuransi</span><span>@rupiah($expAsuransi)</span></div>
                        <div class="flex justify-between pl-3"><span>Beban Perlengkapan</span><span>@rupiah($expPerlengkapan)</span></div>
                        <div class="flex justify-between pl-3"><span>Beban Penyusutan Peralatan</span><span>@rupiah($expPenyusutan)</span></div>
                        <div class="flex justify-between font-bold border-t border-slate-100 pt-2 text-slate-800">
                            <span>Total Beban Operasional</span><span>@rupiah($totalExp)</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center bg-indigo-50 text-indigo-900 p-3 rounded-xl font-bold text-sm border border-indigo-100/50">
                        <span>LABA BERSIH (NET INCOME)</span>
                        <span class="text-base text-indigo-700">@rupiah($netIncome)</span>
                    </div>
                </div>
            </div>

            <!-- Laporan Perubahan Modal -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="border-b border-slate-100 pb-3 text-center mb-4">
                        <span class="text-xs text-indigo-600 font-semibold tracking-widest uppercase">Perusahaan Jasa "Anugerah Sakti"</span>
                        <h3 class="text-base font-bold text-slate-800 mt-1">Laporan Perubahan Modal</h3>
                        <p class="text-[11px] text-slate-400">Untuk Periode yang Berakhir 30 April 2008</p>
                    </div>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between"><span>Modal Tuan Sakti (1 April 2008)</span><span>@rupiah($initialCap)</span></div>
                        <div class="flex justify-between text-emerald-600"><span>(+) Laba Bersih April 2008</span><span>@rupiah($netIncome)</span></div>
                        <div class="flex justify-between text-rose-600"><span>(-) Prive Tuan Sakti (Pengambilan Pribadi)</span><span>@rupiah($prive)</span></div>
                        <div class="flex justify-between font-bold border-t border-slate-100 pt-2 text-slate-800">
                            <span>Kenaikan Neto Modal Pemilik</span><span>@rupiah($capIncrease)</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center bg-indigo-50 text-indigo-900 p-3 rounded-xl font-bold text-sm border border-indigo-100/50 mt-4">
                    <span>MODAL TUAN SAKTI (30 April 2008)</span>
                    <span class="text-base text-indigo-700">@rupiah($finalCap)</span>
                </div>
            </div>
        </div>

        <!-- Neraca -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-3 text-center">
                <span class="text-xs text-indigo-600 font-semibold tracking-widest uppercase">Perusahaan Jasa "Anugerah Sakti"</span>
                <h3 class="text-lg font-bold text-slate-800 mt-1">Neraca (Balance Sheet)</h3>
                <p class="text-[11px] text-slate-400">Per 30 April 2008</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-xs">
                <div class="space-y-4">
                    <div>
                        <h4 class="font-bold text-indigo-700 uppercase tracking-wider text-[11px] mb-2.5">Aktiva Lancar</h4>
                        <div class="space-y-2 pl-2">
                            <div class="flex justify-between"><span>Kas</span><span>@rupiah($kas)</span></div>
                            <div class="flex justify-between"><span>Piutang Dagang</span><span>@rupiah($piutang)</span></div>
                            <div class="flex justify-between"><span>Perlengkapan Kantor</span><span>@rupiah($perlengkapan)</span></div>
                            <div class="flex justify-between"><span>Sewa Dibayar Dimuka</span><span>@rupiah($sewa)</span></div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-indigo-700 uppercase tracking-wider text-[11px] mb-2.5">Aktiva Tetap</h4>
                        <div class="space-y-2 pl-2">
                            <div class="flex justify-between"><span>Peralatan Kantor</span><span>@rupiah($peralatan)</span></div>
                            <div class="flex justify-between text-slate-400 italic">
                                <span>(-) Akumulasi Penyusutan Peralatan</span>
                                <span>(Rp {{ number_format($akumPeny, 0, ',', '.') }})</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center bg-slate-100 p-3 rounded-xl font-bold text-slate-800 text-sm">
                        <span>TOTAL AKTIVA</span><span>@rupiah($totalAssets)</span>
                    </div>
                </div>
                <div class="space-y-4 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-bold text-indigo-700 uppercase tracking-wider text-[11px] mb-2.5">Kewajiban Jangka Pendek</h4>
                            <div class="space-y-2 pl-2">
                                <div class="flex justify-between"><span>Hutang Dagang</span><span>@rupiah($hutang)</span></div>
                                <div class="flex justify-between"><span>Hutang Gaji</span><span>@rupiah($hutangGaji)</span></div>
                                <div class="flex justify-between"><span>Iklan Diterima Dimuka</span><span>@rupiah($iklanDimuka)</span></div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold text-indigo-700 uppercase tracking-wider text-[11px] mb-2.5">Modal Pemilik</h4>
                            <div class="pl-2">
                                <div class="flex justify-between"><span>Modal Tuan Sakti (Akhir)</span><span>@rupiah($finalCap)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center bg-indigo-100 text-indigo-900 p-3 rounded-xl font-bold text-sm border border-indigo-200">
                        <span>TOTAL PASIVA</span><span>@rupiah($totalPassives)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
