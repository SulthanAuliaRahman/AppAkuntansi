{{-- resources/views/admin/users/partials/tab-akses.blade.php --}}
@php $activeTab = request('tab', 'users'); @endphp

<section id="tab-akses" class="tab-content {{ $activeTab === 'akses' ? 'active' : '' }} space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-bold text-slate-800">Pemetaan Akses Akun per Role</h2>
        <button onclick="openModal('modal-akses')"
            class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl text-sm
                   font-semibold shadow-md flex items-center gap-2">
            <i class="fa-solid fa-link"></i>
            <span class="hidden sm:inline">Atur</span> Koneksi
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
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-5">
                            <p class="font-bold text-slate-800">{{ $user->name }}</p>
                            <span class="inline-flex items-center gap-1 mt-1 {{ $user->role_class ?? 'bg-slate-100 text-slate-600' }} text-[11px] font-bold px-2 py-0.5 rounded-md">
                                {{ $user->role->nama_role ?? '—' }}
                            </span>
                        </td>
                        <td class="py-4 px-5">
                            @if($user->role?->is_full_access)
                                <span class="inline-flex items-center gap-1.5 bg-indigo-600 text-white text-xs font-bold px-3 py-1.5 rounded-md shadow-sm">
                                    <i class="fa-solid fa-asterisk text-[9px]"></i> Semua Akun (Bypass)
                                </span>
                            @else
                                @php $akunList = $user->role?->aksesAkun ?? collect(); @endphp
                                <div class="flex flex-wrap gap-2">
                                    @forelse($akunList as $akun)
                                        <span class="bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md">
                                            {{ $akun->kode_akun }} — {{ $akun->nama_akun }}
                                        </span>
                                    @empty
                                        <span class="text-slate-400 text-xs italic">Belum ada akses dikonfigurasi</span>
                                    @endforelse
                                    @if($akunList->count() > 0)
                                        <span class="text-slate-400 text-xs font-medium self-center">
                                            {{ $akunList->count() }} akun
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center">
                            <button onclick="openModal('modal-akses')"
                                class="bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600
                                       border border-slate-200 hover:border-indigo-200 px-3 py-1.5 rounded-lg
                                       text-xs font-bold transition-colors">
                                Edit Akses
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-12 text-center">
                            <i class="fa-solid fa-key text-3xl text-slate-300 mb-2 block"></i>
                            <p class="text-sm text-slate-400">Tidak ada data akses.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
