<x-admin-layout>
    <!-- Stat Cards Grid (Square boxes in 3-col on mobile, standard on desktop) -->
    <div class="grid grid-cols-3 gap-2.5 sm:gap-4 md:gap-6">
        <!-- Card 1: Total Sales -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-2 sm:p-4 md:p-6 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between aspect-square md:aspect-auto">
            <div class="absolute top-0 inset-x-0 h-1 bg-blue-600"></div>
            <!-- Header: Label + Icon -->
            <div class="flex items-center justify-between gap-1">
                <p class="font-mono text-[9px] sm:text-[11px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">
                    <span class="sm:hidden">Sales</span>
                    <span class="hidden sm:inline">Total Sales</span>
                </p>
                <div class="flex h-5 w-5 sm:h-8 sm:w-8 md:h-10 md:w-10 items-center justify-center rounded-md sm:rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/20 shrink-0">
                    <svg class="h-2.5 w-2.5 sm:h-4 sm:w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <!-- Value (Full card width, no icon squeezing) -->
            <div class="my-auto sm:my-0 sm:mt-2 min-w-0">
                <h3 class="text-xs min-[360px]:text-sm sm:text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 truncate" title="₱{{ number_format($salesTotal, 2) }}">
                    <span class="text-[10px] min-[360px]:text-xs sm:text-lg md:text-xl font-bold text-slate-500 mr-0.5">₱</span>{{ number_format($salesTotal, 0) }}
                </h3>
            </div>

            <!-- Mobile status indicator -->
            <div class="flex md:hidden items-center text-[8px] min-[360px]:text-[9px] sm:text-[10px] font-bold text-blue-600 truncate">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-500 mr-1 shrink-0"></span>
                <span class="truncate">Revenue</span>
            </div>
            <!-- Desktop subtitle -->
            <div class="hidden md:flex mt-4 items-center text-xs font-medium text-slate-500">
                <span class="inline-block h-2 w-2 rounded-full bg-blue-500 mr-1.5"></span>
                <span>Branch revenue from completed orders</span>
            </div>
        </div>

        <!-- Card 2: Active vs Total Requests -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-2 sm:p-4 md:p-6 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between aspect-square md:aspect-auto">
            <div class="absolute top-0 inset-x-0 h-1 bg-emerald-600"></div>
            <!-- Header: Label + Icon -->
            <div class="flex items-center justify-between gap-1">
                <p class="font-mono text-[9px] sm:text-[11px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">
                    <span class="sm:hidden">Active</span>
                    <span class="hidden sm:inline">Active / Total Requests</span>
                </p>
                <div class="flex h-5 w-5 sm:h-8 sm:w-8 md:h-10 md:w-10 items-center justify-center rounded-md sm:rounded-xl bg-teal-500 text-white shadow-sm shadow-teal-400/20 shrink-0">
                    <svg class="h-2.5 w-2.5 sm:h-4 sm:w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>

            <!-- Value -->
            <div class="my-auto sm:my-0 sm:mt-2 min-w-0">
                <h3 class="text-xs min-[360px]:text-sm sm:text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 truncate">
                    {{ $activeCount }} <span class="text-[9px] min-[360px]:text-[10px] sm:text-base font-medium text-slate-400">/{{ $requestCount }}</span>
                </h3>
            </div>

            <!-- Mobile progress bar -->
            <div class="flex md:hidden flex-col gap-1 w-full">
                <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-emerald-600 transition-all duration-500" style="width: {{ $requestCount > 0 ? min(100, round(($activeCount / $requestCount) * 100)) : 0 }}%;"></div>
                </div>
                <span class="text-[8px] sm:text-[9px] font-bold text-teal-600 truncate text-right">
                    {{ $requestCount > 0 ? round(($activeCount / $requestCount) * 100) : 0 }}% active
                </span>
            </div>
            <!-- Desktop progress bar -->
            <div class="hidden md:block mt-4 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-emerald-600 transition-all duration-500" style="width: {{ $requestCount > 0 ? min(100, round(($activeCount / $requestCount) * 100)) : 0 }}%;"></div>
            </div>
        </div>

        <!-- Card 3: Received / In-Store Laundry -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-2 sm:p-4 md:p-6 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between aspect-square md:aspect-auto">
            <div class="absolute top-0 inset-x-0 h-1 bg-purple-600"></div>
            <!-- Header: Label + Icon -->
            <div class="flex items-center justify-between gap-1">
                <p class="font-mono text-[9px] sm:text-[11px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">
                    <span class="sm:hidden">Received</span>
                    <span class="hidden sm:inline">Received (In-Store)</span>
                </p>
                <div class="flex h-5 w-5 sm:h-8 sm:w-8 md:h-10 md:w-10 items-center justify-center rounded-md sm:rounded-xl bg-purple-500 text-white shadow-sm shadow-purple-500/20 shrink-0">
                    <svg class="h-2.5 w-2.5 sm:h-4 sm:w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Value -->
            <div class="my-auto sm:my-0 sm:mt-2 min-w-0">
                <h3 class="text-xs min-[360px]:text-sm sm:text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 truncate">
                    {{ $pendingCount }}
                </h3>
            </div>

            <!-- Mobile status indicator -->
            <div class="flex md:hidden items-center text-[8px] min-[360px]:text-[9px] sm:text-[10px] font-bold {{ $pendingCount > 0 ? 'text-purple-700' : 'text-slate-400' }} truncate">
                <span class="inline-block h-1.5 w-1.5 rounded-full {{ $pendingCount > 0 ? 'bg-purple-600 animate-pulse' : 'bg-slate-300' }} mr-1 shrink-0"></span>
                <span class="truncate">{{ $pendingCount > 0 ? 'In Queue' : 'None' }}</span>
            </div>
            <!-- Desktop subtitle -->
            <div class="hidden md:flex mt-4 items-center text-xs font-medium {{ $pendingCount > 0 ? 'text-purple-700' : 'text-slate-400' }}">
                <span class="inline-block h-2 w-2 rounded-full {{ $pendingCount > 0 ? 'bg-purple-600 animate-pulse' : 'bg-slate-300' }} mr-1.5"></span>
                <span>{{ $pendingCount > 0 ? $pendingCount . ' orders awaiting wash / action' : 'No received orders waiting' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Section: Recent Activity Table (Left) + Quick Actions (Right) -->
    <div class="mt-5 sm:mt-7 grid gap-4 sm:gap-6 lg:grid-cols-3">
        <!-- Live Wash & Dry Tracker (Mobile View Only: Shown in place of recent activity) -->
        <div id="adminTrackerCard" class="order-2 block lg:hidden rounded-2xl border border-blue-200/80 bg-gradient-to-b from-blue-50/40 via-white to-white p-4 sm:p-5 shadow-sm relative group">
            @if(isset($washingAndDryingRequests) && $washingAndDryingRequests->isNotEmpty())
                <div>
                    <!-- Header with Live Pulse, Title, and Carousel Navigation / Counter -->
                    <div class="flex items-center justify-between gap-2 pb-3 border-b border-blue-100/70">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 leading-tight">Active Cycles</h3>
                                <p class="text-[10px] text-slate-400 font-medium">
                                    {{ $washingAndDryingRequests->count() === 1 ? '1 active cycle' : $washingAndDryingRequests->count() . ' active cycles' }}
                                </p>
                            </div>
                        </div>

                        @if($washingAndDryingRequests->count() > 1)
                            <!-- Carousel Controls: Prev, Slide Counter, Next -->
                            <div class="flex items-center gap-1 bg-white/90 border border-slate-200/80 rounded-full px-2 py-0.5 shadow-2xs">
                                <button type="button" onclick="changeAdminTrackerSlide(-1)" class="h-5 w-5 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-600 hover:text-blue-600 transition cursor-pointer" title="Previous Active Cycle">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <span id="adminTrackerSlideCounter" class="font-mono text-[10px] font-bold text-slate-600 px-1 select-none">
                                    1 / {{ $washingAndDryingRequests->count() }}
                                </span>
                                <button type="button" onclick="changeAdminTrackerSlide(1)" class="h-5 w-5 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-600 hover:text-blue-600 transition cursor-pointer" title="Next Active Cycle">
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
                    <div id="adminTrackerSlidesWrapper" class="mt-3 relative">
                        @foreach($washingAndDryingRequests as $idx => $activeReq)
                            @php
                                $actCycle = $activeReq->cycle_info;
                                $isWash = ($activeReq->status === 'washing');
                                $isDry = ($activeReq->status === 'drying');
                                $isActWashBuffer = ($isWash && ($activeReq->cycle_phase === 'resting' || $actCycle['phase'] === 'resting'));
                                $isActFolding = ($isDry && ($activeReq->cycle_phase === 'folding' || $actCycle['phase'] === 'folding'));

                                $activeStatusBadge = match(true) {
                                    $isActWashBuffer => 'bg-amber-100 text-amber-800 border-amber-300',
                                    $isActFolding => 'bg-teal-100 text-teal-800 border-teal-300',
                                    $isWash => 'bg-purple-100 text-purple-700 border-purple-200',
                                    $isDry => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200'
                                };

                                $activeStatusLabel = match(true) {
                                    $isActWashBuffer => 'Wash Buffer',
                                    $isActFolding => 'Folding',
                                    $isWash => 'Washing',
                                    $isDry => 'Drying',
                                    default => ucfirst($activeReq->status)
                                };

                                $machineName = $isWash 
                                    ? ($activeReq->washingMachine->name ?? 'Washer') 
                                    : ($activeReq->dryerMachine->name ?? 'Dryer');
                            @endphp

                            <div class="admin-tracker-slide transition-all duration-300 {{ $idx === 0 ? 'block' : 'hidden' }}" data-slide-index="{{ $idx }}">
                                <!-- Slide Meta Header (Req Number, Customer, & Status Badge) -->
                                <div class="flex items-center justify-between gap-2 mb-2.5">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="font-mono text-xs font-bold text-blue-900 shrink-0">
                                            #REQ-{{ str_pad($activeReq->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="text-slate-300 font-mono text-xs shrink-0">·</span>
                                        <span class="text-xs font-semibold text-slate-700 truncate" title="{{ $activeReq->customer->username ?? 'Walk-in Customer' }}">
                                            {{ $activeReq->customer->username ?? 'Walk-in Customer' }}
                                        </span>
                                    </div>
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold border shadow-2xs shrink-0 {{ $activeStatusBadge }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current animate-ping"></span>
                                        <span>{{ $activeStatusLabel }}</span>
                                    </span>
                                </div>

                                <!-- Status Highlight Card -->
                                @if(!$isActFolding)
                                    <!-- Live Timer Countdown & Machine Badge -->
                                    <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-3 flex items-center justify-between">
                                        <div>
                                            <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400">
                                                {{ $isActWashBuffer ? 'Buffer Time Remaining' : 'Cycle Time Remaining' }}
                                            </span>
                                            <div class="text-2xl font-black font-mono text-slate-900 tracking-tight mt-0.5 flex items-baseline gap-1.5">
                                                <span data-cycle-timer data-remaining="{{ (int)$actCycle['remaining_seconds'] }}" data-paused="{{ $actCycle['is_paused'] ? '1' : '0' }}">{{ $actCycle['display_time'] }}</span>
                                                @if($actCycle['is_paused'])
                                                     <span class="text-[10px] font-sans font-bold text-amber-600 uppercase tracking-normal">(Paused)</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center gap-1 font-mono text-[11px] font-bold px-2.5 py-1 rounded-lg border shadow-2xs {{ $isActWashBuffer ? 'bg-amber-100 text-amber-800 border-amber-200' : ($isWash ? 'bg-purple-100 text-purple-700 border-purple-200' : 'bg-indigo-100 text-indigo-700 border-indigo-200') }}">
                                                @if($isWash)
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                                @else
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                                @endif
                                                <span>{{ $machineName }}</span>
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <!-- Folding State -->
                                    <div class="rounded-xl border border-teal-200/80 bg-teal-50/80 p-3 flex items-center gap-2.5">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-600 text-white shrink-0 shadow-2xs font-bold text-sm">
                                            👕
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-teal-950">Folding in Progress</p>
                                            <p class="text-[10px] text-teal-700">Finished drying. Being folded and packed for pickup.</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Compact 2x2 Details Grid -->
                                <div class="mt-2.5 grid grid-cols-2 gap-2 text-xs">
                                    <div class="rounded-xl border border-slate-100 bg-white p-2.5 shadow-2xs">
                                        <span class="text-[10px] font-mono font-semibold uppercase text-slate-400">Customer</span>
                                        <p class="font-bold text-slate-900 truncate mt-0.5">{{ $activeReq->customer->username ?? 'Walk-in Customer' }}</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-100 bg-white p-2.5 shadow-2xs">
                                        <span class="text-[10px] font-mono font-semibold uppercase text-slate-400">Machine</span>
                                        <p class="font-bold text-slate-900 truncate mt-0.5">{{ $machineName }}</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-100 bg-white p-2.5 shadow-2xs">
                                        <span class="text-[10px] font-mono font-semibold uppercase text-slate-400">Category</span>
                                        <p class="font-bold text-slate-900 truncate mt-0.5">{{ $activeReq->clothType->name ?? 'Standard Laundry' }}</p>
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
                                                {{ $activeReq->quantity ? $activeReq->quantity . ' pcs' : 'Standard' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="mt-3">
                                    <a href="{{ route('admin.service-requests.show', $activeReq->id) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-2 px-3 font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99]">
                                        <span>Manage Request & Machine</span>
                                        <span>→</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Carousel Dot Indicators (if multiple) -->
                @if($washingAndDryingRequests->count() > 1)
                    <div class="flex items-center justify-center gap-1.5 mt-3 pt-2.5 border-t border-slate-100">
                        @foreach($washingAndDryingRequests as $dotIdx => $dotReq)
                            <button type="button" onclick="goToAdminTrackerSlide({{ $dotIdx }})" class="admin-tracker-dot h-1.5 rounded-full transition-all duration-300 {{ $dotIdx === 0 ? 'w-5 bg-blue-600' : 'w-1.5 bg-slate-300 hover:bg-slate-400' }}" title="Request #REQ-{{ str_pad($dotReq->id, 4, '0', STR_PAD_LEFT) }}"></button>
                        @endforeach
                    </div>
                @endif
            @else
                <!-- Clean Empty State (When no machines are active) -->
                <div>
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="flex h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 leading-tight">Active Cycles</h3>
                            <p class="text-[10px] text-slate-400 font-medium">No cycles running</p>
                        </div>
                    </div>
                    <div class="py-6 text-center">
                        <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 mb-2.5 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800">No Active Cycles</p>
                        <p class="text-[11px] text-slate-400 mt-0.5 max-w-[220px] mx-auto">All washers and dryers are currently idle.</p>
                        <div class="mt-3.5 flex items-center justify-center gap-2">
                            <a href="{{ route('admin.service-requests') }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
                                <span>All Requests</span>
                            </a>
                            @if(!auth('admin')->user()?->isSuperAdmin())
                            <a href="{{ route('admin.service-requests.create') }}" class="inline-flex items-center gap-1 rounded-xl bg-blue-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-blue-700 transition shadow-2xs">
                                <span>+ New Request</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Recent Activity Section (spans 2 columns on desktop, cards on mobile) -->
        <div class="order-3 lg:order-1 lg:col-span-2 rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 sm:pb-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold tracking-tight text-slate-900">Recent Activity</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Latest service requests and updates at your branch.</p>
                </div>
                <a class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline transition" href="{{ route('admin.service-requests') }}">
                    View all →
                </a>
            </div>

            <!-- Mobile View: Clean Touch Cards (< lg screens) -->
            <div class="block lg:hidden mt-3.5 space-y-2.5">
                @forelse($requests as $request)
                    @php
                        $mStatusBadgeClass = match($request->status) {
                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'received' => 'bg-slate-100 text-slate-700 border-slate-200',
                            'washing' => 'bg-purple-100 text-purple-700 border-purple-200',
                            'drying' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                            'ready_for_pickup', 'ready' => 'bg-teal-100 text-teal-700 border-teal-200',
                            'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                        };

                        $mStatusLabel = match($request->status) {
                            'pending' => 'Pending',
                            'received' => 'Received',
                            'washing' => 'Washing',
                            'drying' => 'Drying',
                            'ready_for_pickup', 'ready' => 'Ready for Pickup',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                            default => ucfirst($request->status)
                        };
                    @endphp
                    <div class="rounded-xl border border-slate-200/80 bg-slate-50/40 p-3 hover:bg-white transition shadow-2xs">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <span class="font-mono text-xs font-extrabold text-slate-900">
                                #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold border shadow-2xs {{ $mStatusBadgeClass }}">
                                @if(in_array($request->status, ['washing', 'drying']))
                                    <span class="h-1.5 w-1.5 rounded-full bg-current animate-ping"></span>
                                @endif
                                <span>{{ $mStatusLabel }}</span>
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-xs text-slate-600 mb-2">
                            <div class="min-w-0 pr-2">
                                <p class="font-bold text-slate-800 truncate">{{ $request->customer->username ?? 'Walk-in' }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ $request->clothType->name ?? 'Laundry' }}</p>
                            </div>
                            <span class="font-mono font-bold text-slate-800 text-xs shrink-0">
                                PHP {{ number_format($request->final_price ?? $request->estimated_price, 0) }}
                            </span>
                        </div>

                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                            <span class="text-[10px] text-slate-400 font-mono">
                                {{ $request->created_at ? $request->created_at->format('m/d/y · g:iA') : 'Today' }}
                            </span>
                            <a href="{{ route('admin.service-requests.show', $request->id) }}" class="inline-flex items-center gap-1 rounded-lg bg-white border border-slate-200 px-2.5 py-1 text-[11px] font-bold text-slate-700 hover:text-blue-600 hover:border-blue-300 transition shadow-2xs">
                                <span>Manage</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-slate-400 text-xs">
                        <p class="font-semibold text-slate-600">No recent orders found</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">New orders at this branch will appear here.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Table (>= lg screens) -->
            <div class="hidden lg:block mt-4 overflow-x-auto -mx-4 sm:mx-0">
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Order ID</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Customer</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Category</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($requests as $request)
                            @php
                                $statusBadgeClass = match($request->status) {
                                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'received' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'washing' => 'bg-purple-100 text-purple-700 border-purple-200',
                                    'drying' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                    'ready', 'ready_for_pickup' => 'bg-teal-100 text-teal-700 border-teal-200',
                                    'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200'
                                };
                                $statusLabel = match($request->status) {
                                    'ready', 'ready_for_pickup' => 'Ready',
                                    'completed' => 'Completed',
                                    'washing' => 'Washing',
                                    'drying' => 'Drying',
                                    'received' => 'Received',
                                    'pending' => 'Pending',
                                    'cancelled' => 'Cancelled',
                                    default => ucfirst(str_replace('_', ' ', $request->status))
                                };
                            @endphp
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="py-4 font-mono font-bold text-blue-900 text-xs">
                                    #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-4 text-sm font-semibold text-slate-900 whitespace-nowrap">
                                    <span class="truncate max-w-[150px] sm:max-w-[180px] inline-block align-middle" title="{{ $request->customer->username ?? 'Walk-in Customer' }}">
                                        {{ $request->customer->username ?? 'Walk-in Customer' }}
                                    </span>
                                </td>
                                <td class="py-4">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="inline-block rounded-full px-2.5 py-0.5 text-[11px] font-bold border uppercase tracking-wider {{ $statusBadgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                        @if(in_array($request->status, ['washing', 'drying']))
                                            @php 
                                                 $dashCycle = $request->cycle_info; 
                                                 $isDashResting = ($dashCycle['phase'] === 'resting');
                                                 $isDashFolding = ($dashCycle['phase'] === 'folding');
                                            @endphp
                                            @if($isDashFolding)
                                                <span class="inline-flex items-center gap-1 font-mono text-[10px] font-extrabold px-2 py-0.5 rounded-lg border text-teal-700 bg-teal-50 border-teal-200 shadow-2xs">
                                                    Folding
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 font-mono text-[10px] font-extrabold px-2 py-0.5 rounded-lg border {{ $isDashResting ? 'text-amber-700 bg-amber-50 border-amber-200' : ($request->status === 'washing' ? 'text-purple-700 bg-purple-50 border-purple-200' : 'text-indigo-700 bg-indigo-50 border-indigo-200') }}">
                                                    @if($isDashResting) Buffer: @endif<span data-cycle-timer data-remaining="{{ (int)$dashCycle['remaining_seconds'] }}" data-paused="{{ $dashCycle['is_paused'] ? '1' : '0' }}">{{ $dashCycle['display_time'] }}</span>
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 text-xs font-medium text-slate-500 whitespace-nowrap">
                                    <span class="truncate max-w-[140px] sm:max-w-[170px] inline-block align-middle" title="{{ $request->clothType->name ?? 'Laundry' }}">
                                        {{ $request->clothType->name ?? 'Laundry' }}
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <a href="{{ route('admin.service-requests.show', $request->id) }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200/90 bg-white px-3.5 py-1.5 text-xs font-bold text-slate-800 hover:border-blue-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-2xs group">
                                        <span>Details</span>
                                        <svg class="h-3.5 w-3.5 text-slate-600 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-xs text-slate-400">
                                    <p class="font-semibold text-slate-600">No recent orders found</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">New service requests at this branch will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Machine Working Status Panel (Right Column on desktop, Top above active cycles on mobile) -->
        <div class="order-1 lg:order-2 lg:col-span-1 rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-900 flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                            <span>Machine Working Status</span>
                        </h2>
                        <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">
                            {{ auth('admin')->user()?->isSuperAdmin() ? 'Live availability across all branches' : 'Live availability for this branch' }}
                        </p>
                    </div>
                    <a href="{{ route('admin.machines') }}" class="text-[11px] sm:text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center gap-0.5 transition">
                        <span>Manage</span>
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                @if(!auth('admin')->user()?->isSuperAdmin())
                <!-- New Service Request Button (Branch Manager Only) -->
                <div class="mb-4">
                    <a class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-2.5 px-4 font-semibold text-xs flex items-center justify-center gap-2 shadow-sm transition-all duration-200 active:scale-[0.99]" href="{{ route('admin.service-requests.create') }}">
                        <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>New Service Request</span>
                    </a>
                </div>
                @endif

                <!-- Live Machine Status Across Branches -->
                @if(isset($branchMachineStats) && $branchMachineStats->isNotEmpty())
                    <div class="space-y-3 max-h-[440px] overflow-y-auto pr-0.5">
                        @foreach($branchMachineStats as $bStat)
                        <div class="rounded-2xl border border-slate-200/90 bg-slate-50/60 p-3.5 transition-all duration-200 hover:border-blue-200 hover:bg-slate-50 hover:shadow-2xs">
                            <!-- Branch Header -->
                            <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/70">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <div class="h-5 w-5 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-bold text-xs text-slate-900 truncate">{{ $bStat['name'] }} Branch</span>
                                </div>
                                
                                <div class="flex items-center gap-1 shrink-0">
                                    @if($bStat['total_in_use'] > 0)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-700 bg-blue-100/70 border border-blue-200 px-2 py-0.5 rounded-full">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                            {{ $bStat['total_in_use'] }} Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-100/70 border border-emerald-200 px-2 py-0.5 rounded-full">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                            All Ready
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- 2-col Washer & Dryer Cards -->
                            <div class="grid grid-cols-2 gap-2">
                                <!-- Washing Machine Card -->
                                <div class="rounded-xl border border-slate-200/80 bg-white p-2.5 flex flex-col justify-between shadow-2xs hover:border-blue-300 transition-colors">
                                    <div>
                                        <div class="flex items-center justify-between gap-1 mb-1">
                                            <div class="flex items-center gap-1 min-w-0">
                                                <span class="h-4 w-4 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                                    <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4V4zm4 4h.01M12 4v4m0 4a4 4 0 100 8 4 4 0 000-8z" />
                                                    </svg>
                                                </span>
                                                <span class="text-[11px] font-bold text-slate-800 truncate">Washing</span>
                                            </div>
                                            @if($bStat['washers']['in_use'] > 0)
                                                <span class="text-[9px] font-extrabold text-blue-700 bg-blue-50 border border-blue-100 px-1.5 py-0.5 rounded shrink-0">
                                                    {{ $bStat['washers']['in_use'] }} run
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Ratio e.g. 1/3 or 3/3 -->
                                        <div class="flex items-baseline gap-1 mt-1">
                                            <span class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                                                {{ $bStat['washers']['ratio'] }}
                                            </span>
                                            <span class="text-[10px] font-semibold text-slate-400">avail</span>
                                        </div>

                                        <!-- Explanatory line e.g. "1 available out of 3" -->
                                        <p class="text-[10px] text-slate-500 font-medium leading-tight mt-0.5">
                                            @if($bStat['washers']['total'] === 0)
                                                0 available out of 0
                                            @elseif($bStat['washers']['available'] === $bStat['washers']['total'])
                                                {{ $bStat['washers']['available'] }} available out of {{ $bStat['washers']['total'] }}
                                            @else
                                                {{ $bStat['washers']['available'] }} available out of {{ $bStat['washers']['total'] }}
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Progress Bar -->
                                    @if($bStat['washers']['total'] > 0)
                                        @php
                                            $wAvailPct = round(($bStat['washers']['available'] / $bStat['washers']['total']) * 100);
                                            $wInUsePct = round(($bStat['washers']['in_use'] / $bStat['washers']['total']) * 100);
                                        @endphp
                                        <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 flex">
                                            <div class="h-full bg-emerald-500 transition-all duration-300" style="width: {{ $wAvailPct }}%;" title="{{ $bStat['washers']['available'] }} available"></div>
                                            <div class="h-full bg-blue-500 transition-all duration-300" style="width: {{ $wInUsePct }}%;" title="{{ $bStat['washers']['in_use'] }} in use"></div>
                                            @if($bStat['washers']['maintenance'] > 0)
                                                <div class="h-full bg-amber-400 transition-all duration-300" style="width: {{ 100 - $wAvailPct - $wInUsePct }}%;" title="{{ $bStat['washers']['maintenance'] }} maintenance"></div>
                                            @endif
                                        </div>
                                        @if($bStat['washers']['maintenance'] > 0)
                                            <p class="text-[9px] font-semibold text-amber-600 mt-1 flex items-center gap-0.5">
                                                <span>⚠️</span> {{ $bStat['washers']['maintenance'] }} maint
                                            </p>
                                        @endif
                                    @endif
                                </div>

                                <!-- Dryer Machine Card -->
                                <div class="rounded-xl border border-slate-200/80 bg-white p-2.5 flex flex-col justify-between shadow-2xs hover:border-amber-300 transition-colors">
                                    <div>
                                        <div class="flex items-center justify-between gap-1 mb-1">
                                            <div class="flex items-center gap-1 min-w-0">
                                                <span class="h-4 w-4 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                                    <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                                <span class="text-[11px] font-bold text-slate-800 truncate">Dryer</span>
                                            </div>
                                            @if($bStat['dryers']['in_use'] > 0)
                                                <span class="text-[9px] font-extrabold text-amber-700 bg-amber-50 border border-amber-100 px-1.5 py-0.5 rounded shrink-0">
                                                    {{ $bStat['dryers']['in_use'] }} run
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Ratio e.g. 3/3 -->
                                        <div class="flex items-baseline gap-1 mt-1">
                                            <span class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                                                {{ $bStat['dryers']['ratio'] }}
                                            </span>
                                            <span class="text-[10px] font-semibold text-slate-400">avail</span>
                                        </div>

                                        <!-- Explanatory line e.g. "3 available out of 3" -->
                                        <p class="text-[10px] text-slate-500 font-medium leading-tight mt-0.5">
                                            @if($bStat['dryers']['total'] === 0)
                                                0 available out of 0
                                            @elseif($bStat['dryers']['available'] === $bStat['dryers']['total'])
                                                {{ $bStat['dryers']['available'] }} available out of {{ $bStat['dryers']['total'] }}
                                            @else
                                                {{ $bStat['dryers']['available'] }} available out of {{ $bStat['dryers']['total'] }}
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Progress Bar -->
                                    @if($bStat['dryers']['total'] > 0)
                                        @php
                                            $dAvailPct = round(($bStat['dryers']['available'] / $bStat['dryers']['total']) * 100);
                                            $dInUsePct = round(($bStat['dryers']['in_use'] / $bStat['dryers']['total']) * 100);
                                        @endphp
                                        <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 flex">
                                            <div class="h-full bg-emerald-500 transition-all duration-300" style="width: {{ $dAvailPct }}%;" title="{{ $bStat['dryers']['available'] }} available"></div>
                                            <div class="h-full bg-amber-500 transition-all duration-300" style="width: {{ $dInUsePct }}%;" title="{{ $bStat['dryers']['in_use'] }} in use"></div>
                                            @if($bStat['dryers']['maintenance'] > 0)
                                                <div class="h-full bg-amber-400 transition-all duration-300" style="width: {{ 100 - $dAvailPct - $dInUsePct }}%;" title="{{ $bStat['dryers']['maintenance'] }} maintenance"></div>
                                            @endif
                                        </div>
                                        @if($bStat['dryers']['maintenance'] > 0)
                                            <p class="text-[9px] font-semibold text-amber-600 mt-1 flex items-center gap-0.5">
                                                <span>⚠️</span> {{ $bStat['dryers']['maintenance'] }} maint
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-6 text-center text-slate-400">
                        <p class="text-xs">No machine statistics available.</p>
                    </div>
                @endif
            </div>

            <!-- System Status Indicator Card -->
            <div class="mt-6 rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1.5">System Status</p>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-emerald-800">All Systems Operational</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Cycle Countdown Ticker & Manager Tracker Carousel -->
    <script>
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
                        setTimeout(function() {
                            window.location.reload();
                        }, 1200);
                    }
                }, 1000);
            }
        })();

        // Manager Wash & Dry Tracker Carousel Rotation (Mobile)
        (function() {
            const slides = document.querySelectorAll('.admin-tracker-slide');
            const dots = document.querySelectorAll('.admin-tracker-dot');
            const counter = document.getElementById('adminTrackerSlideCounter');
            const card = document.getElementById('adminTrackerCard');
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
                }, 5000);
            }

            function stopTimer() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            window.changeAdminTrackerSlide = function(direction) {
                updateSlide(currentSlide + direction);
                startTimer();
            };

            window.goToAdminTrackerSlide = function(index) {
                updateSlide(index);
                startTimer();
            };

            if (card) {
                card.addEventListener('mouseenter', stopTimer);
                card.addEventListener('mouseleave', startTimer);
                card.addEventListener('touchstart', stopTimer, { passive: true });
                card.addEventListener('touchend', startTimer, { passive: true });
            }

            startTimer();
        })();
    </script>
</x-admin-layout>

