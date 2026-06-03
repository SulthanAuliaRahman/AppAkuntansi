@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">2. Buku Besar (Ledger - Single Balance Format)</h2>
                <p class="text-sm text-slate-500">Mutasi saldo masing-masing akun perkiraan secara kronologis</p>
            </div>
            <form method="GET" action="{{ route('akuntansi.bukubesar') }}" class="flex items-center gap-3 w-full sm:w-auto">
                <label class="text-sm font-medium text-slate-500 whitespace-nowrap">Pilih Akun:</label>
                <select name="akun" onchange="this.form.submit()"
                    class="bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-64">
                    @foreach ($accounts as $code => $config)
                        <option value="{{ $code }}" {{ $selectedCode === $code ? 'selected' : '' }}>
                            {{ $code }} - {{ $config['name'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center flex-wrap gap-2">
                <div class="flex items-center gap-3">
                    <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-lg">{{ $selectedCode }}</span>
                    <h3 class="text-base font-bold text-slate-800">{{ $selectedConfig['name'] }}</h3>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Saldo Akhir</span>
                    <p class="text-lg font-bold text-slate-800">@rupiah($finalBalance)</p>
                </div>
            </div>
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
                        @foreach ($entries as $e)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-5 font-semibold text-slate-600">{{ $e['date'] }}</td>
                            <td class="py-3 px-5 text-slate-700">{{ $e['desc'] }}</td>
                            <td class="py-3 px-5 text-right font-medium">{{ $e['debit'] > 0 ? 'Rp '.number_format($e['debit'],0,',','.') : '-' }}</td>
                            <td class="py-3 px-5 text-right font-medium">{{ $e['credit'] > 0 ? 'Rp '.number_format($e['credit'],0,',','.') : '-' }}</td>
                            <td class="py-3 px-5 text-right text-indigo-700 font-bold">
                                {{ $selectedConfig['normal'] === 'debit' ? 'Rp '.number_format($e['balance'],0,',','.') : '-' }}
                            </td>
                            <td class="py-3 px-5 text-right text-indigo-700 font-bold">
                                {{ $selectedConfig['normal'] === 'credit' ? 'Rp '.number_format($e['balance'],0,',','.') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
