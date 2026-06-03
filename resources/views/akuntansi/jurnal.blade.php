@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">1. Jurnal Umum (General Journal)</h2>
                <p class="text-sm text-slate-500">Pencatatan seluruh transaksi kronologis selama periode April 2008</p>
            </div>
            <button onclick="document.getElementById('transaction-modal').style.display='flex';setTimeout(()=>{document.getElementById('modal-container').classList.remove('scale-95','opacity-0');document.getElementById('modal-container').classList.add('scale-100','opacity-100')},50)"
                class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Transaksi Baru
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                            <th class="py-3.5 px-5">Tanggal</th>
                            <th class="py-3.5 px-5">Keterangan / Akun</th>
                            <th class="py-3.5 px-5">Ref (No. Perk)</th>
                            <th class="py-3.5 px-5 text-right">Debet (Rp)</th>
                            <th class="py-3.5 px-5 text-right">Kredit (Rp)</th>
                            <th class="py-3.5 px-5 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        <tr class="bg-indigo-50/40 font-medium text-slate-400 text-xs">
                            <td class="py-2.5 px-5">31 Mar</td>
                            <td class="py-2.5 px-5" colspan="4">Kombinasi saldo awal neraca periode sebelumnya (Perpindahan Buku)</td>
                            <td class="py-2.5 px-5 text-center">
                                <span class="bg-indigo-100 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Sistem</span>
                            </td>
                        </tr>
                        @foreach ($transactions as $t)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-5 font-semibold text-slate-600">{{ $t['date'] }}</td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-800">{{ $accounts[$t['debitAcc']]['name'] }}</div>
                                <div class="pl-5 text-slate-500 italic mt-1 font-medium">{{ $accounts[$t['creditAcc']]['name'] }}</div>
                                <span class="text-xs text-indigo-600 block mt-1.5 font-medium">{{ $t['desc'] }}</span>
                            </td>
                            <td class="py-4 px-5">
                                <div class="text-slate-600 font-bold">{{ $t['debitAcc'] }}</div>
                                <div class="pl-5 text-slate-400 mt-1">{{ $t['creditAcc'] }}</div>
                            </td>
                            <td class="py-4 px-5 text-right font-semibold text-slate-800">@rupiah($t['amount'])</td>
                            <td class="py-4 px-5 text-right font-semibold text-slate-800">
                                <div class="h-6"></div>
                                <div>@rupiah($t['amount'])</div>
                            </td>
                            <td class="py-4 px-5 text-center">
                                @if (!$t['is_static'])
                                    <form method="POST" action="{{ route('akuntansi.jurnal.destroy', $t['id']) }}" class="inline"
                                          onsubmit="return confirm('Hapus transaksi ini?')"
                                          onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-2 rounded-xl transition-all">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs font-semibold text-slate-400 italic">Static Case</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 font-bold border-t-2 border-slate-200 text-slate-800">
                            <td colspan="3" class="py-4 px-5 text-right uppercase">Total Jurnal Umum</td>
                            <td class="py-4 px-5 text-right text-indigo-700">@rupiah($totalDebit)</td>
                            <td class="py-4 px-5 text-right text-indigo-700">@rupiah($totalDebit)</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal Tambah Transaksi -->
<div id="transaction-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-container">
        <div class="bg-indigo-700 text-white p-5 flex justify-between items-center">
            <h3 class="text-base font-bold flex items-center gap-2"><i class="fa-solid fa-file-invoice"></i> Catat Transaksi Jurnal Umum</h3>
            <button onclick="document.getElementById('modal-container').classList.remove('scale-100','opacity-100');document.getElementById('modal-container').classList.add('scale-95','opacity-0');setTimeout(()=>{document.getElementById('transaction-modal').style.display='none'},250)"
                class="text-indigo-200 hover:text-white transition-all text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="{{ route('akuntansi.jurnal.store') }}" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal April 2008</label>
                    <select name="date" class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @for ($i = 1; $i <= 30; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }} Apr">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }} April 2008</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nilai Transaksi (Rp)</label>
                    <input type="number" name="amount" required min="1000"
                        class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Contoh: 500000">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Keterangan Transaksi</label>
                <input type="text" name="desc" required
                    class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Deskripsi ringkas aktivitas bisnis">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Akun Debet</label>
                    <select name="debit_acc" class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach ($accounts as $code => $config)
                            <option value="{{ $code }}" {{ $code === '111' ? 'selected' : '' }}>{{ $code }} - {{ $config['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Akun Kredit</label>
                    <select name="credit_acc" class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach ($accounts as $code => $config)
                            <option value="{{ $code }}" {{ $code === '411' ? 'selected' : '' }}>{{ $code }} - {{ $config['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="pt-2 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-container').classList.remove('scale-100','opacity-100');document.getElementById('modal-container').classList.add('scale-95','opacity-0');setTimeout(()=>{document.getElementById('transaction-modal').style.display='none'},250)"
                    class="px-5 py-2.5 rounded-xl text-slate-500 hover:bg-slate-50 font-semibold text-sm transition-all border border-slate-200">Batal</button>
                <button type="submit" class="bg-indigo-700 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition-all">Posting Jurnal</button>
            </div>
        </form>
    </div>
</div>
@endsection
