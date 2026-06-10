<section id="tab-users" class="tab-content active space-y-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h2 class="text-lg font-bold text-slate-800">Daftar Pengguna</h2>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative flex-1 sm:flex-none">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
                </div>
                <input type="text" id="search-users" oninput="filterTable('tbl-users', this.value)"
                    class="w-full sm:w-52 rounded-xl border border-slate-300 pl-9 pr-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all placeholder:text-slate-400"
                    placeholder="Cari nama atau email...">
            </div>
            <button onclick="openModal('modal-user')" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md flex items-center gap-2 shrink-0">
                <i class="fa-solid fa-plus"></i> <span class="hidden sm:inline">Tambah</span> User
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table id="tbl-users" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                        <th class="py-3.5 px-5 w-14">#</th>
                        <th class="py-3.5 px-5">Nama Lengkap</th>
                        <th class="py-3.5 px-5">Email</th>
                        <th class="py-3.5 px-5">Role</th>
                        <th class="py-3.5 px-5 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-5 font-medium text-slate-400">{{ $user['id'] }}</td>
                        <td class="py-3 px-5 font-semibold text-slate-800">{{ $user['name'] }}</td>
                        <td class="py-3 px-5 text-slate-500">{{ $user['email'] }}</td>
                        <td class="py-3 px-5">
                            <span class="{{ $user['role_class'] }} text-xs font-bold px-2.5 py-1 rounded-lg">{{ $user->role['nama_role'] }}</span>
                        </td>
                        <td class="py-3 px-5 text-center">
                            <button onclick="openModal('modal-user', true, '{{ route('admin.users.update', $user->id) }}')" title="Edit User" class="...">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="confirmDelete('{{ $user->name }}', '{{ route('admin.users.destroy', $user->id) }}')" title="Hapus User" class="...">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="users-empty" class="hidden py-12 text-center">
            <i class="fa-solid fa-users-slash text-3xl text-slate-300 mb-2"></i>
            <p class="text-sm text-slate-400">Tidak ada pengguna yang cocok dengan pencarian.</p>
        </div>
    </div>
</section>
