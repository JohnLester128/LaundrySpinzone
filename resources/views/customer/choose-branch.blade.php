<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choose Branch — Spinzone Laundry</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 flex items-center justify-center p-4 sm:p-6 antialiased selection:bg-blue-500 selection:text-white relative overflow-x-hidden">
    
    <!-- Ambient Background Blobs -->
    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-blue-100/60 blur-3xl"></div>
        <div class="absolute top-1/3 -right-32 h-96 w-96 rounded-full bg-indigo-100/50 blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/4 h-96 w-96 rounded-full bg-teal-100/40 blur-3xl"></div>
    </div>

    <main class="w-full max-w-md relative z-10">
        <!-- Back to Home Link -->
        <div class="mb-3.5 flex justify-start">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Back to Home</span>
            </a>
        </div>

        <!-- Main Card -->
        <div class="relative overflow-hidden rounded-3xl bg-white/95 p-7 sm:p-9 shadow-xl shadow-slate-900/5 border border-slate-200/80 backdrop-blur-md">
            
            <!-- Top Gradient Bar -->
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-teal-500"></div>

            <!-- Logo & Brand Header -->
            <div class="flex flex-col items-center text-center">
                <!-- Water Drop Icon Badge -->
                <div class="mb-3.5 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/25">
                    <svg class="h-7 w-7 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2.69c0 0-6.36 7.42-6.36 11.66A6.36 6.36 0 0 0 12 20.71a6.36 6.36 0 0 0 6.36-6.36C18.36 10.11 12 2.69 12 2.69zM12 18.7a4.36 4.36 0 0 1-4.36-4.35c0-2.31 3.12-6.52 4.36-8.08 1.24 1.56 4.36 5.76 4.36 8.08A4.36 4.36 0 0 1 12 18.7z"/>
                    </svg>
                </div>

                <!-- Brand Title -->
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    Spinzone <span class="text-blue-600">Laundry</span>
                </h1>
                
                <!-- Tagline -->
                <p class="mt-2 text-[10px] font-bold tracking-[0.24em] text-slate-400 uppercase font-mono">
                    FRESH &amp; CLEAN EVERY <span class="font-extrabold text-blue-600">TIME</span>
                </p>
            </div>

            <!-- Section Header -->
            <div class="mt-6 text-center">
                <h2 class="text-lg font-bold text-slate-900">
                    Choose Your Branch
                </h2>
                <p class="text-xs text-slate-400 mt-1">Select the laundry branch nearest to you</p>
            </div>

            <!-- Notifications -->
            <div class="mt-4">
                <x-notification />
            </div>

            <!-- Branch Selection List -->
            <div class="mt-5 space-y-3">
                @forelse($branches as $branch)
                    @php
                        $displayName = \Illuminate\Support\Str::replaceLast(' Branch', '', $branch->name);
                        $lowerName = strtolower($displayName);
                        $isBaybay = str_contains($lowerName, 'baybay');
                        $isPanay = str_contains($lowerName, 'panay');
                        
                        $subtext = match(true) {
                            $isBaybay => 'Main Branch · Full Service',
                            $isPanay => 'Express Service Branch',
                            default => $branch->address ? $branch->address : 'Branch Location'
                        };
                    @endphp

                    <form method="POST" action="{{ route('branch.select') }}">
                        @csrf
                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                        <button type="submit" class="group flex w-full items-center justify-between rounded-2xl border border-slate-200/80 bg-white p-4 text-left shadow-2xs transition-all duration-200 hover:border-blue-400 hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 active:bg-blue-50/20 cursor-pointer">
                            
                            <!-- Left Icon Box -->
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white shadow-2xs">
                                <!-- Washing Machine Icon (Same for all branches) -->
                                <svg class="h-5 w-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                                    <rect x="4" y="3" width="16" height="18" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="13" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="8" cy="6" r="1" fill="currentColor"/>
                                    <circle cx="11" cy="6" r="1" fill="currentColor"/>
                                    <path d="M10.5 13.5a2 2 0 0 1 2.5-1" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <!-- Branch Info Text -->
                            <div class="ml-3.5 flex-1 overflow-hidden">
                                <span class="block text-sm font-bold text-slate-900 leading-snug group-hover:text-blue-600 transition-colors truncate">
                                    {{ $displayName }}
                                </span>
                                <span class="block text-xs font-medium text-slate-400 mt-0.5 truncate">
                                    {{ $subtext }}
                                </span>
                            </div>

                            <!-- Right Chevron Icon -->
                            <div class="ml-2 text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>
                        </button>
                    </form>
                @empty
                    <p class="text-center py-6 text-xs text-slate-400">No active branches found.</p>
                @endforelse
            </div>

            <!-- Footer Info Notice -->
            <div class="mt-7 pt-4 border-t border-slate-100 flex items-center justify-center gap-1.5 text-center">
                <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" d="M12 16v-4m0-4h.01"/>
                </svg>
                <p class="text-[11px] font-medium text-slate-400">
                    You can change your branch later in your profile.
                </p>
            </div>

        </div>
    </main>

</body>
</html>
