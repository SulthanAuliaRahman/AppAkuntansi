@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">3. Neraca Saldo (Trial Balance)</h2>
            <p class="text-sm text-slate-500">Saldo kumulatif sebelum penyesuaian per 30 April 2008</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                            <th class="py-3.5 px-5">No. Perk</th>
                            <th class="py-3.5 px-5">Nama Perkiraan</th>
                            <th class="py-3.5 px-5 text-right">Debet (Rp)</th>
                            <th class="py-3.5 px-5 text-right">Kredit (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        @foreach ($rows as $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-5 font-semibold text-slate-500">{{ $row['code'] }}</td>
                            <td class="py-3.5 px-5 font-bold text-slate-800">{{ $row['config']['name'] }}</td>
                            <td class="py-3.5 px-5 text-right font-semibold text-slate-700">
                                {{ $row['debit'] > 0 ? 'Rp '.number_format($row['debit'],0,',','.') : '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-right font-semibold text-slate-700">
                                {{ $row['credit'] > 0 ? 'Rp '.number_format($row['credit'],0,',','.') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 font-bold border-t-2 border-slate-200 text-slate-800">
                            <td colspan="2" class="py-4 px-5 text-right uppercase">Total Neraca Saldo</td>
                            <td class="py-4 px-5 text-right text-indigo-700">@rupiah($totalDebit)</td>
                            <td class="py-4 px-5 text-right text-indigo-700">@rupiah($totalCredit)</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
