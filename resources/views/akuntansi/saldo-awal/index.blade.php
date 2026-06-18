@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">0. Saldo Awal Akun</h2>
                    <p class="text-sm text-slate-500">Setup saldo awal setiap akun untuk periode pertama</p>
                </div>
                <button onclick="openAddModal()"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Saldo Awal
                </button>
            </div>
        </div>

        <!-- Alerts -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
                <p class="text-sm font-semibold text-red-800 mb-2">⚠️ Validasi Error:</p>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="text-xl">✅</span>
                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('partial_success'))
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="text-xl">⚠️</span>
                <p class="text-sm font-semibold text-amber-800">{{ session('partial_success') }}</p>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Daftar Saldo Awal Akun</h3>
                <p class="text-xs text-slate-500 mt-1">Klik akun untuk edit saldo awalnya</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                            <th class="py-3 px-5">Kode</th>
                            <th class="py-3 px-5">Nama Akun</th>
                            <th class="py-3 px-5">Klasifikasi</th>
                            <th class="py-3 px-5">Saldo Normal</th>
                            <th class="py-3 px-5 text-right">Debet (Rp)</th>
                            <th class="py-3 px-5 text-right">Kredit (Rp)</th>
                            <th class="py-3 px-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        @forelse ($saldoAwals as $saldo)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-5">
                                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                                    {{ $saldo->kode_akun }}
                                </span>
                            </td>
                            <td class="py-3 px-5 font-medium text-slate-700">{{ $saldo->nama_akun }}</td>
                            <td class="py-3 px-5 text-xs text-slate-600">{{ $saldo->jenis_nama }}</td>
                            <td class="py-3 px-5 text-xs">
                                <span class="px-2 py-1 rounded {{ $saldo->saldo_normal === 'DEBET' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }} font-semibold">
                                    {{ ucfirst(strtolower($saldo->saldo_normal)) }}
                                </span>
                            </td>
                            <td class="py-3 px-5 text-right font-medium">
                                @if ($saldo->debet > 0)
                                    <span class="text-green-700">Rp {{ number_format($saldo->debet, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-right font-medium">
                                @if ($saldo->kredit > 0)
                                    <span class="text-blue-700">Rp {{ number_format($saldo->kredit, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('akuntansi.saldoawal.edit', $saldo->kode_akun) }}"
                                        class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 p-2 rounded-xl transition-all"
                                        title="Edit saldo awal">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('akuntansi.saldoawal.destroy', $saldo->kode_akun) }}" class="inline"
                                          onsubmit="return confirm('Hapus saldo awal akun {{ $saldo->kode_akun }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-2 rounded-xl transition-all" title="Hapus saldo awal">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 px-5 text-center text-slate-500">
                                Tidak ada data saldo awal
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-slate-100 bg-slate-50 text-xs text-slate-600">
                📌 Total akun: <span class="font-bold">{{ $saldoAwals->count() }}</span>
            </div>
        </div>
    </div>
</main>

<!-- Modal Tambah Saldo Awal -->
<div id="add-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden">
        <div class="bg-indigo-700 text-white p-5 flex justify-between items-center">
            <h3 class="text-base font-bold flex items-center gap-2"><i class="fa-solid fa-plus-circle"></i> Tambah Saldo Awal Akun</h3>
            <button onclick="closeModal()" class="text-indigo-200 hover:text-white transition-all text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="p-6">
            <form id="add-form" method="POST" action="{{ route('akuntansi.saldoawal.store') }}" class="space-y-4" novalidate>
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Pilih Akun</label>
                    <select name="kode_akun" id="add-kode_akun" required
                        class="w-full bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih Akun --</option>
                        @php
                            use App\Models\Akuns;
                            $allAkuns = Akuns::with('jenisAkun')->orderBy('kode_akun')->get();
                        @endphp
                        @foreach ($allAkuns as $akun)
                            @if (!in_array($akun->kode_akun, $saldoAwals->pluck('kode_akun')->toArray()))
                                <option value="{{ $akun->kode_akun }}">
                                    {{ $akun->kode_akun }} - {{ $akun->nama_akun }} ({{ $akun->jenisAkun->nama ?? 'N/A' }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('kode_akun')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Nominal (Rp)</label>
                    <input type="text" id="add-nominal-display"
                        value="{{ old('nominal') ? number_format((int) old('nominal'), 0, ',', '.') : '' }}"
                        inputmode="numeric"
                        oninput="formatNominalInput(this, 'add-nominal')"
                        class="w-full bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Contoh: 120.000.000">
                    <input type="hidden" name="nominal" id="add-nominal" value="{{ old('nominal', 0) }}">
                    @error('nominal')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Tipe Saldo</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-slate-200 rounded-xl hover:bg-blue-50 transition-colors">
                            <input type="radio" name="tipe" value="debit" class="w-4 h-4 text-indigo-600" {{ old('tipe') === 'debit' ? 'checked' : '' }} required>
                            <span class="flex-1">
                                <span class="font-semibold text-slate-700">Debit</span>
                                <p class="text-xs text-slate-500">Saldo debit (Asset, Biaya)</p>
                            </span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-slate-200 rounded-xl hover:bg-purple-50 transition-colors">
                            <input type="radio" name="tipe" value="kredit" class="w-4 h-4 text-indigo-600" {{ old('tipe') === 'kredit' ? 'checked' : '' }} required>
                            <span class="flex-1">
                                <span class="font-semibold text-slate-700">Kredit</span>
                                <p class="text-xs text-slate-500">Saldo kredit (Liabilitas, Modal, Pendapatan)</p>
                            </span>
                        </label>
                    </div>
                    @error('tipe')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                    <p class="font-semibold mb-1">💡 Aturan Saldo Awal:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Hanya <strong>Debet ATAU Kredit</strong> yang boleh diisi, tidak boleh keduanya</li>
                        <li>Minimal salah satu harus memiliki nilai > 0</li>
                    </ul>
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-200">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-semibold transition-colors">
                        <i class="fa-solid fa-save"></i> Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-800 px-6 py-2.5 rounded-xl font-semibold transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function formatNominalInput(el, hiddenId) {
    const raw = el.value.replace(/\./g, '').replace(/[^0-9]/g, '');
    document.getElementById(hiddenId).value = raw || '0';
    el.value = raw ? parseInt(raw, 10).toLocaleString('id-ID') : '';
}

function openAddModal() {
    document.getElementById('add-modal').style.display = 'flex';
    document.getElementById('add-form').reset();
    document.getElementById('add-nominal-display').value = '';
    document.getElementById('add-nominal').value = '0';
}

function closeModal() {
    document.getElementById('add-modal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('add-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

@endsection
