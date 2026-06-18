@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Kategori Akun</h2>
                <p class="text-sm text-slate-500">Pengaturan kelompok/jenis akun sebagai induk dari klasifikasi akun (Chart of Accounts).</p>
            </div>
            <button onclick="openModal('create-modal')"
                class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md flex items-center gap-2 transition-all">
                <i class="fa-solid fa-plus"></i> Tambah Kategori
            </button>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            {{ session('success') }}
        </div>
        @endif

        {{-- Alert Error --}}
        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
            {{ session('error') }}
        </div>
        @endif

        {{-- Tabel --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                            <th class="py-3.5 px-5 w-32">Kode</th>
                            <th class="py-3.5 px-5">Nama Kategori</th>
                            <th class="py-3.5 px-5 text-center w-36">Jumlah Akun</th>
                            <th class="py-3.5 px-5 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        @forelse ($jenisAkuns as $ja)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-5 font-bold text-indigo-700 tracking-wide">{{ $ja->kode }}</td>
                            <td class="py-4 px-5 font-semibold text-slate-800">{{ $ja->nama }}</td>
                            <td class="py-4 px-5 text-center">
                                <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2.5 py-0.5 rounded-full border border-indigo-100">
                                    {{ $ja->akuns_count }} akun
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center flex justify-center gap-2">
                                <button type="button"
                                    onclick="openEditModal({{ json_encode($ja) }})"
                                    class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 p-2 rounded-xl transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <form method="POST" action="{{ route('akuntansi.kategori.destroy', $ja->id) }}"
                                    onsubmit="return confirm('Hapus kategori {{ $ja->nama }}? Pastikan tidak ada akun terdaftar.')" class="inline">
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
                            <td colspan="4" class="py-8 text-center font-medium text-slate-400 italic bg-slate-50/50">
                                Belum ada kategori akun. Klik tombol di kanan atas untuk menambahkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

{{-- Modal Tambah --}}
<div id="create-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-container">
        <div class="bg-indigo-700 text-white p-5 flex justify-between items-center">
            <h3 class="text-base font-bold flex items-center gap-2"><i class="fa-solid fa-folder-plus"></i> Tambah Kategori Akun</h3>
            <button onclick="closeModal('create-modal')" class="text-indigo-200 hover:text-white text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" action="{{ route('akuntansi.kategori.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kode Kategori</label>
                <input type="text" name="kode" required placeholder="Contoh:1.1"
                    class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <p class="text-xs text-slate-400 mt-1">Kode ini akan menjadi prefix kode akun klasifikasi.</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Kategori</label>
                <input type="text" name="nama" required placeholder="Contoh: Aset Lancar, Kewajiban"
                    class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="pt-2 flex justify-end gap-3">
                <button type="button" onclick="closeModal('create-modal')"
                    class="px-5 py-2.5 rounded-xl text-slate-500 hover:bg-slate-50 font-semibold text-sm border border-slate-200">Batal</button>
                <button type="submit" class="bg-indigo-700 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="edit-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-container">
        <div class="bg-slate-800 text-white p-5 flex justify-between items-center">
            <h3 class="text-base font-bold flex items-center gap-2"><i class="fa-solid fa-pen-to-square"></i> Ubah Kategori Akun</h3>
            <button onclick="closeModal('edit-modal')" class="text-slate-300 hover:text-white text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" id="edit-form" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kode Kategori</label>
                <input type="text" name="kode" id="edit_kode" required
                    class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500">
                <p class="text-xs text-amber-500 mt-1 font-medium">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Mengubah kode akan memengaruhi semua akun yang terdaftar di kategori ini.
                </p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Kategori</label>
                <input type="text" name="nama" id="edit_nama" required
                    class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>
            <div class="pt-2 flex justify-end gap-3">
                <button type="button" onclick="closeModal('edit-modal')"
                    class="px-5 py-2.5 rounded-xl text-slate-500 hover:bg-slate-50 font-semibold text-sm border border-slate-200">Batal</button>
                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.querySelector('.modal-container').classList.replace('scale-95', 'scale-100');
            modal.querySelector('.modal-container').classList.replace('opacity-0', 'opacity-100');
        }, 50);
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const container = modal.querySelector('.modal-container');
        container.classList.replace('scale-100', 'scale-95');
        container.classList.replace('opacity-100', 'opacity-0');
        setTimeout(() => { modal.style.display = 'none'; }, 250);
    }

    function openEditModal(data) {
        document.getElementById('edit_kode').value = data.kode;
        document.getElementById('edit_nama').value = data.nama;
        document.getElementById('edit-form').action = `/akuntansi/kategori/${data.id}`;
        openModal('edit-modal');
    }
</script>
@endsection
