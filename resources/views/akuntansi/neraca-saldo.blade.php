@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div>
        @include('akuntansi.partials.navigation')
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="text-center sm:text-left flex-1">
                    <h1 class="text-lg font-bold text-slate-600 uppercase tracking-wide">Perusahaan Jasa Anugerah Sakti</h1>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Neraca Saldo</h2>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('akuntansi.neracasaldo.excel') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                        <i class="fa-solid fa-file-excel"></i>
                        <span>Export Excel</span>
                    </a>
                </div>
            </div>
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
    @forelse ($rows as $row)
    <tr class="hover:bg-slate-50/50 transition-colors">
        <td class="py-3.5 px-5 font-semibold text-slate-500">{{ $row['code'] }}</td>
        <td class="py-3.5 px-5 font-bold text-slate-800">{{ $row['config']['name'] }}</td>
        
        <td class="py-3.5 px-5 text-right font-semibold text-slate-700">
            {{ (!empty($row['debit']) && $row['debit'] != 0) ? 'Rp ' . number_format((float)$row['debit'], 0, ',', '.') : '-' }}
        </td>
        
        <td class="py-3.5 px-5 text-right font-semibold text-slate-700">
            {{ (!empty($row['credit']) && $row['credit'] != 0) ? 'Rp ' . number_format((float)$row['credit'], 0, ',', '.') : '-' }}
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="py-8 px-5 text-center text-slate-500">
            📋 Belum ada data akun atau saldo Buku Besar yang dapat dimuat.
        </td>
    </tr>
    @endforelse
</tbody>
                    <tfoot>
                        <tr class="bg-slate-50 font-bold border-t-2 border-slate-200 text-slate-800">
                            <td colspan="2" class="py-4 px-5 text-right uppercase tracking-wider">Total Akhir Neraca Saldo</td>
                            <td class="py-4 px-5 text-right text-indigo-700 text-base">@rupiah($totalDebit)</td>
                            <td class="py-4 px-5 text-right text-indigo-700 text-base">@rupiah($totalCredit)</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection