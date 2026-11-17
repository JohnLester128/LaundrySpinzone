<x-customer-layout>
    <x-slot:title>Customer Dashboard — Spinzone Laundry</x-slot:title>

    <!-- Notification Banner -->
    <x-notification />

    @php
        $activeRequests = $serviceRequests->whereNotIn('status', ['completed', 'cancelled'])->values();
        $completedRequests = $serviceRequests->where('status', 'completed');
        $totalSpent = $completedRequests->sum(function($r) {
            return $r->final_price ?? $r->estimated_price ?? 0;
        });
    @endphp

    <!-- Stat Cards Grid (Square boxes in 3-col on mobile, standard on desktop) -->
    <div class="grid grid-cols-3 gap-2.5 sm:gap-4 md:gap-6">
        <!-- Card 1: Active Requests -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-2.5 sm:p-4 md:p-6 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between aspect-square md:aspect-auto">
            <div class="absolute top-0 inset-x-0 h-1 bg-blue-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-[9px] sm:text-[11px] md:text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <span class="sm:hidden">Active</span>
                        <span class="hidden sm:inline">Active Requests</span>
                    </p>
                    <h3 class="mt-1 sm:mt-2 text-xl sm:text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
                        {{ $activeRequests->count() }}
                    </h3>
                </div>
                <div class="flex h-7 w-7 sm:h-9 sm:w-9 md:h-11 md:w-11 items-center justify-center rounded-lg sm:rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/20 shrink-0">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <!-- Mobile status indicator -->
            <div class="flex md:hidden items-center text-[9px] sm:text-[10px] font-bold {{ $activeRequests->count() > 0 ? 'text-blue-600' : 'text-slate-400' }} truncate">
                <span class="inline-block h-1.5 w-1.5 rounded-full {{ $activeRequests->count() > 0 ? 'bg-blue-600 animate-pulse' : 'bg-slate-300' }} mr-1 shrink-0"></span>
                <span class="truncate">{{ $activeRequests->count() > 0 ? 'In Shop' : 'None' }}</span>
            </div>
            <!-- Desktop subtitle -->
            <div class="hidden md:flex mt-4 items-center text-xs font-medium {{ $activeRequests->count() > 0 ? 'text-blue-600' : 'text-slate-400' }}">
                <span class="inline-block h-2 w-2 rounded-full {{ $activeRequests->count() > 0 ? 'bg-blue-600 animate-pulse' : 'bg-slate-300' }} mr-1.5"></span>
                <span>{{ $activeRequests->count() > 0 ? 'In progress at laundry shop' : 'No active laundry in queue' }}</span>
            </div>
        </div>

        <!-- Card 2: Completed Orders -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-2.5 sm:p-4 md:p-6 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between aspect-square md:aspect-auto">
            <div class="absolute top-0 inset-x-0 h-1 bg-emerald-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-[9px] sm:text-[11px] md:text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <span class="sm:hidden">Done</span>
                        <span class="hidden sm:inline">Completed Orders</span>
                    </p>
                    <h3 class="mt-1 sm:mt-2 text-xl sm:text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
                        {{ $completedRequests->count() }}
                    </h3>
                </div>
                <div class="flex h-7 w-7 sm:h-9 sm:w-9 md:h-11 md:w-11 items-center justify-center rounded-lg sm:rounded-xl bg-teal-400 text-white shadow-sm shadow-teal-400/20 shrink-0">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <!-- Mobile status indicator -->
            <div class="flex md:hidden items-center text-[9px] sm:text-[10px] font-bold text-emerald-600 truncate">
                <svg class="mr-0.5 h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span class="truncate">Ready</span>
            </div>
            <!-- Desktop subtitle -->
            <div class="hidden md:flex mt-4 items-center text-xs font-medium text-emerald-600">
                <svg class="mr-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Ready & delivered wash history</span>
            </div>
        </div>

        <!-- Card 3: Total Requests -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-2.5 sm:p-4 md:p-6 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between aspect-square md:aspect-auto">
            <div class="absolute top-0 inset-x-0 h-1 bg-purple-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-[9px] sm:text-[11px] md:text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <span class="sm:hidden">Total</span>
                        <span class="hidden sm:inline">Total Requests</span>
                    </p>
                    <h3 class="mt-1 sm:mt-2 text-xl sm:text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
                        {{ $serviceRequests->count() }}
                    </h3>
                </div>
                <div class="flex h-7 w-7 sm:h-9 sm:w-9 md:h-11 md:w-11 items-center justify-center rounded-lg sm:rounded-xl bg-purple-500 text-white shadow-sm shadow-purple-500/20 shrink-0">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <!-- Mobile status indicator -->
            
        </div>
    </div>

    <!-- Main Content Section: Recent Activity Table (Left) + Quick Actions (Right) -->
    <!-- Main Content Section: Recent Activity (Left on desktop) + Quick Actions/Tracker (Right on desktop) -->
    <div class="mt-4 sm:mt-7 grid gap-6 lg:grid-cols-3">
        <!-- Recent Activity Section (spans 2 columns, table on desktop, cards on mobile) -->
        <div class="lg:col-span-2 lg:order-1 order-2 rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 sm:pb-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold tracking-tight text-slate-900">Recent Service Requests</h2>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Your latest orders and their real-time progress.</p>
                </div>
                <a class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline transition" href="{{ route('customer.requests') }}">
                    View all ({{ $serviceRequests->count() }})
                </a>
            </div>

            <!-- Mobile View: Clean Touch Cards (< md screens) -->
            <div class="block md:hidden mt-3.5 space-y-2.5">
                @forelse($serviceRequests->take(4) as $request)
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

                        $dWeight = $request->actual_weight ?? $request->estimated_weight;
                    @endphp
                    <div class="rounded-xl border border-slate-200/80 bg-slate-50/40 p-3 hover:bg-white transition shadow-2xs">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="font-mono text-xs font-extrabold text-slate-900">
                                #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold {{ $badgeClass }}">
                                    @if(in_array($request->status, ['washing', 'drying']))
                                        <span class="h-1.5 w-1.5 rounded-full bg-current animate-ping"></span>
                                    @endif
                                    <span>{{ $statusLabel }}</span>
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs text-slate-600 mb-2.5">
                            <span class="font-medium text-slate-700">
                                {{ $request->clothType->name ?? 'Laundry' }} · 
                                @if($request->comforter_size)
                                    {{ $request->comforter_size }}@if($dWeight && $dWeight > 0) ({{ ($dWeight + 0) }} kg)@endif
                                @elseif($dWeight)
                                    {{ ($dWeight + 0) }} kg
                                @else
                                    {{ $request->pricingTier->name ?? 'Standard' }}
                                @endif
                            </span>
                            <span class="font-mono font-black text-slate-900">
                                PHP {{ number_format($request->final_price ?? $request->estimated_price, 0) }}
                            </span>
                        </div>

                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                            <span class="text-[10px] text-slate-400 font-mono">
                                {{ $request->created_at ? $request->created_at->format('M d, Y') : 'Recent' }}
                            </span>
                            <a href="{{ route('customer.requests.show', $request) }}" class="inline-flex items-center gap-1 rounded-lg bg-white border border-slate-200 px-2.5 py-1 text-[11px] font-bold text-slate-700 hover:text-blue-600 hover:border-blue-300 transition shadow-2xs">
                                <span>Track Details</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-slate-400 text-xs">
                        <p class="font-semibold text-slate-600">No service requests yet</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Book your first laundry request today!</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Comprehensive Table (>= md screens) -->
            <div class="hidden md:block mt-4 overflow-x-auto">
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
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                        @if(in_array($request->status, ['washing', 'drying']))
                                            @php 
                                                $rcCycle = $request->cycle_info; 
                                                $isRcResting = ($rcCycle['phase'] === 'resting');
                                                $isRcFolding = ($rcCycle['phase'] === 'folding');
                                            @endphp
                                            @if($isRcFolding)
                                                <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold px-2 py-0.5 rounded-md border shadow-2xs text-teal-700 bg-teal-50 border-teal-200">
                                                    Folding
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold px-2 py-0.5 rounded-md border shadow-2xs {{ $isRcResting ? 'text-amber-700 bg-amber-50 border-amber-200' : ($request->status === 'washing' ? 'text-purple-700 bg-purple-50 border-purple-200' : 'text-indigo-700 bg-indigo-50 border-indigo-200') }}">
                                                    @if($isRcResting) Buffer: @endif<span data-cycle-timer data-remaining="{{ (int)$rcCycle['remaining_seconds'] }}" data-paused="{{ $rcCycle['is_paused'] ? '1' : '0' }}">{{ $rcCycle['display_time'] }}</span>
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 text-sm text-slate-600">
                                    {{ $request->clothType->name ?? 'Laundry' }} · 
                                    @php
                                        $dWeight = $request->actual_weight ?? $request->estimated_weight;
                                    @endphp
                                    @if($request->comforter_size)
                                        {{ $request->comforter_size }}@if($dWeight && $dWeight > 0) ({{ ($dWeight + 0) }} kg)@endif
                                    @elseif($dWeight)
                                        {{ ($dWeight + 0) }} kg
                                    @else
                                        {{ $request->pricingTier->name ?? 'Standard' }}
                                    @endif
                                </td>
                                <td class="py-4 text-sm font-bold text-slate-900 text-right">
                                    PHP {{ number_format($request->final_price ?? $request->estimated_price, 0) }}
                                </td>
                                <td class="py-4 text-right">
                                    <a href="{{ route('customer.requests.show', $request) }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200/90 bg-white px-3.5 py-1.5 text-xs font-bold text-slate-800 hover:border-blue-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-2xs group">
                                        <span>Details</span>
                                        <svg class="h-3.5 w-3.5 text-slate-600 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
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

        <!-- Right Column: Live Laundry Tracker Carousel (All Active Requests) -->
        @if($activeRequests->isNotEmpty())
            <div id="laundryTrackerCard" class="rounded-2xl border border-blue-200/80 bg-gradient-to-b from-blue-50/40 via-white to-white p-5 shadow-sm flex flex-col justify-between relative group">
                <div>
                    <!-- Header with Live Pulse, Title, and Carousel Navigation / Counter -->
                    <div class="flex items-center justify-between gap-2 pb-3 border-b border-blue-100/70">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 leading-tight">Laundry Tracker</h3>
                                <p class="text-[10px] text-slate-400 font-medium">
                                    {{ $activeRequests->count() === 1 ? '1 Active Order' : $activeRequests->count() . ' Active Orders' }}
                                </p>
                            </div>
                        </div>

                        @if($activeRequests->count() > 1)
                            <!-- Carousel Controls: Prev, Slide Counter, Next -->
                            <div class="flex items-center gap-1 bg-white/90 border border-slate-200/80 rounded-full px-2 py-0.5 shadow-2xs">
                                <button type="button" onclick="changeTrackerSlide(-1)" class="h-5 w-5 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-600 hover:text-blue-600 transition cursor-pointer" title="Previous Active Laundry">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <span id="trackerSlideCounter" class="font-mono text-[10px] font-bold text-slate-600 px-1 select-none">
                                    1 / {{ $activeRequests->count() }}
                                </span>
                                <button type="button" onclick="changeTrackerSlide(1)" class="h-5 w-5 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-600 hover:text-blue-600 transition cursor-pointer" title="Next Active Laundry">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full shadow-2xs">
                                Live
                            </span>
                        @endif
                    </div>

                    <!-- Carousel Slides Container -->
                    <div id="trackerSlidesWrapper" class="mt-3 relative">
                        @foreach($activeRequests as $idx => $activeReq)
                            @php
                                $actCycle = $activeReq->cycle_info;
                                $isActWashBuffer = ($activeReq->status === 'washing' && ($activeReq->cycle_phase === 'resting' || $actCycle['phase'] === 'resting'));
                                $isActFolding = ($activeReq->status === 'drying' && ($activeReq->cycle_phase === 'folding' || $actCycle['phase'] === 'folding'));

                                $activeStatusBadge = match($activeReq->status) {
                                    'washing' => $isActWashBuffer ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-purple-100 text-purple-700 border-purple-200',
                                    'drying' => $isActFolding ? 'bg-teal-100 text-teal-800 border-teal-300' : 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                    'ready_for_pickup', 'ready' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    'received' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    default => 'bg-amber-100 text-amber-700 border-amber-200'
                                };

                                $activeStatusLabel = match($activeReq->status) {
                                    'washing' => $isActWashBuffer ? 'Wash Buffer' : 'Washing',
                                    'drying' => $isActFolding ? 'Folding' : 'Drying',
                                    'ready_for_pickup', 'ready' => 'Ready for Pick-up',
                                    'received' => 'Received',
                                    default => 'Pending Queue'
                                };
                            @endphp

                            <div class="tracker-slide transition-all duration-300 {{ $idx === 0 ? 'block' : 'hidden' }}" data-slide-index="{{ $idx }}">
                                <!-- Slide Meta Header (Req Number & Status Badge) -->
                                <div class="flex items-center justify-between gap-2 mb-2.5">
                                    <span class="font-mono text-xs font-bold text-slate-700">
                                        #REQ-{{ str_pad($activeReq->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold border shadow-2xs {{ $activeStatusBadge }}">
                                        @if(in_array($activeReq->status, ['washing', 'drying', 'ready_for_pickup', 'ready']))
                                            <span class="h-1.5 w-1.5 rounded-full bg-current animate-ping"></span>
                                        @endif
                                        <span>{{ $activeStatusLabel }}</span>
                                    </span>
                                </div>

                                <!-- Status Highlight Card -->
                                @if(in_array($activeReq->status, ['washing', 'drying']) && !$isActFolding)
                                    <!-- Live Timer Countdown -->
                                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3 flex items-center justify-between">
                                        <div>
                                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400">Time Remaining</span>
                                            <div class="text-xl font-black font-mono text-slate-900 tracking-tight mt-0.5">
                                                <span data-cycle-timer data-remaining="{{ (int)$actCycle['remaining_seconds'] }}" data-paused="{{ $actCycle['is_paused'] ? '1' : '0' }}">{{ $actCycle['display_time'] }}</span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center gap-1 font-mono text-[11px] font-bold px-2 py-0.5 rounded-lg border {{ $isActWashBuffer ? 'bg-amber-100 text-amber-800 border-amber-200' : ($activeReq->status === 'washing' ? 'bg-purple-100 text-purple-700 border-purple-200' : 'bg-indigo-100 text-indigo-700 border-indigo-200') }}">
                                                {{ $isActWashBuffer ? 'Buffer' : ($activeReq->status === 'washing' ? ($activeReq->washingMachine->name ?? 'Washer') : ($activeReq->dryerMachine->name ?? 'Dryer')) }}
                                            </span>
                                        </div>
                                    </div>
                                @elseif($isActFolding)
                                    <!-- Folding State -->
                                    <div class="rounded-xl border border-teal-200/80 bg-teal-50/70 p-3 flex items-center gap-2.5">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-teal-600 text-white shrink-0 shadow-2xs font-bold text-xs">
                                            👕
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-teal-950">Folding in Progress</p>
                                            <p class="text-[10px] text-teal-700">Staff is folding and bagging your clean laundry.</p>
                                        </div>
                                    </div>
                                @elseif(in_array($activeReq->status, ['ready_for_pickup', 'ready']))
                                    <!-- Ready for Pick-up State -->
                                    <div class="rounded-xl border border-emerald-200 bg-emerald-50/90 p-3 flex items-center gap-2.5 shadow-2xs">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-white shrink-0 shadow-2xs">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-emerald-950">Ready for Pick-up!</p>
                                            <p class="text-[10px] text-emerald-800">Your laundry is finished and waiting at the counter.</p>
                                        </div>
                                    </div>
                                @elseif($activeReq->status === 'received')
                                    <!-- Received State -->
                                    <div class="rounded-xl border border-blue-200/80 bg-blue-50/70 p-3 flex items-center gap-2.5">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-600 text-white shrink-0 shadow-2xs">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-blue-950">Received at Shop</p>
                                            <p class="text-[10px] text-blue-700">Weighed & verified. In queue for washing machine.</p>
                                        </div>
                                    </div>
                                @else
                                    <!-- Pending Queue State -->
                                    <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-3 flex items-center gap-2.5">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-500 text-white shrink-0 shadow-2xs">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-amber-950">Pending Confirmation</p>
                                            <p class="text-[10px] text-amber-700">Order booked online. Awaiting counter staff acceptance.</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Compact 2x2 Details Grid -->
                                <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                                    <div class="rounded-xl border border-slate-100 bg-white p-2.5 shadow-2xs">
                                        <span class="text-[10px] font-mono font-semibold uppercase text-slate-400">Type</span>
                                        <p class="font-bold text-slate-900 truncate mt-0.5">{{ $activeReq->clothType->name ?? 'Standard' }}</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-100 bg-white p-2.5 shadow-2xs">
                                        <span class="text-[10px] font-mono font-semibold uppercase text-slate-400">Load</span>
                                        <p class="font-bold text-slate-900 truncate mt-0.5">
                                            @php
                                                $dWeight = $activeReq->actual_weight ?? $activeReq->estimated_weight;
                                            @endphp
                                            @if($activeReq->comforter_size)
                                                {{ $activeReq->comforter_size }}@if($dWeight && $dWeight > 0) ({{ ($dWeight + 0) }} kg)@endif
                                            @elseif($dWeight)
                                                {{ ($dWeight + 0) }} kg
                                            @else
                                                {{ $activeReq->pricingTier->name ?? 'Standard' }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="rounded-xl border border-slate-100 bg-white p-2.5 shadow-2xs">
                                        <span class="text-[10px] font-mono font-semibold uppercase text-slate-400">Qty</span>
                                        <p class="font-bold text-slate-900 mt-0.5">{{ $activeReq->actual_quantity ?? $activeReq->quantity }} pcs</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-100 bg-white p-2.5 shadow-2xs">
                                        <span class="text-[10px] font-mono font-semibold uppercase text-slate-400">Total</span>
                                        <p class="font-black text-blue-600 font-mono mt-0.5">PHP {{ number_format($activeReq->final_price ?? $activeReq->estimated_price, 0) }}</p>
                                    </div>
                                </div>

                                <!-- View Details Link for this slide -->
                                <div class="mt-3">
                                    <a href="{{ route('customer.requests.show', $activeReq) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-2 px-3 font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99]">
                                        <span>View Tracking Stepper & Details</span>
                                        <span>→</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Carousel Dot Indicators (if multiple) & Bottom Action -->
                <div class="mt-3 pt-3 border-t border-slate-100">
                    @if($activeRequests->count() > 1)
                        <div class="flex items-center justify-center gap-1.5 mb-2.5">
                            @foreach($activeRequests as $dotIdx => $dotReq)
                                <button type="button" onclick="goToTrackerSlide({{ $dotIdx }})" class="tracker-dot h-1.5 rounded-full transition-all duration-300 {{ $dotIdx === 0 ? 'w-5 bg-blue-600' : 'w-1.5 bg-slate-300 hover:bg-slate-400' }}" title="Request #REQ-{{ str_pad($dotReq->id, 4, '0', STR_PAD_LEFT) }}"></button>
                            @endforeach
                        </div>
                    @endif
                    <a href="{{ route('customer.service-requests') }}" class="w-full border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl py-1.5 px-3 font-semibold text-xs flex items-center justify-center gap-1.5 transition">
                        <span>+ New Service Request</span>
                    </a>
                </div>
            </div>
        @else
            <!-- When No Active Laundry: Clean New Request Action Card -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">Ready for Fresh Laundry?</h3>
                    <p class="text-xs text-slate-500 mb-4">Book a new drop-off or pickup request in seconds.</p>
                    <a class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-3 px-4 font-bold text-xs flex items-center justify-center gap-2 shadow-xs transition active:scale-[0.99]" href="{{ route('customer.service-requests') }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>New Service Request</span>
                    </a>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a class="w-full border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl py-2 px-3 font-semibold text-xs flex items-center justify-center gap-1.5 transition" href="{{ route('customer.requests') }}">
                        <span>View All Requests ({{ $serviceRequests->count() }})</span>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Live Customer Dashboard Cycle Countdown Ticker & Carousel Controller -->
    <script>
        // 1. Cycle Countdown Ticker
        (function() {
            function formatTime(sec) {
                const m = Math.floor(sec / 60);
                const s = sec % 60;
                return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
            }

            const timerNodes = document.querySelectorAll('[data-cycle-timer]');
            if (timerNodes.length > 0) {
                const timers = [];
                timerNodes.forEach(function(node) {
                    const paused = node.getAttribute('data-paused') === '1';
                    const rem = parseInt(node.getAttribute('data-remaining') || '0', 10);
                    timers.push({ node: node, remaining: rem, paused: paused });
                });

                let reloadTriggered = false;
                setInterval(function() {
                    let shouldReload = false;
                    timers.forEach(function(item) {
                        if (!item.paused && item.remaining > 0) {
                            item.remaining--;
                            item.node.textContent = formatTime(item.remaining);
                            if (item.remaining <= 0) {
                                shouldReload = true;
                            }
                        }
                    });

                    if (shouldReload && !reloadTriggered) {
                        reloadTriggered = true;
                        sessionStorage.setItem('spinzone_tracker_scroll', window.scrollY);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                }, 1000);
            }

            // Real-time Laundry Tracker Sync for Active Orders
            const initialActiveRequests = {
                @foreach($activeRequests as $req)
                    '{{ $req->id }}': {
                        status: '{{ $req->status }}',
                        cycle_phase: '{{ $req->cycle_phase }}',
                        weight_verification_status: '{{ $req->weight_verification_status }}'
                    },
                @endforeach
            };
            const initialActiveCount = {{ $activeRequests->count() }};
            let dashboardReloading = false;

            function triggerDashboardReload() {
                if (dashboardReloading) return;
                dashboardReloading = true;
                sessionStorage.setItem('spinzone_tracker_scroll', window.scrollY);
                window.location.reload();
            }

            window.addEventListener('spinzone:tracker-sync', function(e) {
                const list = (e.detail && e.detail.activeRequests) || [];
                if (list.length !== initialActiveCount) {
                    triggerDashboardReload();
                    return;
                }
                for (let i = 0; i < list.length; i++) {
                    const item = list[i];
                    const prev = initialActiveRequests[String(item.id)];
                    if (!prev) {
                        triggerDashboardReload();
                        return;
                    }
                    if (prev.status !== item.status || 
                        prev.cycle_phase !== item.cycle_phase || 
                        (prev.weight_verification_status || '') !== (item.weight_verification_status || '')) {
                        triggerDashboardReload();
                        return;
                    }
                }
            });

            // Fast tracker check if active orders are ongoing
            if (initialActiveCount > 0) {
                setInterval(function() {
                    if (typeof fetchCustomerRealtimeNotifs === 'function' && !document.hidden) {
                        fetchCustomerRealtimeNotifs();
                    }
                }, 6000);
            }
        })();

        // 2. Active Laundry Tracker Carousel Rotation
        (function() {
            const slides = document.querySelectorAll('.tracker-slide');
            const dots = document.querySelectorAll('.tracker-dot');
            const counter = document.getElementById('trackerSlideCounter');
            const card = document.getElementById('laundryTrackerCard');
            const totalSlides = slides.length;

            if (totalSlides <= 1) return;

            let currentSlide = 0;
            let timer = null;

            function updateSlide(index) {
                currentSlide = (index + totalSlides) % totalSlides;

                slides.forEach((slide, i) => {
                    if (i === currentSlide) {
                        slide.classList.remove('hidden');
                        slide.classList.add('block');
                    } else {
                        slide.classList.remove('block');
                        slide.classList.add('hidden');
                    }
                });

                dots.forEach((dot, i) => {
                    if (i === currentSlide) {
                        dot.classList.remove('w-1.5', 'bg-slate-300');
                        dot.classList.add('w-5', 'bg-blue-600');
                    } else {
                        dot.classList.remove('w-5', 'bg-blue-600');
                        dot.classList.add('w-1.5', 'bg-slate-300');
                    }
                });

                if (counter) {
                    counter.textContent = (currentSlide + 1) + ' / ' + totalSlides;
                }
            }

            function startTimer() {
                stopTimer();
                timer = setInterval(function() {
                    updateSlide(currentSlide + 1);
                }, 5000); // Rotates every 5 seconds
            }

            function stopTimer() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            window.changeTrackerSlide = function(direction) {
                updateSlide(currentSlide + direction);
                startTimer();
            };

            window.goToTrackerSlide = function(index) {
                updateSlide(index);
                startTimer();
            };

            if (card) {
                card.addEventListener('mouseenter', stopTimer);
                card.addEventListener('mouseleave', startTimer);
            }

            startTimer();
        })();
    </script>
</x-customer-layout>
