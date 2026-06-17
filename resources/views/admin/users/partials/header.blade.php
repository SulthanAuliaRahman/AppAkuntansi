{{-- resources/views/admin/users/partials/header.blade.php --}}
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
        <a href="{{ route('dashboard') }}"
           class="bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2 rounded-xl text-sm font-semibold transition-all flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</header>
