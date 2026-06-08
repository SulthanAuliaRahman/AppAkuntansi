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
                    <p class="text-sm text-slate-500 mt-1">Menutup akun nominal (Pendapatan, Beban, Prive) ke Modal akhir periode</p>
                </div>
                @if (!$isGenerated)
                    <form action="{{ route('akuntansi.penutup.generate') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> Generate Jurnal Penutup
                        </button>
                    </form>
                @else
                    <form action="{{ route('akuntansi.penutup.generate') }}" method="POST"
                          onsubmit="return confirm('Generate ulang akan menghapus jurnal penutup yang sudah ada. Lanjutkan?')">
                        @csrf
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-rotate"></i> Generate Ulang
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="text-xl">✅</span>
                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if ($isGenerated)
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="text-xl">✅</span>
                <p class="text-sm font-semibold text-green-800">Jurnal Penutup sudah di-generate. Tabel menampilkan data yang tersimpan di database.</p>
            </div>
        @else
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="text-xl">⚠️</span>
                <p class="text-sm font-semibold text-amber-800">Jurnal Penutup belum di-generate. Tabel di bawah adalah preview berdasarkan saldo saat ini.</p>
            </div>
        @endif

        @if ($totalRev == 0 && $totalExp == 0)
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="text-xl">❌</span>
                <p class="text-sm font-semibold text-red-800">Belum ada data transaksi pendapatan atau beban untuk ditutup.</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                            <th class="py-3.5 px-5 w-24">Tanggal</th>
                            <th class="py-3.5 px-5">Akun</th>
                            <th class="py-3.5 px-5 text-center w-16">Ref</th>
                            <th class="py-3.5 px-5 text-right w-40">Debet (Rp)</th>
                            <th class="py-3.5 px-5 text-right w-40">Kredit (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">

                        {{-- =============================================
                             STEP 1: Tutup Pendapatan ke Ikhtisar L/R
                             Pendapatan bersaldo KREDIT → di-Debit untuk dinolkan
                             ============================================= --}}
                        @if (count($pendapatanAccounts) > 0)
                        <tr class="bg-indigo-50/40">
                            <td colspan="5" class="py-2 px-5 text-xs font-bold text-indigo-700">
                                Langkah 1 — Menutup Pendapatan ke Ikhtisar Laba Rugi
                            </td>
                        </tr>
                        @foreach ($pendapatanAccounts as $code => $akun)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-5 text-slate-500 text-xs">Akhir Periode</td>
                            <td class="py-3 px-5 font-semibold text-slate-700">{{ $akun['name'] }}</td>
                            <td class="py-3 px-5 text-center text-slate-500">{{ $code }}</td>
                            <td class="py-3 px-5 text-right font-medium text-slate-800">@rupiah($akun['balance'])</td>
                            <td class="py-3 px-5 text-right text-slate-400">-</td>
                        </tr>
                        @endforeach
                        <tr class="hover:bg-slate-50/50 border-b-2 border-indigo-100">
                            <td class="py-3 px-5 text-slate-500 text-xs">Akhir Periode</td>
                            <td class="py-3 px-5 pl-10 italic text-slate-500">Ikhtisar Laba Rugi</td>
                            <td class="py-3 px-5 text-center text-slate-400">313</td>
                            <td class="py-3 px-5 text-right text-slate-400">-</td>
                            <td class="py-3 px-5 text-right font-bold text-indigo-700">@rupiah($totalRev)</td>
                        </tr>
                        @endif

                        {{-- =============================================
                             STEP 2: Tutup Beban ke Ikhtisar L/R
                             Beban bersaldo DEBET → di-Kredit untuk dinolkan
                             ============================================= --}}
                        @if (count($bebanAccounts) > 0)
                        <tr class="bg-slate-50/80">
                            <td colspan="5" class="py-2 px-5 text-xs font-bold text-slate-600">
                                Langkah 2 — Menutup Beban ke Ikhtisar Laba Rugi
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-5 text-slate-500 text-xs">Akhir Periode</td>
                            <td class="py-3 px-5 font-semibold text-slate-700">Ikhtisar Laba Rugi</td>
                            <td class="py-3 px-5 text-center text-slate-500">313</td>
                            <td class="py-3 px-5 text-right font-bold text-slate-800">@rupiah($totalExp)</td>
                            <td class="py-3 px-5 text-right text-slate-400">-</td>
                        </tr>
                        @foreach ($bebanAccounts as $code => $akun)
                        <tr class="hover:bg-slate-50/50 {{ $loop->last ? 'border-b-2 border-slate-200' : '' }}">
                            <td class="py-3 px-5 text-slate-500 text-xs">Akhir Periode</td>
                            <td class="py-3 px-5 pl-10 italic text-slate-500">{{ $akun['name'] }}</td>
                            <td class="py-3 px-5 text-center text-slate-400">{{ $code }}</td>
                            <td class="py-3 px-5 text-right text-slate-400">-</td>
                            <td class="py-3 px-5 text-right font-medium text-slate-700">@rupiah($akun['balance'])</td>
                        </tr>
                        @endforeach
                        @endif

                        {{-- =============================================
                             STEP 3: Tutup Ikhtisar L/R ke Modal
                             Laba → Debit ILR, Kredit Modal
                             Rugi → Debit Modal, Kredit ILR
                             ============================================= --}}
                        @if ($modalCode)
                        <tr class="bg-green-50/60">
                            <td colspan="5" class="py-2 px-5 text-xs font-bold text-green-700">
                                Langkah 3 — Menutup Ikhtisar Laba Rugi ke Modal
                                @if ($netIncome >= 0)
                                    <span class="text-green-600">(Laba Bersih: @rupiah($netIncome))</span>
                                @else
                                    <span class="text-rose-600">(Rugi Bersih: @rupiah(abs($netIncome)))</span>
                                @endif
                            </td>
                        </tr>
                        @if ($netIncome >= 0)
                        {{-- Laba: Debit Ikhtisar L/R → Kredit Modal --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-5 text-slate-500 text-xs">Akhir Periode</td>
                            <td class="py-3 px-5 font-semibold text-slate-700">Ikhtisar Laba Rugi</td>
                            <td class="py-3 px-5 text-center text-slate-500">313</td>
                            <td class="py-3 px-5 text-right font-bold text-slate-800">@rupiah($netIncome)</td>
                            <td class="py-3 px-5 text-right text-slate-400">-</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 border-b-2 border-green-100">
                            <td class="py-3 px-5 text-slate-500 text-xs">Akhir Periode</td>
                            <td class="py-3 px-5 pl-10 italic text-slate-500">{{ $modalName }}</td>
                            <td class="py-3 px-5 text-center text-slate-400">{{ $modalCode }}</td>
                            <td class="py-3 px-5 text-right text-slate-400">-</td>
                            <td class="py-3 px-5 text-right font-bold text-green-700">@rupiah($netIncome)</td>
                        </tr>
                        @else
                        {{-- Rugi: Debit Modal → Kredit Ikhtisar L/R --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-5 text-slate-500 text-xs">Akhir Periode</td>
                            <td class="py-3 px-5 font-semibold text-slate-700">{{ $modalName }}</td>
                            <td class="py-3 px-5 text-center text-slate-500">{{ $modalCode }}</td>
                            <td class="py-3 px-5 text-right font-bold text-rose-700">@rupiah(abs($netIncome))</td>
                            <td class="py-3 px-5 text-right text-slate-400">-</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 border-b-2 border-rose-100">
                            <td class="py-3 px-5 text-slate-500 text-xs">Akhir Periode</td>
                            <td class="py-3 px-5 pl-10 italic text-slate-500">Ikhtisar Laba Rugi</td>
                            <td class="py-3 px-5 text-center text-slate-400">313</td>
                            <td class="py-3 px-5 text-right text-slate-400">-</td>
                            <td class="py-3 px-5 text-right font-bold text-rose-700">@rupiah(abs($netIncome))</td>
                        </tr>
                        @endif
                        @endif

                        {{-- =============================================
                             STEP 4: Tutup Prive ke Modal
                             Prive bersaldo DEBET → Debit Modal, Kredit Prive
                             ============================================= --}}
                        @if (count($priveAccounts) > 0 && $modalCode)
                        <tr class="bg-rose-50/40">
                            <td colspan="5" class="py-2 px-5 text-xs font-bold text-rose-700">
                                Langkah 4 — Menutup Prive ke Modal
                            </td>
                        </tr>
                        @foreach ($priveAccounts as $code => $akun)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-5 text-slate-500 text-xs">Akhir Periode</td>
                            <td class="py-3 px-5 font-semibold text-slate-700">{{ $modalName }}</td>
                            <td class="py-3 px-5 text-center text-slate-500">{{ $modalCode }}</td>
                            <td class="py-3 px-5 text-right font-bold text-slate-800">@rupiah($akun['balance'])</td>
                            <td class="py-3 px-5 text-right text-slate-400">-</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 {{ $loop->last ? 'border-b-2 border-rose-100' : '' }}">
                            <td class="py-3 px-5 text-slate-500 text-xs">Akhir Periode</td>
                            <td class="py-3 px-5 pl-10 italic text-slate-500">{{ $akun['name'] }}</td>
                            <td class="py-3 px-5 text-center text-slate-400">{{ $code }}</td>
                            <td class="py-3 px-5 text-right text-slate-400">-</td>
                            <td class="py-3 px-5 text-right font-medium text-rose-700">@rupiah($akun['balance'])</td>
                        </tr>
                        @endforeach
                        @endif

                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 font-bold border-t-2 border-slate-300 text-slate-800">
                            <td colspan="3" class="py-4 px-5 text-right uppercase text-[11px] tracking-wider">Total</td>
                            <td class="py-4 px-5 text-right text-indigo-700 text-base">@rupiah($totalDebit)</td>
                            <td class="py-4 px-5 text-right text-indigo-700 text-base">@rupiah($totalDebit)</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Summary Card -->
            <div class="p-5 border-t border-slate-100 bg-slate-50 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase">Total Pendapatan</p>
                    <p class="font-bold text-indigo-700 mt-0.5">@rupiah($totalRev)</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase">Total Beban</p>
                    <p class="font-bold text-slate-700 mt-0.5">@rupiah($totalExp)</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase">Laba / Rugi Bersih</p>
                    <p class="font-bold mt-0.5 {{ $netIncome >= 0 ? 'text-green-600' : 'text-rose-600' }}">
                        {{ $netIncome >= 0 ? '+' : '-' }} @rupiah(abs($netIncome))
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase">Total Prive</p>
                    <p class="font-bold text-rose-600 mt-0.5">@rupiah($totalPrive)</p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
