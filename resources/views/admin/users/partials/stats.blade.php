{{-- resources/views/admin/users/partials/banner.blade.php --}}
<div class="bg-blue-50 border border-blue-100 p-5 rounded-2xl flex items-start sm:items-center gap-4 shadow-sm">
    <div class="bg-blue-100 p-3 rounded-xl text-blue-600 hidden sm:flex items-center justify-center shrink-0">
        <i class="fa-solid fa-shield-halved text-2xl"></i>
    </div>
    <div>
        <h3 class="font-bold text-slate-800 text-sm md:text-base mb-1">Konsep Pembagian Role & Limitasi Kode Akun</h3>
        <p class="text-sm text-slate-600 leading-relaxed">
            Setiap pengguna dapat dibatasi hak penginputannya berdasarkan Role.<br>
            Contoh: Role <span class="font-semibold text-slate-800">STAFF_AP</span> hanya memproses akun <b>Kas & Hutang</b>,
            sementara <span class="font-semibold text-slate-800">STAFF_AR</span> dikhususkan untuk mencatat <b>Piutang & Pendapatan</b>.
        </p>
    </div>
</div>
