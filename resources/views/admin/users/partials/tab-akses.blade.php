<section id="tab-akses" class="tab-content space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-bold text-slate-800">Pemetaan Akses Akun per User</h2>
        <button onclick="openModal('modal-akses')" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md flex items-center gap-2">
            <i class="fa-solid fa-link"></i> <span class="hidden sm:inline">Atur</span> Koneksi
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                        <th class="py-3.5 px-5 w-1/5">User</th>
                        <th class="py-3.5 px-5">Kode Akun yang Diizinkan</th>
                        <th class="py-3.5 px-5 text-center w-32">Kelola</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-5">
                            <p class="font-bold text-slate-800">{{ $user['name'] }}</p>
                            <span class="inline-flex items-center gap-1 mt-1 {{ $user['role_class'] }} text-[11px] font-bold px-2 py-0.5 rounded-md">
                                <i class="fa-solid {{ $user['icon'] }} text-[9px]"></i> {{ $user['role'] }}
                            </span>
                        </td>
                        <td class="py-4 px-5">
                            @if($user['role'] === 'SUPERVISOR')
                                <span class="inline-flex items-center gap-1.5 bg-indigo-600 text-white text-xs font-bold px-3 py-1.5 rounded-md shadow-sm">
                                    <i class="fa-solid fa-asterisk text-[9px]"></i> Semua Akun (Bypass)
                                </span>
                            @else
                                <div class="flex flex-wrap gap-2">
                                    @if($user['role'] === 'STAFF_AP')
                                        <span class="bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md">111 — Kas</span>
                                        <span class="bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md">211 — Utang Usaha</span>
                                        <span class="bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md">212 — Utang Gaji</span>
                                        <span class="text-slate-400 text-xs font-medium self-center">3 akun</span>
                                    @else
                                        <span class="bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md">111 — Kas</span>
                                        <span class="bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md">112 — Piutang Usaha</span>
                                        <span class="bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md">411 — Pendapatan Jasa</span>
                                        <span class="text-slate-400 text-xs font-medium self-center">3 akun</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center">
                            <button onclick="openModal('modal-akses', {{ $user['id'] }})" class="bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 border border-slate-200 hover:border-indigo-200 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                Edit Akses
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
