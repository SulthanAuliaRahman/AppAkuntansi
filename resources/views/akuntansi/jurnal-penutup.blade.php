@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">7. Jurnal Penutup (Closing Entries)</h2>
                    <p class="text-sm text-slate-500">Menghapus saldo rekening nominal (Pendapatan & Beban) dan memindahkannya ke Modal</p>
                </div>
                <form action="{{ route('akuntansi.penutup.generate') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i> Generate Jurnal Penutup
                    </button>
                </form>
            </div>
        </div>

        <!-- Status Alert -->
        @if ($isGenerated)
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="text-xl">✅</span>
                <p class="text-sm font-semibold text-green-800">Jurnal Penutup sudah dibuat dan diposting ke Jurnal Umum</p>
            </div>
        @else
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="text-xl">⚠️</span>
                <p class="text-sm font-semibold text-amber-800">Jurnal Penutup belum dibuat. Klik tombol di atas untuk generate otomatis.</p>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="text-xl">✅</span>
                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                            <th class="py-3.5 px-5">Tanggal</th>
                            <th class="py-3.5 px-5">Akun Jurnal Penutup</th>
                            <th class="py-3.5 px-5 text-center">Ref</th>
                            <th class="py-3.5 px-5 text-right">Debet (Rp)</th>
                            <th class="py-3.5 px-5 text-right">Kredit (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        <!-- 1. Tutup Pendapatan ke Ikhtisar L/R -->
                        <tr class="bg-indigo-50/20 font-semibold text-slate-700 text-xs">
                            <td class="py-3 px-5" rowspan="3">30 Apr</td>
                            <td class="py-3 px-5 font-bold">{{ $accounts['411']['name'] }}</td>
                            <td class="py-3 px-5 text-center">411</td>
                            <td class="py-3 px-5 text-right">@rupiah($revJasa)</td>
                            <td class="py-3 px-5 text-right">-</td>
                        </tr>
                        <tr class="bg-indigo-50/20 font-semibold text-slate-700 text-xs">
                            <td class="py-3 px-5 font-bold">{{ $accounts['412']['name'] }}</td>
                            <td class="py-3 px-5 text-center">412</td>
                            <td class="py-3 px-5 text-right">@rupiah($revIklan)</td>
                            <td class="py-3 px-5 text-right">-</td>
                        </tr>
                        <tr class="bg-indigo-50/20 font-semibold text-slate-700 text-xs border-b border-slate-200">
                            <td class="py-3 px-5 pl-8 text-slate-500 italic">Ikhtisar Laba Rugi</td>
                            <td class="py-3 px-5 text-center text-slate-400">313</td>
                            <td class="py-3 px-5 text-right">-</td>
                            <td class="py-3 px-5 text-right font-bold">@rupiah($totalRev)</td>
                        </tr>

                        <!-- 2. Tutup Beban ke Ikhtisar L/R -->
                        <tr class="bg-slate-50 font-semibold text-slate-700 text-xs">
                            <td class="py-3 px-5" rowspan="7">30 Apr</td>
                            <td class="py-3 px-5 font-bold">Ikhtisar Laba Rugi</td>
                            <td class="py-3 px-5 text-center">313</td>
                            <td class="py-3 px-5 text-right">@rupiah($totalExp)</td>
                            <td class="py-3 px-5 text-right">-</td>
                        </tr>
                        @foreach ([
                            ['511', $expGaji], ['512', $expSewa], ['513', $expIklan],
                            ['514', $expAsuransi], ['515', $expPerlengkapan], ['516', $expPenyusutan]
                        ] as [$code, $amount])
                        <tr class="bg-slate-50 font-semibold text-slate-500 text-xs {{ $loop->last ? 'border-b border-slate-200' : '' }}">
                            <td class="py-3 px-5 pl-8 italic">{{ $accounts[$code]['name'] }}</td>
                            <td class="py-3 px-5 text-center">{{ $code }}</td>
                            <td class="py-3 px-5 text-right">-</td>
                            <td class="py-3 px-5 text-right">@rupiah($amount)</td>
                        </tr>
                        @endforeach

                        <!-- 3. Tutup Ikhtisar L/R ke Modal -->
                        <tr class="bg-indigo-50/10 font-semibold text-slate-700 text-xs">
                            <td class="py-3 px-5" rowspan="2">30 Apr</td>
                            <td class="py-3 px-5 font-bold">Ikhtisar Laba Rugi</td>
                            <td class="py-3 px-5 text-center">313</td>
                            <td class="py-3 px-5 text-right">@rupiah($netIncome)</td>
                            <td class="py-3 px-5 text-right">-</td>
                        </tr>
                        <tr class="bg-indigo-50/10 font-semibold text-slate-700 text-xs border-b border-slate-200">
                            <td class="py-3 px-5 pl-8 text-slate-500 italic">Modal Tuan Sakti</td>
                            <td class="py-3 px-5 text-center">311</td>
                            <td class="py-3 px-5 text-right">-</td>
                            <td class="py-3 px-5 text-right font-bold">@rupiah($netIncome)</td>
                        </tr>

                        <!-- 4. Tutup Prive ke Modal -->
                        <tr class="bg-slate-50 font-semibold text-slate-700 text-xs">
                            <td class="py-3 px-5" rowspan="2">30 Apr</td>
                            <td class="py-3 px-5 font-bold">Modal Tuan Sakti</td>
                            <td class="py-3 px-5 text-center">311</td>
                            <td class="py-3 px-5 text-right">@rupiah($prive)</td>
                            <td class="py-3 px-5 text-right">-</td>
                        </tr>
                        <tr class="bg-slate-50 font-semibold text-slate-700 text-xs">
                            <td class="py-3 px-5 pl-8 text-slate-500 italic">Prive Tuan Sakti</td>
                            <td class="py-3 px-5 text-center">312</td>
                            <td class="py-3 px-5 text-right">-</td>
                            <td class="py-3 px-5 text-right font-bold">@rupiah($prive)</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 font-bold border-t-2 border-slate-200 text-slate-800">
                            <td colspan="3" class="py-4 px-5 text-right uppercase text-[11px]">Total Jurnal Penutup</td>
                            <td class="py-4 px-5 text-right text-indigo-700">@rupiah($totalDebit)</td>
                            <td class="py-4 px-5 text-right text-indigo-700">@rupiah($totalDebit)</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
