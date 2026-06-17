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
        .modal-wrapper { display: none; }
        .modal-wrapper.open { display: block; animation: fadeIn 0.2s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
            <a href="{{ route('akuntansi.dashboard') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2 rounded-xl text-sm font-semibold transition-all flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </header>

    {{-- ========== NOTIFIKASI SUCCESS/ERROR ========== --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex justify-between items-center shadow-sm">
                <span><i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}</span>
                <button onclick="this.parentElement.style.display='none'"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    @endif

    {{-- ========== MAIN CONTENT ========== --}}
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        @yield('content')
    </main>

    {{-- Modal Konfirmasi Hapus (Global) --}}
    @include('admin.partials.modal-confirm-delete')

    {{-- ========== JAVASCRIPT GLOBAL ========== --}}
    <script>
        function openModal(modalId, isEdit = false, url = null) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            const backdrop = modal.querySelector('.modal-backdrop');
            const panel = modal.querySelector('.modal-panel');
            modal.classList.add('open');

            // Set Form Attributes for Users
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

            // Set Form Attributes for Roles
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

            // Animation
            requestAnimationFrame(() => requestAnimationFrame(() => {
                backdrop.classList.replace('opacity-0', 'opacity-100');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }));

            backdrop.onclick = () => closeModal(modalId);
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
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

        // Search Filter (Khusus Users)
        function filterTable(tableId, query) {
            const rows = document.querySelectorAll(`#${tableId} tbody tr`);
            if(!rows.length) return;
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
    </script>
</body>
</html>
