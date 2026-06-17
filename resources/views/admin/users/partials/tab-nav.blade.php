{{-- resources/views/admin/users/partials/tab-nav.blade.php --}}
{{--
    Tab aktif dibaca dari query string: ?tab=users|roles|akses
    Jika tidak ada, default ke 'users'.
    Setiap tombol menggunakan <a> tag agar tab state tersimpan di URL
    sehingga setelah redirect (store/update/delete), tab tetap terbuka.
--}}
@php $activeTab = request('tab', 'users'); @endphp

<nav class="flex flex-wrap gap-2 p-1.5 bg-slate-200/80 rounded-2xl backdrop-blur-sm shadow-inner sticky top-[72px] z-30">
    @foreach([
        'users' => ['icon' => 'fa-users',    'label' => 'Daftar Users'],
        'roles' => ['icon' => 'fa-user-tag', 'label' => 'Master Roles'],
        'akses' => ['icon' => 'fa-key',      'label' => 'Pemetaan Akses'],
    ] as $tab => $item)
        <a href="{{ request()->fullUrlWithQuery(['tab' => $tab]) }}"
           data-tab="{{ $tab }}"
           class="tab-btn px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-2
                  {{ $activeTab === $tab ? 'active text-indigo-700' : 'text-slate-600 hover:text-indigo-600 hover:bg-white/50' }}">
            <i class="fa-solid {{ $item['icon'] }}"></i> {{ $item['label'] }}
        </a>
    @endforeach
</nav>
