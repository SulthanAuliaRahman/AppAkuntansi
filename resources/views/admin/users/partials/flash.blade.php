{{-- resources/views/admin/users/partials/flash.blade.php --}}
@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex justify-between items-center">
            <span><i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-rose-100 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex justify-between items-center">
            <span><i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-rose-500 hover:text-rose-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
@endif
