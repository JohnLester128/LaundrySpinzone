<x-admin-layout>
    <!-- Back Link & Header -->
    <div class="mb-6">
        <a href="{{ route('admin.service-requests') }}" class="inline-flex items-center gap-2 font-mono text-xs font-bold text-slate-500 hover:text-blue-600 transition group mb-3">
            <svg class="h-4 w-4 transform transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Service Requests</span>
        </a>

        @php
            $statusRaw = $serviceRequest->status;
            $cycle = $serviceRequest->cycle_info;
            $isResting = ($statusRaw === 'washing' && ($serviceRequest->cycle_phase === 'resting' || $cycle['phase'] === 'resting'));
            $isFolding = ($statusRaw === 'drying' && ($serviceRequest->cycle_phase === 'folding' || $cycle['phase'] === 'folding'));

            $statusLabel = match($statusRaw) {
                'pending' => 'Pending',
                'received' => 'Received',
                'washing' => $isResting ? 'Washed · Wash Buffer' : 'Washing',
                'drying' => $isFolding ? 'Folding & Packaging' : 'Drying',
                'ready_for_pickup', 'ready' => 'Ready for Pickup',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
                default => ucfirst($statusRaw)
            };

            $statusBadgeClass = match($statusRaw) {
                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                'received' => 'bg-slate-100 text-slate-700 border-slate-200',
                'washing' => $isResting ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-purple-100 text-purple-700 border-purple-200',
                'drying' => $isFolding ? 'bg-teal-100 text-teal-800 border-teal-300' : 'bg-indigo-100 text-indigo-700 border-indigo-200',
                'ready_for_pickup', 'ready' => 'bg-teal-100 text-teal-700 border-teal-200',
                'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
                default => 'bg-slate-100 text-slate-700 border-slate-200'
            };

            $statusDotClass = match($statusRaw) {
                'pending' => 'bg-amber-500',
                'received' => 'bg-slate-500',
                'washing' => $isResting ? 'bg-amber-500' : 'bg-purple-600',
                'drying' => $isFolding ? 'bg-teal-500' : 'bg-indigo-600',
                'ready_for_pickup', 'ready' => 'bg-teal-500',
                'completed' => 'bg-emerald-500',
                'cancelled' => 'bg-rose-500',
                default => 'bg-slate-500'
            };

            $nextStatus = match($statusRaw) {
                'pending' => 'received',
                'received' => 'washing',
                'washing' => 'drying',
                'drying' => 'ready_for_pickup',
                'ready_for_pickup', 'ready' => 'completed',
                default => null
            };

            $nextStatusLabel = match($nextStatus) {
                'received' => 'Accept & Mark as Received',
                'washing' => 'Move to Washing',
                'drying' => $isResting ? 'Move to Drying Now (Skip Buffer)' : 'Move to Drying',
                'ready_for_pickup' => $isFolding ? 'Finish Folding ➔ Ready for Pickup' : 'Mark as Ready for Pickup',
                'completed' => 'Complete Order & Collect Payment',
                default => null
            };

            $stepIndex = match($statusRaw) {
                'pending' => 1,
                'received' => 2,
                'washing' => 3,
                'drying' => 4,
                'ready_for_pickup', 'ready' => 5,
                'completed' => 6,
                default => 1
            };
        @endphp

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <span class="font-mono text-sm font-bold text-slate-400">
                        #REQ-{{ str_pad($serviceRequest->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-extrabold border shadow-2xs {{ $statusBadgeClass }}">
                        <span class="h-2 w-2 rounded-full {{ $statusDotClass }} animate-pulse"></span>
                        <span>{{ $statusLabel }}</span>
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 mt-1">
                    {{ $serviceRequest->customer->username ?? 'Walk-in Customer' }}
                </h1>
            </div>
        </div>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Visual Order Timeline Stepper -->
    <div class="mb-6 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-x-auto">
        <p class="text-xs font-mono uppercase font-bold tracking-wider text-slate-400 mb-4">Order Progress Stepper</p>

        <div class="grid grid-cols-6 min-w-[650px] relative">
            <!-- Background Line (from center of first dot to center of last dot) -->
            <div class="absolute top-4 h-1 bg-slate-100 z-0" style="left: calc(100% / 12); right: calc(100% / 12);"></div>
            <div class="absolute top-4 h-1 bg-blue-600 z-0 transition-all duration-500" style="left: calc(100% / 12); width: calc((100% - 100% / 6) * {{ max(0, min(100, ($stepIndex - 1) * 20)) }} / 100);"></div>

            <!-- Step 1: Pending -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 1 ? 'bg-amber-500 text-white shadow-amber-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    1
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 1 ? 'text-slate-900' : 'text-slate-400' }}">Pending</p>
                <p class="text-[10px] text-slate-400 font-mono">Online Request</p>
            </div>

            <!-- Step 2: Received -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 2 ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    2
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 2 ? 'text-slate-900' : 'text-slate-400' }}">Received</p>
                <p class="text-[10px] text-slate-400 font-mono">Counter Entry</p>
            </div>

            <!-- Step 3: Washing -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 3 ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    3
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 3 ? 'text-slate-900' : 'text-slate-400' }}">Washing</p>
                <p class="text-[10px] text-slate-400 font-mono">In Machine</p>
            </div>

            <!-- Step 4: Drying -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 4 ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    4
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 4 ? 'text-slate-900' : 'text-slate-400' }}">Drying</p>
                <p class="text-[10px] text-slate-400 font-mono">Tumble Dryer</p>
            </div>

            <!-- Step 5: Ready for Pickup -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 5 ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    5
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 5 ? 'text-slate-900' : 'text-slate-400' }}">Ready</p>
                <p class="text-[10px] text-slate-400 font-mono">Shelved &amp; Ready</p>
            </div>

            <!-- Step 6: Completed -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 6 ? 'bg-emerald-600 text-white shadow-emerald-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    ✓
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 6 ? 'text-emerald-700' : 'text-slate-400' }}">Completed</p>
                <p class="text-[10px] text-slate-400 font-mono">Paid & Released</p>
            </div>
        </div>
    </div>

    @if(in_array($serviceRequest->status, ['washing', 'drying']))
        <!-- Dynamic Live Cycle Timer Card (Compact & Modern) -->
        <div class="mb-5 rounded-2xl border {{ $cycle['phase'] === 'folding' ? 'border-teal-200/80 bg-gradient-to-br from-teal-50/60 via-white to-emerald-50/20' : ($cycle['phase'] === 'resting' ? 'border-amber-200/80 bg-gradient-to-br from-amber-50/60 via-white to-amber-50/20' : ($cycle['phase'] === 'drying' ? 'border-indigo-200/80 bg-gradient-to-br from-indigo-50/60 via-white to-indigo-50/20' : 'border-blue-200/80 bg-gradient-to-br from-blue-50/60 via-white to-blue-50/20')) }} p-4 sm:p-5 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 mb-3 border-b {{ $cycle['phase'] === 'folding' ? 'border-teal-200/60' : ($cycle['phase'] === 'resting' ? 'border-amber-200/60' : ($cycle['phase'] === 'drying' ? 'border-indigo-100' : 'border-blue-100')) }}">
                <div class="flex items-center gap-3">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[10px] font-mono font-bold uppercase tracking-wider {{ $cycle['phase'] === 'folding' ? 'bg-teal-100 text-teal-800' : ($cycle['phase'] === 'resting' ? 'bg-amber-100 text-amber-800' : ($cycle['phase'] === 'drying' ? 'bg-indigo-100 text-indigo-800' : 'bg-blue-100 text-blue-800')) }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $cycle['phase'] === 'folding' ? 'bg-teal-500' : ($cycle['phase'] === 'resting' ? 'bg-amber-500' : ($cycle['phase'] === 'drying' ? 'bg-indigo-600' : 'bg-blue-600')) }} animate-pulse"></span>
                                <span>{{ $cycle['phase_label'] }}</span>
                            </span>
                            @if($cycle['is_paused'])
                                <span class="rounded-full bg-rose-100 text-rose-700 text-[9px] font-bold px-1.5 py-0.5 font-mono">PAUSED</span>
                            @endif
                        </div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 mt-0.5">
                            @if($cycle['phase'] === 'folding')
                                Drying Complete · Folding &amp; Packaging in Progress
                            @elseif($cycle['phase'] === 'resting')
                                Wash Complete · Wash Buffer &amp; Transfer to Dryer
                            @elseif($cycle['phase'] === 'drying')
                                Drying in Progress: {{ $serviceRequest->dryerMachine->name ?? 'Dryer Unit' }}
                            @else
                                Washing in Progress: {{ $serviceRequest->washingMachine->name ?? 'Washer Unit' }}
                            @endif
                        </h2>
                    </div>
                </div>

                <!-- Timer Controls or Folding Button (Manager Only) -->
                @if(!auth('admin')->user()?->isSuperAdmin())
                <div class="flex flex-wrap items-center gap-2">
                    @if($cycle['phase'] === 'folding')
                        <form method="POST" action="{{ route('admin.service-requests.cycle-action', $serviceRequest) }}">
                            @csrf
                            <input type="hidden" name="action" value="finish_folding">
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white px-3.5 py-1.5 text-xs font-bold shadow-xs transition active:scale-[0.99] cursor-pointer">
                                <span>Finish Folding ➔ Ready for Pickup</span>
                            </button>
                        </form>
                    @else
                        <!-- Pause / Resume -->
                        <form method="POST" action="{{ route('admin.service-requests.cycle-action', $serviceRequest) }}">
                            @csrf
                            <input type="hidden" name="action" value="{{ $cycle['is_paused'] ? 'resume' : 'pause' }}">
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs cursor-pointer">
                                @if($cycle['is_paused'])
                                    <svg class="h-3 w-3 text-emerald-600 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    <span>Resume</span>
                                @else
                                    <svg class="h-3 w-3 text-amber-600 fill-current" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                                    <span>Pause</span>
                                @endif
                            </button>
                        </form>

                        <!-- Add 5 Minutes -->
                        <form method="POST" action="{{ route('admin.service-requests.cycle-action', $serviceRequest) }}">
                            @csrf
                            <input type="hidden" name="action" value="add_time">
                            <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs cursor-pointer" title="Add 5 more minutes to current cycle">
                                <span>+5m</span>
                            </button>
                        </form>

                        <!-- Skip Action Button -->
                        <form method="POST" action="{{ route('admin.service-requests.cycle-action', $serviceRequest) }}">
                            @csrf
                            @if($cycle['phase'] === 'washing')
                                <input type="hidden" name="action" value="skip_wash">
                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 text-xs font-bold transition shadow-xs cursor-pointer">
                                    <span>Finish Wash ➔ Wash Buffer</span>
                                </button>
                            @elseif($cycle['phase'] === 'resting')
                                <input type="hidden" name="action" value="skip_rest">
                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 text-xs font-bold transition shadow-xs cursor-pointer">
                                    <span>Start Drying Now ➔</span>
                                </button>
                            @elseif($cycle['phase'] === 'drying')
                                <input type="hidden" name="action" value="skip_dry">
                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-3 py-1 text-xs font-bold transition shadow-xs cursor-pointer">
                                    <span>Finish Drying ➔ Folding</span>
                                </button>
                            @endif
                        </form>
                    @endif
                </div>
                @endif
            </div>

            @if($cycle['phase'] === 'folding')
                <!-- Folding Phase Card: Clean status, compact -->
                <div class="flex items-center justify-between gap-3 bg-white/80 rounded-xl p-3 border border-teal-200/70 shadow-2xs">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-teal-100 text-teal-700 text-[10px] font-bold">
                            ✓
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900">Dryer is freed and clothes are being folded.</p>
                            <p class="text-[11px] text-slate-500">When packaged, click "Finish Folding ➔ Ready for Pickup" above.</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-mono font-bold text-teal-800 bg-teal-100/80 border border-teal-200">
                        Folding
                    </span>
                </div>
            @else
                <!-- Compact Countdown & Progress Bar -->
                <div class="space-y-2">
                    <div class="flex items-baseline justify-between gap-2">
                        <div class="flex items-baseline gap-2">
                            <span id="cycleTimerDisplay" class="text-2xl sm:text-3xl font-black font-mono tracking-tight text-slate-900">
                                {{ $cycle['display_time'] }}
                            </span>
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400">
                                {{ $cycle['is_paused'] ? 'PAUSED' : 'REMAINING' }}
                            </span>
                        </div>
                        <div class="text-right text-[11px] font-mono font-semibold text-slate-500">
                            @if($cycle['phase'] === 'resting')
                                Wash buffer: <strong class="text-amber-700 font-bold">{{ $serviceRequest->rest_duration_minutes ?? 5 }}m</strong> (In {{ $serviceRequest->washingMachine->name ?? 'washer' }})
                            @elseif($cycle['phase'] === 'drying')
                                Drying duration: <strong class="text-indigo-700 font-bold">{{ $serviceRequest->dry_duration_minutes ?? 25 }}m</strong>
                            @else
                                Washing duration: <strong class="text-blue-700 font-bold">{{ $serviceRequest->wash_duration_minutes ?? 33 }}m</strong>
                            @endif
                        </div>
                    </div>

                    <!-- Sleek Progress Bar -->
                    <div class="relative w-full h-2 rounded-full bg-slate-200/80 overflow-hidden shadow-inner">
                        <div id="cycleProgressBar" class="h-full rounded-full transition-all duration-1000 {{ $cycle['phase'] === 'resting' ? 'bg-gradient-to-r from-amber-400 to-amber-500' : ($cycle['phase'] === 'drying' ? 'bg-gradient-to-r from-indigo-500 to-purple-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600') }}" style="width: {{ $cycle['percentage'] }}%;"></div>
                    </div>
                    
                    @if($cycle['phase'] === 'resting')
                        <div class="flex items-center justify-between text-[11px] text-amber-800 bg-amber-100/60 rounded-lg px-2.5 py-1 border border-amber-200/70">
                            <span class="font-semibold">Washer complete! Unload clothes and transfer to a dryer unit.</span>
                        </div>
                    @endif
                </div>

                <script>
                    (function() {
                        let remainingSeconds = {{ (int) $cycle['remaining_seconds'] }};
                        let totalSeconds = {{ (int) $cycle['total_seconds'] }};
                        const isPaused = {{ $cycle['is_paused'] ? 'true' : 'false' }};
                        const timerDisplay = document.getElementById('cycleTimerDisplay');
                        const progressBar = document.getElementById('cycleProgressBar');
                        let syncTriggered = false;

                        function formatTime(sec) {
                            const m = Math.floor(sec / 60);
                            const s = sec % 60;
                            return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
                        }

                        if (!isPaused && remainingSeconds > 0) {
                            const interval = setInterval(function() {
                                remainingSeconds--;
                                if (remainingSeconds < 0) remainingSeconds = 0;

                                if (timerDisplay) {
                                    timerDisplay.textContent = formatTime(remainingSeconds);
                                }
                                if (progressBar && totalSeconds > 0) {
                                    const pct = Math.min(100, Math.round(((totalSeconds - remainingSeconds) / totalSeconds) * 100));
                                    progressBar.style.width = pct + '%';
                                }

                                if (remainingSeconds <= 0 && !syncTriggered) {
                                    syncTriggered = true;
                                    clearInterval(interval);
                                    if (timerDisplay) timerDisplay.textContent = '00:00';
                                    sessionStorage.setItem('spinzone_admin_scroll', window.scrollY);
                                    
                                    fetch('{{ route('admin.service-requests.cycle-sync', $serviceRequest) }}', {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        }
                                    }).then(function() {
                                        window.location.reload();
                                    }).catch(function() {
                                        window.location.reload();
                                    });
                                }
                            }, 1000);
                        }

                        // Periodic background sync to detect phase changes or machine completions
                        const currentStatus = '{{ $serviceRequest->status }}';
                        const currentPhase = '{{ $serviceRequest->cycle_phase }}';
                        let adminReloading = false;

                        setInterval(function() {
                            if (adminReloading || document.hidden) return;
                            fetch('{{ route('admin.service-requests.cycle-sync', $serviceRequest) }}', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (!data || !data.success) return;
                                if (data.changed || data.status !== currentStatus || data.cycle_phase !== currentPhase) {
                                    adminReloading = true;
                                    sessionStorage.setItem('spinzone_admin_scroll', window.scrollY);
                                    window.location.reload();
                                }
                            })
                            .catch(() => {});
                        }, 6000);
                    })();
                </script>
            @endif
        </div>
    @endif

    <!-- Main Content 2-Column Grid -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Left Column: Details & Verification (2 Columns) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card 1: Laundry Details & Financial Breakdown -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Laundry Details</h2>
                            <p class="text-xs text-slate-400 font-medium">Clothing details, weights, quantity, and pricing</p>
                        </div>
                    </div>

                    <span class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700 border border-slate-200/60">
                        {{ $serviceRequest->clothType->name ?? 'Standard Wash' }}
                    </span>
                </div>

                @if(!$serviceRequest->clothType->requires_size && $serviceRequest->weight_verification_status === 'correct')
                    <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-3.5 flex items-start gap-2.5">
                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xs mt-0.5">✓</div>
                        <div>
                            <p class="text-xs font-bold text-emerald-900">Weight & Piece Count Verified as Correct</p>
                            <p class="text-[11px] text-emerald-700 mt-0.5">{{ $serviceRequest->verification_note }}</p>
                        </div>
                    </div>
                @elseif(!$serviceRequest->clothType->requires_size && $serviceRequest->weight_verification_status === 'incorrect')
                    <div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50/80 p-3.5 flex items-start gap-2.5">
                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-amber-600 text-white font-bold text-xs mt-0.5">!</div>
                        <div>
                            <p class="text-xs font-bold text-amber-900">Weight & Piece Count Adjusted</p>
                            <p class="text-[11px] text-amber-800 mt-0.5">{{ $serviceRequest->verification_note }}</p>
                        </div>
                    </div>
                @endif

                <!-- 3-Grid Spec Summary Pills -->
                <div class="grid gap-3 sm:grid-cols-3 mb-6">
                    <div class="rounded-2xl bg-slate-50 p-3.5 border border-slate-100">
                        <p class="text-[10px] font-mono font-bold uppercase text-slate-400">
                            {{ $serviceRequest->comforter_size ? 'Comforter Size' : 'Weight' }}
                        </p>
                        <p class="text-base font-extrabold text-slate-900 mt-0.5 font-mono">
                            @php
                                $dWeight = $serviceRequest->actual_weight ?? $serviceRequest->estimated_weight;
                            @endphp
                            @if($serviceRequest->comforter_size)
                                {{ $serviceRequest->comforter_size }}
                                @if($dWeight && $dWeight > 0)
                                    <span class="text-xs font-bold text-slate-500 font-mono">({{ ($dWeight + 0) }} kg)</span>
                                @endif
                            @elseif($serviceRequest->actual_weight ?? $serviceRequest->estimated_weight)
                                {{ number_format($serviceRequest->actual_weight ?? $serviceRequest->estimated_weight, 0) }} kg
                            @else
                                {{ $serviceRequest->pricingTier->name ?? 'Standard' }}
                            @endif
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-3.5 border border-slate-100">
                        <p class="text-[10px] font-mono font-bold uppercase text-slate-400">
                            Tier Rate
                        </p>
                        <p class="text-base font-extrabold text-slate-900 mt-0.5 font-mono">
                            <span class="text-purple-700">₱{{ number_format($serviceRequest->pricingTier->price ?? 0, 0) }}</span>
                            @if($serviceRequest->pricingTier)
                                <span class="text-[11px] font-semibold text-slate-400">({{ $serviceRequest->pricingTier->name }})</span>
                            @endif
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-3.5 border border-slate-100">
                        <p class="text-[10px] font-mono font-bold uppercase text-slate-400">Quantity</p>
                        <p class="text-base font-extrabold text-slate-900 mt-0.5 font-mono">
                            {{ $serviceRequest->actual_quantity ?? $serviceRequest->quantity }} pcs
                        </p>
                    </div>
                </div>

                @if($serviceRequest->items->count() > 0)
                    <!-- Itemized Cloth Checklist Table -->
                    <div class="mb-6 rounded-3xl border border-slate-200/80 bg-slate-50/50 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2.5">
                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 font-mono">Itemized Cloth Checklist</h3>
                                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-700 font-mono">{{ $serviceRequest->items->count() }} item types</span>
                            </div>
                            <div class="text-[11px] font-mono text-slate-500">
                                Declared: <strong class="text-slate-800">{{ $serviceRequest->total_declared_items }} pcs</strong>
                                @if($serviceRequest->status !== 'pending')
                                    · Verified: <strong class="{{ $serviceRequest->total_verified_items === $serviceRequest->total_declared_items ? 'text-emerald-700' : 'text-amber-700' }}">{{ $serviceRequest->total_verified_items }} pcs</strong>
                                @endif
                            </div>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-50/80 font-mono text-[10px] uppercase font-bold text-slate-500">
                                        <th class="py-2.5 px-3">Item Type</th>
                                        <th class="py-2.5 px-3 text-center">Customer Count</th>
                                        <th class="py-2.5 px-3 text-center">Staff Verified</th>
                                        <th class="py-2.5 px-3 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($serviceRequest->items as $item)
                                        @php
                                            $isVerified = ($item->verified_qty !== null);
                                            $isMatch = $item->isVerifiedMatch();
                                            $diff = $isVerified ? ($item->verified_qty - $item->declared_qty) : 0;
                                        @endphp
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <td class="py-2.5 px-3 font-semibold text-slate-800">
                                                {{ $item->item_name }}
                                                @if($item->declared_qty === 0)
                                                    <span class="ml-1 rounded bg-amber-50 px-1.5 py-0.5 text-[9px] font-bold text-amber-700 border border-amber-200">Discovered</span>
                                                @elseif($item->is_custom)
                                                    <span class="ml-1 rounded bg-purple-50 px-1.5 py-0.5 text-[9px] font-bold text-purple-700 border border-purple-200">Other</span>
                                                @endif
                                            </td>
                                            <td class="py-2.5 px-3 text-center font-mono font-bold text-slate-600">
                                                @if($item->declared_qty === 0)
                                                    <span class="text-slate-400 italic font-normal text-[11px]">None</span>
                                                @else
                                                    {{ $item->declared_qty }} pcs
                                                @endif
                                            </td>
                                            <td class="py-2.5 px-3 text-center font-mono font-bold {{ $isVerified ? ($isMatch ? 'text-emerald-700' : 'text-amber-700') : 'text-slate-400' }}">
                                                {{ $isVerified ? $item->verified_qty . ' pcs' : 'Pending' }}
                                            </td>
                                            <td class="py-2.5 px-3 text-right">
                                                @if(!$isVerified)
                                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600">
                                                        Pending Check
                                                    </span>
                                                @elseif($item->declared_qty === 0)
                                                    <span class="inline-flex items-center gap-1 rounded-md bg-purple-50 px-2 py-0.5 text-[10px] font-bold text-purple-700 border border-purple-200">
                                                        <span>+</span> {{ $item->verified_qty }} pcs extra
                                                    </span>
                                                @elseif($isMatch)
                                                    <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                                        <span>✓</span> Matched
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 border border-amber-200">
                                                        <span>⚠</span> {{ $diff > 0 ? "+{$diff} extra" : "{$diff} missing" }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Add-on Treatments Section -->
                <div class="mb-6">
                    <p class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 mb-2">Selected Add-ons</p>
                    <div class="flex flex-wrap items-center gap-2">
                        @forelse($serviceRequest->addons as $addon)
                            <span class="inline-flex items-center gap-1.5 rounded-xl bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 border border-blue-200/60 shadow-2xs">
                                <span>{{ $addon->name }}</span>
                                <span class="font-mono text-[11px] text-blue-600">(+PHP {{ number_format($addon->pivot->price ?? $addon->price, 0) }})</span>
                            </span>
                        @empty
                            <span class="text-xs text-slate-400 font-medium italic">No extra add-on treatments selected.</span>
                        @endforelse
                    </div>
                </div>

                <!-- Notes Block -->
                @if($serviceRequest->notes)
                    <div class="mb-6 rounded-2xl bg-amber-50/60 p-4 border border-amber-200/60">
                        <div class="flex items-center gap-2 text-amber-800 font-bold text-xs mb-1">
                            <span>Special Customer Instructions</span>
                        </div>
                        <p class="text-xs text-amber-900 font-medium leading-relaxed">
                            "{{ $serviceRequest->notes }}"
                        </p>
                    </div>
                @endif

                <!-- Pricing Summary Row -->
                <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-mono font-bold uppercase text-slate-400">Base Tier Rate</p>
                        <p class="text-sm font-bold text-slate-700 font-mono">
                            PHP {{ number_format(($serviceRequest->pricingTier->price ?? 0) * ($serviceRequest->clothType->requires_size ? ($serviceRequest->actual_quantity ?? $serviceRequest->quantity) : 1), 0) }}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-[10px] font-mono font-bold uppercase text-slate-400">Total Price</p>
                        <p class="text-2xl font-extrabold text-emerald-600 tracking-tight font-mono">
                            PHP {{ number_format($serviceRequest->final_price ?? $serviceRequest->estimated_price, 0) }}
                        </p>
                    </div>
                </div>
            </div>

            @php
                $needsVerification = ($serviceRequest->status === 'pending' || $serviceRequest->final_price === null) && !in_array($serviceRequest->status, ['completed', 'cancelled']);
            @endphp

            @if($needsVerification)
                @if(!auth('admin')->user()?->isSuperAdmin())
                <!-- Card 2: Verify & Calibrate Laundry Form (Manager Only) -->
                <div id="verification-section" class="rounded-3xl border {{ $serviceRequest->status === 'pending' ? 'border-blue-200/80 bg-gradient-to-br from-blue-50/40 via-white to-white' : 'border-amber-300 bg-gradient-to-br from-amber-50/60 via-white to-white' }} p-6 sm:p-7 shadow-sm transition-all duration-300">
                    
                    @if($serviceRequest->status !== 'pending' && $serviceRequest->final_price === null)
                        <div class="mb-5 rounded-2xl border border-amber-300 bg-amber-50/90 p-3.5 flex items-start gap-2.5">
                            <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-amber-600 text-white font-bold text-xs mt-0.5">!</div>
                            <div>
                                <p class="text-xs font-bold text-amber-950">Scale Verification Required</p>
                                <p class="text-[11px] text-amber-800 mt-0.5 leading-relaxed">This request is active but missing verified scale weight and final price. Calibrate the weight below to calculate the final price and unlock order completion.</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-3 pb-4 mb-4 border-b {{ $serviceRequest->status === 'pending' ? 'border-blue-100' : 'border-amber-100' }}">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl {{ $serviceRequest->status === 'pending' ? 'bg-blue-600' : 'bg-amber-600' }} text-white shadow-xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 18h12l3-18H3z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Verify Laundry Items &amp; Specs</h2>
                            <p class="text-xs text-slate-500 font-medium">
                                {{ $serviceRequest->status === 'pending' ? 'Verify piece count and calibrate scale weight. Submitting automatically receives the order.' : 'Calibrate scale weight and calculate final price to allow completion.' }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.service-requests.details', $serviceRequest) }}" class="space-y-4">
                        @csrf
                        @if($serviceRequest->clothType->requires_size)
                            <!-- Comforter Verification: Size & Scale Weight -->
                            <div class="grid gap-4 sm:grid-cols-2">
                                <!-- Verified Comforter Size (Sets Price) -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 font-mono">
                                        Verified Comforter Size <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select name="pricing_tier_id" required class="w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 py-2.5 text-xs font-mono font-bold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs">
                                            @foreach($serviceRequest->clothType->pricingTiers as $tier)
                                                <option value="{{ $tier->id }}" {{ (old('pricing_tier_id', $serviceRequest->pricing_tier_id) == $tier->id) ? 'selected' : '' }}>
                                                    {{ $tier->name }} — ₱{{ number_format($tier->price, 0) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 font-mono">
                                        Submitted: <span class="font-bold text-slate-700">{{ $serviceRequest->comforter_size ?? $serviceRequest->pricingTier->name }}</span>
                                    </p>
                                </div>

                                <!-- Verified Scale Weight (Sets Cycle Time) -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 font-mono">
                                        Verified Scale Weight (kg) <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" min="1" max="8" step="1" name="actual_weight" value="{{ old('actual_weight', $serviceRequest->actual_weight ? (int)$serviceRequest->actual_weight : ($serviceRequest->estimated_weight ? (int)$serviceRequest->estimated_weight : '')) }}" placeholder="1-8" required oninput="if(parseFloat(this.value) > 8) this.value = 8;" class="w-full rounded-2xl border border-slate-200 bg-white pl-4 pr-12 py-2.5 text-xs font-mono font-bold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-mono font-bold text-slate-400">
                                            kg
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 font-mono">Allowed: 1 - 8 kg · Determines wash &amp; dry cycle times</p>
                                </div>
                            </div>
                        @else
                            @php
                                $maxAllowedWeight = ($serviceRequest->cloth_type_id == 2) ? 5 : 8;
                            @endphp
                            <div class="grid gap-4 sm:grid-cols-2">
                                <!-- Final Scale Weight -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 font-mono">
                                        Verified Scale Weight (kg) <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" min="1" max="{{ $maxAllowedWeight }}" step="1" name="actual_weight" value="{{ old('actual_weight', $serviceRequest->actual_weight ? (int)$serviceRequest->actual_weight : ($serviceRequest->estimated_weight ? (int)$serviceRequest->estimated_weight : '')) }}" placeholder="1-{{ $maxAllowedWeight }}" required oninput="if(parseFloat(this.value) > {{ $maxAllowedWeight }}) this.value = {{ $maxAllowedWeight }};" class="w-full rounded-2xl border border-slate-200 bg-white pl-4 pr-12 py-2.5 text-xs font-mono font-bold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-mono font-bold text-slate-400">
                                            kg
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 font-mono">Allowed: 1 - {{ $maxAllowedWeight }} kg · Customer submitted: {{ number_format($serviceRequest->estimated_weight ?? 0, 0) }} kg</p>
                                </div>

                                <!-- Verified Cloth Quantity -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 font-mono">
                                        Verified Cloth Count (pcs) <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" min="1" step="1" name="actual_quantity" id="inputAdminActualQuantity" value="{{ old('actual_quantity', $serviceRequest->actual_quantity ?? $serviceRequest->quantity) }}" placeholder="e.g. 10" required class="w-full rounded-2xl border border-slate-200 bg-white pl-4 pr-12 py-2.5 text-xs font-mono font-bold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-mono font-bold text-slate-400">
                                            pcs
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 font-mono">Customer submitted: {{ $serviceRequest->quantity }} pcs</p>
                                </div>
                            </div>

                            <!-- Item-by-item & Discovered Clothes Verification Section -->
                            <div class="mt-4 rounded-3xl border border-slate-200/90 bg-slate-50/70 p-4 space-y-3.5">
                                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/80">
                                    <div>
                                        <h4 class="text-xs font-extrabold text-slate-900">Verify Clothing Piece Counts</h4>
                                        <p class="text-[10px] text-slate-400 font-mono">Verify declared items &amp; add any extra/other clothes found in bag.</p>
                                    </div>
                                    @if($serviceRequest->items->count() > 0)
                                        <button type="button" onclick="matchAllClothCounts()" class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-white px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-50 shadow-2xs transition cursor-pointer">
                                            <svg class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>All Declared Match</span>
                                        </button>
                                    @endif
                                </div>

                                @if($serviceRequest->items->count() > 0)
                                    <div class="grid gap-2.5 sm:grid-cols-2">
                                        @foreach($serviceRequest->items as $item)
                                            <div id="existing_item_row_{{ $item->id }}" class="flex items-center justify-between rounded-2xl bg-white p-3 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition">
                                                <div class="truncate pr-2">
                                                    <div class="flex items-center gap-1.5 truncate">
                                                        <p class="text-xs font-bold text-slate-800 truncate">{{ $item->item_name }}</p>
                                                        @if($item->declared_qty === 0)
                                                            <span class="rounded bg-amber-50 px-1.5 py-0.5 text-[9px] font-bold text-amber-700 border border-amber-200 shrink-0">Discovered</span>
                                                        @elseif($item->is_custom)
                                                            <span class="rounded bg-purple-50 px-1.5 py-0.5 text-[9px] font-bold text-purple-700 border border-purple-200 shrink-0">Other</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-[10px] font-mono text-slate-400 mt-0.5">
                                                        {{ $item->declared_qty > 0 ? "Declared: {$item->declared_qty} pcs" : "Not declared by customer" }}
                                                    </p>
                                                </div>
                                                <div class="flex items-center gap-1.5 shrink-0">
                                                    <button type="button" onclick="adjustVerifiedQty({{ $item->id }}, -1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">-</button>
                                                    <input type="number" min="0" max="500" name="verified_items[{{ $item->id }}]" id="v_item_{{ $item->id }}" data-declared="{{ $item->declared_qty }}" value="{{ old('verified_items.' . $item->id, $item->verified_qty ?? $item->declared_qty) }}" oninput="recalcVerifiedTotal()" class="verified-item-input w-12 rounded-xl border border-slate-200 bg-white py-1.5 text-center font-mono text-xs font-bold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                                                    <button type="button" onclick="adjustVerifiedQty({{ $item->id }}, 1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">+</button>
                                                    @if($item->declared_qty === 0 || $item->is_custom)
                                                        <button type="button" onclick="removeExistingItem({{ $item->id }}, 'existing_item_row_{{ $item->id }}')" class="flex h-8 w-8 items-center justify-center rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Remove item">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Discovered / Other Clothes Section -->
                                <div class="pt-3 border-t border-slate-200/80">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h5 class="text-xs font-bold text-slate-800">Other / Discovered Clothes</h5>
                                            <p class="text-[10px] text-slate-400 font-mono">Found unlisted, random, or extra clothes in bag? Add them here.</p>
                                        </div>
                                        <button type="button" onclick="addAdminVerificationClothRow()" class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100 transition cursor-pointer shadow-2xs">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                            </svg>
                                            <span>Add Other Cloth</span>
                                        </button>
                                    </div>

                                    @if($serviceRequest->items->count() === 0)
                                        <p id="noItemsHelpText" class="text-xs text-slate-400 italic py-1">
                                            No breakdown declared by customer. Click <strong>"Add Other Cloth"</strong> to record any clothes found.
                                        </p>
                                    @endif

                                    <div id="adminNewItemsContainer" class="space-y-2 mt-2"></div>
                                    <div id="adminDeletedItemsContainer"></div>
                                </div>
                            </div>

                            <!-- Verification / Discrepancy Note -->
                            <div class="mt-4">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 font-mono">
                                    Intake Note for Customer (Optional)
                                </label>
                                <input type="text" name="verification_note_custom" placeholder="e.g. 1 polo missing from bag, collar stain noted on arrival..." value="{{ old('verification_note_custom') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs text-slate-800 font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs">
                            </div>
                        @endif

                        <button type="submit" class="mt-4 w-full inline-flex items-center justify-center gap-2 rounded-2xl {{ $serviceRequest->status === 'pending' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-emerald-600 hover:bg-emerald-700' }} py-3.5 px-4 text-xs font-extrabold text-white shadow-md active:scale-95 transition cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ $serviceRequest->status === 'pending' ? 'Verify & Set Status to Received' : 'Save Verification & Calculate Final Price' }}</span>
                        </button>
                    </form>
                </div>
                @else
                <!-- Card 2: Read-Only Pending Intake Notice (Owner View) -->
                <div class="rounded-3xl border border-amber-200/80 bg-gradient-to-br from-amber-50/60 via-white to-white p-6 sm:p-7 shadow-sm">
                    <div class="flex items-center gap-3 pb-3 mb-3 border-b border-amber-100">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900">Awaiting Manager Intake &amp; Verification</h2>
                            <p class="text-xs text-slate-500 font-medium">Customer submitted order online. Intake scale check will be completed by the store manager.</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Once clothes arrive physically at the branch, the store manager will verify the scale weight, confirm the piece count, and accept the order into active processing.
                    </p>
                </div>
                @endif
            @endif

            <!-- Card 3: Audit Trail & Status History Log -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-purple-100 text-purple-600 shadow-2xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Audit Trail & Status Log</h2>
                        <p class="text-xs text-slate-400 font-medium">History of status changes and staff updates</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($serviceRequest->statusHistories as $history)
                        <div class="flex items-start gap-3.5 relative">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-blue-600 text-xs font-bold shrink-0 mt-0.5">
                                ✓
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-bold text-slate-900">
                                        Status changed to <span class="capitalize text-blue-600">{{ str_replace('_', ' ', $history->status) }}</span>
                                    </p>
                                    <span class="font-mono text-[10px] text-slate-400">
                                        {{ $history->started_at ? $history->started_at->format('M d, h:i A') : 'N/A' }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                    Recorded by system / staff member.
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center">
                            <p class="text-xs text-slate-400">No previous status log history recorded.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right Column: Status Actions & Customer Profile Panel (1 Column) -->
        <div class="lg:col-span-1 space-y-6">

            @if(!auth('admin')->user()?->isSuperAdmin())
            <!-- Card 1: Update Status (Manager Only) -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">Update Status</h3>
                        <p class="text-[11px] text-slate-400 font-medium">Advance workflow step</p>
                    </div>

                    <!-- Options Popover for Manual Jump -->
                    <div class="relative inline-block text-left" id="statusMenuWrapper">
                        <button type="button" onclick="document.getElementById('statusMenuPopover').classList.toggle('hidden')" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition shadow-2xs" title="Jump to any status">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                        </button>

                        <div id="statusMenuPopover" class="hidden absolute right-0 mt-2 w-56 rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                            <p class="px-3 py-1.5 text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1">
                                Jump to Status
                            </p>
                            @foreach(['received' => 'Received', 'washing' => 'Washing', 'drying' => 'Drying', 'ready_for_pickup' => 'Ready for Pickup', 'completed' => 'Completed'] as $stKey => $stLbl)
                                @php
                                    $isBlockedUnverified = ($serviceRequest->status === 'pending' && $serviceRequest->final_price === null && $stKey !== 'pending');
                                @endphp
                                @if($isBlockedUnverified)
                                    <button type="button" onclick="alert('Please verify laundry scale weight using the intake form before changing status.'); document.getElementById('verification-section')?.scrollIntoView({behavior: 'smooth', block: 'center'});" class="w-full text-left rounded-xl px-3 py-2 text-xs font-semibold text-slate-400 hover:bg-slate-50 transition cursor-not-allowed">
                                        {{ $stLbl }} <span class="text-[10px] text-amber-600 font-mono font-bold">(Needs Verification)</span>
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('admin.service-requests.status', $serviceRequest) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $stKey }}">
                                        <button type="submit" class="w-full text-left rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100 transition {{ $serviceRequest->status === $stKey ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700' }}">
                                            {{ $stLbl }}
                                        </button>
                                    </form>
                                @endif
                            @endforeach

                            @if($serviceRequest->status !== 'completed' && $serviceRequest->status !== 'cancelled')
                                <div class="my-1 border-t border-slate-100"></div>
                                <form method="POST" action="{{ route('admin.service-requests.status', $serviceRequest) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" onclick="return confirm('Are you sure you want to cancel this request?')" class="w-full text-left rounded-xl px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition">
                                        Cancel Request
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Primary Step Advancement Text Header & Machine Selection Boxes -->
                @if($nextStatus)
                    @if($nextStatus === 'washing')
                        <!-- Section Title Text (Not a link or button) -->
                        <div class="mb-3">
                            <h4 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <span class="flex h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                                <span>Move to Washing</span>
                            </h4>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Select a washer machine unit to assign & start cycle:</p>
                        </div>

                        <!-- Clickable Machine Boxes for Washing -->
                        <div class="space-y-2.5">
                            @forelse($washers as $washer)
                                @php
                                    $isMaintenance = ($washer->status === 'maintenance');
                                    $isCurrent = ($serviceRequest->washing_machine_id == $washer->id);
                                    $isInUseByOther = ($washer->status === 'in_use' && !$isCurrent);
                                    $isAvailable = ($washer->status === 'available');
                                    $canSelect = $isAvailable || ($isCurrent && !$isMaintenance);
                                @endphp

                                @if($isMaintenance)
                                    <!-- Disabled: Under Maintenance -->
                                    <div class="w-full flex items-center justify-between p-3.5 rounded-2xl border-2 border-amber-300 bg-amber-50/70 opacity-80 cursor-not-allowed shadow-2xs">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-200 text-amber-900 font-extrabold text-sm shadow-xs">
                                                W
                                            </div>
                                            <div class="text-left">
                                                <div class="flex items-center gap-2">
                                                    <p class="font-extrabold text-sm text-slate-800">{{ $washer->name }}</p>
                                                    @if($isCurrent)
                                                        <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-md bg-amber-200 text-amber-900 border border-amber-300">Currently Assigned</span>
                                                    @endif
                                                </div>
                                                <p class="text-[11px] font-bold text-amber-700 flex items-center gap-1.5 mt-0.5">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                    <span>Under Maintenance</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1 text-xs font-bold text-amber-700 bg-white/80 px-2.5 py-1 rounded-xl border border-amber-200/80 shadow-2xs">
                                            <svg class="h-3.5 w-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <span>Unavailable</span>
                                        </div>
                                    </div>
                                @elseif($isInUseByOther)
                                    <!-- Disabled: In Use by Other Order -->
                                    <div class="w-full flex items-center justify-between p-3.5 rounded-2xl border-2 border-slate-200 bg-slate-50/80 opacity-70 cursor-not-allowed shadow-2xs">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-200 text-slate-600 font-extrabold text-sm shadow-xs">
                                                W
                                            </div>
                                            <div class="text-left">
                                                <p class="font-extrabold text-sm text-slate-700">{{ $washer->name }}</p>
                                                <p class="text-[11px] font-semibold text-slate-500 flex items-center gap-1.5 mt-0.5">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                                    <span>In Use by another order</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-xs font-bold text-slate-500 bg-slate-200/70 px-2.5 py-1 rounded-xl">
                                            <span>In Use</span>
                                        </div>
                                    </div>
                                @else
                                    <!-- Available / Active Button -->
                                    <form method="POST" action="{{ route('admin.service-requests.status', $serviceRequest) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="washing">
                                        <input type="hidden" name="washing_machine_id" value="{{ $washer->id }}">
                                        <button type="submit" class="w-full flex items-center justify-between p-3.5 rounded-2xl border-2 {{ $isCurrent ? 'border-purple-500 bg-purple-50' : 'border-blue-200 bg-blue-50/50 hover:bg-blue-600 hover:border-blue-600 hover:text-white' }} group transition-all duration-200 shadow-2xs cursor-pointer active:scale-98">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white group-hover:bg-white group-hover:text-blue-600 font-extrabold text-sm transition shadow-xs">
                                                    W
                                                </div>
                                                <div class="text-left">
                                                    <div class="flex items-center gap-2">
                                                        <p class="font-extrabold text-sm text-slate-900 group-hover:text-white transition">{{ $washer->name }}</p>
                                                        @if($isCurrent)
                                                            <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-md bg-purple-100 text-purple-700 border border-purple-200 group-hover:bg-white/20 group-hover:text-white group-hover:border-white/40">Current</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-[11px] text-slate-500 group-hover:text-blue-100 transition capitalize">Status: Available</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-xs font-extrabold text-blue-600 group-hover:text-white transition">
                                                <span>{{ $isCurrent ? 'Start Washing' : 'Assign & Start' }}</span>
                                                <svg class="h-4 w-4 transform transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </div>
                                        </button>
                                    </form>
                                @endif
                            @empty
                                <div class="rounded-2xl border border-dashed border-amber-300 bg-amber-50 p-4 text-center">
                                    <p class="text-xs font-bold text-amber-800">No Washing Machines Found</p>
                                    <p class="text-[11px] text-amber-700 mt-0.5">Please register a washer unit or free a unit.</p>
                                    <a href="{{ route('admin.machines') }}" class="mt-2 inline-block text-xs font-extrabold text-blue-600 underline">Manage Machines →</a>
                                </div>
                            @endforelse
                        </div>

                    @elseif($nextStatus === 'drying')
                        <!-- Section Title Text (Not a link or button) -->
                        <div class="mb-3">
                            <h4 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <span class="flex h-2.5 w-2.5 rounded-full bg-indigo-600"></span>
                                <span>Move to Drying</span>
                            </h4>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Select a dryer machine unit to assign & start drying:</p>
                        </div>

                        <!-- Clickable Machine Boxes for Drying -->
                        <div class="space-y-2.5">
                            @forelse($dryers as $dryer)
                                @php
                                    $isMaintenance = ($dryer->status === 'maintenance');
                                    $isCurrent = ($serviceRequest->dryer_machine_id == $dryer->id);
                                    $isInUseByOther = ($dryer->status === 'in_use' && !$isCurrent);
                                    $isAvailable = ($dryer->status === 'available');
                                    $canSelect = $isAvailable || ($isCurrent && !$isMaintenance);
                                @endphp

                                @if($isMaintenance)
                                    <!-- Disabled: Under Maintenance -->
                                    <div class="w-full flex items-center justify-between p-3.5 rounded-2xl border-2 border-amber-300 bg-amber-50/70 opacity-80 cursor-not-allowed shadow-2xs">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-200 text-amber-900 font-extrabold text-sm shadow-xs">
                                                D
                                            </div>
                                            <div class="text-left">
                                                <div class="flex items-center gap-2">
                                                    <p class="font-extrabold text-sm text-slate-800">{{ $dryer->name }}</p>
                                                    @if($isCurrent)
                                                        <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-md bg-amber-200 text-amber-900 border border-amber-300">Currently Assigned</span>
                                                    @endif
                                                </div>
                                                <p class="text-[11px] font-bold text-amber-700 flex items-center gap-1.5 mt-0.5">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                    <span>Under Maintenance</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1 text-xs font-bold text-amber-700 bg-white/80 px-2.5 py-1 rounded-xl border border-amber-200/80 shadow-2xs">
                                            <svg class="h-3.5 w-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <span>Unavailable</span>
                                        </div>
                                    </div>
                                @elseif($isInUseByOther)
                                    <!-- Disabled: In Use by Other Order -->
                                    <div class="w-full flex items-center justify-between p-3.5 rounded-2xl border-2 border-slate-200 bg-slate-50/80 opacity-70 cursor-not-allowed shadow-2xs">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-200 text-slate-600 font-extrabold text-sm shadow-xs">
                                                D
                                            </div>
                                            <div class="text-left">
                                                <p class="font-extrabold text-sm text-slate-700">{{ $dryer->name }}</p>
                                                <p class="text-[11px] font-semibold text-slate-500 flex items-center gap-1.5 mt-0.5">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                                    <span>In Use by another order</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-xs font-bold text-slate-500 bg-slate-200/70 px-2.5 py-1 rounded-xl">
                                            <span>In Use</span>
                                        </div>
                                    </div>
                                @else
                                    <!-- Available / Active Button -->
                                    <form method="POST" action="{{ route('admin.service-requests.status', $serviceRequest) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="drying">
                                        <input type="hidden" name="dryer_machine_id" value="{{ $dryer->id }}">
                                        <button type="submit" class="w-full flex items-center justify-between p-3.5 rounded-2xl border-2 {{ $isCurrent ? 'border-indigo-500 bg-indigo-50' : 'border-indigo-200 bg-indigo-50/50 hover:bg-indigo-600 hover:border-indigo-600 hover:text-white' }} group transition-all duration-200 shadow-2xs cursor-pointer active:scale-98">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white group-hover:bg-white group-hover:text-indigo-600 font-extrabold text-sm transition shadow-xs">
                                                    D
                                                </div>
                                                <div class="text-left">
                                                    <div class="flex items-center gap-2">
                                                        <p class="font-extrabold text-sm text-slate-900 group-hover:text-white transition">{{ $dryer->name }}</p>
                                                        @if($isCurrent)
                                                            <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-md bg-indigo-100 text-indigo-700 border border-indigo-200 group-hover:bg-white/20 group-hover:text-white group-hover:border-white/40">Current</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-[11px] text-slate-500 group-hover:text-indigo-100 transition capitalize">Status: Available</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-xs font-extrabold text-indigo-600 group-hover:text-white transition">
                                                <span>{{ $isCurrent ? 'Start Drying' : 'Assign & Start' }}</span>
                                                <svg class="h-4 w-4 transform transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </div>
                                        </button>
                                    </form>
                                @endif
                            @empty
                                <div class="rounded-2xl border border-dashed border-amber-300 bg-amber-50 p-4 text-center">
                                    <p class="text-xs font-bold text-amber-800">No Dryer Machines Found</p>
                                    <p class="text-[11px] text-amber-700 mt-0.5">Please register a dryer unit or free a unit.</p>
                                    <a href="{{ route('admin.machines') }}" class="mt-2 inline-block text-xs font-extrabold text-blue-600 underline">Manage Machines →</a>
                                </div>
                            @endforelse
                        </div>

                    @elseif($nextStatus === 'received')
                        <!-- Verification Required Prompt -->
                        <div class="rounded-2xl border border-blue-200 bg-blue-50/70 p-4">
                            <div class="flex items-center gap-2 text-blue-900 font-extrabold text-xs mb-1.5">
                                <svg class="h-4 w-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>Verification Required</span>
                            </div>
                            <p class="text-xs text-blue-800/80 mb-3 leading-relaxed">
                                Weigh clothes and confirm piece count on the verification form to calibrate scale pricing and accept this order.
                            </p>
                            <a href="#verification-section" onclick="document.getElementById('verification-section')?.scrollIntoView({behavior: 'smooth', block: 'center'});" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 py-3 px-4 text-xs font-extrabold text-white shadow-md hover:bg-blue-700 active:scale-95 transition cursor-pointer">
                                <span>Verify &amp; Accept Order ↓</span>
                            </a>
                        </div>

                    @elseif($nextStatus === 'completed' && $serviceRequest->final_price === null)
                        <!-- Verification Required before Completion Warning -->
                        <div class="rounded-2xl border border-amber-300 bg-amber-50 p-4">
                            <div class="flex items-center gap-2 text-amber-900 font-extrabold text-xs mb-1">
                                <svg class="h-4 w-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Scale Verification Required</span>
                            </div>
                            <p class="text-xs text-amber-800 mb-3 leading-relaxed">
                                Please calibrate scale weight and calculate the final price before completing this order.
                            </p>
                            <a href="#verification-section" onclick="document.getElementById('verification-section')?.scrollIntoView({behavior: 'smooth', block: 'center'});" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-amber-600 py-3 px-4 text-xs font-extrabold text-white shadow-md hover:bg-amber-700 active:scale-95 transition cursor-pointer">
                                <span>Verify Laundry Now ↓</span>
                            </a>
                        </div>

                    @else
                        <!-- Normal Active Primary Button for other status steps -->
                        <form method="POST" action="{{ route('admin.service-requests.status', $serviceRequest) }}">
                            @csrf
                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 py-3 px-4 text-xs font-extrabold text-white shadow-md hover:bg-blue-700 active:scale-95 transition cursor-pointer">
                                <span>{{ $nextStatusLabel }}</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </form>
                    @endif
                @else
                    <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200/60 text-center">
                        <span class="flex h-8 w-8 mx-auto items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xs shadow-xs">✓</span>
                        <p class="text-xs font-extrabold text-emerald-800 mt-2">Request Completed</p>
                        <p class="text-[11px] text-emerald-700/80 mt-0.5">This service request has been completed and logged into sales records.</p>
                    </div>
                @endif
            </div>
            @else
            <!-- Card 1: Order Status Summary (Owner Read-Only View) -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">Order Status</h3>
                        <p class="text-[11px] text-slate-400 font-medium font-mono">Status &amp; Processing Info</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-extrabold border shadow-2xs {{ $statusBadgeClass }}">
                        <span class="h-2 w-2 rounded-full {{ $statusDotClass }} animate-pulse"></span>
                        <span>{{ $statusLabel }}</span>
                    </span>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Branch Location:</span>
                        <span class="font-bold text-slate-800">{{ $serviceRequest->branch->name ?? 'Main Branch' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Assigned Washer:</span>
                        <span class="font-bold text-slate-800 font-mono">{{ $serviceRequest->washingMachine->name ?? 'None' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Assigned Dryer:</span>
                        <span class="font-bold text-slate-800 font-mono">{{ $serviceRequest->dryerMachine->name ?? 'None' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Handled By:</span>
                        <span class="font-bold text-slate-800">{{ $serviceRequest->acceptedBy->username ?? ($serviceRequest->admin->username ?? 'Branch Manager') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <span class="text-slate-400 font-medium">Payment Status:</span>
                        <span class="font-bold {{ $serviceRequest->status === 'completed' ? 'text-emerald-600' : 'text-amber-600' }} font-mono">
                            {{ $serviceRequest->status === 'completed' ? 'Paid (₱' . number_format($serviceRequest->final_price ?? $serviceRequest->estimated_price, 0) . ')' : 'Pending Settlement' }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 rounded-2xl bg-blue-50/70 p-3.5 border border-blue-100 flex items-start gap-2.5 text-[11px] text-blue-800">
                    <svg class="h-4 w-4 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Status updates, scale calibration, and machine assignments are handled exclusively by branch managers.</span>
                </div>
            </div>
            @endif

            <!-- Card 2: Customer Profile Info -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                    @php
                        $cUsername = $serviceRequest->customer->username ?? 'Walk-in Customer';
                        $cInitial = strtoupper(substr($cUsername, 0, 1));
                    @endphp
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-extrabold text-base shadow-2xs">
                        {{ $cInitial }}
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900 leading-snug">{{ $cUsername }}</h3>
                        <p class="text-xs text-slate-400 font-medium">Customer Profile</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Phone Contact:</span>
                        <span class="font-bold text-slate-800 font-mono">
                            {{ $serviceRequest->customer->phone ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Assigned Branch:</span>
                        <span class="font-bold text-slate-800">
                            {{ $serviceRequest->branch->name ?? 'Central Branch' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-400 font-medium">Request Date:</span>
                        <span class="font-bold text-slate-800">
                            {{ $serviceRequest->created_at ? $serviceRequest->created_at->format('M d, Y') : 'Today' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Machine Assignment Badges -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-100 text-purple-700 font-bold text-xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">Machine Assignment</h3>
                        <p class="text-[11px] text-slate-400 font-medium">Washer & Dryer Units</p>
                    </div>
                </div>

                <div class="space-y-2.5">
                    <!-- Washer -->
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-medium text-slate-500">Washing Machine:</span>
                        @if($serviceRequest->washingMachine)
                            <span class="inline-flex items-center gap-1 rounded-xl bg-purple-100 px-2.5 py-1 text-xs font-bold text-purple-700">
                                {{ $serviceRequest->washingMachine->name }}
                            </span>
                        @else
                            <span class="text-xs font-medium text-slate-400 italic">Not assigned</span>
                        @endif
                    </div>

                    <!-- Dryer -->
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-medium text-slate-500">Tumble Dryer:</span>
                        @if($serviceRequest->dryerMachine)
                            <span class="inline-flex items-center gap-1 rounded-xl bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">
                                {{ $serviceRequest->dryerMachine->name }}
                            </span>
                        @else
                            <span class="text-xs font-medium text-slate-400 italic">Not assigned</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Verification & Popover Scripts -->
    <script>
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('statusMenuWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('statusMenuPopover')?.classList.add('hidden');
            }
        });

        let adminNewItemIndex = 0;

        function adjustVerifiedQty(itemId, delta) {
            const input = document.getElementById('v_item_' + itemId);
            if (!input) return;
            let current = parseInt(input.value) || 0;
            current = Math.max(0, current + delta);
            input.value = current;
            recalcVerifiedTotal();
        }

        function matchAllClothCounts() {
            document.querySelectorAll('.verified-item-input').forEach(inp => {
                if (inp.dataset.declared !== undefined) {
                    const declared = parseInt(inp.dataset.declared) || 0;
                    inp.value = declared;
                }
            });
            recalcVerifiedTotal();
        }

        function addAdminVerificationClothRow(name = '', qty = 1) {
            const container = document.getElementById('adminNewItemsContainer');
            const helpText = document.getElementById('noItemsHelpText');
            if (!container) return;
            if (helpText) helpText.classList.add('hidden');

            const idx = adminNewItemIndex++;
            const row = document.createElement('div');
            row.id = `admin_new_item_${idx}`;
            row.className = 'flex items-center gap-2 rounded-2xl bg-white p-2.5 border border-purple-200/90 shadow-2xs hover:border-purple-300 transition';
            row.innerHTML = `
                <div class="flex-1 min-w-0">
                    <input type="text" name="new_items[${idx}][name]" value="${name}" placeholder="Cloth name (e.g. Random Cap, Extra Towel, Socks)" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-1.5 text-xs font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:ring-2 focus:ring-purple-100 outline-none transition">
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="text-[9px] font-mono font-bold text-purple-700 bg-purple-50 px-2 py-1 rounded-md border border-purple-200 hidden sm:inline-block">Other</span>
                    <button type="button" onclick="adjustAdminNewItemQty(${idx}, -1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">-</button>
                    <input type="number" min="1" max="500" name="new_items[${idx}][qty]" id="admin_new_qty_${idx}" value="${qty}" oninput="recalcVerifiedTotal()" class="verified-item-input w-12 rounded-xl border border-purple-200 bg-white py-1.5 text-center font-mono text-xs font-bold text-slate-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-100 outline-none transition">
                    <button type="button" onclick="adjustAdminNewItemQty(${idx}, 1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-purple-200 bg-purple-50 text-purple-700 hover:bg-purple-100 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">+</button>
                    <button type="button" onclick="removeAdminVerificationClothRow(${idx})" class="flex h-8 w-8 items-center justify-center rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Remove cloth">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            `;
            container.appendChild(row);
            recalcVerifiedTotal();
        }

        function adjustAdminNewItemQty(idx, delta) {
            const input = document.getElementById(`admin_new_qty_${idx}`);
            if (!input) return;
            let current = parseInt(input.value) || 0;
            current = Math.max(1, current + delta);
            input.value = current;
            recalcVerifiedTotal();
        }

        function removeAdminVerificationClothRow(idx) {
            const row = document.getElementById(`admin_new_item_${idx}`);
            if (row) {
                row.remove();
            }
            const container = document.getElementById('adminNewItemsContainer');
            const helpText = document.getElementById('noItemsHelpText');
            if (container && container.children.length === 0 && helpText) {
                helpText.classList.remove('hidden');
            }
            recalcVerifiedTotal();
        }

        function removeExistingItem(itemId, rowElementId) {
            const row = document.getElementById(rowElementId);
            if (row) {
                row.remove();
            }
            const delContainer = document.getElementById('adminDeletedItemsContainer');
            if (delContainer) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'delete_items[]';
                hiddenInput.value = itemId;
                delContainer.appendChild(hiddenInput);
            }
            recalcVerifiedTotal();
        }

        function recalcVerifiedTotal() {
            let total = 0;
            const inputs = document.querySelectorAll('.verified-item-input');
            if (inputs.length === 0) return;

            inputs.forEach(inp => {
                total += parseInt(inp.value) || 0;
            });

            const qtyField = document.getElementById('inputAdminActualQuantity');
            if (qtyField) {
                qtyField.value = total > 0 ? total : 1;
            }
        }
    </script>
</x-admin-layout>
