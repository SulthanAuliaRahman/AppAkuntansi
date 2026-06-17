{{-- resources/views/admin/users/partials/tab-roles.blade.php --}}
@php $activeTab = request('tab', 'users'); @endphp

<section id="tab-roles" class="tab-content {{ $activeTab === 'roles' ? 'active' : '' }} space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-bold text-slate-800">Master Role</h2>
        <button onclick="resetModalRole()"
            class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm
                   font-semibold shadow-md flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span class="hidden sm:inline">Tambah</span> Role
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                        <th class="py-3.5 px-5 w-14">#</th>
                        <th class="py-3.5 px-5 w-48">Nama Role</th>
                        <th class="py-3.5 px-5">Deskripsi</th>
                        <th class="py-3.5 px-5 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($roles as $role)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-5 font-medium text-slate-400">{{ $role->id }}</td>
                        <td class="py-3 px-5">
                            <div class="flex items-center gap-2">
                                <span class="{{ $role->badge ?? 'bg-slate-100 text-slate-700' }} text-xs font-bold px-2.5 py-1 rounded-lg">
                                    {{ $role->nama_role }}
                                </span>
                                @if($role->is_full_access)
                                    <span class="bg-indigo-100 text-indigo-700 text-[10px] font-bold px-1.5 py-0.5 rounded">
                                        BYPASS
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-5 text-slate-600">{{ $role->deskripsi ?? '—' }}</td>
                        <td class="py-3 px-5 text-center">
                            <button onclick="openModalEditRole({{ $role->id }}, '{{ addslashes($role->nama_role) }}', '{{ addslashes($role->deskripsi ?? '') }}', {{ $role->is_full_access ? 'true' : 'false' }}, '{{ route('admin.roles.update', $role->id) }}')"
                                title="Edit Role"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200 transition-colors">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <button onclick="confirmDelete('{{ addslashes($role->nama_role) }}', '{{ route('admin.roles.destroy', $role->id) }}', 'roles')"
                                title="Hapus Role"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition-colors ml-1">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center">
                            <i class="fa-solid fa-user-tag text-3xl text-slate-300 mb-2 block"></i>
                            <p class="text-sm text-slate-400">Belum ada role yang dibuat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
