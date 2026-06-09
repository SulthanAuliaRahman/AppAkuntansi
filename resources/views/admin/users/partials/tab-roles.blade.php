<section id="tab-roles" class="tab-content space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-bold text-slate-800">Master Role</h2>
        <button onclick="openModal('modal-role')" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> <span class="hidden sm:inline">Tambah</span> Role
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
                    @foreach($roles as $role)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-5 font-medium text-slate-400">{{ $role['id'] }}</td>
                        <td class="py-3 px-5">
                            <span class="{{ $role['badge'] }} text-xs font-bold px-2.5 py-1 rounded-lg">{{ $role['nama'] }}</span>
                        </td>
                        <td class="py-3 px-5 text-slate-600">{{ $role['desc'] }}</td>
                        <td class="py-3 px-5 text-center">
                            <button onclick="openModal('modal-role', {{ $role['id'] }})" title="Edit Role" class="text-indigo-500 hover:text-indigo-700 hover:bg-indigo-50 p-1.5 rounded-lg transition-colors">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
