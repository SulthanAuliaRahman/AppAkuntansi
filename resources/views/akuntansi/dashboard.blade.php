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
