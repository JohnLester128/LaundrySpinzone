<x-customer-layout>
    <x-slot:title>Service Request #REQ-{{ str_pad($serviceRequest->id, 4, '0', STR_PAD_LEFT) }} — Spinzone Laundry</x-slot:title>

    @php
        $statusRaw = $serviceRequest->status;
        $statusLabel = match($statusRaw) {
            'pending' => 'Pending',
            'received' => 'Received',
            'washing' => 'Washing',
            'drying' => 'Drying',
            'ready_for_pickup', 'ready' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($statusRaw)
        };

        $statusBadgeClass = match($statusRaw) {
            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
            'received' => 'bg-slate-100 text-slate-700 border-slate-200',
            'washing' => 'bg-purple-100 text-purple-700 border-purple-200',
            'drying' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            'ready_for_pickup', 'ready' => 'bg-teal-100 text-teal-700 border-teal-200',
            'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200'
        };

        $statusDotClass = match($statusRaw) {
            'pending' => 'bg-amber-500',
            'received' => 'bg-slate-500',
            'washing' => 'bg-purple-600',
            'drying' => 'bg-indigo-600',
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

            <!-- Quick Print Invoice -->
            <button type="button" onclick="window.print()" class="flex h-10 items-center gap-2 rounded-2xl border border-slate-200/80 bg-white px-4 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-2xs cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Print Invoice</span>
            </button>
        </div>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Visual Order Progress Stepper -->
    <div class="mb-6 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-x-auto">
        <p class="text-xs font-mono uppercase font-bold tracking-wider text-slate-400 mb-4">Order Progress Stepper</p>

        <div class="flex items-center justify-between min-w-[650px] relative px-4">
            <!-- Background Line -->
            <div class="absolute left-10 right-10 top-4 h-1 bg-slate-100 z-0"></div>
            <div class="absolute left-10 top-4 h-1 bg-blue-600 z-0 transition-all duration-500" style="width: {{ max(0, min(100, ($stepIndex - 1) * 20)) }}%;"></div>

            <!-- Step 1: Pending -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 1 ? 'bg-amber-500 text-white shadow-amber-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    1
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 1 ? 'text-slate-900' : 'text-slate-400' }}">Pending</p>
                <span class="text-[10px] text-slate-400">Order Placed</span>
            </div>

            <!-- Step 2: Received -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 2 ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    2
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 2 ? 'text-slate-900' : 'text-slate-400' }}">Received</p>
                <span class="text-[10px] text-slate-400">Weighed by Staff</span>
            </div>

            <!-- Step 3: Washing -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 3 ? 'bg-purple-600 text-white shadow-purple-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    3
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 3 ? 'text-slate-900' : 'text-slate-400' }}">Washing</p>
                <span class="text-[10px] text-slate-400">In Washer</span>
            </div>

            <!-- Step 4: Drying -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 4 ? 'bg-indigo-600 text-white shadow-indigo-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    4
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 4 ? 'text-slate-900' : 'text-slate-400' }}">Drying</p>
                <span class="text-[10px] text-slate-400">In Dryer</span>
            </div>

            <!-- Step 5: Ready -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 5 ? 'bg-teal-500 text-white shadow-teal-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    5
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 5 ? 'text-slate-900' : 'text-slate-400' }}">Ready</p>
                <span class="text-[10px] text-slate-400">Ready for Pick-up</span>
            </div>

            <!-- Step 6: Completed -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 6 ? 'bg-emerald-500 text-white shadow-emerald-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    6
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 6 ? 'text-slate-900' : 'text-slate-400' }}">Completed</p>
                <span class="text-[10px] text-slate-400">Claimed & Paid</span>
            </div>
        </div>
    </div>

    <!-- Weight & Count Verification Status Banner (If applicable) -->
    @if(!$serviceRequest->clothType->requires_size && $serviceRequest->weight_verification_status === 'correct')
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-2xs flex items-start gap-3">
            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xs">✓</span>
            <div>
                <p class="font-bold text-sm">Weight & Item Count Verified Correct</p>
                <p class="mt-0.5 text-xs text-emerald-700 leading-relaxed">
                    {{ $serviceRequest->verification_note ?? 'Your submitted laundry weight and item count were verified as correct by our branch staff.' }}
                </p>
            </div>
        </div>
    @elseif(!$serviceRequest->clothType->requires_size && $serviceRequest->weight_verification_status === 'incorrect')
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
        <!-- Left Side: Laundry Specifications Card (Spans 2 columns) -->
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
                        <h2 class="text-xl font-bold tracking-tight text-slate-900">Laundry Specifications</h2>
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
                            <dd class="text-base font-bold text-purple-700 mt-1">{{ $serviceRequest->comforter_size }}</dd>
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
        </div>
    </div>
</x-customer-layout>
