@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">1. Jurnal Umum (General Journal)</h2>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 items-end sm:items-center w-full sm:w-auto">
                {{-- Date Filter Form --}}
                <form method="GET" action="{{ route('akuntansi.jurnal') }}" class="flex items-center gap-2">
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="bg-white border border-slate-200 px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Tanggal Awal">
                    <span class="text-slate-400 text-sm font-medium">s/d</span>
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="bg-white border border-slate-200 px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Tanggal Akhir">
                    <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-xl text-sm font-semibold transition-all">
                        Filter
                    </button>
                    @if(!empty($startDate) || !empty($endDate))
                        <a href="{{ route('akuntansi.jurnal') }}" class="text-rose-500 hover:text-rose-700 px-2 text-sm font-medium">Reset</a>
                    @endif
                </form>

                <button onclick="openAddModal()"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-plus"></i> Tambah Transaksi Baru
                </button>
            </div>
        </div>

        {{-- Flash success --}}
        @if (session('success'))
        <div id="flash-success" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            {{ session('success') }}
        </div>
        @endif

        {{-- Server-side validation errors --}}
        @if ($errors->any())
        <div class="flex flex-col gap-1 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium">
            <div class="flex items-center gap-2 font-bold mb-1"><i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Transaksi ditolak:</div>
            @foreach ($errors->all() as $error)
                <div class="pl-5">• {{ $error }}</div>
            @endforeach
        </div>
        @endif

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
                            <td class="py-2.5 px-5 text-center">
                                <span class="bg-indigo-100 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Sistem</span>
                            </td>
                        </tr>
                        @foreach ($transactions as $t)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-5 font-semibold text-slate-600">{{ $t['date'] }}</td>
                            <td class="py-4 px-5">
                                @foreach ($t['entries'] as $entry)
                                    <div class="{{ $entry['type'] === 'debet' ? 'font-bold text-slate-800' : 'pl-5 text-slate-500 italic font-medium' }} mb-1">
                                        {{ $accounts[$entry['account']]['name'] ?? 'Akun tidak ditemukan' }}
                                    </div>
                                @endforeach
                                <span class="text-xs text-indigo-600 block mt-1.5 font-medium">{{ $t['desc'] }}</span>
                            </td>
                            <td class="py-4 px-5">
                                @foreach ($t['entries'] as $entry)
                                    <div class="{{ $entry['type'] === 'debet' ? 'text-slate-600 font-bold' : 'pl-5 text-slate-400' }} mb-1">
                                        {{ $entry['account'] }}
                                    </div>
                                @endforeach
                            </td>
                            <td class="py-4 px-5 text-right font-semibold text-slate-800">
                                @foreach ($t['entries'] as $entry)
                                    <div class="mb-1">
                                        @if($entry['type'] === 'debet')
                                            @rupiah($entry['amount'])
                                        @else
                                            <span class="invisible">0</span>
                                        @endif
                                    </div>
                                @endforeach
                            </td>
                            <td class="py-4 px-5 text-right font-semibold text-slate-800">
                                @foreach ($t['entries'] as $entry)
                                    <div class="mb-1">
                                        @if($entry['type'] === 'kredit')
                                            @rupiah($entry['amount'])
                                        @else
                                            <span class="invisible">0</span>
                                        @endif
                                    </div>
                                @endforeach
                            </td>
                            <td class="py-4 px-5 text-center">
                                @if (!$t['is_static'])
                                    <div class="flex justify-center gap-2">
                                        <button type="button"
                                            onclick="openEditModal({{ $t['id'] }})"
                                            class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 p-2 rounded-xl transition-all"
                                            title="Edit transaksi">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form method="POST" action="{{ route('akuntansi.jurnal.destroy', $t['id']) }}" class="inline"
                                              onsubmit="return confirm('Hapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-2 rounded-xl transition-all" title="Hapus transaksi">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
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
                            <td class="py-4 px-5 text-right text-indigo-700">@rupiah($totalCredit)</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</main>

{{-- Datalist untuk autocomplete pencarian akun --}}
<datalist id="akun-list">
    @foreach($akunsList as $akun)
        <option value="{{ $akun['kode_akun'] }} - {{ $akun['nama_akun'] }}"></option>
    @endforeach
</datalist>

{{-- ===== MODAL TAMBAH TRANSAKSI ===== --}}
<div id="add-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] flex flex-col" id="add-modal-container">
        <div class="bg-indigo-700 text-white p-5 flex justify-between items-center shrink-0">
            <h3 class="text-base font-bold flex items-center gap-2"><i class="fa-solid fa-file-invoice"></i> Catat Transaksi Jurnal Umum</h3>
            <button onclick="closeModal('add')" class="text-indigo-200 hover:text-white transition-all text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="overflow-y-auto flex-1">
            <form id="add-form" method="POST" action="{{ route('akuntansi.jurnal.store') }}" class="p-6 space-y-4" novalidate>
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                        <input type="date" name="date" id="add-date" required
                            class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Keterangan</label>
                        <input type="text" name="desc" id="add-desc" required
                            class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Deskripsi singkat transaksi">
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Entri Akun</label>
                        <button type="button" onclick="addEntryRow('add-entries-container', 'add')"
                            class="text-indigo-600 hover:text-indigo-700 text-xs font-semibold flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-indigo-50 transition-all">
                            <i class="fa-solid fa-plus"></i> Tambah Baris
                        </button>
                    </div>

                    <div id="add-entries-container" class="space-y-2 bg-slate-50 p-3 rounded-xl border border-slate-200 min-h-[80px]">
                        {{-- JS akan mengisi baris awal --}}
                    </div>

                    {{-- Balance indicator --}}
                    <div id="add-balance-indicator" class="p-3 rounded-xl border-2 border-slate-200 bg-slate-50">
                        <div class="grid grid-cols-3 gap-3 items-end">
                            <div>
                                <p class="text-xs font-semibold text-slate-500 mb-0.5">Total Debet</p>
                                <p class="text-base font-bold text-slate-400" id="add-total-debit">Rp 0</p>
                            </div>
                            <div class="text-center">
                                <i id="add-balance-icon" class="fa-solid fa-equals text-2xl text-slate-300"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-500 mb-0.5">Total Kredit</p>
                                <p class="text-base font-bold text-slate-400" id="add-total-credit">Rp 0</p>
                            </div>
                        </div>
                        <p id="add-balance-msg" class="text-xs mt-2 font-semibold text-slate-400 hidden"></p>
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="closeModal('add')"
                        class="px-5 py-2.5 rounded-xl text-slate-500 hover:bg-slate-50 font-semibold text-sm transition-all border border-slate-200">Batal</button>
                    <button type="submit" id="add-submit-btn"
                        class="bg-indigo-700 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition-all">
                        <i class="fa-solid fa-paper-plane mr-1"></i>Posting Jurnal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL EDIT TRANSAKSI ===== --}}
<div id="edit-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] flex flex-col" id="edit-modal-container">
        <div class="bg-slate-800 text-white p-5 flex justify-between items-center shrink-0">
            <h3 class="text-base font-bold flex items-center gap-2"><i class="fa-solid fa-pen-to-square"></i> Edit Transaksi Jurnal Umum</h3>
            <button onclick="closeModal('edit')" class="text-slate-300 hover:text-white transition-all text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>

        {{-- Loading state --}}
        <div id="edit-loading" class="flex-1 flex flex-col items-center justify-center py-16 gap-3 text-slate-400">
            <i class="fa-solid fa-spinner fa-spin text-2xl"></i>
            <p class="text-sm font-medium">Memuat data transaksi…</p>
        </div>

        <div id="edit-form-wrapper" class="overflow-y-auto flex-1 hidden">
            <form id="edit-form" method="POST" action="" class="p-6 space-y-4" novalidate>
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                        <input type="date" name="date" id="edit-date" required
                            class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Keterangan</label>
                        <input type="text" name="desc" id="edit-desc" required
                            class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Deskripsi singkat transaksi">
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Entri Akun</label>
                        <button type="button" onclick="addEntryRow('edit-entries-container', 'edit')"
                            class="text-indigo-600 hover:text-indigo-700 text-xs font-semibold flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-indigo-50 transition-all">
                            <i class="fa-solid fa-plus"></i> Tambah Baris
                        </button>
                    </div>

                    <div id="edit-entries-container" class="space-y-2 bg-slate-50 p-3 rounded-xl border border-slate-200 min-h-[80px]">
                    </div>

                    {{-- Balance indicator --}}
                    <div id="edit-balance-indicator" class="p-3 rounded-xl border-2 border-slate-200 bg-slate-50">
                        <div class="grid grid-cols-3 gap-3 items-end">
                            <div>
                                <p class="text-xs font-semibold text-slate-500 mb-0.5">Total Debet</p>
                                <p class="text-base font-bold text-slate-400" id="edit-total-debit">Rp 0</p>
                            </div>
                            <div class="text-center">
                                <i id="edit-balance-icon" class="fa-solid fa-equals text-2xl text-slate-300"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-500 mb-0.5">Total Kredit</p>
                                <p class="text-base font-bold text-slate-400" id="edit-total-credit">Rp 0</p>
                            </div>
                        </div>
                        <p id="edit-balance-msg" class="text-xs mt-2 font-semibold text-slate-400 hidden"></p>
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('edit')"
                        class="px-5 py-2.5 rounded-xl text-slate-500 hover:bg-slate-50 font-semibold text-sm transition-all border border-slate-200">Batal</button>
                    <button type="submit" id="edit-submit-btn"
                        class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition-all">
                        <i class="fa-solid fa-floppy-disk mr-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ─── Data akun dari server ────────────────────────────────────────────────
const akunsList = @json($akunsList ?? []);

// ─── Helpers ──────────────────────────────────────────────────────────────
function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(value);
}

// ─── Entry row builder ────────────────────────────────────────────────────
let _rowId = 0;
function buildEntryRow(prefix, type = 'debet', akunKode = '', jumlah = '') {
    const id  = ++_rowId;
    const key = `entries[${id}]`;
    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 items-center entry-row';

    // Cari nama akun jika akunKode sudah ada (untuk edit)
    const akunNama = akunKode ? (akunsList.find(a => a.kode_akun == akunKode)?.nama_akun || '') : '';
    const rawValue = akunKode ? `${akunKode} - ${akunNama}` : '';

    row.innerHTML = `
        <select name="${key}[type]"
            class="col-span-3 entry-type bg-white border border-slate-200 px-2 py-2 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="debet"  ${type === 'debet'  ? 'selected' : ''}>Debet</option>
            <option value="kredit" ${type === 'kredit' ? 'selected' : ''}>Kredit</option>
        </select>

        <div class="col-span-5 relative">
            <input type="text" list="akun-list" value="${rawValue}"
                placeholder="Cari kode atau nama..." autocomplete="off"
                class="w-full entry-akun-raw bg-white border border-slate-200 px-2 py-2 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <input type="hidden" name="${key}[akun_kode]" class="entry-akun" value="${akunKode}">
        </div>

        <input  type="number" name="${key}[jumlah]"
            value="${jumlah}"
            placeholder="Jumlah" min="1000" step="1000"
            class="col-span-3 entry-amount bg-white border border-slate-200 px-2 py-2 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400">

        <button type="button" title="Hapus baris"
            class="col-span-1 remove-btn text-rose-400 hover:text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition-all flex items-center justify-center">
            <i class="fa-solid fa-trash-can text-sm"></i>
        </button>
    `;

    // Handle extraction of kode_akun from raw input
    const rawInput = row.querySelector('.entry-akun-raw');
    const hiddenInput = row.querySelector('.entry-akun');

    rawInput.addEventListener('input', (e) => {
        const val = e.target.value;
        const code = val.split(' - ')[0].trim();
        hiddenInput.value = code;
        refreshTotals(prefix);
    });

    row.querySelector('.remove-btn').addEventListener('click', () => {
        row.remove();
        refreshTotals(prefix);
    });

    row.querySelectorAll('.entry-type, .entry-amount').forEach(el => {
        el.addEventListener('input',  () => refreshTotals(prefix));
        el.addEventListener('change', () => refreshTotals(prefix));
    });

    return row;
}

function addEntryRow(containerId, prefix) {
    const container = document.getElementById(containerId);
    container.appendChild(buildEntryRow(prefix));
    refreshTotals(prefix);
}

// ─── Balance refresh ──────────────────────────────────────────────────────
function refreshTotals(prefix) {
    const containerId = prefix + '-entries-container';
    let debit = 0, credit = 0;

    document.querySelectorAll(`#${containerId} .entry-row`).forEach(row => {
        const type   = row.querySelector('.entry-type').value;
        const amount = parseInt(row.querySelector('.entry-amount').value) || 0;
        if (type === 'debet') debit  += amount;
        else                  credit += amount;
    });

    const debitEl  = document.getElementById(`${prefix}-total-debit`);
    const creditEl = document.getElementById(`${prefix}-total-credit`);
    const iconEl   = document.getElementById(`${prefix}-balance-icon`);
    const msgEl    = document.getElementById(`${prefix}-balance-msg`);
    const indEl    = document.getElementById(`${prefix}-balance-indicator`);
    const btnEl    = document.getElementById(`${prefix}-submit-btn`);

    debitEl.textContent  = formatRupiah(debit);
    creditEl.textContent = formatRupiah(credit);

    const balanced = debit > 0 && debit === credit;
    const selisih  = Math.abs(debit - credit);

    if (balanced) {
        // ✅ Balance
        [debitEl, creditEl].forEach(el => {
            el.classList.remove('text-slate-400', 'text-rose-600');
            el.classList.add('text-green-600');
        });
        iconEl.className = 'fa-solid fa-equals text-2xl text-green-500';
        indEl.classList.remove('border-slate-200', 'border-rose-300');
        indEl.classList.add('border-green-300', 'bg-green-50');
        msgEl.textContent = '✓ Jurnal sudah balance — siap diposting.';
        msgEl.className   = 'text-xs mt-2 font-semibold text-green-600';
        msgEl.classList.remove('hidden');
        btnEl.disabled    = false;
        btnEl.classList.remove('opacity-50', 'cursor-not-allowed');
    } else if (debit === 0 && credit === 0) {
        // Blank state
        [debitEl, creditEl].forEach(el => {
            el.classList.remove('text-green-600', 'text-rose-600');
            el.classList.add('text-slate-400');
        });
        iconEl.className = 'fa-solid fa-equals text-2xl text-slate-300';
        indEl.classList.remove('border-green-300', 'bg-green-50', 'border-rose-300', 'bg-rose-50');
        indEl.classList.add('border-slate-200', 'bg-slate-50');
        msgEl.classList.add('hidden');
        btnEl.disabled   = false;
        btnEl.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        // ❌ Tidak balance
        [debitEl, creditEl].forEach(el => {
            el.classList.remove('text-slate-400', 'text-green-600');
            el.classList.add('text-rose-600');
        });
        iconEl.className = 'fa-solid fa-not-equal text-2xl text-rose-500';
        indEl.classList.remove('border-slate-200', 'bg-slate-50', 'border-green-300', 'bg-green-50');
        indEl.classList.add('border-rose-300', 'bg-rose-50');
        msgEl.textContent = `✗ Tidak balance! Selisih: ${formatRupiah(selisih)}`;
        msgEl.className   = 'text-xs mt-2 font-semibold text-rose-600';
        msgEl.classList.remove('hidden');
        btnEl.disabled    = true;
        btnEl.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

// ─── Validation sebelum submit ─────────────────────────────────────────────
function validateForm(prefix, e) {
    const containerId = prefix + '-entries-container';
    const errors = [];

    const date = document.getElementById(`${prefix}-date`)?.value;
    const desc = document.getElementById(`${prefix}-desc`)?.value;

    if (!date) errors.push('Tanggal transaksi wajib diisi.');
    if (!desc || !desc.trim()) errors.push('Keterangan transaksi wajib diisi.');

    let debit = 0, credit = 0, rowIndex = 0;
    document.querySelectorAll(`#${containerId} .entry-row`).forEach(row => {
        rowIndex++;
        const type   = row.querySelector('.entry-type').value;
        const akun   = row.querySelector('.entry-akun').value;
        const amount = parseInt(row.querySelector('.entry-amount').value) || 0;

        if (!akun) errors.push(`Baris ${rowIndex}: akun belum dipilih.`);
        if (amount < 1000) errors.push(`Baris ${rowIndex}: jumlah minimal Rp 1.000.`);

        if (type === 'debet') debit  += amount;
        else                  credit += amount;
    });

    if (rowIndex < 2) errors.push('Minimal harus ada 2 baris entri.');

    if (debit !== credit) {
        errors.push(
            `Jurnal tidak balance! Debet ${formatRupiah(debit)} ≠ Kredit ${formatRupiah(credit)}. ` +
            `Selisih: ${formatRupiah(Math.abs(debit - credit))}.`
        );
    }

    if (errors.length) {
        e.preventDefault();
        showValidationError(errors);
        return false;
    }
    return true;
}

function showValidationError(errors) {
    // Tampilkan di modal sebagai inline error, bukan alert()
    const existing = document.querySelector('.modal-validation-error');
    if (existing) existing.remove();

    const box = document.createElement('div');
    box.className = 'modal-validation-error bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-xl text-sm mx-6 mb-0 -mt-2';
    box.innerHTML = `<div class="font-bold flex items-center gap-2 mb-1">
            <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Transaksi ditolak:
        </div>` +
        errors.map(e => `<div class="pl-4">• ${e}</div>`).join('');

    // Insert before form buttons (last child of form)
    const form = box.closest ? null : null; // just prepend before submit section
    const activeForm = document.querySelector('#add-modal[style*="flex"]') 
        ? document.getElementById('add-form')
        : document.getElementById('edit-form');

    if (activeForm) {
        const btnRow = activeForm.querySelector('.pt-2.flex.justify-end');
        activeForm.insertBefore(box, btnRow);
        box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

// ─── Modal helpers ────────────────────────────────────────────────────────
function openModal(prefix) {
    const modal     = document.getElementById(`${prefix}-modal`);
    const container = document.getElementById(`${prefix}-modal-container`);
    modal.style.display = 'flex';
    requestAnimationFrame(() => requestAnimationFrame(() => {
        container.classList.remove('scale-95', 'opacity-0');
        container.classList.add('scale-100', 'opacity-100');
    }));
}

function closeModal(prefix) {
    const modal     = document.getElementById(`${prefix}-modal`);
    const container = document.getElementById(`${prefix}-modal-container`);
    container.classList.remove('scale-100', 'opacity-100');
    container.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.style.display = 'none'; }, 250);
    // Bersihkan inline validation errors
    document.querySelectorAll('.modal-validation-error').forEach(el => el.remove());
}

// ─── Add modal ────────────────────────────────────────────────────────────
function openAddModal() {
    const container = document.getElementById('add-entries-container');
    container.innerHTML = '';
    container.appendChild(buildEntryRow('add', 'debet'));
    container.appendChild(buildEntryRow('add', 'kredit'));
    refreshTotals('add');
    document.getElementById('add-date').value = '';
    document.getElementById('add-desc').value = '';
    openModal('add');
}

document.getElementById('add-form').addEventListener('submit', function(e) {
    document.querySelectorAll('.modal-validation-error').forEach(el => el.remove());
    validateForm('add', e);
});

// ─── Edit modal ───────────────────────────────────────────────────────────
function openEditModal(jurnalId) {
    // Reset state
    document.getElementById('edit-loading').classList.remove('hidden');
    document.getElementById('edit-form-wrapper').classList.add('hidden');
    document.getElementById('edit-entries-container').innerHTML = '';
    document.querySelectorAll('.modal-validation-error').forEach(el => el.remove());

    openModal('edit');

    fetch(`/jurnal/${jurnalId}/details`)
        .then(r => {
            if (!r.ok) throw new Error(`HTTP ${r.status}`);
            return r.json();
        })
        .then(data => {
            // Isi form
            document.getElementById('edit-date').value = data.tanggal;
            document.getElementById('edit-desc').value = data.keterangan;
            document.getElementById('edit-form').action = `/jurnal/${data.id}`;

            // Isi entri
            const container = document.getElementById('edit-entries-container');
            container.innerHTML = '';

            if (data.details && data.details.length > 0) {
                data.details.forEach(d => {
                    container.appendChild(
                        buildEntryRow('edit', d.type, d.akun_kode, d.jumlah)
                    );
                });
            } else {
                // Fallback: 1 debet + 1 kredit kosong
                container.appendChild(buildEntryRow('edit', 'debet'));
                container.appendChild(buildEntryRow('edit', 'kredit'));
            }

            refreshTotals('edit');

            document.getElementById('edit-loading').classList.add('hidden');
            document.getElementById('edit-form-wrapper').classList.remove('hidden');
        })
        .catch(err => {
            console.error(err);
            document.getElementById('edit-loading').innerHTML = `
                <i class="fa-solid fa-circle-exclamation text-2xl text-rose-400"></i>
                <p class="text-sm font-medium text-rose-500">Gagal memuat data. Coba lagi.</p>
                <button onclick="closeModal('edit')" class="mt-2 text-xs text-slate-500 underline">Tutup</button>
            `;
        });
}

document.getElementById('edit-form').addEventListener('submit', function(e) {
    document.querySelectorAll('.modal-validation-error').forEach(el => el.remove());
    validateForm('edit', e);
});

// ─── Close on backdrop click ──────────────────────────────────────────────
['add-modal', 'edit-modal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) closeModal(id.replace('-modal', ''));
    });
});

// ─── Auto-hide flash ──────────────────────────────────────────────────────
const flash = document.getElementById('flash-success');
if (flash) setTimeout(() => flash.remove(), 4000);
</script>
@endsection