<x-customer-layout>
    <x-slot:title>Service Request #REQ-{{ str_pad($serviceRequest->id, 4, '0', STR_PAD_LEFT) }} — Spinzone Laundry</x-slot:title>

    @php
        $cycle = $serviceRequest->cycle_info;
        $statusRaw = $serviceRequest->status;
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

    <!-- Back Link & Header -->
    <div class="mb-6">
        <a href="{{ route('customer.requests') }}" class="inline-flex items-center gap-2 font-mono text-xs font-bold text-slate-500 hover:text-blue-600 transition group mb-3">
            <svg class="h-4 w-4 transform transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to My Requests</span>
        </a>

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
                    {{ $serviceRequest->clothType->name ?? 'Laundry Order' }}
                </h1>
            </div>

            <div class="flex items-center gap-2.5">
                @if($serviceRequest->status === 'completed')
                    <a href="{{ route('customer.feedback', ['request_id' => $serviceRequest->id]) }}" class="flex h-10 items-center gap-2 rounded-2xl border border-amber-200 bg-amber-50 px-4 text-xs font-bold text-amber-800 hover:bg-amber-100 transition shadow-2xs">
                        <svg class="h-4 w-4 text-amber-500 fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span>{{ $serviceRequest->feedbacks->count() ? 'Review Submitted' : 'Rate This Service' }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Visual Order Progress Stepper -->
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
                <span class="text-[10px] text-slate-400">Order Placed</span>
            </div>

            <!-- Step 2: Received -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 2 ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    2
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 2 ? 'text-slate-900' : 'text-slate-400' }}">Received</p>
                <span class="text-[10px] text-slate-400">Weighed by Staff</span>
            </div>

            <!-- Step 3: Washing -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 3 ? 'bg-purple-600 text-white shadow-purple-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    3
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 3 ? 'text-slate-900' : 'text-slate-400' }}">Washing</p>
                <span class="text-[10px] text-slate-400">In Washer</span>
            </div>

            <!-- Step 4: Drying -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 4 ? 'bg-indigo-600 text-white shadow-indigo-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    4
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 4 ? 'text-slate-900' : 'text-slate-400' }}">Drying</p>
                <span class="text-[10px] text-slate-400">In Dryer</span>
            </div>

            <!-- Step 5: Ready -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 5 ? 'bg-teal-500 text-white shadow-teal-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    5
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 5 ? 'text-slate-900' : 'text-slate-400' }}">Ready</p>
                <span class="text-[10px] text-slate-400">Ready for Pick-up</span>
            </div>

            <!-- Step 6: Completed -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 6 ? 'bg-emerald-500 text-white shadow-emerald-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    6
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 6 ? 'text-slate-900' : 'text-slate-400' }}">Completed</p>
                <span class="text-[10px] text-slate-400">Claimed & Paid</span>
            </div>
        </div>
    </div>

    @if(in_array($serviceRequest->status, ['washing', 'drying']))
        <!-- Customer Live Cycle Progress Card (Compact & Modern) -->
        <div class="mb-5 rounded-2xl border {{ $cycle['phase'] === 'folding' ? 'border-teal-200/80 bg-gradient-to-br from-teal-50/60 via-white to-emerald-50/20' : ($cycle['phase'] === 'resting' ? 'border-amber-200/80 bg-gradient-to-br from-amber-50/60 via-white to-amber-50/20' : ($cycle['phase'] === 'drying' ? 'border-indigo-200/80 bg-gradient-to-br from-indigo-50/60 via-white to-indigo-50/20' : 'border-blue-200/80 bg-gradient-to-br from-blue-50/60 via-white to-blue-50/20')) }} p-4 sm:p-5 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 mb-3 border-b {{ $cycle['phase'] === 'folding' ? 'border-teal-200/60' : ($cycle['phase'] === 'resting' ? 'border-amber-200/60' : ($cycle['phase'] === 'drying' ? 'border-indigo-100' : 'border-blue-100')) }}">
                <div class="flex items-center gap-3">
                    <div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[10px] font-mono font-bold uppercase tracking-wider {{ $cycle['phase'] === 'folding' ? 'bg-teal-100 text-teal-800' : ($cycle['phase'] === 'resting' ? 'bg-amber-100 text-amber-800' : ($cycle['phase'] === 'drying' ? 'bg-indigo-100 text-indigo-800' : 'bg-blue-100 text-blue-800')) }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $cycle['phase'] === 'folding' ? 'bg-teal-500' : ($cycle['phase'] === 'resting' ? 'bg-amber-500' : ($cycle['phase'] === 'drying' ? 'bg-indigo-600' : 'bg-blue-600')) }} animate-pulse"></span>
                            <span>{{ $cycle['phase_label'] }}</span>
                        </span>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 mt-0.5">
                            @if($cycle['phase'] === 'folding')
                                Drying Complete · Folding &amp; Packaging
                            @elseif($cycle['phase'] === 'resting')
                                Wash Completed · Wash Buffer &amp; Transfer to Dryer
                            @elseif($cycle['phase'] === 'drying')
                                Drying Cycle in Progress
                            @else
                                Washing Cycle in Progress
                            @endif
                        </h2>
                    </div>
                </div>

                @if($cycle['phase'] === 'folding')
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-teal-100 text-teal-800 font-mono text-xs font-bold border border-teal-200">
                        <span>With Staff</span>
                    </div>
                @else
                    <div class="flex items-baseline gap-2">
                        <span id="custCycleTimerDisplay" class="text-2xl sm:text-3xl font-black font-mono tracking-tight text-slate-900">
                            {{ $cycle['display_time'] }}
                        </span>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400">
                            remaining
                        </span>
                    </div>
                @endif
            </div>

            @if($cycle['phase'] === 'folding')
                <!-- Folding Phase Information (No Timer) -->
                <div class="rounded-xl bg-white/80 p-3 border border-teal-200/70 text-xs text-slate-600 leading-relaxed space-y-0.5">
                    <p class="font-bold text-slate-800">Your laundry has finished drying!</p>
                    <p class="text-[11px] text-slate-500">Our staff is now carefully folding and bagging your clothes. You will receive a notification as soon as it's ready for pickup.</p>
                </div>
            @else
                <!-- Progress Bar -->
                <div class="space-y-1.5">
                    <div class="relative w-full h-2 rounded-full bg-slate-200/80 overflow-hidden shadow-inner">
                        <div id="custCycleProgressBar" class="h-full rounded-full transition-all duration-1000 {{ $cycle['phase'] === 'resting' ? 'bg-gradient-to-r from-amber-400 to-amber-500' : ($cycle['phase'] === 'drying' ? 'bg-gradient-to-r from-indigo-500 to-purple-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600') }}" style="width: {{ $cycle['percentage'] }}%;"></div>
                    </div>
                    <div class="flex justify-between items-center text-[11px] font-mono text-slate-500">
                        <span>
                            @if($cycle['phase'] === 'resting')
                                Wash cool-down &amp; transfer buffer
                            @elseif($cycle['phase'] === 'drying')
                                Assigned to {{ $serviceRequest->dryerMachine->name ?? 'Dryer' }}
                            @else
                                Assigned to {{ $serviceRequest->washingMachine->name ?? 'Washer' }}
                            @endif
                        </span>
                        <span>Live status auto-updates</span>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Weight & Count Verification Status Banner (If applicable) -->
    @if($serviceRequest->weight_verification_status === 'correct')
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-2xs flex items-start gap-3">
            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xs">✓</span>
            <div>
                <p class="font-bold text-sm">Weight & Item Count Verified Correct</p>
                <p class="mt-0.5 text-xs text-emerald-700 leading-relaxed">
                    {{ $serviceRequest->verification_note ?? 'Your submitted laundry weight and item count were verified as correct by our branch staff.' }}
                </p>
            </div>
        </div>
    @elseif($serviceRequest->weight_verification_status === 'incorrect')
        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-900 shadow-2xs flex items-start gap-3">
            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-600 text-white font-bold text-xs">!</span>
            <div>
                <p class="font-bold text-sm">Weight / Item Count Adjusted by Branch Staff</p>
                <p class="mt-0.5 text-xs text-amber-800 leading-relaxed">
                    {{ $serviceRequest->verification_note ?? 'Your laundry weight or item count was adjusted upon weigh-in scale verification.' }}
                </p>
            </div>
        </div>
    @endif

    <!-- Main Grid Layout -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Left Side: Laundry Details Card (Spans 2 columns) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-blue-600"></div>

                <div class="flex items-center gap-3 pb-5 border-b border-slate-100">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100/80 text-blue-600 shadow-2xs">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-slate-900">Laundry Details</h2>
                        <p class="text-xs font-medium text-slate-400">Full details of your service package and add-ons.</p>
                    </div>
                </div>

                <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                        <dt class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Branch Location</dt>
                        <dd class="text-base font-bold text-slate-900 mt-1">{{ $serviceRequest->branch->name ?? 'Central Branch' }}</dd>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                        <dt class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Clothing Type</dt>
                        <dd class="text-base font-bold text-slate-900 mt-1">{{ $serviceRequest->clothType->name }}</dd>
                    </div>

                    @if($serviceRequest->comforter_size)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <dt class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Comforter Size</dt>
                            <dd class="text-base font-bold text-purple-700 mt-1">
                                {{ $serviceRequest->comforter_size }}
                                @php
                                    $dWeight = $serviceRequest->actual_weight ?? $serviceRequest->estimated_weight;
                                @endphp
                                @if($dWeight && $dWeight > 0)
                                    <span class="text-xs font-bold text-slate-500 font-mono">({{ ($dWeight + 0) }} kg{{ $serviceRequest->actual_weight ? ' verified' : '' }})</span>
                                @endif
                            </dd>
                        </div>
                    @else
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <dt class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Weight (kg)</dt>
                            <dd class="text-base font-bold text-slate-900 mt-1">
                                @if($serviceRequest->actual_weight)
                                    {{ ($serviceRequest->actual_weight + 0) }} kg <span class="text-xs font-semibold text-emerald-600 font-mono">(Verified)</span>
                                @elseif($serviceRequest->estimated_weight)
                                    {{ ($serviceRequest->estimated_weight + 0) }} kg <span class="text-xs font-semibold text-slate-400 font-mono">(Estimated)</span>
                                @else
                                    {{ $serviceRequest->pricingTier->name ?? 'Standard' }}
                                @endif
                            </dd>
                        </div>
                    @endif

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                        <dt class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Items Quantity</dt>
                        <dd class="text-base font-bold text-slate-900 mt-1">
                            {{ $serviceRequest->actual_quantity ?? $serviceRequest->quantity }} pcs
                        </dd>
                    </div>
                </dl>

                @if($serviceRequest->items->count() > 0)
                    <!-- Itemized Clothing Checklist Card -->
                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2.5">
                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 font-mono">Itemized Cloth Checklist</h3>
                                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-extrabold text-blue-700 font-mono">{{ $serviceRequest->items->count() }} items</span>
                            </div>
                            <span class="text-[11px] font-mono text-slate-400">Anti-Loss Protection</span>
                        </div>

                        <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-slate-50/50">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-100/70 font-mono text-[10px] uppercase font-bold text-slate-500">
                                        <th class="py-2.5 px-3">Item Type</th>
                                        <th class="py-2.5 px-3 text-center">Submitted Count</th>
                                        <th class="py-2.5 px-3 text-center">Store Verified</th>
                                        <th class="py-2.5 px-3 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
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
                                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-500">
                                                        Pending Check
                                                    </span>
                                                @elseif($item->declared_qty === 0)
                                                    <span class="inline-flex items-center gap-1 rounded-md bg-purple-50 px-2 py-0.5 text-[10px] font-bold text-purple-700 border border-purple-200">
                                                        <span>+</span> {{ $item->verified_qty }} pcs extra
                                                    </span>
                                                @elseif($isMatch)
                                                    <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                                        <span>✓</span> Verified
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

                <!-- Add-ons Section -->
                <div class="mt-5 pt-4 border-t border-slate-100">
                    <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Selected Add-Ons</p>
                    @if($serviceRequest->addons->count())
                        <div class="flex flex-wrap gap-2">
                            @foreach($serviceRequest->addons as $addon)
                                <span class="inline-flex items-center gap-1.5 rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 border border-blue-100">
                                    <span>✦</span>
                                    <span>{{ $addon->name }} (+PHP {{ number_format($addon->price, 0) }})</span>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm font-medium text-slate-400">No add-ons requested.</p>
                    @endif
                </div>

                <!-- Special Notes / Instructions -->
                @if($serviceRequest->notes)
                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Special Instructions</p>
                        <div class="rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4 text-xs font-medium text-slate-700 leading-relaxed">
                            {{ $serviceRequest->notes }}
                        </div>
                    </div>
                @endif

                <!-- Total Pricing Summary Strip -->
                <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between rounded-2xl bg-gradient-to-r from-blue-50/70 to-indigo-50/70 p-5 border border-blue-100">
                    <div>
                        <p class="text-xs uppercase font-mono font-bold tracking-wider text-slate-500">Total Price</p>
                        <p class="text-xs text-slate-400 mt-0.5">Includes base rate and all add-ons</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl sm:text-3xl font-black text-blue-600">
                            PHP {{ number_format($serviceRequest->final_price ?? $serviceRequest->estimated_price, 0) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Branch Information & Timestamps / History (1 column) -->
        <div class="space-y-6">
            <!-- Branch Contact / Info Card -->
            <div class="rounded-3xl border border-blue-100 bg-blue-50/40 p-6 shadow-xs">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-600 text-white shadow-xs">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900">Branch Location</h2>
                </div>
                <p class="text-sm font-bold text-slate-800">{{ $serviceRequest->branch->name ?? 'Spinzone Laundry' }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ $serviceRequest->branch->address ?? 'Main Branch Location' }}</p>
            </div>

            <!-- Timestamps & History Card -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">Timestamps</h3>
                <dl class="mt-4 space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-400 font-mono uppercase">Submitted</dt>
                        <dd class="font-bold text-slate-800">{{ $serviceRequest->created_at->format('M d, Y · h:i A') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-400 font-mono uppercase">Last Updated</dt>
                        <dd class="font-bold text-slate-800">{{ $serviceRequest->updated_at->format('M d, Y · h:i A') }}</dd>
                    </div>
                </dl>

                @if($serviceRequest->statusHistories->count())
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <h4 class="font-mono text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Status Timeline</h4>
                        <div class="space-y-3">
                            @foreach($serviceRequest->statusHistories->sortByDesc('started_at') as $history)
                                <div class="flex items-center justify-between text-xs py-1 border-b border-slate-50 last:border-0">
                                    <span class="font-bold capitalize text-slate-800">{{ str_replace('_', ' ', $history->status) }}</span>
                                    <span class="font-mono text-slate-400 text-[11px]">{{ $history->started_at ? $history->started_at->format('M d, h:i A') : ($history->created_at ? $history->created_at->format('M d, h:i A') : 'N/A') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            @if($serviceRequest->feedbacks->isNotEmpty())
                <!-- Customer Submitted Feedback Card -->
                <div class="rounded-3xl border border-amber-200/80 bg-amber-50/40 p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-amber-200/60 mb-3">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-amber-500 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span>Your Service Review</span>
                        </h3>
                        <span class="text-xs font-mono font-bold text-amber-800">
                            {{ $serviceRequest->feedbacks->first()->rating }}.0 / 5.0
                        </span>
                    </div>
                    @php $firstFb = $serviceRequest->feedbacks->first(); @endphp
                    @if($firstFb->subject)
                        <p class="text-xs font-bold text-slate-800 break-words break-all">{{ $firstFb->subject }}</p>
                    @endif
                    @if($firstFb->comment)
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed break-words break-all">{{ $firstFb->comment }}</p>
                    @endif
                    @if(!empty($firstFb->aspects))
                        <div class="mt-2.5 flex flex-wrap gap-1">
                            @foreach($firstFb->aspects as $asp)
                                <span class="rounded-lg bg-white/80 border border-amber-200 px-2 py-0.5 text-[10px] font-medium text-amber-900">
                                    {{ $asp }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    <p class="text-[10px] font-mono text-slate-400 mt-3 pt-2 border-t border-amber-200/50">
                        Submitted {{ $firstFb->created_at->format('M d, Y · h:i A') }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Real-time Laundry Tracker Sync & Countdown Script -->
    <script>
        (function() {
            const currentReqId = '{{ $serviceRequest->id }}';
            let currentStatus = '{{ $serviceRequest->status }}';
            let currentPhase = '{{ $serviceRequest->cycle_phase }}';
            let currentWeightVerification = '{{ $serviceRequest->weight_verification_status }}';
            let remainingSeconds = {{ (int) ($cycle['remaining_seconds'] ?? 0) }};
            let totalSeconds = {{ (int) ($cycle['total_seconds'] ?? 0) }};
            const timerDisplay = document.getElementById('custCycleTimerDisplay');
            const progressBar = document.getElementById('custCycleProgressBar');
            let isReloading = false;
            let syncInterval = null;

            function triggerReload() {
                if (isReloading) return;
                isReloading = true;
                sessionStorage.setItem('spinzone_tracker_scroll', window.scrollY);
                window.location.reload();
            }

            function formatTime(sec) {
                const m = Math.floor(sec / 60);
                const s = sec % 60;
                return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
            }

            // 1. Live Countdown Timer (if washing or drying)
            if (remainingSeconds > 0) {
                const countdownInterval = setInterval(function() {
                    remainingSeconds--;
                    if (remainingSeconds < 0) remainingSeconds = 0;

                    if (timerDisplay) timerDisplay.textContent = formatTime(remainingSeconds);
                    if (progressBar && totalSeconds > 0) {
                        const pct = Math.min(100, Math.round(((totalSeconds - remainingSeconds) / totalSeconds) * 100));
                        progressBar.style.width = pct + '%';
                    }

                    if (remainingSeconds <= 0) {
                        clearInterval(countdownInterval);
                        checkStatus();
                    }
                }, 1000);
            }

            // 2. Real-time Background Status Check
            function checkStatus() {
                if (isReloading) return;
                fetch('{{ route('customer.requests.cycle-sync', $serviceRequest) }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (!data || !data.success) return;

                    // If status, cycle phase, or weight verification changed, reload immediately!
                    if (data.changed || 
                        data.status !== currentStatus || 
                        data.cycle_phase !== currentPhase || 
                        (data.weight_verification_status || '') !== (currentWeightVerification || '')) {
                        triggerReload();
                        return;
                    }

                    // Synchronize timer drift if active
                    if (data.info && typeof data.info.remaining_seconds === 'number' && remainingSeconds > 0) {
                        const srvRemaining = data.info.remaining_seconds;
                        if (Math.abs(srvRemaining - remainingSeconds) > 2) {
                            remainingSeconds = srvRemaining;
                            if (timerDisplay) timerDisplay.textContent = formatTime(remainingSeconds);
                        }
                    }
                })
                .catch(() => {});
            }

            // Check every 5 seconds if not yet completed or cancelled
            if (currentStatus !== 'completed' && currentStatus !== 'cancelled') {
                syncInterval = setInterval(checkStatus, 5000);
            }

            // Check immediately when tab regains focus or visibility
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden && currentStatus !== 'completed' && currentStatus !== 'cancelled') {
                    checkStatus();
                }
            });
            window.addEventListener('focus', function() {
                if (currentStatus !== 'completed' && currentStatus !== 'cancelled') {
                    checkStatus();
                }
            });

            // 3. Listen to global real-time event from notification poller
            window.addEventListener('spinzone:tracker-sync', function(e) {
                if (isReloading) return;
                const activeList = (e.detail && e.detail.activeRequests) || [];
                const found = activeList.find(r => String(r.id) === String(currentReqId));
                if (found) {
                    if (found.status !== currentStatus || 
                        found.cycle_phase !== currentPhase || 
                        (found.weight_verification_status || '') !== (currentWeightVerification || '')) {
                        triggerReload();
                    }
                } else if (currentStatus !== 'completed' && currentStatus !== 'cancelled') {
                    // It was active and is no longer in active requests -> order was completed or cancelled!
                    triggerReload();
                }
            });
        })();
    </script>
</x-customer-layout>
