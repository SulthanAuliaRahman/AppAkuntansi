@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">5. Kertas Kerja / Neraca Lajur (Worksheet 10-Column)</h2>
            <p class="text-sm text-slate-500">Lembar kerja pembantu penyusunan Laporan Keuangan secara komprehensif</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse table-fixed min-w-[1350px]">
                    <thead>
                        <tr class="bg-slate-800 text-white uppercase text-[10px] font-bold tracking-wider border-b border-slate-700 text-center">
                            <th rowspan="2" class="py-3 px-4 text-left w-16">Ref</th>
                            <th rowspan="2" class="py-3 px-4 text-left w-52">Nama Akun Perkiraan</th>
                            <th colspan="2" class="py-2 px-3 border-l border-slate-700">Neraca Saldo</th>
                            <th colspan="2" class="py-2 px-3 border-l border-slate-700">Penyesuaian</th>
                            <th colspan="2" class="py-2 px-3 border-l border-slate-700">NSD</th>
                            <th colspan="2" class="py-2 px-3 border-l border-slate-700">Laba Rugi</th>
                            <th colspan="2" class="py-2 px-3 border-l border-slate-700">Neraca</th>
                        </tr>
                        <tr class="bg-slate-700 text-slate-200 uppercase text-[9px] font-bold tracking-wider text-center">
                            <th class="py-2 px-2 border-l border-slate-600">D</th><th class="py-2 px-2 border-r border-slate-600">K</th>
                            <th class="py-2 px-2">D</th><th class="py-2 px-2 border-r border-slate-600">K</th>
                            <th class="py-2 px-2">D</th><th class="py-2 px-2 border-r border-slate-600">K</th>
                            <th class="py-2 px-2">D</th><th class="py-2 px-2 border-r border-slate-600">K</th>
                            <th class="py-2 px-2">D</th><th class="py-2 px-2">K</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-slate-100">
                        @foreach ($rows as $row)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2.5 px-4 text-slate-500 font-semibold">{{ $row['code'] }}</td>
                            <td class="py-2.5 px-4 font-bold text-slate-800 truncate">{{ $row['config']['name'] }}</td>
                            
                            {{-- 1. Kolom Neraca Saldo Awal (Sudah di-fix agar bisa membaca angka minus) --}}
                            <td class="py-2 px-2 text-right border-l border-slate-100">
                                {{ (!empty($row['tbD']) && $row['tbD'] != 0) ? 'Rp '.number_format((float)$row['tbD'],0,',','.') : '-' }}
                            </td>
                            <td class="py-2 px-2 text-right border-r border-slate-100">
                                {{ (!empty($row['tbK']) && $row['tbK'] != 0) ? 'Rp '.number_format((float)$row['tbK'],0,',','.') : '-' }}
                            </td>
                            
                            {{-- 2. Kolom Jurnal Penyesuaian (AJP) --}}
                            <td class="py-2 px-2 text-right">
                                {{ (!empty($row['ajeD']) && $row['ajeD'] != 0) ? 'Rp '.number_format((float)$row['ajeD'],0,',','.') : '-' }}
                            </td>
                            <td class="py-2 px-2 text-right border-r border-slate-100">
                                {{ (!empty($row['ajeK']) && $row['ajeK'] != 0) ? 'Rp '.number_format((float)$row['ajeK'],0,',','.') : '-' }}
                            </td>
                            
                            {{-- 3. Kolom Neraca Saldo Setelah Penyesuaian (NSD) --}}
                            <td class="py-2 px-2 text-right">
                                {{ (!empty($row['nsdD']) && $row['nsdD'] != 0) ? 'Rp '.number_format((float)$row['nsdD'],0,',','.') : '-' }}
                            </td>
                            <td class="py-2 px-2 text-right border-r border-slate-100">
                                {{ (!empty($row['nsdK']) && $row['nsdK'] != 0) ? 'Rp '.number_format((float)$row['nsdK'],0,',','.') : '-' }}
                            </td>
                            
                            {{-- 4. Kolom Laba Rugi --}}
                            <td class="py-2 px-2 text-right">
                                {{ (!empty($row['lrD']) && $row['lrD'] != 0) ? 'Rp '.number_format((float)$row['lrD'],0,',','.') : '-' }}
                            </td>
                            <td class="py-2 px-2 text-right border-r border-slate-100">
                                {{ (!empty($row['lrK']) && $row['lrK'] != 0) ? 'Rp '.number_format((float)$row['lrK'],0,',','.') : '-' }}
                            </td>
                            
                            {{-- 5. Kolom Neraca Akhir --}}
                            <td class="py-2 px-2 text-right">
                                {{ (!empty($row['nD']) && $row['nD'] != 0) ? 'Rp '.number_format((float)$row['nD'],0,',','.') : '-' }}
                            </td>
                            <td class="py-2 px-2 text-right">
                                {{ (!empty($row['nK']) && $row['nK'] != 0) ? 'Rp '.number_format((float)$row['nK'],0,',','.') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="text-xs font-bold divide-y divide-slate-200">
                        <tr class="bg-slate-50 font-semibold text-slate-800">
                            <td colspan="2" class="py-3 px-4 text-right uppercase text-[10px]">Subtotal Kertas Kerja</td>
                            <td class="py-3 px-2 text-right border-l border-slate-200">@rupiah($sums['tbD'])</td>
                            <td class="py-3 px-2 text-right border-r border-slate-200">@rupiah($sums['tbK'])</td>
                            <td class="py-3 px-2 text-right">@rupiah($sums['ajeD'])</td>
                            <td class="py-3 px-2 text-right border-r border-slate-200">@rupiah($sums['ajeK'])</td>
                            <td class="py-3 px-2 text-right">@rupiah($sums['nsdD'])</td>
                            <td class="py-3 px-2 text-right border-r border-slate-200">@rupiah($sums['nsdK'])</td>
                            <td class="py-3 px-2 text-right">@rupiah($sums['lrD'])</td>
                            <td class="py-3 px-2 text-right border-r border-slate-200">@rupiah($sums['lrK'])</td>
                            <td class="py-3 px-2 text-right">@rupiah($sums['nD'])</td>
                            <td class="py-3 px-2 text-right">@rupiah($sums['nK'])</td>
                        </tr>
                        
                        <tr class="bg-emerald-50 text-emerald-800">
                            <td colspan="2" class="py-3 px-4 text-right uppercase text-[10px] font-bold">Laba Bersih Operasional</td>
                            <td colspan="6" class="bg-white border-r border-slate-200"></td>
                            <td class="py-3 px-2 text-right font-bold">{{ $isProfit ? 'Rp '.number_format($labaRugiDiff,0,',','.') : '-' }}</td>
                            <td class="py-3 px-2 text-right font-bold border-r border-slate-200">{{ !$isProfit ? 'Rp '.number_format(abs($labaRugiDiff),0,',','.') : '-' }}</td>
                            <td class="py-3 px-2 text-right font-bold">{{ !$isProfit ? 'Rp '.number_format(abs($neracaDiff),0,',','.') : '-' }}</td>
                            <td class="py-3 px-2 text-right font-bold">{{ $isProfit ? 'Rp '.number_format($neracaDiff,0,',','.') : '-' }}</td>
                        </tr>
                        
                        <tr class="bg-indigo-900 text-white font-bold border-t-2 border-indigo-950">
                            <td colspan="2" class="py-3.5 px-4 text-right uppercase text-[10px]">Total Grand Balance</td>
                            <td class="py-3.5 px-2 text-right border-l">@rupiah($sums['tbD'])</td>
                            <td class="py-3.5 px-2 text-right border-r">@rupiah($sums['tbK'])</td>
                            <td class="py-3.5 px-2 text-right">@rupiah($sums['ajeD'])</td>
                            <td class="py-3.5 px-2 text-right border-r">@rupiah($sums['ajeK'])</td>
                            <td class="py-3.5 px-2 text-right">@rupiah($sums['nsdD'])</td>
                            <td class="py-3.5 px-2 text-right border-r">@rupiah($sums['nsdK'])</td>
                            @php
                                $finalLR_D = $isProfit ? $sums['lrD'] + $labaRugiDiff : $sums['lrD'];
                                $finalLR_K = $isProfit ? $sums['lrK'] : $sums['lrK'] + abs($labaRugiDiff);
                                $finalN_D  = $isProfit ? $sums['nD'] : $sums['nD'] + abs($neracaDiff);
                                $finalN_K  = $isProfit ? $sums['nK'] + $neracaDiff : $sums['nK'];
                            @endphp
                            <td class="py-3.5 px-2 text-right">@rupiah($finalLR_D)</td>
                            <td class="py-3.5 px-2 text-right border-r">@rupiah($finalLR_K)</td>
                            <td class="py-3.5 px-2 text-right">@rupiah($finalN_D)</td>
                            <td class="py-3.5 px-2 text-right">@rupiah($finalN_K)</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection