{{-- resources/views/admin/users/partials/modal-role.blade.php --}}
<div id="modal-role" class="modal-wrapper fixed inset-0 z-50" role="dialog" aria-modal="true" aria-labelledby="modal-role-title">
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
            <div class="modal-panel relative bg-white rounded-2xl shadow-xl w-full sm:max-w-md opacity-0 translate-y-4 sm:scale-95 transition-all duration-300">

                <form action="{{ route('admin.roles.store') }}" method="POST" id="form-role">
                    @csrf
                    <input type="hidden" name="redirect_tab" value="roles">
                    <input type="hidden" name="_method" id="form-role-method" value="POST">

                    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 id="modal-role-title" class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <div id="modal-role-icon" class="bg-violet-100 text-violet-600 p-2 rounded-lg">
                                <i class="fa-solid fa-user-tag text-sm"></i>
                            </div>
                            <span id="modal-role-title-text">Form Master Role</span>
                        </h3>
                        <button type="button" onclick="closeModal('modal-role')"
                            class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 p-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="px-5 py-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                Nama Role <span class="text-slate-400 font-normal text-xs">(harus unik)</span>
                            </label>
                            <input type="text" name="nama_role" id="role-nama" required
                                placeholder="Contoh: STAFF_KASIR"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm uppercase
                                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi Wewenang</label>
                            <textarea name="deskripsi" id="role-deskripsi" rows="3"
                                placeholder="Jelaskan wewenang role ini..."
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm
                                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none resize-none transition-all"></textarea>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="is_full_access" value="0">
                            <input type="checkbox" name="is_full_access" value="1" id="role-full-access"
                                class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                            <label for="role-full-access" class="text-sm font-medium text-slate-700 cursor-pointer">
                                Bypass (Akses Penuh ke semua akun)
                            </label>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-5 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 rounded-b-2xl">
                        <button type="button" onclick="closeModal('modal-role')"
                            class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-white px-4 py-2.5
                                   text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl
                                   bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Role
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
