@extends('layouts.akuntansi')

@section('content')
<style media="print">
    /* 1. Mempertahankan warna latar belakang dan layout asli template */
    body {
        background-color: #f8fafc !important; /* Warna slate-50 bawaan dashboard */
        color: #1e293b !important; /* Warna slate-800 */
    }
    
    /* 2. Sembunyikan tombol print dan navigasi menu agar tidak mengotori kertas */
    .no-print, 
    button, 
    nav, 
    header,
    .sidebar {
        display: none !important;
    }

    /* 3. Memaksa browser/printer mempertahankan warna (indigo, abu-abu tabel, dll) */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* 4. Menjaga ukuran layout agar tetap proporsional seperti di layar monitor */
    main {
        max-width: 100% !important;
        padding: 24px !important;
        margin: 0 auto !important;
    }

    .rounded-2xl {
        border-radius: 1rem !important;
    }

    .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }
</style>

<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="no-print">
        @include('akuntansi.partials.navigation')
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative">
            
            <div class="text-center space-y-1">
                <h1 class="text-lg font-bold text-slate-600 uppercase tracking-wide">Perusahaan Jasa Cleaning Service</h1> 
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Neraca Saldo</h2> 
                <p class="text-sm font-semibold text-slate-500">Per: 30 April 2008</p> 
            </div>

            <div class="absolute top-6 right-6 no-print">
                <button onclick="window.print()" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4"></path>
                    </svg>
                    Print Neraca
                </button>
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
                                {{ $row['debit'] > 0 ? 'Rp ' . number_format($row['debit'], 0, ',', '.') : '-' }}
                            </td>
                            
                            <td class="py-3.5 px-5 text-right font-semibold text-slate-700">
                                {{ $row['credit'] > 0 ? 'Rp ' . number_format($row['credit'], 0, ',', '.') : '-' }}
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