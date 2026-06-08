@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-slate-800">6. Laporan Keuangan (Financial Statements)</h2>
                <p class="text-sm text-slate-500">Laporan Keuangan formal Perusahaan Jasa "Anugerah Sakti"</p>
            </div>
            <form method="GET" class="flex gap-2">
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">Filter</button>
                <a href="{{ route('akuntansi.laporan') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-sm hover:bg-slate-50">Reset</a>
                <a href="{{ route('akuntansi.laporan.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="px-4 py-2 bg-indigo-700 text-white rounded-lg text-sm hover:bg-emerald-700 flex items-center gap-2" target="_blank">
                    <i class="fas fa-print"></i> Print
                </a>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Laporan Laba Rugi -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3 text-center">
                    <span class="text-xs text-indigo-600 font-semibold tracking-widest uppercase">Perusahaan Jasa "Anugerah Sakti"</span>
                    <h3 class="text-base font-bold text-slate-800 mt-1">Laporan Laba Rugi</h3>
                    <p class="text-[11px] text-slate-400">
                        Untuk Periode yang Berakhir
                        @if($endDate)
                            {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                        @else
                            30 April 2008
                        @endif
                    </p>
                </div>
                <div class="space-y-4 text-xs">
                    <div class="space-y-2">
                        <h4 class="font-bold text-slate-700 uppercase tracking-wider text-[10px]">Pendapatan</h4>
                        @forelse($revenues as $code => $revenue)
                            <div class="flex justify-between pl-3">
                                <span>{{ $revenue['name'] ?? 'Akun ' . $code }}</span>
                                <span>@rupiah($revenue['balance'])</span>
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
                        @forelse($expenses as $code => $expense)
                            <div class="flex justify-between pl-3">
                                <span>{{ $expense['name'] ?? 'Akun ' . $code }}</span>
                                <span>@rupiah($expense['balance'])</span>
                            </div>
                        @empty
                            <div class="text-slate-400 italic pl-3">Tidak ada beban</div>
                        @endforelse
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
                        <p class="text-[11px] text-slate-400">
                            Untuk Periode yang Berakhir
                            @if($endDate)
                                {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                            @else
                                30 April 2008
                            @endif
                        </p>
                    </div>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between"><span>Modal Awal</span><span>@rupiah($initialCap)</span></div>
                        <div class="flex justify-between text-emerald-600"><span>(+) Laba Bersih</span><span>@rupiah($netIncome)</span></div>
                        <div class="flex justify-between text-rose-600"><span>(-) Prive (Pengambilan Pribadi)</span><span>@rupiah($prive)</span></div>
                        <div class="flex justify-between font-bold border-t border-slate-100 pt-2 text-slate-800">
                            <span>Kenaikan Neto Modal Pemilik</span><span>@rupiah($capIncrease)</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center bg-indigo-50 text-indigo-900 p-3 rounded-xl font-bold text-sm border border-indigo-100/50 mt-4">
                    <span>MODAL PEMILIK (AKHIR)</span>
                    <span class="text-base text-indigo-700">@rupiah($finalCap)</span>
                </div>
            </div>
        </div>

        <!-- Neraca -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-3 text-center">
                <span class="text-xs text-indigo-600 font-semibold tracking-widest uppercase">Perusahaan Jasa "Anugerah Sakti"</span>
                <h3 class="text-lg font-bold text-slate-800 mt-1">Neraca (Balance Sheet)</h3>
                <p class="text-[11px] text-slate-400">
                    Per
                    @if($endDate)
                        {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                    @else
                        30 April 2008
                    @endif
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-xs">
                <!-- AKTIVA -->
                <div class="space-y-4">
                    <div>
                        <h4 class="font-bold text-indigo-700 uppercase tracking-wider text-[11px] mb-2.5">Aktiva</h4>
                        <div class="space-y-2 pl-2">
                            @forelse($assets as $code => $asset)
                                @if(stripos($asset['name'] ?? '', 'akum') === false)
                                    <div class="flex justify-between"><span>{{ $asset['name'] ?? 'Akun ' . $code }}</span><span>@rupiah($asset['balance'])</span></div>
                                @endif
                            @empty
                                <div class="text-slate-400 italic">Tidak ada aktiva</div>
                            @endforelse
                            @foreach($assets as $code => $asset)
                                @if(stripos($asset['name'] ?? '', 'akum') !== false)
                                    <div class="flex justify-between text-slate-400 italic">
                                        <span>{{ $asset['name'] ?? 'Akun ' . $code }}</span>
                                        <span>(Rp {{ number_format(abs($asset['balance']), 0, ',', '.') }})</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-between items-center bg-slate-100 p-3 rounded-xl font-bold text-slate-800 text-sm">
                        <span>TOTAL AKTIVA</span><span>@rupiah($assetTotals['total'])</span>
                    </div>
                </div>

                <!-- PASIVA -->
                <div class="space-y-4 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-bold text-indigo-700 uppercase tracking-wider text-[11px] mb-2.5">Kewajiban</h4>
                            <div class="space-y-2 pl-2">
                                @forelse($liabilities as $code => $liability)
                                    <div class="flex justify-between"><span>{{ $liability['name'] ?? 'Akun ' . $code }}</span><span>@rupiah($liability['balance'])</span></div>
                                @empty
                                    <div class="text-slate-400 italic">Tidak ada kewajiban</div>
                                @endforelse
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold text-indigo-700 uppercase tracking-wider text-[11px] mb-2.5">Modal Pemilik</h4>
                            <div class="pl-2">
                                <div class="flex justify-between"><span>Modal Akhir</span><span>@rupiah($finalCap)</span></div>
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
