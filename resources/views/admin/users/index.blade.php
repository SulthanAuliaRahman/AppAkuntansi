<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User & Akses - Sistem Akuntansi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.3s ease-in-out; }
        .tab-btn.active { background-color: white; color: #4338ca; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .modal-wrapper { display: none; }
        .modal-wrapper.open { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    {{-- ========== HEADER ========== --}}
    @include('admin.users.partials.header')

    {{-- ========== FLASH NOTIFICATIONS ========== --}}
    @include('admin.users.partials.flash')

    {{-- ========== MAIN CONTENT ========== --}}
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- Info Banner --}}
        @include('admin.users.partials.banner')


        {{-- Tab Navigation --}}
        @include('admin.users.partials.tab-nav')

        {{-- Tab Content --}}
        @include('admin.users.partials.tab-users')
        @include('admin.users.partials.tab-roles')
        @include('admin.users.partials.tab-akses')

        {{-- Modals --}}
        @include('admin.users.partials.modal-user')
        @include('admin.users.partials.modal-role')
        @include('admin.users.partials.modal-akses')
        @include('admin.users.partials.modal-delete')

    </main>

    {{-- ========== JAVASCRIPT ========== --}}
    @include('admin.users.partials.scripts')

</body>
</html>
