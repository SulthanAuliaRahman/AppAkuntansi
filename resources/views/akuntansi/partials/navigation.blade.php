@php
$tabs = [
    ['label' => 'Dashboard',            'route' => 'dashboard',    'icon' => 'fa-gauge-high'],
    ['label' => 'Klasifikasi Akun',     'route' => 'akuntansi.klasifikasi.index', 'icon' => 'fa-file-invoice-dollar'],
    ['label' => 'Saldo Awal',        'route' => 'akuntansi.saldoawal',    'icon' => 'fa-solid fa-coins'],
    ['label' => 'Jurnal Umum',       'route' => 'akuntansi.jurnal',       'icon' => 'fa-book-open'],
    ['label' => 'Buku Besar',        'route' => 'akuntansi.bukubesar',    'icon' => 'fa-folder-closed'],
    ['label' => 'Neraca Saldo',      'route' => 'akuntansi.neracasaldo',  'icon' => 'fa-scale-balanced'],
    ['label' => 'Penyesuaian',       'route' => 'akuntansi.penyesuaian',  'icon' => 'fa-sliders'],
    ['label' => 'Kertas Kerja',      'route' => 'akuntansi.kertaskerja',  'icon' => 'fa-table-cells'],
    ['label' => 'Laporan Keuangan', 'route' => 'akuntansi.laporan.keuangan', 'icon' => 'fa-file-invoice-dollar'],
    ['label' => 'Jurnal Penutup',    'route' => 'akuntansi.penutup',      'icon' => 'fa-box-archive'],
];
@endphp

<nav class="flex flex-wrap gap-2 p-1.5 bg-slate-200/80 rounded-2xl mb-6 backdrop-blur-sm shadow-inner sticky top-[72px] z-30">
    @foreach ($tabs as $tab)
        <a href="{{ route($tab['route']) }}"
           class="px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-2
               {{ request()->routeIs($tab['route']) ? 'bg-white text-indigo-700 shadow-md' : 'text-slate-600 hover:text-indigo-600 hover:bg-white/50' }}">
            <i class="fa-solid {{ $tab['icon'] }}"></i> {{ $tab['label'] }}
        </a>
    @endforeach
</nav>
