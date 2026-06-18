@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Edit Saldo Awal Akun</h2>
                    <p class="text-sm text-slate-500">Ubah saldo awal untuk akun: {{ $saldoAwal->kode_akun }}</p>
                </div>
                <a href="{{ route('akuntansi.saldoawal') }}"
                    class="bg-slate-300 hover:bg-slate-400 text-slate-800 px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
                    ← Kembali
                </a>
            </div>
        </div>

        <!-- Alert -->
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

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Account Info -->
            <div class="p-5 border-b border-slate-100 bg-slate-50">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase">Kode Akun</p>
                        <p class="text-lg font-bold text-slate-800 mt-1">{{ $akun->kode_akun }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase">Nama Akun</p>
                        <p class="text-lg font-bold text-slate-800 mt-1">{{ $akun->nama_akun }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase">Klasifikasi</p>
                        <p class="text-lg font-bold text-slate-800 mt-1">{{ $akun->jenis_nama }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase">Saldo Normal</p>
                        <p class="text-lg font-bold {{ $akun->saldo_normal === 'DEBET' ? 'text-blue-700' : 'text-purple-700' }} mt-1">
                            {{ ucfirst(strtolower($akun->saldo_normal)) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('akuntansi.saldoawal.update', $saldoAwal->kode_akun) }}" method="POST" class="p-5 space-y-5">
                @csrf
                @method('PUT')

                @php
                    $tipeSekarang = $saldoAwal->debet > 0 ? 'debit' : 'kredit';
                    $nominalSekarang = $saldoAwal->debet > 0 ? $saldoAwal->debet : $saldoAwal->kredit;
                @endphp

                <div>
                    <label class="text-sm font-semibold text-slate-700 block mb-2">
                        Nominal (Rp)
                    </label>
                    <input type="text"
                        id="nominal-display"
                        inputmode="numeric"
                        oninput="formatNominalInput(this)"
                        value="{{ number_format((int) old('nominal', $nominalSekarang), 0, ',', '.') }}"
                        class="w-full bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('nominal') border-red-500 @enderror"
                        placeholder="Contoh: 120.000.000">
                    <input type="hidden" name="nominal" id="nominal-hidden"
                        value="{{ old('nominal', $nominalSekarang) }}">
                    @error('nominal')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 block mb-2">
                        Tipe Saldo
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-slate-200 rounded-xl hover:bg-blue-50 transition-colors @error('tipe') border-red-500 @enderror">
                            <input type="radio" name="tipe" value="debit" class="w-4 h-4 text-indigo-600"
                                {{ old('tipe', $tipeSekarang) === 'debit' ? 'checked' : '' }} required>
                            <span class="flex-1">
                                <span class="font-semibold text-slate-700">Debit</span>
                                <p class="text-xs text-slate-500">Saldo debit (Asset, Biaya)</p>
                            </span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-slate-200 rounded-xl hover:bg-purple-50 transition-colors @error('tipe') border-red-500 @enderror">
                            <input type="radio" name="tipe" value="kredit" class="w-4 h-4 text-indigo-600"
                                {{ old('tipe', $tipeSekarang) === 'kredit' ? 'checked' : '' }} required>
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

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                    <p class="font-semibold mb-1">💡 Aturan Saldo Awal:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Hanya <strong>Debet ATAU Kredit</strong> yang boleh diisi, tidak boleh keduanya</li>
                        <li>Minimal salah satu harus memiliki nilai > 0</li>
                        <li>Sesuaikan dengan <strong>Saldo Normal</strong> akun</li>
                    </ul>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-5 border-t border-slate-200">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl font-semibold transition-colors">
                        💾 Simpan Perubahan
                    </button>
                    <a href="{{ route('akuntansi.saldoawal') }}"
                        class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-800 px-6 py-2 rounded-xl font-semibold transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
<script>
function formatNominalInput(el) {
    const raw = el.value.replace(/\./g, '').replace(/[^0-9]/g, '');
    document.getElementById('nominal-hidden').value = raw || '0';
    el.value = raw ? parseInt(raw, 10).toLocaleString('id-ID') : '';
}
</script>

@endsection
