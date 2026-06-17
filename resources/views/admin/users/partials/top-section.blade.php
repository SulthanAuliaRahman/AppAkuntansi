{{-- Banner Konsep --}}
<div class="bg-blue-50 border border-blue-100 p-5 rounded-2xl flex items-start sm:items-center gap-4 shadow-sm mb-6">
    <div class="bg-blue-100 p-3 rounded-xl text-blue-600 hidden sm:flex items-center justify-center shrink-0">
        <i class="fa-solid fa-shield-halved text-2xl"></i>
    </div>
    <div>
        <h3 class="font-bold text-slate-800 text-sm md:text-base mb-1">Konsep Pembagian Role & Limitasi Kode Akun</h3>
        <p class="text-sm text-slate-600 leading-relaxed">
            Setiap pengguna dapat dibatasi hak penginputannya berdasarkan Role.<br>
            Contoh: Role <span class="font-semibold text-slate-800">STAFF_AP</span> hanya memproses akun <b>Kas & Hutang</b>.
        </p>
    </div>
</div>

{{-- Tab Navigation --}}
<nav class="flex flex-wrap gap-2 p-1.5 bg-slate-200/80 rounded-2xl backdrop-blur-sm shadow-inner sticky top-[72px] z-30 mb-6">
    <a href="{{ route('admin.users.index') }}"
       class="px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-2 {{ request()->routeIs('admin.users.*') ? 'bg-white text-indigo-700 shadow-md' : 'text-slate-600 hover:text-indigo-600 hover:bg-white/50' }}">
        <i class="fa-solid fa-users"></i> Daftar Users
    </a>
    <a href="{{ route('admin.roles.index') }}"
       class="px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-2 {{ request()->routeIs('admin.roles.*') ? 'bg-white text-indigo-700 shadow-md' : 'text-slate-600 hover:text-indigo-600 hover:bg-white/50' }}">
        <i class="fa-solid fa-user-tag"></i> Master Roles
    </a>
    <a href="{{ route('admin.akses-akun.index') }}"
       class="px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-2 {{ request()->routeIs('admin.akses-akun.*') ? 'bg-white text-indigo-700 shadow-md' : 'text-slate-600 hover:text-indigo-600 hover:bg-white/50' }}">
        <i class="fa-solid fa-key"></i> Pemetaan Akses
    </a>
</nav>
