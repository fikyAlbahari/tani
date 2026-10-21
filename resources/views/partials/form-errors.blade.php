@if ($errors->any())
    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-[13.5px] text-rose-900 shadow-sm">
        <p class="flex items-center gap-2 font-extrabold">
            <i class="bi bi-exclamation-octagon-fill text-rose-500"></i>
            Data belum bisa disimpan
        </p>
        <ul class="mt-2 list-disc space-y-1 pl-5 font-semibold marker:text-rose-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
