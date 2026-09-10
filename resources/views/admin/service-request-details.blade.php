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
                'drying' => 'Move to Drying',
                'ready_for_pickup' => 'Mark as Ready for Pickup',
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

            <!-- Quick Action Header -->
            <div class="flex items-center gap-3">
                <button type="button" onclick="window.print()" class="flex h-10 items-center gap-2 rounded-2xl border border-slate-200/80 bg-white px-4 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Print Invoice</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Visual Order Timeline Stepper -->
    <div class="mb-6 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-x-auto">
        <p class="text-xs font-mono uppercase font-bold tracking-wider text-slate-400 mb-4">Order Progress Stepper</p>

        <div class="flex items-center justify-between min-w-[650px] relative">
            <!-- Background Line -->
            <div class="absolute left-6 right-6 top-4 h-1 bg-slate-100 z-0"></div>
            <div class="absolute left-6 top-4 h-1 bg-blue-600 z-0 transition-all duration-500" style="width: {{ max(0, min(100, ($stepIndex - 1) * 20)) }}%;"></div>

            <!-- Step 1: Pending -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 1 ? 'bg-amber-500 text-white shadow-amber-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    1
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 1 ? 'text-slate-900' : 'text-slate-400' }}">Pending</p>
                <p class="text-[10px] text-slate-400 font-mono">Online Request</p>
            </div>

            <!-- Step 2: Received -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 2 ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    2
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 2 ? 'text-slate-900' : 'text-slate-400' }}">Received</p>
                <p class="text-[10px] text-slate-400 font-mono">Counter Entry</p>
            </div>

            <!-- Step 3: Washing -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 3 ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    3
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 3 ? 'text-slate-900' : 'text-slate-400' }}">Washing</p>
                <p class="text-[10px] text-slate-400 font-mono">In Machine</p>
            </div>

            <!-- Step 4: Drying -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 4 ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    4
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 4 ? 'text-slate-900' : 'text-slate-400' }}">Drying</p>
                <p class="text-[10px] text-slate-400 font-mono">Tumble Dryer</p>
            </div>

            <!-- Step 5: Ready for Pickup -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 5 ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    5
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 5 ? 'text-slate-900' : 'text-slate-400' }}">Ready</p>
                <p class="text-[10px] text-slate-400 font-mono">Folded & Shelved</p>
            </div>

            <!-- Step 6: Completed -->
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex h-9 w-9 items-center justify-center rounded-full font-bold text-xs shadow-2xs transition {{ $stepIndex >= 6 ? 'bg-emerald-600 text-white shadow-emerald-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    ✓
                </div>
                <p class="text-xs font-extrabold mt-2 {{ $stepIndex >= 6 ? 'text-emerald-700' : 'text-slate-400' }}">Completed</p>
                <p class="text-[10px] text-slate-400 font-mono">Paid & Released</p>
            </div>
        </div>
    </div>

    <!-- Main Content 2-Column Grid -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Left Column: Details & Verification (2 Columns) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card 1: Service Specifications & Financial Breakdown -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Service Specifications</h2>
                            <p class="text-xs text-slate-400 font-medium">Clothing details, weights, quantity, and pricing</p>
                        </div>
                    </div>

                    <span class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700 border border-slate-200/60">
                        {{ $serviceRequest->clothType->name ?? 'Standard Wash' }}
                    </span>
                </div>

                @if(!$serviceRequest->clothType->requires_size && $serviceRequest->weight_verification_status === 'correct')
                    <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-3.5 flex items-start gap-2.5">
                        <div class="text-emerald-600 font-bold text-base mt-0.5">✅</div>
                        <div>
                            <p class="text-xs font-bold text-emerald-900">Weight & Piece Count Verified as Correct</p>
                            <p class="text-[11px] text-emerald-700 mt-0.5">{{ $serviceRequest->verification_note }}</p>
                        </div>
                    </div>
                @elseif(!$serviceRequest->clothType->requires_size && $serviceRequest->weight_verification_status === 'incorrect')
                    <div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50/80 p-3.5 flex items-start gap-2.5">
                        <div class="text-amber-600 font-bold text-base mt-0.5">⚠️</div>
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
                            @if($serviceRequest->comforter_size)
                                {{ $serviceRequest->comforter_size }}
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

                <!-- Add-on Treatments Section -->
                <div class="mb-6">
                    <p class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 mb-2">Selected Add-ons</p>
                    <div class="flex flex-wrap items-center gap-2">
                        @forelse($serviceRequest->addons as $addon)
                            <span class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200/60 shadow-2xs">
                                <span>✨ {{ $addon->name }}</span>
                                <span class="font-mono text-[11px] text-emerald-600">(+PHP {{ number_format($addon->pivot->price ?? $addon->price, 0) }})</span>
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
                            <span>📝 Special Customer Instructions</span>
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

            @if($serviceRequest->status === 'pending')
                <!-- Card 2: Verify & Calibrate Laundry Form -->
                <div class="rounded-3xl border border-blue-200/80 bg-gradient-to-br from-blue-50/40 via-white to-white p-6 sm:p-7 shadow-sm">
                    <div class="flex items-center gap-3 pb-4 mb-4 border-b border-blue-100">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 18h12l3-18H3z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Verify Laundry Items & Specs</h2>
                            <p class="text-xs text-slate-500 font-medium">Verify piece count and calibrate scale weight. Submitting automatically receives the order.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.service-requests.details', $serviceRequest) }}" class="space-y-4">
                        @csrf
                        @if($serviceRequest->clothType->requires_size)
                            <!-- Comforter Verification: Select & Confirm Comforter Size (item count is 1) -->
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
                                    Customer submitted: <span class="font-bold text-slate-700">{{ $serviceRequest->comforter_size ?? $serviceRequest->pricingTier->name }}</span> (₱{{ number_format($serviceRequest->pricingTier->price ?? 0, 0) }}) · Comforter is 1 pc
                                </p>
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
                                        <input type="number" min="1" step="1" name="actual_quantity" value="{{ old('actual_quantity', $serviceRequest->actual_quantity ?? $serviceRequest->quantity) }}" placeholder="e.g. 10" required class="w-full rounded-2xl border border-slate-200 bg-white pl-4 pr-12 py-2.5 text-xs font-mono font-bold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-mono font-bold text-slate-400">
                                            pcs
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 font-mono">Customer submitted: {{ $serviceRequest->quantity }} pcs</p>
                                </div>
                            </div>
                        @endif

                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 py-3.5 px-4 text-xs font-extrabold text-white shadow-md hover:bg-blue-700 active:scale-95 transition cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Verify & Set Status to Received</span>
                        </button>
                    </form>
                </div>
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

            <!-- Card 1: Update Status (Moved to Top) -->
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
                                <form method="POST" action="{{ route('admin.service-requests.status', $serviceRequest) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $stKey }}">
                                    <button type="submit" class="w-full text-left rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100 transition {{ $serviceRequest->status === $stKey ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700' }}">
                                        {{ $stLbl }}
                                    </button>
                                </form>
                            @endforeach

                            @if($serviceRequest->status !== 'completed' && $serviceRequest->status !== 'cancelled')
                                <div class="my-1 border-t border-slate-100"></div>
                                <form method="POST" action="{{ route('admin.service-requests.status', $serviceRequest) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" onclick="return confirm('Are you sure you want to cancel this request?')" class="w-full text-left rounded-xl px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition">
                                        ✕ Cancel Request
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
                                <form method="POST" action="{{ route('admin.service-requests.status', $serviceRequest) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="washing">
                                    <input type="hidden" name="washing_machine_id" value="{{ $washer->id }}">
                                    <button type="submit" class="w-full flex items-center justify-between p-3.5 rounded-2xl border-2 {{ $serviceRequest->washing_machine_id == $washer->id ? 'border-purple-500 bg-purple-50' : 'border-blue-200 bg-blue-50/50 hover:bg-blue-600 hover:border-blue-600 hover:text-white' }} group transition-all duration-200 shadow-2xs cursor-pointer active:scale-98">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white group-hover:bg-white group-hover:text-blue-600 font-bold transition shadow-xs">
                                                🧼
                                            </div>
                                            <div class="text-left">
                                                <p class="font-extrabold text-sm text-slate-900 group-hover:text-white transition">{{ $washer->name }}</p>
                                                <p class="text-[11px] text-slate-500 group-hover:text-blue-100 transition capitalize">Status: {{ $washer->status }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-xs font-extrabold text-blue-600 group-hover:text-white transition">
                                            <span>Assign & Move</span>
                                            <svg class="h-4 w-4 transform transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </div>
                                    </button>
                                </form>
                            @empty
                                <div class="rounded-2xl border border-dashed border-amber-300 bg-amber-50 p-4 text-center">
                                    <p class="text-xs font-bold text-amber-800">No Washing Machines Available</p>
                                    <p class="text-[11px] text-amber-700 mt-0.5">Please register or free a washer machine.</p>
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
                                <form method="POST" action="{{ route('admin.service-requests.status', $serviceRequest) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="drying">
                                    <input type="hidden" name="dryer_machine_id" value="{{ $dryer->id }}">
                                    <button type="submit" class="w-full flex items-center justify-between p-3.5 rounded-2xl border-2 {{ $serviceRequest->dryer_machine_id == $dryer->id ? 'border-indigo-500 bg-indigo-50' : 'border-indigo-200 bg-indigo-50/50 hover:bg-indigo-600 hover:border-indigo-600 hover:text-white' }} group transition-all duration-200 shadow-2xs cursor-pointer active:scale-98">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white group-hover:bg-white group-hover:text-indigo-600 font-bold transition shadow-xs">
                                                💨
                                            </div>
                                            <div class="text-left">
                                                <p class="font-extrabold text-sm text-slate-900 group-hover:text-white transition">{{ $dryer->name }}</p>
                                                <p class="text-[11px] text-slate-500 group-hover:text-indigo-100 transition capitalize">Status: {{ $dryer->status }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-xs font-extrabold text-indigo-600 group-hover:text-white transition">
                                            <span>Assign & Move</span>
                                            <svg class="h-4 w-4 transform transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </div>
                                    </button>
                                </form>
                            @empty
                                <div class="rounded-2xl border border-dashed border-amber-300 bg-amber-50 p-4 text-center">
                                    <p class="text-xs font-bold text-amber-800">No Dryer Machines Available</p>
                                    <p class="text-[11px] text-amber-700 mt-0.5">Please register or free a dryer machine.</p>
                                    <a href="{{ route('admin.machines') }}" class="mt-2 inline-block text-xs font-extrabold text-blue-600 underline">Manage Machines →</a>
                                </div>
                            @endforelse
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
                        <span class="text-xl">🎉</span>
                        <p class="text-xs font-extrabold text-emerald-800 mt-1">Request Completed</p>
                        <p class="text-[11px] text-emerald-700/80 mt-0.5">This service request has been completed and logged into sales records.</p>
                    </div>
                @endif
            </div>

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
                        🧺
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
                                🧼 {{ $serviceRequest->washingMachine->name }}
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
                                💨 {{ $serviceRequest->dryerMachine->name }}
                            </span>
                        @else
                            <span class="text-xs font-medium text-slate-400 italic">Not assigned</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Dismiss Popover Script -->
    <script>
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('statusMenuWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('statusMenuPopover')?.classList.add('hidden');
            }
        });
    </script>
</x-admin-layout>
