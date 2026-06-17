{{-- resources/views/admin/users/partials/modal-akses.blade.php --}}
<div id="modal-akses" class="modal-wrapper fixed inset-0 z-50" role="dialog" aria-modal="true">
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
            <div class="modal-panel relative bg-white rounded-2xl shadow-xl w-full sm:max-w-2xl opacity-0 translate-y-4 sm:scale-95 transition-all duration-300">

                <form action="{{ route('admin.akses-akun.sync') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_tab" value="akses">

                    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <div class="bg-emerald-100 text-emerald-600 p-2 rounded-lg">
                                <i class="fa-solid fa-key text-sm"></i>
                            </div>
                            Pemetaan Akses Akun (Pivot)
                        </h3>
                        <button type="button" onclick="closeModal('modal-akses')"
                            class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 p-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="px-5 py-5 space-y-5">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Role yang Dikonfigurasi</label>
                            <select name="role_id" required
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm bg-white
                                       focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none appearance-none cursor-pointer">
                                <option value="" disabled selected>— Pilih Role —</option>
                                @foreach($roles as $r)
                                    @if(!$r->is_full_access)
                                        <option value="{{ $r->id }}">{{ $r->nama_role }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <label class="block text-sm font-semibold text-slate-800">
                                    Assign Kode Akun
                                    <span id="akses-count" class="ml-2 bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-0.5 rounded-full">
                                        0 dipilih
                                    </span>
                                </label>
                                <div class="flex gap-3">
                                    <button type="button" onclick="toggleAllAkun(true)"
                                        class="text-xs font-medium text-emerald-600 hover:underline">Pilih Semua</button>
                                    <button type="button" onclick="toggleAllAkun(false)"
                                        class="text-xs font-medium text-rose-500 hover:underline">Reset</button>
                                </div>
                            </div>

                            <div id="akun-checklist" class="max-h-72 overflow-y-auto custom-scrollbar border border-slate-200 rounded-xl bg-white divide-y divide-slate-100">
                                @foreach($akunGroups as $groupName => $akunsList)
                                    <div class="p-3 space-y-1.5">
                                        <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider pb-1">
                                            {{ $groupName }}
                                        </h4>
                                        @foreach($akunsList as $akun)
                                            <label class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors border border-transparent hover:border-slate-100">
                                                <input type="checkbox" name="akun_id[]" value="{{ $akun->kode_akun }}"
                                                    onchange="updateAksesCount()"
                                                    class="akun-checkbox w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                                                <span class="text-sm text-slate-700">
                                                    <code class="font-mono text-slate-400 mr-1 text-xs">{{ $akun->kode_akun }}</code>
                                                    {{ $akun->nama_akun }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-5 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 rounded-b-2xl">
                        <button type="button" onclick="closeModal('modal-akses')"
                            class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-white px-4 py-2.5
                                   text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl
                                   bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
                            <i class="fa-solid fa-arrows-rotate"></i> Sinkronisasi Pivot
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
