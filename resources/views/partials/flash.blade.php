@if (session('success'))
    <div class="alert-auto mb-5 flex items-start gap-3 rounded-2xl border border-brand-200 bg-brand-50 px-4 py-3.5 text-[13.5px] font-semibold text-brand-900 shadow-sm">
        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-600 text-xs text-white">
            <i class="bi bi-check-lg"></i>
        </span>
        {{ session('success') }}
        <button type="button" class="ml-auto cursor-pointer text-brand-500 transition hover:text-brand-800" onclick="this.closest('.alert-auto').remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert-auto mb-5 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-[13.5px] font-semibold text-rose-900 shadow-sm">
        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-500 text-xs text-white">
            <i class="bi bi-exclamation-lg"></i>
        </span>
        {{ session('error') }}
        <button type="button" class="ml-auto cursor-pointer text-rose-400 transition hover:text-rose-700" onclick="this.closest('.alert-auto').remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
@endif
