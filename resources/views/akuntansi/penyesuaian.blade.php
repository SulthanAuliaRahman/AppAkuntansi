@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">4. Jurnal Penyesuaian (Adjusting Entries)</h2>
                <p class="text-sm text-slate-500">Pencatatan data penyesuaian akhir periode agar menggambarkan kondisi riil keuangan</p>
            </div>
            <div>
                <button onclick="openAddModal()"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah AJP Manual
                </button>
            </div>
        </div>

        {{-- Flash Success Notification --}}
        @if (session('success'))
        <div id="flash-success" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            {{ session('success') }}
        </div>
        @endif

        {{-- Server-Side Validation Errors --}}
        @if ($errors->any())
        <div class="flex flex-col gap-1 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium">
            <div class="flex items-center gap-2 font-bold mb-1"><i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Penyesuaian ditolak:</div>
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
                            <th class="py-3.5 px-5">Keterangan / Akun Perkiraan</th>
                            <th class="py-3.5 px-5">Ref (No. Perk)</th>
                            <th class="py-3.5 px-5 text-right">Debet (Rp)</th>
                            <th class="py-3.5 px-5 text-right">Kredit (Rp)</th>
                            <th class="py-3.5 px-5 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        @forelse ($ajeRows as $t)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-5 font-semibold text-slate-600">{{ $t['date'] }}</td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-800">{{ $accounts[$t['debitAcc']]['name'] ?? 'Akun tidak ditemukan' }}</div>
                                <div class="pl-5 text-slate-500 italic mt-1 font-medium">{{ $accounts[$t['creditAcc']]['name'] ?? 'Akun tidak ditemukan' }}</div>
                                <span class="text-xs text-indigo-600 block mt-1.5 font-medium">{{ $t['desc'] }}</span>
                            </td>
                            <td class="py-4 px-5">
                                <div class="text-slate-600 font-bold">{{ $t['debitAcc'] }}</div>
                                <div class="pl-5 text-slate-400 mt-1">{{ $t['creditAcc'] }}</div>
                            </td>
                            <td class="py-4 px-5 text-right font-semibold text-slate-800">@rupiah($t['debitAmount'])</td>
                            <td class="py-4 px-5 text-right font-semibold text-slate-800">
                                <div class="h-6"></div>
                                <div>@rupiah($t['creditAmount'])</div>
                            </td>
                            <td class="py-4 px-5 text-center">
                                <div class="flex justify-center gap-2">
                                    <button type="button"
                                        onclick="openEditModal({{ $t['id'] }})"
                                        class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 p-2 rounded-xl transition-all"
                                        title="Edit penyesuaian">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form method="POST" action="{{ route('akuntansi.penyesuaian.destroy', $t['id']) }}" class="inline"
                                          onsubmit="return confirm('Hapus entri penyesuaian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-2 rounded-xl transition-all" title="Hapus penyesuaian">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 font-medium bg-slate-50/30">
                                <i class="fa-solid fa-circle-info text-2xl mb-2 block text-slate-300"></i>
                                Belum ada entri Ayat Jurnal Penyesuaian yang tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 font-bold border-t-2 border-slate-200 text-slate-800">
                            <td colspan="3" class="py-4 px-5 text-right uppercase">Total Penyesuaian (AJP)</td>
                            <td class="py-4 px-5 text-right text-indigo-700">@rupiah($totalDebitAJE)</td>
                            <td class="py-4 px-5 text-right text-indigo-700">@rupiah($totalCreditAJE)</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</main>

{{-- ===== MODAL TAMBAH AJP MANUAl ===== --}}
<div id="add-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] flex flex-col" id="add-modal-container">
        <div class="bg-indigo-700 text-white p-5 flex justify-between items-center shrink-0">
            <h3 class="text-base font-bold flex items-center gap-2"><i class="fa-solid fa-file-invoice"></i> Catat Ayat Jurnal Penyesuaian</h3>
            <button onclick="closeModal('add')" class="text-indigo-200 hover:text-white transition-all text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="overflow-y-auto flex-1">
            <form id="add-form" method="POST" action="{{ route('akuntansi.penyesuaian.store') }}" class="p-6 space-y-4" novalidate>
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
                            placeholder="Keterangan penyesuaian">
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Entri Baris Akun</label>
                        <button type="button" onclick="addEntryRow('add-entries-container', 'add')"
                            class="text-indigo-600 hover:text-indigo-700 text-xs font-semibold flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-indigo-50 transition-all">
                            <i class="fa-solid fa-plus"></i> Tambah Baris
                        </button>
                    </div>

                    <div id="add-entries-container" class="space-y-2 bg-slate-50 p-3 rounded-xl border border-slate-200 min-h-[80px]">
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
                        <i class="fa-solid fa-paper-plane mr-1"></i>Posting AJP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL EDIT AJP ===== --}}
<div id="edit-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] flex flex-col" id="edit-modal-container">
        <div class="bg-slate-800 text-white p-5 flex justify-between items-center shrink-0">
            <h3 class="text-base font-bold flex items-center gap-2"><i class="fa-solid fa-pen-to-square"></i> Edit Ayat Jurnal Penyesuaian</h3>
            <button onclick="closeModal('edit')" class="text-slate-300 hover:text-white transition-all text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>

        {{-- Loading state --}}
        <div id="edit-loading" class="flex-1 flex flex-col items-center justify-center py-16 gap-3 text-slate-400">
            <i class="fa-solid fa-spinner fa-spin text-2xl"></i>
            <p class="text-sm font-medium">Memuat data transaksi penyesuaian…</p>
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
                            placeholder="Keterangan penyesuaian">
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Entri Baris Akun</label>
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
const akunsList = @json($akunsList ?? []);

function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(value);
}

function akunOptions(selectedId = '') {
    return akunsList.map(a =>
        `<option value="${a.id}" ${a.id == selectedId ? 'selected' : ''}>
            ${a.kode_akun} – ${a.nama_akun}
        </option>`
    ).join('');
}

let _rowId = 0;
function buildEntryRow(prefix, posisi = 'DEBET', akunId = '', nominal = '') {
    const id  = ++_rowId;
    const key = `entries[${id}]`;
    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 items-center entry-row';

    row.innerHTML = `
        <select name="${key}[posisi]"
            class="col-span-3 entry-type bg-white border border-slate-200 px-2 py-2 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="DEBET"  ${posisi === 'DEBET'  ? 'selected' : ''}>Debet</option>
            <option value="KREDIT" ${posisi === 'KREDIT' ? 'selected' : ''}>Kredit</option>
        </select>

        <select name="${key}[akun_id]"
            class="col-span-5 entry-akun bg-white border border-slate-200 px-2 py-2 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
            <option value="">– Pilih Akun –</option>
            ${akunOptions(akunId)}
        </select>

        <input type="number" name="${key}[nominal]"
            value="${nominal}"
            placeholder="Nominal" min="1000" step="1000"
            class="col-span-3 entry-amount bg-white border border-slate-200 px-2 py-2 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400" required>

        <button type="button" title="Hapus baris"
            class="col-span-1 remove-btn text-rose-400 hover:text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition-all flex items-center justify-center">
            <i class="fa-solid fa-trash-can text-sm"></i>
        </button>
    `;

    row.querySelector('.remove-btn').addEventListener('click', () => {
        row.remove();
        refreshTotals(prefix);
    });

    row.querySelectorAll('.entry-type, .entry-akun, .entry-amount').forEach(el => {
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

function refreshTotals(prefix) {
    const containerId = prefix + '-entries-container';
    let debit = 0, credit = 0;

    document.querySelectorAll(`#${containerId} .entry-row`).forEach(row => {
        const posisi = row.querySelector('.entry-type').value;
        const nominal = parseInt(row.querySelector('.entry-amount').value) || 0;
        if (posisi === 'DEBET') debit += nominal;
        else credit += nominal;
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
        [debitEl, creditEl].forEach(el => {
            el.classList.remove('text-slate-400', 'text-rose-600');
            el.classList.add('text-green-600');
        });
        iconEl.className = 'fa-solid fa-equals text-2xl text-green-500';
        indEl.classList.remove('border-slate-200', 'border-rose-300');
        indEl.classList.add('border-green-300', 'bg-green-50');
        msgEl.textContent = '✓ Jurnal AJP balance — siap diposting.';
        msgEl.className   = 'text-xs mt-2 font-semibold text-green-600';
        msgEl.classList.remove('hidden');
        btnEl.disabled    = false;
        btnEl.classList.remove('opacity-50', 'cursor-not-allowed');
    } else if (debit === 0 && credit === 0) {
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

function validateForm(prefix, e) {
    const containerId = prefix + '-entries-container';
    const errors = [];

    const date = document.getElementById(`${prefix}-date`)?.value;
    const desc = document.getElementById(`${prefix}-desc`)?.value;

    if (!date) errors.push('Tanggal wajib diisi.');
    if (!desc || !desc.trim()) errors.push('Keterangan wajib diisi.');

    let debit = 0, credit = 0, rowIndex = 0;
    document.querySelectorAll(`#${containerId} .entry-row`).forEach(row => {
        rowIndex++;
        const posisi = row.querySelector('.entry-type').value;
        const akun = row.querySelector('.entry-akun').value;
        const nominal = parseInt(row.querySelector('.entry-amount').value) || 0;

        if (!akun) errors.push(`Baris ${rowIndex}: akun perkiraan belum dipilih.`);
        if (nominal < 1000) errors.push(`Baris ${rowIndex}: nominal minimal Rp 1.000.`);

        if (posisi === 'DEBET') debit += nominal;
        else credit += nominal;
    });

    if (rowIndex < 2) errors.push('Minimal harus menyertakan 2 baris entri (Debet & Kredit).');

    if (debit !== credit) {
        errors.push(`Jurnal tidak balance! Selisih: ${formatRupiah(Math.abs(debit - credit))}.`);
    }

    if (errors.length) {
        e.preventDefault();
        showValidationError(errors);
        return false;
    }
    return true;
}

function showValidationError(errors) {
    const existing = document.querySelector('.modal-validation-error');
    if (existing) existing.remove();

    const box = document.createElement('div');
    box.className = 'modal-validation-error bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-xl text-sm mx-6 mb-0 -mt-2';
    box.innerHTML = `<div class="font-bold flex items-center gap-2 mb-1"><i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Posting gagal:</div>` +
        errors.map(e => `<div class="pl-4">• ${e}</div>`).join('');

    const activeForm = document.querySelector('#add-modal[style*="flex"]') 
        ? document.getElementById('add-form')
        : document.getElementById('edit-form');

    if (activeForm) {
        const btnRow = activeForm.querySelector('.pt-2.flex.justify-end');
        activeForm.insertBefore(box, btnRow);
        box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function openModal(prefix) {
    const modal = document.getElementById(`${prefix}-modal`);
    const container = document.getElementById(`${prefix}-modal-container`);
    modal.style.display = 'flex';
    requestAnimationFrame(() => requestAnimationFrame(() => {
        container.classList.remove('scale-95', 'opacity-0');
        container.classList.add('scale-100', 'opacity-100');
    }));
}

window.closeModal = function(prefix) {
    const modal = document.getElementById(`${prefix}-modal`);
    const container = document.getElementById(`${prefix}-modal-container`);
    if(container) {
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
    }
    setTimeout(() => { if(modal) modal.style.display = 'none'; }, 250);
    document.querySelectorAll('.modal-validation-error').forEach(el => el.remove());
}

function openAddModal() {
    const container = document.getElementById('add-entries-container');
    container.innerHTML = '';
    container.appendChild(buildEntryRow('add', 'DEBET'));
    container.appendChild(buildEntryRow('add', 'KREDIT'));
    refreshTotals('add');
    document.getElementById('add-date').value = '';
    document.getElementById('add-desc').value = '';
    openModal('add');
}

document.getElementById('add-form').addEventListener('submit', function(e) {
    document.querySelectorAll('.modal-validation-error').forEach(el => el.remove());
    validateForm('add', e);
});

function openEditModal(ajpId) {
    document.getElementById('edit-loading').classList.remove('hidden');
    document.getElementById('edit-form-wrapper').classList.add('hidden');
    document.getElementById('edit-entries-container').innerHTML = '';
    document.querySelectorAll('.modal-validation-error').forEach(el => el.remove());

    openModal('edit');

    fetch(`/penyesuaian/${ajpId}/details`)
        .then(r => {
            if (!r.ok) throw new Error(`HTTP ${r.status}`);
            return r.json();
        })
        .then(data => {
            document.getElementById('edit-date').value = data.tanggal;
            document.getElementById('edit-desc').value = data.keterangan;
            document.getElementById('edit-form').action = `/penyesuaian/${data.id}`;

            const container = document.getElementById('edit-entries-container');
            container.innerHTML = '';

            if (data.details && data.details.length > 0) {
                data.details.forEach(d => {
                    container.appendChild(
                        buildEntryRow('edit', d.posisi, d.akun_id, d.nominal)
                    );
                });
            } else {
                container.appendChild(buildEntryRow('edit', 'DEBET'));
                container.appendChild(buildEntryRow('edit', 'KREDIT'));
            }

            refreshTotals('edit');
            document.getElementById('edit-loading').classList.add('hidden');
            document.getElementById('edit-form-wrapper').classList.remove('hidden');
        })
        .catch(err => {
            console.error(err);
            document.getElementById('edit-loading').innerHTML = `
                <i class="fa-solid fa-circle-exclamation text-2xl text-rose-400"></i>
                <p class="text-sm font-medium text-rose-500">Gagal mengambil data penyesuaian.</p>
                <button onclick="closeModal('edit')" class="mt-2 text-xs text-slate-500 underline">Tutup</button>
            `;
        });
}

document.getElementById('edit-form').addEventListener('submit', function(e) {
    document.querySelectorAll('.modal-validation-error').forEach(el => el.remove());
    validateForm('edit', e);
});

['add-modal', 'edit-modal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) closeModal(id.replace('-modal', ''));
    });
});

const flash = document.getElementById('flash-success');
if (flash) setTimeout(() => flash.remove(), 4000);
</script>
@endsection