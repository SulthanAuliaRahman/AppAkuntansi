@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-200">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl"><i class="fa-solid fa-wallet text-2xl"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Aset (Aktiva)</p>
                    <h3 class="text-xl font-bold text-slate-800">@rupiah($totalAssets)</h3>
                    <p class="text-xs text-emerald-600 mt-1"><i class="fa-solid fa-circle-check"></i> Balanced</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-200">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl"><i class="fa-solid fa-arrow-trend-up text-2xl"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-xl font-bold text-slate-800">@rupiah($totalRev)</h3>
                    <p class="text-xs text-slate-400 mt-1">Periode berjalan April</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-200">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl"><i class="fa-solid fa-receipt text-2xl"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Beban Usaha</p>
                    <h3 class="text-xl font-bold text-slate-800">@rupiah($totalExp)</h3>
                    <p class="text-xs text-rose-500 mt-1">Termasuk Penyusutan</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-200">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl"><i class="fa-solid fa-coins text-2xl"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Laba Bersih</p>
                    <h3 class="text-xl font-bold text-slate-800">@rupiah($netIncome)</h3>
                    <p class="text-xs text-emerald-600 font-medium mt-1">Laba Bersih Bertambah</p>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <h4 class="text-base font-bold text-slate-800 mb-1">Alokasi Aset Perusahaan</h4>
                    <p class="text-xs text-slate-400 mb-4">Representasi nilai likuiditas aset lancar & tetap</p>
                </div>
                <div class="relative w-full h-64"><canvas id="chart-assets"></canvas></div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <h4 class="text-base font-bold text-slate-800 mb-1">Struktur Laba Rugi</h4>
                    <p class="text-xs text-slate-400 mb-4">Perbandingan Pendapatan terhadap Beban-Beban</p>
                </div>
                <div class="relative w-full h-64"><canvas id="chart-income-structure"></canvas></div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <h4 class="text-base font-bold text-slate-800 mb-1">Rincian Pengeluaran Beban</h4>
                    <p class="text-xs text-slate-400 mb-4">Distribusi biaya operasional bulan April</p>
                </div>
                <div class="relative w-full h-64"><canvas id="chart-expenses-breakdown"></canvas></div>
            </div>
        </div>

        <!-- Sandbox Banner -->
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white p-6 rounded-3xl shadow-lg relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="bg-amber-400 text-slate-950 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">Sandbox Mode</span>
                        <h4 class="text-lg font-bold">Simulator Interaktif Akuntansi</h4>
                    </div>
                    <p class="text-sm text-indigo-200 max-w-2xl">
                        Bereksperimen mengubah transaksi di menu Jurnal Umum. Semua siklus akuntansi dihitung otomatis oleh server dari Jurnal hingga Laporan Keuangan!
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('akuntansi.jurnal') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Kelola Transaksi
                    </a>
                    <form method="POST" action="{{ route('akuntansi.reset') }}">
                        @csrf
                        <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all flex items-center gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Reset Data Kasus
                        </button>
                    </form>
                </div>
            </div>
            <div class="absolute -right-10 -bottom-10 text-white/5 text-9xl font-bold select-none pointer-events-none">
                <i class="fa-solid fa-sliders"></i>
            </div>
        </div>
    </div>
</main>

<script>
const chartData = @json($chartData);

document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('chart-assets').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Kas', 'Piutang', 'Perlengkapan', 'Sewa Dibayar Dimuka', 'Peralatan Neto'],
            datasets: [{ data: chartData.assets, backgroundColor: ['#4f46e5','#3b82f6','#10b981','#f59e0b','#8b5cf6'], borderWidth: 2, borderColor: '#ffffff' }]
        },
        options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } }, responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('chart-income-structure').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Laba Rugi'],
            datasets: [
                { label: 'Pendapatan',  data: [chartData.revenue],   backgroundColor: '#10b981', borderRadius: 8 },
                { label: 'Beban Usaha', data: [chartData.expenses],  backgroundColor: '#ef4444', borderRadius: 8 },
                { label: 'Laba Bersih', data: [chartData.netIncome], backgroundColor: '#f59e0b', borderRadius: 8 },
            ]
        },
        options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } }, scales: { y: { ticks: { font: { size: 9 } } } }, responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('chart-expenses-breakdown').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Gaji', 'Sewa', 'Iklan', 'Asuransi', 'Perlengkapan', 'Penyusutan'],
            datasets: [{ data: chartData.expBreakdown, backgroundColor: ['#ef4444','#f59e0b','#3b82f6','#10b981','#6366f1','#ec4899'] }]
        },
        options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } }, responsive: true, maintainAspectRatio: false }
    });
});
</script>
@endsection
