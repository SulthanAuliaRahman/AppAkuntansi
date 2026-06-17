<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User & Akses - Sistem Akuntansi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.3s ease-in-out; }
        .tab-btn.active { background-color: white; color: #4338ca; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .modal-wrapper { display: none; }
        .modal-wrapper.open { display: block; }
        #confirm-delete-modal .modal-panel { max-width: 28rem; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    {{-- ========== HEADER ========== --}}
    <header class="bg-gradient-to-r from-indigo-700 via-blue-700 to-indigo-800 text-white shadow-xl sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-white/10 p-2.5 rounded-xl backdrop-blur-md border border-white/20">
                    <i class="fa-solid fa-users-gear text-2xl text-amber-300"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold tracking-wider text-indigo-200 uppercase">Administrator Panel</span>
                    <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">Manajemen Hak Akses</h1>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2 rounded-xl text-sm font-semibold transition-all flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </header>

    {{-- ========== NOTIFIKASI SUCCESS/ERROR ========== --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex justify-between items-center">
                <span><i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}</span>
                <button onclick="this.parentElement.style.display='none'"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    @endif

    {{-- ========== MAIN CONTENT ========== --}}
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- Banner Konsep --}}
        <div class="bg-blue-50 border border-blue-100 p-5 rounded-2xl flex items-start sm:items-center gap-4 shadow-sm">
            <div class="bg-blue-100 p-3 rounded-xl text-blue-600 hidden sm:flex items-center justify-center shrink-0">
                <i class="fa-solid fa-shield-halved text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm md:text-base mb-1">Konsep Pembagian Role & Limitasi Kode Akun</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Setiap pengguna dapat dibatasi hak penginputannya berdasarkan Role.<br>
                    Contoh: Role <span class="font-semibold text-slate-800">STAFF_AP</span> hanya memproses akun <b>Kas & Hutang</b>, sementara <span class="font-semibold text-slate-800">STAFF_AR</span> dikhususkan untuk mencatat <b>Piutang & Pendapatan</b>.
                </p>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex items-center gap-3">
                <div class="bg-indigo-100 text-indigo-600 p-3 rounded-xl shrink-0"><i class="fa-solid fa-users text-lg"></i></div>
                <div><p class="text-2xl font-extrabold text-slate-800">{{ $users->count() }}</p><p class="text-xs text-slate-500 font-medium">Total Users</p></div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex items-center gap-3">
                <div class="bg-violet-100 text-violet-600 p-3 rounded-xl shrink-0"><i class="fa-solid fa-user-tag text-lg"></i></div>
                <div><p class="text-2xl font-extrabold text-slate-800">{{ $roles->count() }}</p><p class="text-xs text-slate-500 font-medium">Role Aktif</p></div>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <nav class="flex flex-wrap gap-2 p-1.5 bg-slate-200/80 rounded-2xl backdrop-blur-sm shadow-inner sticky top-[72px] z-30">
            <button onclick="switchTab('users')" id="btn-users" class="tab-btn active px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-indigo-600 hover:bg-white/50 transition-all duration-200 flex items-center gap-2">
                <i class="fa-solid fa-users"></i> <span class="hidden sm:inline">1.</span> Daftar Users
            </button>
            <button onclick="switchTab('roles')" id="btn-roles" class="tab-btn px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-indigo-600 hover:bg-white/50 transition-all duration-200 flex items-center gap-2">
                <i class="fa-solid fa-user-tag"></i> <span class="hidden sm:inline">2.</span> Master Roles
            </button>
            <button onclick="switchTab('akses')" id="btn-akses" class="tab-btn px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-indigo-600 hover:bg-white/50 transition-all duration-200 flex items-center gap-2">
                <i class="fa-solid fa-key"></i> <span class="hidden sm:inline">3.</span> Pemetaan Akses
            </button>
        </nav>

        {{-- ========== MEMANGGIL FILE PARTIALS ========== --}}
        @include('admin.users.partials.tab-users')
        @include('admin.users.partials.tab-roles')
        @include('admin.users.partials.tab-akses')

    {{-- ========================================================
         MODALS AREA (Sekarang menggunakan Form)
         ======================================================== --}}

    {{-- MODAL: User --}}
    <div id="modal-user" class="modal-wrapper fixed inset-0 z-50" role="dialog" aria-modal="true">
        <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
                <div class="modal-panel relative bg-white rounded-2xl shadow-xl w-full sm:max-w-lg opacity-0 translate-y-4 sm:scale-95 transition-all duration-300">

                    <form action="{{ route('admin.users.store') }}" method="POST" id="form-user">
                        @csrf
                        {{-- Method spoofing untuk update (diatur via JS nanti jika edit) --}}
                        <input type="hidden" name="_method" id="form-user-method" value="POST">

                        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 id="modal-user-title" class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <div class="bg-indigo-100 text-indigo-600 p-2 rounded-lg"><i class="fa-solid fa-user-plus text-sm"></i></div>
                                Tambah Pengguna Baru
                            </h3>
                            <button type="button" onclick="closeModal('modal-user')" class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 p-1.5 rounded-lg transition-colors">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>
                        <div class="px-5 py-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none"><i class="fa-regular fa-id-card"></i></span>
                                    <input type="text" name="name" required placeholder="Masukkan nama lengkap" class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none"><i class="fa-regular fa-envelope"></i></span>
                                    <input type="email" name="email" required placeholder="contoh@perusahaan.com" class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Password <span id="modal-user-pass-hint" class="text-xs text-slate-400 font-normal hidden">(kosongkan jika tidak diubah)</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="password" id="user-password" placeholder="Min. 8 karakter" class="w-full rounded-xl border border-slate-300 pl-10 pr-10 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                                    <button type="button" onclick="togglePasswordVisibility(this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"><i class="fa-solid fa-eye"></i></button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tetapkan Role</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none"><i class="fa-solid fa-user-shield"></i></span>
                                    <select name="role_id" required class="w-full rounded-xl border border-slate-300 pl-10 pr-8 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all bg-white appearance-none cursor-pointer">
                                        <option value="" disabled selected>— Pilih Role Akuntansi —</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                                        @endforeach
                                    </select>
                                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 pointer-events-none"><i class="fa-solid fa-chevron-down text-xs"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-5 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 rounded-b-2xl">
                            <button type="button" onclick="closeModal('modal-user')" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors"><i class="fa-solid fa-floppy-disk"></i> Simpan Data</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: Role --}}
    <div id="modal-role" class="modal-wrapper fixed inset-0 z-50" role="dialog" aria-modal="true">
        <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
                <div class="modal-panel relative bg-white rounded-2xl shadow-xl w-full sm:max-w-md opacity-0 translate-y-4 sm:scale-95 transition-all duration-300">

                    <form action="{{ route('admin.roles.store') }}" method="POST" id="form-role">
                        @csrf
                        <input type="hidden" name="_method" id="form-role-method" value="POST">

                        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 id="modal-role-title" class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <div class="bg-violet-100 text-violet-600 p-2 rounded-lg"><i class="fa-solid fa-user-tag text-sm"></i></div> Form Master Role
                            </h3>
                            <button type="button" onclick="closeModal('modal-role')" class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 p-1.5 rounded-lg transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
                        </div>
                        <div class="px-5 py-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Role <span class="text-slate-400 font-normal text-xs">(harus unik)</span></label>
                                <input type="text" name="nama_role" required placeholder="Contoh: STAFF_KASIR" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none uppercase transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi Wewenang</label>
                                <textarea name="deskripsi" rows="3" placeholder="Jelaskan wewenang role ini..." class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none resize-none transition-all"></textarea>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="is_full_access" value="0">
                                <input type="checkbox" name="is_full_access" value="1" id="is_full_access" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                <label for="is_full_access" class="text-sm font-medium text-slate-700 cursor-pointer">Bypass (Akses Penuh)</label>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-5 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 rounded-b-2xl">
                            <button type="button" onclick="closeModal('modal-role')" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors"><i class="fa-solid fa-floppy-disk"></i> Simpan Role</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: Pemetaan Akses Akun --}}
    <div id="modal-akses" class="modal-wrapper fixed inset-0 z-50" role="dialog" aria-modal="true">
        <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
                <div class="modal-panel relative bg-white rounded-2xl shadow-xl w-full sm:max-w-2xl opacity-0 translate-y-4 sm:scale-95 transition-all duration-300">

                    <form action="{{ route('admin.akses-akun.sync') }}" method="POST">
                        @csrf
                        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <div class="bg-emerald-100 text-emerald-600 p-2 rounded-lg"><i class="fa-solid fa-key text-sm"></i></div> Pemetaan Akses Akun (Pivot)
                            </h3>
                            <button type="button" onclick="closeModal('modal-akses')" class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 p-1.5 rounded-lg transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
                        </div>
                        <div class="px-5 py-5 space-y-5">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Role yang Dikonfigurasi</label>
                                <select name="role_id" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none appearance-none cursor-pointer">
                                    <option value="" disabled selected>— Pilih Role —</option>
                                    @foreach($roles as $r)
                                        @if(!$r->is_full_access)
                                            <option value="{{ $r->id }}">{{ $r->nama_role }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <label class="block text-sm font-semibold text-slate-800">
                                        Assign Kode Akun <span id="akses-count" class="ml-2 bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-0.5 rounded-full">0 dipilih</span>
                                    </label>
                                    <div class="flex gap-3">
                                        <button type="button" onclick="toggleAllAkun(true)" class="text-xs font-medium text-emerald-600 hover:underline">Pilih Semua</button>
                                        <button type="button" onclick="toggleAllAkun(false)" class="text-xs font-medium text-rose-500 hover:underline">Reset</button>
                                    </div>
                                </div>

                                <div id="akun-checklist" class="max-h-72 overflow-y-auto custom-scrollbar border border-slate-200 rounded-xl bg-white divide-y divide-slate-100">
                                    @foreach($akunGroups as $groupName => $akunsList)
                                        <div class="p-3 space-y-1.5">
                                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider pb-1">{{ $groupName }}</h4>
                                            @foreach($akunsList as $akun)
                                                <label class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors border border-transparent hover:border-slate-100">
                                                    {{-- Menggunakan ID atau Kode Akun (Pastikan Controller menyesuaikan) --}}
                                                    <input type="checkbox" name="akun_id[]" value="{{ $akun->kode_akun }}" onchange="updateAksesCount()"
                                                        class="akun-checkbox w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                                                    <span class="text-sm text-slate-700"><code class="font-mono text-slate-400 mr-1 text-xs">{{ $akun->kode_akun }}</code>{{ $akun->nama_akun }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-5 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 rounded-b-2xl">
                            <button type="button" onclick="closeModal('modal-akses')" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors"><i class="fa-solid fa-arrows-rotate"></i> Sinkronisasi Pivot</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: Delete Confirm --}}
    <div id="modal-confirm-delete" class="modal-wrapper fixed inset-0 z-50" role="dialog" aria-modal="true">
        <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="modal-panel relative bg-white rounded-2xl shadow-xl w-full max-w-sm opacity-0 translate-y-4 sm:scale-95 transition-all duration-300">

                    <form action="" method="POST" id="form-delete">
                        @csrf
                        @method('DELETE')
                        <div class="p-6 text-center">
                            <div class="bg-rose-100 text-rose-500 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fa-solid fa-triangle-exclamation text-2xl"></i></div>
                            <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Data?</h3>
                            <p class="text-sm text-slate-500 mb-1">Anda akan menghapus:</p>
                            <p id="delete-target-name" class="text-sm font-bold text-slate-800 mb-4">—</p>
                            <p class="text-xs text-slate-400">Aksi ini tidak dapat dibatalkan.</p>
                        </div>
                        <div class="px-5 pb-5 flex gap-2">
                            <button type="button" onclick="closeModal('modal-confirm-delete')" class="flex-1 inline-flex justify-center items-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700 transition-colors"><i class="fa-solid fa-trash"></i> Ya, Hapus</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- ========== JAVASCRIPT ========== --}}
    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active', 'bg-white', 'text-indigo-700', 'shadow-md');
                el.classList.add('text-slate-600');
            });
            document.getElementById('tab-' + tabId).classList.add('active');
            const btn = document.getElementById('btn-' + tabId);
            btn.classList.add('active', 'bg-white', 'text-indigo-700', 'shadow-md');
            btn.classList.remove('text-slate-600');
        }

        // Logic Open Modal ditambahkan argumen url untuk menangani Edit Action
        function openModal(modalId, isEdit = false, url = null) {
            const modal = document.getElementById(modalId);
            const backdrop = modal.querySelector('.modal-backdrop');
            const panel = modal.querySelector('.modal-panel');
            modal.classList.add('open');

            if (modalId === 'modal-user') {
                const title = modal.querySelector('#modal-user-title');
                const hint  = modal.querySelector('#modal-user-pass-hint');
                const form  = document.getElementById('form-user');
                const method= document.getElementById('form-user-method');
                const pass  = document.getElementById('user-password');

                if (isEdit && url) {
                    title.innerHTML = `<div class="bg-amber-100 text-amber-600 p-2 rounded-lg"><i class="fa-solid fa-pen text-sm"></i></div> Edit Pengguna`;
                    hint.classList.remove('hidden');
                    form.action = url;
                    method.value = 'PUT';
                    pass.required = false;
                } else {
                    title.innerHTML = `<div class="bg-indigo-100 text-indigo-600 p-2 rounded-lg"><i class="fa-solid fa-user-plus text-sm"></i></div> Tambah Pengguna Baru`;
                    hint.classList.add('hidden');
                    form.action = "{{ route('admin.users.store') }}";
                    method.value = 'POST';
                    pass.required = true;
                    form.reset();
                }
            }

            if (modalId === 'modal-role') {
                const title = modal.querySelector('#modal-role-title');
                const form  = document.getElementById('form-role');
                const method= document.getElementById('form-role-method');

                if (isEdit && url) {
                    title.innerHTML = `<div class="bg-amber-100 text-amber-600 p-2 rounded-lg"><i class="fa-solid fa-pen text-sm"></i></div> Edit Role`;
                    form.action = url;
                    method.value = 'PUT';
                } else {
                    title.innerHTML = `<div class="bg-violet-100 text-violet-600 p-2 rounded-lg"><i class="fa-solid fa-user-tag text-sm"></i></div> Form Master Role`;
                    form.action = "{{ route('admin.roles.store') }}";
                    method.value = 'POST';
                    form.reset();
                }
            }

            requestAnimationFrame(() => requestAnimationFrame(() => {
                backdrop.classList.replace('opacity-0', 'opacity-100');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }));

            backdrop.onclick = () => closeModal(modalId);
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const backdrop = modal.querySelector('.modal-backdrop');
            const panel = modal.querySelector('.modal-panel');

            backdrop.classList.replace('opacity-100', 'opacity-0');
            panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            panel.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');

            setTimeout(() => modal.classList.remove('open'), 300);
        }

        function confirmDelete(name, deleteUrl) {
            document.getElementById('delete-target-name').textContent = name;
            document.getElementById('form-delete').action = deleteUrl;
            openModal('modal-confirm-delete');
        }

        function filterTable(tableId, query) {
            const rows = document.querySelectorAll(`#${tableId} tbody tr`);
            const q = query.toLowerCase().trim();
            let visible = 0;
            rows.forEach(row => {
                const match = row.textContent.toLowerCase().includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            const empty = document.getElementById('users-empty');
            if (empty) empty.classList.toggle('hidden', visible > 0);
        }

        function togglePasswordVisibility(btn) {
            const input = btn.closest('.relative').querySelector('input');
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function updateAksesCount() {
            const count = document.querySelectorAll('.akun-checkbox:checked').length;
            const badge = document.getElementById('akses-count');
            if (badge) badge.textContent = count + ' dipilih';
        }

        function toggleAllAkun(checked) {
            document.querySelectorAll('.akun-checkbox').forEach(cb => cb.checked = checked);
            updateAksesCount();
        }

        document.addEventListener('DOMContentLoaded', updateAksesCount);
    </script>
</body>
</html>
