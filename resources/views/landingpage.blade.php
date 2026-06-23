<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Akuntansi Perusahaan Jasa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <nav class="sticky top-0 z-50 border-b border-slate-100 bg-white/90 backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white shadow-sm shadow-indigo-100">
                        <i class="fa-solid fa-chart-pie text-lg"></i>
                    </span>
                    <span>
                        <span class="block text-sm font-extrabold leading-tight tracking-tight text-slate-900 sm:text-base">FinansialApps</span>
                        <span class="block text-xs font-semibold text-slate-400">Akuntansi Perusahaan Jasa</span>
                    </span>
                </a>

                <div class="hidden items-center gap-7 md:flex">
                    <a href="#alur" class="text-sm font-semibold text-slate-500 transition-colors hover:text-indigo-600">Alur</a>
                    <a href="#fitur" class="text-sm font-semibold text-slate-500 transition-colors hover:text-indigo-600">Fitur</a>
                    <a href="#ringkas" class="text-sm font-semibold text-slate-500 transition-colors hover:text-indigo-600">Ringkasan</a>
                </div>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-100 transition-all hover:bg-indigo-700">
                            Dashboard
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-100 transition-all hover:bg-indigo-700">
                            Login
                            <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <header class="overflow-hidden bg-gradient-to-b from-indigo-50/60 via-white to-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-18 lg:px-8 lg:py-20">
            <div class="grid items-center gap-10 lg:grid-cols-[1.04fr_0.96fr]">
                <section class="max-w-2xl text-center lg:text-left">
                    <span class="inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-white px-3 py-1.5 text-xs font-bold text-indigo-700 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                        Sistem pembukuan jasa
                    </span>

                    <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                        Kelola siklus akuntansi dengan lebih rapi.
                    </h1>

                    <p class="mx-auto mt-5 max-w-xl text-base leading-7 text-slate-500 sm:text-lg lg:mx-0">
                        Dari jurnal umum sampai laporan keuangan, semua disusun dalam alur kerja yang jelas dan mudah dipantau.
                    </p>

                    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row lg:justify-start">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-950 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-slate-200 transition-all hover:bg-slate-800">
                                    Buka Dashboard
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-950 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-slate-200 transition-all hover:bg-slate-800">
                                    Masuk Sistem
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            @endauth
                        @endif
                        <a href="#alur" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm transition-all hover:border-indigo-100 hover:text-indigo-700">
                            Lihat Alur
                        </a>
                    </div>
                </section>

                <section class="mx-auto w-full max-w-xl">
                    <div class="rounded-3xl border border-slate-100 bg-white p-4 shadow-2xl shadow-slate-200/70">
                        <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-4">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-rose-400"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Seimbang</span>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs font-semibold text-slate-400">Total Debit</p>
                                <p class="mt-2 text-xl font-extrabold text-slate-950">Rp 128,5 jt</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs font-semibold text-slate-400">Total Kredit</p>
                                <p class="mt-2 text-xl font-extrabold text-slate-950">Rp 128,5 jt</p>
                            </div>
                        </div>

                        <div class="mt-3 rounded-2xl border border-slate-100 p-4">
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Progress laporan</p>
                                    <p class="text-xs text-slate-400">Periode berjalan</p>
                                </div>
                                <span class="text-sm font-extrabold text-indigo-600">85%</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100">
                                <div class="h-2 w-[85%] rounded-full bg-gradient-to-r from-indigo-600 to-blue-600"></div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-2">
                            <div class="flex items-center justify-between rounded-2xl bg-white px-4 py-3 ring-1 ring-slate-100">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                        <i class="fa-solid fa-book text-sm"></i>
                                    </span>
                                    <span class="text-sm font-semibold text-slate-700">Jurnal Umum</span>
                                </div>
                                <span class="text-xs font-bold text-slate-400">24 transaksi</span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl bg-white px-4 py-3 ring-1 ring-slate-100">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                        <i class="fa-solid fa-file-invoice-dollar text-sm"></i>
                                    </span>
                                    <span class="text-sm font-semibold text-slate-700">Laporan Keuangan</span>
                                </div>
                                <span class="text-xs font-bold text-emerald-600">Siap</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </header>

    <section id="ringkas" class="border-y border-slate-100 bg-white">
        <div class="mx-auto grid max-w-7xl gap-4 px-4 py-8 sm:grid-cols-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <i class="fa-solid fa-list-check"></i>
                </span>
                <div>
                    <p class="text-sm font-extrabold text-slate-900">Alur lengkap</p>
                    <p class="text-xs text-slate-500">Jurnal sampai penutup</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-scale-balanced"></i>
                </span>
                <div>
                    <p class="text-sm font-extrabold text-slate-900">Saldo tervalidasi</p>
                    <p class="text-xs text-slate-500">Debit dan kredit terpantau</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-file-export"></i>
                </span>
                <div>
                    <p class="text-sm font-extrabold text-slate-900">Laporan siap pakai</p>
                    <p class="text-xs text-slate-500">Ringkas untuk evaluasi</p>
                </div>
            </div>
        </div>
    </section>

    <section id="alur" class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto mb-10 max-w-2xl text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-slate-950">Alur pembukuan</h2>
                <p class="mt-3 text-sm leading-6 text-slate-500">Urutan kerja dibuat jelas agar proses akuntansi mudah diikuti dari awal sampai akhir periode.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 transition-all hover:-translate-y-0.5 hover:border-indigo-100 hover:bg-white hover:shadow-lg hover:shadow-slate-100">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-sm font-extrabold text-white">1</span>
                    <h3 class="mt-4 font-extrabold text-slate-950">Jurnal Umum</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Catat transaksi operasional secara kronologis.</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 transition-all hover:-translate-y-0.5 hover:border-indigo-100 hover:bg-white hover:shadow-lg hover:shadow-slate-100">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-sm font-extrabold text-white">2</span>
                    <h3 class="mt-4 font-extrabold text-slate-950">Buku Besar</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Lihat mutasi dan saldo akhir setiap akun.</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 transition-all hover:-translate-y-0.5 hover:border-indigo-100 hover:bg-white hover:shadow-lg hover:shadow-slate-100">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-sm font-extrabold text-white">3</span>
                    <h3 class="mt-4 font-extrabold text-slate-950">Penyesuaian</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Susun ayat penyesuaian dan kertas kerja.</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 transition-all hover:-translate-y-0.5 hover:border-indigo-100 hover:bg-white hover:shadow-lg hover:shadow-slate-100">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 text-sm font-extrabold text-white">4</span>
                    <h3 class="mt-4 font-extrabold text-slate-950">Laporan</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Hasilkan laporan keuangan dan jurnal penutup.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="border-t border-slate-100 bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div class="max-w-2xl">
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-950">Fitur utama</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Dirancang untuk membantu pekerjaan pembukuan harian tetap cepat, akurat, dan mudah dibaca.</p>
                </div>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="inline-flex w-fit items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all hover:text-indigo-700 hover:ring-indigo-100">
                        Mulai masuk
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                @endif
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-wallet text-lg"></i>
                    </span>
                    <h3 class="mt-5 font-extrabold text-slate-950">Metrik keuangan</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Pantau aset, pendapatan, beban, dan laba bersih dari dashboard.</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                        <i class="fa-solid fa-chart-pie text-lg"></i>
                    </span>
                    <h3 class="mt-5 font-extrabold text-slate-950">Visualisasi ringkas</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Informasi utama disajikan dalam kartu dan grafik yang mudah discan.</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                        <i class="fa-solid fa-shield-halved text-lg"></i>
                    </span>
                    <h3 class="mt-5 font-extrabold text-slate-950">Kontrol saldo</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Status debit dan kredit membantu menemukan ketidaksesuaian lebih cepat.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-950 py-8 text-slate-400">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-indigo-300">
                    <i class="fa-solid fa-chart-pie"></i>
                </span>
                <p class="text-xs">&copy; {{ date('Y') }} FinansialApps. Aplikasi akuntansi perusahaan jasa.</p>
            </div>
            <a href="{{ route('login') }}" class="text-xs font-semibold transition-colors hover:text-white">Login Sistem</a>
        </div>
    </footer>
</body>
</html>
