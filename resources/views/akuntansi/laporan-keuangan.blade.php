@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">6B. Laporan Keuangan Setelah Penyesuaian</h2>
            <p class="text-sm text-slate-500">Disusun dari kolom Laba Rugi dan Neraca pada Kertas Kerja</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3 text-center">
                    <span class="text-xs text-indigo-600 font-semibold tracking-widest uppercase">Perusahaan Jasa "Anugerah Sakti"</span>
                    <h3 class="text-base font-bold text-slate-800 mt-1">Laporan Laba Rugi</h3>
                    <p class="text-[11px] text-slate-400">Untuk Periode yang Berakhir 30 April 2008</p>
                </div>
                <div class="space-y-4 text-xs">
                    <div class="space-y-2">
                        <h4 class="font-bold text-slate-700 uppercase tracking-wider text-[10px]">Pendapatan</h4>
                        @forelse($revenues as $revenue)
                            <div class="flex justify-between gap-4 pl-3">
                                <span>{{ $revenue['name'] }}</span>
                                <span>@rupiah($revenue['amount'])</span>
                            </div>
                        @empty
                            <div class="text-slate-400 italic pl-3">Tidak ada pendapatan</div>
                        @endforelse
                        <div class="flex justify-between font-bold border-t border-slate-100 pt-2 text-slate-800">
                            <span>Total Pendapatan</span><span>@rupiah($totalRev)</span>
                        </div>
                    </div>
                    <div class="space-y-2 pt-2">
                        <h4 class="font-bold text-slate-700 uppercase tracking-wider text-[10px]">Beban-Beban Operasional</h4>
                        @forelse($expenses as $expense)
                            <div class="flex justify-between gap-4 pl-3">
                                <span>{{ $expense['name'] }}</span>
                                <span>@rupiah($expense['amount'])</span>
                            </div>
                        @empty
                            <div class="text-slate-400 italic pl-3">Tidak ada beban</div>
                        @endforelse
                        <div class="flex justify-between font-bold border-t border-slate-100 pt-2 text-slate-800">
                            <span>Total Beban Operasional</span><span>@rupiah($totalExp)</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center bg-indigo-50 text-indigo-900 p-3 rounded-xl font-bold text-sm border border-indigo-100/50">
                        <span>{{ $netIncome >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}</span>
                        <span class="text-base text-indigo-700">@rupiah(abs($netIncome))</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="border-b border-slate-100 pb-3 text-center mb-4">
                        <span class="text-xs text-indigo-600 font-semibold tracking-widest uppercase">Perusahaan Jasa "Anugerah Sakti"</span>
                        <h3 class="text-base font-bold text-slate-800 mt-1">Laporan Perubahan Modal</h3>
                        <p class="text-[11px] text-slate-400">Untuk Periode yang Berakhir 30 April 2008</p>
                    </div>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between gap-4"><span>Modal Awal</span><span>@rupiah($initialCap)</span></div>
                        <div class="flex justify-between gap-4 {{ $netIncome >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            <span>{{ $netIncome >= 0 ? '(+) Laba Bersih' : '(-) Rugi Bersih' }}</span>
                            <span>@rupiah(abs($netIncome))</span>
                        </div>
                        <div class="flex justify-between gap-4 text-rose-600"><span>(-) Prive</span><span>@rupiah($prive)</span></div>
                        <div class="flex justify-between gap-4 font-bold border-t border-slate-100 pt-2 text-slate-800">
                            <span>Perubahan Neto Modal Pemilik</span><span>@rupiah($capIncrease)</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center bg-indigo-50 text-indigo-900 p-3 rounded-xl font-bold text-sm border border-indigo-100/50 mt-4">
                    <span>MODAL PEMILIK (AKHIR)</span>
                    <span class="text-base text-indigo-700">@rupiah($finalCap)</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-3 text-center">
                <span class="text-xs text-indigo-600 font-semibold tracking-widest uppercase">Perusahaan Jasa "Anugerah Sakti"</span>
                <h3 class="text-lg font-bold text-slate-800 mt-1">Neraca</h3>
                <p class="text-[11px] text-slate-400">Per 30 April 2008</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-xs">
                <div class="space-y-4">
                    <div>
                        <h4 class="font-bold text-indigo-700 uppercase tracking-wider text-[11px] mb-2.5">Aktiva</h4>
                        <div class="space-y-2 pl-2">
                            @forelse($assets as $asset)
                                @php
                                    $isContraAsset = stripos($asset['name'], 'akum') !== false
                                        || stripos($asset['name'], 'penyusutan') !== false
                                        || ($asset['normal'] ?? 'debit') === 'credit';
                                @endphp
                                <div class="flex justify-between gap-4 {{ $isContraAsset ? 'text-slate-400 italic' : '' }}">
                                    <span>{{ $asset['name'] }}</span>
                                    <span>
                                        @if($isContraAsset)
                                            (Rp {{ number_format(abs($asset['amount']), 0, ',', '.') }})
                                        @else
                                            @rupiah($asset['amount'])
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <div class="text-slate-400 italic">Tidak ada aktiva</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="flex justify-between items-center bg-slate-100 p-3 rounded-xl font-bold text-slate-800 text-sm">
                        <span>TOTAL AKTIVA</span><span>@rupiah($assetTotals['total'])</span>
                    </div>
                </div>

                <div class="space-y-4 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-bold text-indigo-700 uppercase tracking-wider text-[11px] mb-2.5">Kewajiban</h4>
                            <div class="space-y-2 pl-2">
                                @forelse($liabilities as $liability)
                                    <div class="flex justify-between gap-4"><span>{{ $liability['name'] }}</span><span>@rupiah($liability['amount'])</span></div>
                                @empty
                                    <div class="text-slate-400 italic">Tidak ada kewajiban</div>
                                @endforelse
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold text-indigo-700 uppercase tracking-wider text-[11px] mb-2.5">Modal Pemilik</h4>
                            <div class="pl-2">
                                <div class="flex justify-between gap-4"><span>Modal Akhir</span><span>@rupiah($finalCap)</span></div>
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
