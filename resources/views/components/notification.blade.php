@if (session('success'))
    <div class="mb-5 flex items-center gap-3 rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-xs font-bold text-emerald-800 shadow-2xs">
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-white font-extrabold text-xs">✓</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="mb-5 flex items-center gap-3 rounded-2xl bg-rose-50 p-4 border border-rose-200 text-xs font-bold text-rose-800 shadow-2xs">
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-white font-extrabold text-xs">✕</span>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if (session('warning'))
    <div class="mb-5 flex items-center gap-3 rounded-2xl bg-amber-50 p-4 border border-amber-200 text-xs font-bold text-amber-800 shadow-2xs">
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-600 text-white font-extrabold text-xs">!</span>
        <span>{{ session('warning') }}</span>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="mb-5 rounded-2xl bg-rose-50 p-4 border border-rose-200 text-xs font-semibold text-rose-800 shadow-2xs">
        <div class="flex items-center gap-2 font-bold mb-1.5">
            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-rose-600 text-white text-[10px]">✕</span>
            <span>Please check the following errors:</span>
        </div>
        <ul class="list-disc list-inside space-y-1 text-rose-700 pl-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif