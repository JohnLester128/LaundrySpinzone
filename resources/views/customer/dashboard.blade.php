<x-customer-layout>
    <x-slot:title>Customer Dashboard — Spinzone Laundry</x-slot:title>

    <!-- Notification Banner -->
    <x-notification />

    @php
        $activeRequests = $serviceRequests->whereNotIn('status', ['completed', 'cancelled']);
        $completedRequests = $serviceRequests->where('status', 'completed');
        $totalSpent = $completedRequests->sum(function($r) {
            return $r->final_price ?? $r->estimated_price ?? 0;
        });
        $currentActive = $activeRequests->first();
    @endphp

    <!-- Stat Cards Grid -->
    <div class="grid gap-6 md:grid-cols-3">
        <!-- Card 1: Active Requests -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md">
            <div class="absolute top-0 inset-x-0 h-1 bg-blue-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Active Requests</p>
                    <h3 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        {{ $activeRequests->count() }}
                    </h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium {{ $activeRequests->count() > 0 ? 'text-blue-600' : 'text-slate-400' }}">
                <span class="inline-block h-2 w-2 rounded-full {{ $activeRequests->count() > 0 ? 'bg-blue-600 animate-pulse' : 'bg-slate-300' }} mr-1.5"></span>
                <span>{{ $activeRequests->count() > 0 ? 'In progress at laundry shop' : 'No active laundry in queue' }}</span>
            </div>
        </div>

        <!-- Card 2: Completed Orders -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md">
            <div class="absolute top-0 inset-x-0 h-1 bg-emerald-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Completed Orders</p>
                    <h3 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        {{ $completedRequests->count() }}
                    </h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-teal-400 text-white shadow-md shadow-teal-400/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-emerald-600">
                <svg class="mr-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Ready & delivered wash history</span>
            </div>
        </div>

        <!-- Card 3: Total Requests -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md">
            <div class="absolute top-0 inset-x-0 h-1 bg-purple-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Total Requests</p>
                    <h3 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        {{ $serviceRequests->count() }}
                    </h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-500 text-white shadow-md shadow-purple-500/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-purple-600">
                <span>Branch: {{ auth('customer')->user()->branch->name ?? 'Central Branch' }}</span>
            </div>
        </div>
    </div>

    <!-- Active Laundry Live Tracker (If an order is currently active) -->
    @if($currentActive)
        @php
            $activeStatusBadge = match($currentActive->status) {
                'washing' => 'bg-purple-100 text-purple-700 border-purple-200',
                'drying' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                'ready_for_pickup', 'ready' => 'bg-teal-100 text-teal-700 border-teal-200',
                'received' => 'bg-slate-100 text-slate-700 border-slate-200',
                default => 'bg-amber-100 text-amber-700 border-amber-200'
            };

            $activeStatusLabel = match($currentActive->status) {
                'washing' => '🧺 Washing in Progress',
                'drying' => '💨 Drying in Progress',
                'ready_for_pickup', 'ready' => '✓ Ready for Pick-up',
                'received' => '📥 Order Received by Staff',
                default => '⏳ Pending Queue'
            };
        @endphp

        <div class="mt-7 rounded-3xl border border-blue-200 bg-gradient-to-r from-blue-50/60 via-white to-indigo-50/60 p-6 sm:p-7 shadow-xs">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-blue-100/80">
                <div class="flex items-center gap-3">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-600"></span>
                    </span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Current Laundry In-Progress</h2>
                        <p class="text-xs text-slate-500">Live tracking for Request #REQ-{{ str_pad($currentActive->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-extrabold border shadow-2xs {{ $activeStatusBadge }}">
                    {{ $activeStatusLabel }}
                </span>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-white bg-white/90 p-4 shadow-2xs">
                    <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Laundry Type</p>
                    <p class="text-base font-bold text-slate-900 mt-1">{{ $currentActive->clothType->name ?? 'Standard' }}</p>
                </div>
                <div class="rounded-2xl border border-white bg-white/90 p-4 shadow-2xs">
                    <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Weight / Size</p>
                    <p class="text-base font-bold text-slate-900 mt-1">
                        @if($currentActive->comforter_size)
                            {{ $currentActive->comforter_size }}
                        @elseif($currentActive->actual_weight ?? $currentActive->estimated_weight)
                            {{ number_format($currentActive->actual_weight ?? $currentActive->estimated_weight, 0) }} kg
                        @else
                            {{ $currentActive->pricingTier->name ?? 'Standard' }}
                        @endif
                    </p>
                </div>
                <div class="rounded-2xl border border-white bg-white/90 p-4 shadow-2xs">
                    <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Quantity</p>
                    <p class="text-base font-bold text-slate-900 mt-1">{{ $currentActive->actual_quantity ?? $currentActive->quantity }} pcs</p>
                </div>
                <div class="rounded-2xl border border-white bg-white/90 p-4 shadow-2xs">
                    <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Estimated Total</p>
                    <p class="text-base font-black text-blue-600 mt-1">PHP {{ number_format($currentActive->final_price ?? $currentActive->estimated_price, 0) }}</p>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-end">
                <a href="{{ route('customer.requests.show', $currentActive) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-2xl px-5 py-2.5 shadow-md shadow-blue-600/20 transition active:scale-[0.99]">
                    <span>View Tracking Stepper & Details</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    @endif

    <!-- Main Content Section: Recent Activity Table (Left) + Quick Actions (Right) -->
    <div class="mt-7 grid gap-6 lg:grid-cols-3">
        <!-- Recent Activity Table (spans 2 columns) -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h2 class="text-xl font-bold tracking-tight text-slate-900">Recent Service Requests</h2>
                <a class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline transition" href="{{ route('customer.requests') }}">
                    View all ({{ $serviceRequests->count() }})
                </a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Request ID</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Details</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400 text-right">Price</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($serviceRequests->take(5) as $request)
                            @php
                                $badgeClass = match($request->status) {
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'washing' => 'bg-purple-100 text-purple-700',
                                    'drying' => 'bg-indigo-100 text-indigo-700',
                                    'ready_for_pickup', 'ready' => 'bg-teal-100 text-teal-700',
                                    'received' => 'bg-slate-100 text-slate-700',
                                    'cancelled' => 'bg-rose-100 text-rose-700',
                                    default => 'bg-amber-100 text-amber-700'
                                };

                                $statusLabel = match($request->status) {
                                    'completed' => 'Completed',
                                    'washing' => 'Washing',
                                    'drying' => 'Drying',
                                    'ready_for_pickup', 'ready' => 'Ready',
                                    'received' => 'Received',
                                    'cancelled' => 'Cancelled',
                                    default => 'Pending'
                                };
                            @endphp
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="py-4 font-semibold text-slate-900 text-sm font-mono">
                                    #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-4">
                                    <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="py-4 text-sm text-slate-600">
                                    {{ $request->clothType->name ?? 'Laundry' }} · 
                                    @if($request->comforter_size)
                                        {{ $request->comforter_size }}
                                    @elseif($request->actual_weight ?? $request->estimated_weight)
                                        {{ number_format($request->actual_weight ?? $request->estimated_weight, 0) }} kg
                                    @else
                                        {{ $request->pricingTier->name ?? 'Standard' }}
                                    @endif
                                </td>
                                <td class="py-4 text-sm font-bold text-slate-900 text-right">
                                    PHP {{ number_format($request->final_price ?? $request->estimated_price, 0) }}
                                </td>
                                <td class="py-4 text-right">
                                    <a href="{{ route('customer.requests.show', $request) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                        Details →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-sm">
                                    No service requests found. Create your first request!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions Panel (Right Column) -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 mb-5">Quick Actions</h2>
                <div class="space-y-3.5">
                    <!-- New Service Request Button -->
                    <a class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-2xl py-3.5 px-4 font-semibold text-sm flex items-center justify-center gap-2.5 shadow-md shadow-blue-600/20 transition-all duration-200 active:scale-[0.99]" href="{{ route('customer.service-requests') }}">
                        <div class="flex h-5 w-5 items-center justify-center rounded-full bg-white/20">
                            <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span>New Service Request</span>
                    </a>

                    <!-- View All Requests Button -->
                    <a class="w-full border-2 border-blue-600 text-blue-600 hover:bg-blue-50/60 rounded-2xl py-3.5 px-4 font-semibold text-sm flex items-center justify-center gap-2.5 transition-all duration-200 active:scale-[0.99]" href="{{ route('customer.requests') }}">
                        <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>View All Requests</span>
                    </a>

                    <!-- Update Branch / Profile -->
                    <a class="w-full border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-2xl py-3 px-4 font-semibold text-sm flex items-center justify-center gap-2 transition" href="{{ route('customer.profile') }}">
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profile</span>
                    </a>
                </div>
            </div>

            <!-- Branch & Store Status Indicator -->
            <div class="mt-6 rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Branch Status</p>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-emerald-800">
                        {{ auth('customer')->user()->branch->name ?? 'Central Branch' }} — Open for Washing
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-customer-layout>
