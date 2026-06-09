<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Akuntansi Perusahaan Jasa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-2 rounded-xl text-white">
                        <i class="fa-solid fa-chart-pie text-xl"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold tracking-wider text-indigo-600 uppercase block leading-none">Siklus Pemrosesan</span>
                        <span class="text-lg font-bold text-slate-900 tracking-tight">FinansialApps</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#fitur" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Fitur Utama</a>
                    <a href="#alur" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Siklus Akuntansi</a>
                    <a href="#keunggulan" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Keunggulan</a>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-sm font-semibold shadow-md shadow-indigo-100 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i> Login Sistem
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <header class="relative overflow-hidden bg-gradient-to-b from-indigo-50/50 via-white to-slate-50 py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                        Khusus Perusahaan Jasa
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-none">
                        Kelola Siklus Akuntansi <br>
                        <span class="bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">Secara Real-Time</span>
                    </h1>
                    <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto lg:mx-0">
                        Sistem otomasi akuntansi interaktif terintegrasi yang memproses pencatatan dari jurnal umum, penyesuaian, hingga menghasilkan laporan keuangan akurat dalam satu siklus penuh.
                    </p>
                    <div class="flex flex-wrap justify-center lg:justify-start gap-4 pt-2">
                        <a href="{{ route('login') }}" class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-3 rounded-xl font-semibold text-sm transition-all shadow-lg flex items-center gap-2">
                            Mulai Demo Aplikasi <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                        <a href="#fitur" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-6 py-3 rounded-xl font-semibold text-sm transition-all flex items-center gap-2">
                            Pelajari Layout
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-white p-4 rounded-3xl shadow-xl border border-slate-100 relative">
                        <div class="flex justify-between items-center pb-3 border-b border-slate-100 mb-4 text-xs text-slate-400">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 bg-rose-400 rounded-full"></span>
                                <span class="w-2.5 h-2.5 bg-amber-400 rounded-full"></span>
                                <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full"></span>
                            </div>
                            <span class="font-medium bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded">Status Balanced</span>
                        </div>
                        <div class="space-y-3">
                            <div class="h-24 bg-slate-50 rounded-2xl p-4 flex flex-col justify-between">
                                <div class="w-1/3 h-3 bg-slate-200 rounded"></div>
                                <div class="w-1/2 h-6 bg-indigo-200 rounded"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="h-20 bg-slate-50 rounded-2xl p-3 space-y-2">
                                    <div class="w-1/2 h-2 bg-slate-200 rounded"></div>
                                    <div class="w-3/4 h-4 bg-slate-300 rounded"></div>
                                </div>
                                <div class="h-20 bg-slate-50 rounded-2xl p-3 space-y-2">
                                    <div class="w-1/2 h-2 bg-slate-200 rounded"></div>
                                    <div class="w-3/4 h-4 bg-slate-300 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="alur" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 space-y-3">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900">Alur Pembukuan 1 Siklus Penuh</h2>
                <p class="text-sm text-slate-500">
                    Sistem memproses data secara berurutan sesuai kaidah standar akuntansi perusahaan jasa.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 hover:border-indigo-100 transition-all">
                    <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold text-sm mb-4">1</div>
                    <h3 class="font-bold text-slate-900 mb-1">Jurnal Umum</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Pencatatan seluruh transaksi operasional perusahaan secara kronologis berdasarkan bukti transaksi.</p>
                </div>

                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 hover:border-indigo-100 transition-all">
                    <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold text-sm mb-4">2</div>
                    <h3 class="font-bold text-slate-900 mb-1">Buku Besar & Neraca Saldo</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Klasifikasi otomatis mutasi saldo per akun perkiraan serta kompilasi saldo akhir sebelum penyesuaian.</p>
                </div>

                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 hover:border-indigo-100 transition-all">
                    <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold text-sm mb-4">3</div>
                    <h3 class="font-bold text-slate-900 mb-1">Penyesuaian & Kertas Kerja</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Penyusunan ayat penyesuaian akhir periode dan visualisasi kertas kerja lembar lajur 10 kolom.</p>
                </div>

                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 hover:border-indigo-100 transition-all">
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl flex items-center justify-center font-bold text-sm mb-4">4</div>
                    <h3 class="font-bold text-slate-900 mb-1">Laporan Keuangan</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Output siap pakai berupa Laporan Laba Rugi, Perubahan Ekuitas, Neraca, hingga Jurnal Penutup.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-16 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900">Fitur Utama Dashboard</h2>
                <p class="text-sm text-slate-500">Komponen visualisasi data pendukung analisis keuangan entitas jasa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl w-fit">
                        <i class="fa-solid fa-wallet text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Metrik Finansial Utama</h3>
                        <p class="text-xs text-slate-400 mt-1">Isi deskripsi layout kartu penampil Total Aset, Pendapatan, Beban Usaha, dan Laba Bersih di sini.</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl w-fit">
                        <i class="fa-solid fa-chart-pie text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Grafik Alokasi Interaktif</h3>
                        <p class="text-xs text-slate-400 mt-1">Isi deskripsi visualisasi struktur alokasi aset atau distribusi biaya beban menggunakan chart.</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl w-fit">
                        <i class="fa-solid fa-scale-balanced text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Validasi Saldo Seimbang</h3>
                        <p class="text-xs text-slate-400 mt-1">Isi deskripsi deteksi otomatis keseimbangan (matching balance) matematika debet dan kredit di sini.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-12 mt-auto border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-chart-pie text-2xl text-indigo-400"></i>
                <p class="text-xs">
                    &copy; {{ date('Y') }} FinansialApps. Hak Cipta Dilindungi Undang-Undang.
                </p>
            </div>
            <div class="flex gap-6 text-xs">
                <a href="#" class="hover:text-white transition-colors">Panduan Sistem</a>
                <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

</body>
</html>
