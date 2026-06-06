@extends('layouts.akuntansi')

@section('content')
<style media="print">
    @page {
        size: A4;
        margin: 10mm;
    }
    body {
        margin: 0;
        padding: 0;
    }
    main {
        max-width: 100% !important;
        padding: 0 !important;
    }
    .no-print {
        display: none !important;
    }
    button {
        display: none !important;
    }
    form {
        margin-bottom: 0 !important;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 6px 4px !important;
        font-size: 11px !important;
    }
    .shadow-sm, .border {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    .bg-amber-50, .bg-slate-50, .bg-slate-100 {
        background: #f9fafb !important;
    }
</style>
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <!-- Header & Filter Controls -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">2. Buku Besar (Ledger - Single Balance Format)</h2>
                    <p class="text-sm text-slate-500">Mutasi saldo masing-masing akun perkiraan secara kronologis</p>
                </div>
                <button onclick="window.print()" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4"></path>
                    </svg>
                    Print
                </button>
            </div>

            <!-- View Mode Tabs -->
            <div class="flex gap-2 mb-4 border-b border-slate-200">
                <a href="{{ route('akuntansi.bukubesar', ['view' => 'all']) }}"
                    class="px-4 py-2 text-sm font-semibold {{ request('view', 'all') === 'all' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-600 hover:text-indigo-600' }}">
                    📊 Semua Akun
                </a>
                <a href="{{ route('akuntansi.bukubesar', ['view' => 'single']) }}"
                    class="px-4 py-2 text-sm font-semibold {{ request('view') === 'single' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-600 hover:text-indigo-600' }}">
                    🔍 Akun Tertentu
                </a>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('akuntansi.bukubesar') }}" class="space-y-3">
                <input type="hidden" name="view" value="{{ request('view', 'all') }}">
                <input type="hidden" name="page" value="{{ $page ?? 1 }}">

                @if(request('view') === 'single')
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <label class="text-sm font-medium text-slate-500 block mb-2">Pilih Akun (Akun dengan Transaksi):</label>
                            <select name="akun"
                                class="w-full bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($accountsWithData as $code)
                                    @php $config = $accounts[$code]; @endphp
                                    <option value="{{ $code }}" {{ request('akun') === $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $config['name'] }} ({{ $config['class'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl text-sm font-semibold transition-colors">
                                Tampilkan
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Date Filter -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-slate-500 block mb-2">Dari Tanggal:</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="w-full bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex-1">
                        <label class="text-sm font-medium text-slate-500 block mb-2">Sampai Tanggal:</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="w-full bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-xl text-sm font-semibold transition-colors">
                            Filter
                        </button>
                        @if($startDate || $endDate)
                            <a href="{{ route('akuntansi.bukubesar', ['view' => request('view', 'all')]) }}" class="bg-slate-300 hover:bg-slate-400 text-slate-800 px-6 py-2 rounded-xl text-sm font-semibold transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Multiple Accounts Display -->
        @if(!empty($pagedAccounts))
            <div class="space-y-6">
                <div class="text-xs text-slate-500 bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
                    💡 Menampilkan {{ count($pagedAccounts) }} akun dengan transaksi (halaman {{ $page ?? 1 }} dari {{ $totalPages ?? 1 }})
                </div>

                @foreach ($pagedAccounts as $code => $account)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <!-- Account Header -->
                    <div class="p-5 border-b border-slate-100 bg-slate-50">
                        <div class="flex justify-between items-start flex-wrap gap-4 mb-3">
                            <div class="flex items-center gap-3">
                                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-lg">{{ $code }}</span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">{{ $account['config']['name'] }}</h3>
                                    <p class="text-xs text-slate-500">{{ $account['config']['class'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Saldo Normal</span>
                                <p class="text-sm font-bold text-slate-800">{{ ucfirst($account['config']['normal']) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ledger Table -->
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                                    <th class="py-3 px-5">Tanggal</th>
                                    <th class="py-3 px-5">Keterangan</th>
                                    <th class="py-3 px-5 text-right">Debet (Rp)</th>
                                    <th class="py-3 px-5 text-right">Kredit (Rp)</th>
                                    <th class="py-3 px-5 text-right">Saldo Debet (Rp)</th>
                                    <th class="py-3 px-5 text-right">Saldo Kredit (Rp)</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse ($account['entries'] as $index => $e)
                                <tr class="@if($index === 0) bg-amber-50 @else hover:bg-slate-50/50 @endif transition-colors">
                                    <td class="py-3 px-5 font-semibold @if($index === 0) text-amber-700 @else text-slate-600 @endif">{{ $e['date'] }}</td>
                                    <td class="py-3 px-5 @if($index === 0) text-amber-700 font-semibold @else text-slate-700 @endif">{{ $e['desc'] }}</td>
                                    <td class="py-3 px-5 text-right font-medium">{{ $e['debit'] > 0 ? 'Rp '.number_format($e['debit'],0,',','.') : '-' }}</td>
                                    <td class="py-3 px-5 text-right font-medium">{{ $e['credit'] > 0 ? 'Rp '.number_format($e['credit'],0,',','.') : '-' }}</td>
                                    <td class="py-3 px-5 text-right text-indigo-700 font-bold">
                                        {{ $account['config']['normal'] === 'debit' ? 'Rp '.number_format($e['balance'],0,',','.') : '-' }}
                                    </td>
                                    <td class="py-3 px-5 text-right text-indigo-700 font-bold">
                                        {{ $account['config']['normal'] === 'credit' ? 'Rp '.number_format($e['balance'],0,',','.') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-4 px-5 text-center text-slate-500">Tidak ada transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="p-5 border-t border-slate-100 bg-slate-50">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-center">
                                <span class="text-xs uppercase font-bold text-slate-500 tracking-wider">Total Debet</span>
                                <p class="text-lg font-bold text-slate-800">@rupiah($account['summary']['totalDebit'])</p>
                            </div>
                            <div class="text-center border-l border-r border-slate-200">
                                <span class="text-xs uppercase font-bold text-slate-500 tracking-wider">Total Kredit</span>
                                <p class="text-lg font-bold text-slate-800">@rupiah($account['summary']['totalCredit'])</p>
                            </div>
                            <div class="text-center">
                                <span class="text-xs uppercase font-bold text-slate-500 tracking-wider">Saldo Akhir</span>
                                <p class="text-lg font-bold text-indigo-700">@rupiah($account['summary']['finalBalance'])</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($totalPages > 1)
            <div class="flex justify-center items-center gap-2 mt-8">
                @if($page > 1)
                    <a href="{{ route('akuntansi.bukubesar', ['page' => $page - 1, 'view' => 'all', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
                        ← Sebelumnya
                    </a>
                @endif

                <div class="flex gap-1">
                    @for($i = 1; $i <= $totalPages; $i++)
                        <a href="{{ route('akuntansi.bukubesar', ['page' => $i, 'view' => 'all', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                            class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors
                            {{ $i === $page ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }}">
                            {{ $i }}
                        </a>
                    @endfor
                </div>

                @if($page < $totalPages)
                    <a href="{{ route('akuntansi.bukubesar', ['page' => $page + 1, 'view' => 'all', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
                        Berikutnya →
                    </a>
                @endif
            </div>

            <div class="text-center mt-4 text-sm text-slate-600">
                Halaman <span class="font-bold">{{ $page }}</span> dari <span class="font-bold">{{ $totalPages }}</span>
            </div>
            @endif
        @else
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 text-center">
                <p class="text-slate-500">📋 Tidak ada akun dengan transaksi di Jurnal Umum</p>
                <p class="text-sm text-slate-400 mt-2">Buat transaksi di Jurnal Umum terlebih dahulu untuk melihat Buku Besar</p>
            </div>
        @endif
    </div>
</main>
@endsection
