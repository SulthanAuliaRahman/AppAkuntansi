{{-- resources/views/admin/users/partials/modal-user.blade.php --}}
<div id="modal-user" class="modal-wrapper fixed inset-0 z-50" role="dialog" aria-modal="true" aria-labelledby="modal-user-title">
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
            <div class="modal-panel relative bg-white rounded-2xl shadow-xl w-full sm:max-w-lg opacity-0 translate-y-4 sm:scale-95 transition-all duration-300">

                <form action="{{ route('admin.users.store') }}" method="POST" id="form-user">
                    @csrf
                    {{-- Tab aktif dikirim agar setelah redirect tetap di tab yang sama --}}
                    <input type="hidden" name="redirect_tab" value="users">
                    <input type="hidden" name="_method" id="form-user-method" value="POST">

                    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 id="modal-user-title" class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <div class="bg-indigo-100 text-indigo-600 p-2 rounded-lg">
                                <i class="fa-solid fa-user-plus text-sm"></i>
                            </div>
                            <span id="modal-user-title-text">Tambah Pengguna Baru</span>
                        </h3>
                        <button type="button" onclick="closeModal('modal-user')"
                            class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 p-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="px-5 py-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                                    <i class="fa-regular fa-id-card"></i>
                                </span>
                                <input type="text" name="name" id="user-name" required
                                    placeholder="Masukkan nama lengkap"
                                    class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm
                                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>
                                <input type="email" name="email" id="user-email" required
                                    placeholder="contoh@perusahaan.com"
                                    class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm
                                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                Password
                                <span id="modal-user-pass-hint" class="text-xs text-slate-400 font-normal hidden">
                                    (kosongkan jika tidak diubah)
                                </span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input type="password" name="password" id="user-password"
                                    placeholder="Min. 8 karakter"
                                    class="w-full rounded-xl border border-slate-300 pl-10 pr-10 py-2.5 text-sm
                                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                                <button type="button" onclick="togglePasswordVisibility(this)"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tetapkan Role</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                                    <i class="fa-solid fa-user-shield"></i>
                                </span>
                                <select name="role_id" id="user-role" required
                                    class="w-full rounded-xl border border-slate-300 pl-10 pr-8 py-2.5 text-sm
                                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none
                                           transition-all bg-white appearance-none cursor-pointer">
                                    <option value="" disabled selected>— Pilih Role Akuntansi —</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                                    @endforeach
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 pointer-events-none">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-5 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 rounded-b-2xl">
                        <button type="button" onclick="closeModal('modal-user')"
                            class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-white px-4 py-2.5
                                   text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl
                                   bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
