<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Akuntansi Interaktif - Anugerah Sakti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- Top Header -->
    <header class="bg-gradient-to-r from-indigo-700 via-blue-700 to-indigo-800 text-white shadow-xl sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-white/10 p-2.5 rounded-xl backdrop-blur-md border border-white/20">
                    <i class="fa-solid fa-chart-pie text-2xl text-amber-300"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold tracking-wider text-indigo-200 uppercase">Aplikasi Akuntansi Interaktif</span>
                    <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">Perusahaan Jasa "Anugerah Sakti"</h1>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden md:block">
                    <p class="text-xs text-indigo-200">Kasus Kelompok 2</p>
                    <p class="text-sm font-semibold">Periode April 2008</p>
                </div>
                <div class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-pulse"></span>
                    Status Buku Seimbang (Balanced)
                </div>
            </div>
        </div>
    </header>

    @yield('content')

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-400 space-y-2">
            <p class="font-bold text-slate-500">Kelompok 2 - Perusahaan Jasa "Anugerah Sakti" © 2026</p>
            <p>Siklus Akuntansi Interaktif: Jurnal Umum, Buku Besar, Neraca Saldo, Jurnal Penyesuaian, Kertas Kerja, Laporan Keuangan, Jurnal Penutup.</p>
        </div>
    </footer>

    <!-- Toast Flash Message -->
    @if (session('success'))
    <div id="toast" class="fixed bottom-5 right-5 z-50 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3 transition-all duration-300">
        <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
        <span class="text-xs font-semibold">{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(function () {
            const t = document.getElementById('toast');
            if (t) { t.style.opacity = '0'; t.style.transform = 'translateY(10px)'; setTimeout(() => t.remove(), 300); }
        }, 3000);
    </script>
    @endif

</body>
</html>
