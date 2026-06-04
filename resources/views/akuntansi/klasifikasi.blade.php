@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Klasifikasi Akun (Chart of Accounts)</h2>
                <p class="text-sm text-slate-500">Pengaturan kode rekening akuntansi beserta parameter saldo normal sebelum pencatatan jurnal.</p>
            </div>
            <button onclick="openModal('create-modal')"
                class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md flex items-center gap-2 transition-all">
                <i class="fa-solid fa-plus"></i> Tambah Akun Klasifikasi
            </button>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                            <th class="py-3.5 px-5 w-32">Kode Perkiraan</th>
                            <th class="py-3.5 px-5">Nama Akun Rekening</th>
                            <th class="py-3.5 px-5">Kategori / Jenis Akun</th>
                            <th class="py-3.5 px-5 text-center w-36">Saldo Normal</th>
                            <th class="py-3.5 px-5 text-center w-28">Status</th>
                            <th class="py-3.5 px-5 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        @forelse ($akuns as $akun)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-5 font-bold text-indigo-700 tracking-wide">{{ $akun->kode_akun }}</td>
                            <td class="py-4 px-5 font-semibold text-slate-800">{{ $akun->nama_akun }}</td>
                            <td class="py-4 px-5">
                                <span class="bg-slate-100 text-slate-700 text-xs font-medium px-2.5 py-1 rounded-lg">
                                    {{ $akun->jenisAkun->kode }} - {{ $akun->jenisAkun->nama }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                @if($akun->saldo_normal === 'DEBET')
                                    <span class="bg-sky-50 text-sky-700 text-xs font-bold px-2.5 py-0.5 rounded-full border border-sky-100">DEBET</span>
                                @else
                                    <span class="bg-amber-50 text-amber-700 text-xs font-bold px-2.5 py-0.5 rounded-full border border-amber-100">KREDIT</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center">
                                @if($akun->aktif)
                                    <span class="inline-flex items-center gap-1.5 text-emerald-600 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-slate-400 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-slate-300"></span> Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center flex justify-center gap-2">
                                <button type="button"
                                    onclick="openEditModal({{ json_encode($akun) }})"
                                    class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 p-2 rounded-xl transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <form method="POST" action="{{ route('akuntansi.klasifikasi.destroy', $akun->id) }}"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus klasifikasi akun {{ $akun->nama_akun }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-2 rounded-xl transition-all">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center font-medium text-slate-400 italic bg-slate-50/50">
                                Belum ada klasifikasi akun yang dibuat. Klik tombol di kanan atas untuk menambahkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div id="create-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-container">
        <div class="bg-indigo-700 text-white p-5 flex justify-between items-center">
            <h3 class="text-base font-bold flex items-center gap-2"><i class="fa-solid fa-folder-plus"></i> Tambah Akun Klasifikasi</h3>
            <button onclick="closeModal('create-modal')" class="text-indigo-200 hover:text-white transition-all text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="{{ route('akuntansi.klasifikasi.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Jenis Akun</label>
                <select name="jenis_akun_id" id="create_jenis_akun_id" required class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="" disabled selected>-- Pilih Kategori Utama --</option>
                    @foreach ($jenisAkuns as $ja)
                        <option value="{{ $ja->id }}" data-kode="{{ $ja->kode }}">{{ $ja->kode }} - {{ $ja->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kode Akun Perkiraan (*)</label>
                    <div class="flex rounded-xl shadow-sm overflow-hidden border border-slate-200 focus-within:ring-2 focus-within:ring-indigo-500">
                        <span id="create-prefix-badge" class="inline-flex items-center px-3.5 bg-slate-100 text-slate-500 text-sm font-bold border-r border-slate-200 select-none">
                            ? .
                        </span>
                        <input type="text" name="kode_suffix" required placeholder="Contoh: 01"
                            class="w-full bg-slate-50 px-3.5 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Saldo Normal</label>
                    <select name="saldo_normal" required class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="DEBET">DEBET</option>
                        <option value="KREDIT">KREDIT</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Akun</label>
                <input type="text" name="nama_akun" required placeholder="Contoh: Kas Kecil, Piutang Usaha"
                    class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="pt-2 flex justify-end gap-3">
                <button type="button" onclick="closeModal('create-modal')"
                    class="px-5 py-2.5 rounded-xl text-slate-500 hover:bg-slate-50 font-semibold text-sm transition-all border border-slate-200">Batal</button>
                <button type="submit" class="bg-indigo-700 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition-all">Simpan Klasifikasi</button>
            </div>
        </form>
    </div>
</div>

<div id="edit-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-container">
        <div class="bg-slate-800 text-white p-5 flex justify-between items-center">
            <h3 class="text-base font-bold flex items-center gap-2"><i class="fa-solid fa-pen-to-square"></i> Ubah Klasifikasi Akun</h3>
            <button onclick="closeModal('edit-modal')" class="text-slate-300 hover:text-white transition-all text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" id="edit-form" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Jenis Akun</label>
                <select name="jenis_akun_id" id="edit_jenis_akun_id" required class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500">
                    @foreach ($jenisAkuns as $ja)
                        <option value="{{ $ja->id }}" data-kode="{{ $ja->kode }}">{{ $ja->kode }} - {{ $ja->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kode Akun Perkiraan (*)</label>
                    <div class="flex rounded-xl shadow-sm overflow-hidden border border-slate-200 focus-within:ring-2 focus-within:ring-slate-500">
                        <span id="edit-prefix-badge" class="inline-flex items-center px-3.5 bg-slate-100 text-slate-500 text-sm font-bold border-r border-slate-200 select-none">
                            ? .
                        </span>
                        <input type="text" name="kode_suffix" id="edit_kode_suffix" required
                            class="w-full bg-slate-50 px-3.5 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Saldo Normal</label>
                    <select name="saldo_normal" id="edit_saldo_normal" required class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500">
                        <option value="DEBET">DEBET</option>
                        <option value="KREDIT">KREDIT</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Akun</label>
                <input type="text" name="nama_akun" id="edit_nama_akun" required
                    class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Status Akun</label>
                <select name="aktif" id="edit_aktif" required class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif (Arsip)</option>
                </select>
            </div>
            <div class="pt-2 flex justify-end gap-3">
                <button type="button" onclick="closeModal('edit-modal')"
                    class="px-5 py-2.5 rounded-xl text-slate-500 hover:bg-slate-50 font-semibold text-sm transition-all border border-slate-200">Batal</button>
                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition-all">Perbarui Akun</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        const container = modal.querySelector('.modal-container');

        modal.style.display = 'flex';
        setTimeout(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const container = modal.querySelector('.modal-container');

        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 250);
    }

    // --- LOGIC OTOMATISASI PREFIX BADGE ---

    // Listener untuk Modal Tambah
    document.getElementById('create_jenis_akun_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const prefixKode = selectedOption.getAttribute('data-kode');
        document.getElementById('create-prefix-badge').textContent = prefixKode + '.';
    });

    // Listener untuk Modal Edit
    document.getElementById('edit_jenis_akun_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const prefixKode = selectedOption.getAttribute('data-kode');
        document.getElementById('edit-prefix-badge').textContent = prefixKode + '.';
    });

    function openEditModal(akunData) {
        // 1. Set Kategori Utama
        document.getElementById('edit_jenis_akun_id').value = akunData.jenis_akun_id;

        // 2. Perbarui Text Badge Berdasarkan Jenis Akun bawaan data
        const prefixKode = akunData.jenis_akun.kode;
        document.getElementById('edit-prefix-badge').textContent = prefixKode + '.';

        // 3. Pecah kode_akun asli untuk mengambil bagian (*) saja
        // Misal: "1.1.01" diganti bagian "1.1." menjadi "" sisa "01"
        const suffix = akunData.kode_akun.replace(prefixKode + '.', '');
        document.getElementById('edit_kode_suffix').value = suffix;

        // 4. Set input form lainnya
        document.getElementById('edit_nama_akun').value = akunData.nama_akun;
        document.getElementById('edit_saldo_normal').value = akunData.saldo_normal;
        document.getElementById('edit_aktif').value = akunData.aktif ? "1" : "0";

        const form = document.getElementById('edit-form');
        form.action = `/klasifikasi/${akunData.id}`;

        openModal('edit-modal');
    }
</script>
@endsection
