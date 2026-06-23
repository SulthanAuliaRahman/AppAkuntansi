<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - FinansialApps</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-indigo-50/40 via-white to-slate-50 text-slate-800 antialiased">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <section class="w-full max-w-md">
            <a href="{{ url('/') }}" class="mx-auto mb-7 flex w-fit items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white shadow-sm">
                    <i class="fa-solid fa-chart-pie text-xl"></i>
                </span>
                <span class="text-lg font-bold tracking-tight text-slate-900">FinansialApps</span>
            </a>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-lg shadow-slate-200/50 sm:p-8">
                <div class="mb-6 text-center">
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Login</h1>
                </div>

                @if (session('status'))
                    <div class="mb-5 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-4">
                            <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-indigo-600 transition-colors hover:text-indigo-700">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label for="remember_me" class="flex items-center gap-2 text-sm font-medium text-slate-600">
                        <input id="remember_me"
                               type="checkbox"
                               name="remember"
                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        Ingat saya
                    </label>

                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-100 transition-all hover:bg-indigo-700">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Login
                    </button>
                </form>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-sm font-semibold text-slate-500 transition-colors hover:text-indigo-600">
                    Kembali ke beranda
                </a>
            </div>
        </section>
    </main>
</body>
</html>
