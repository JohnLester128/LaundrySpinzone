<x-admin-layout>
    <!-- Page Header & Period Switcher Bar -->
    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <div>
            <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Financial Overview</p>
            <h1 class="text-2xl sm:text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">Sales Dashboard</h1>
        </div>

        <!-- Period Filter Pills + Branch Filter (Owner) + Export Button -->
        <div class="flex flex-wrap items-center gap-2">
            @php
                $isOwner = auth('admin')->user()->isSuperAdmin();
                $curPeriod = $period ?? 'all';
            @endphp

            <!-- Quick Period Switcher Pills -->
            <div class="inline-flex items-center rounded-2xl border border-slate-200/80 bg-white p-1 shadow-2xs">
                @foreach(['all' => 'All Time', 'today' => 'Today', 'week' => 'This Week', 'month' => 'This Month'] as $pKey => $pLabel)
                    <a href="{{ route('admin.sales', array_merge(request()->query(), ['period' => $pKey])) }}" 
                       class="px-2.5 sm:px-3 py-1.5 text-xs font-semibold rounded-xl transition {{ $curPeriod === $pKey ? 'bg-blue-600 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        {{ $pLabel }}
                    </a>
                @endforeach
            </div>

            @php
                $currentPeriodLabel = match($period ?? 'all') {
                    'today' => 'Today',
                    'week' => 'This Week',
                    'month' => 'This Month',
                    default => 'All Time',
                };
                $exportScopeName = $isOwner 
                    ? ($branchFilter ? ($branches->firstWhere('id', $branchFilter)->name ?? 'Selected Branch') : 'All Branches (HQ)')
                    : (auth('admin')->user()->branch->name ?? 'This Branch');

                $exportRecords = $sales->map(function($sale) {
                    $req = $sale->serviceRequest;
                    return [
                        'sale_id' => '#SALE-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT),
                        'request_code' => '#REQ-' . str_pad($sale->service_request_id, 4, '0', STR_PAD_LEFT),
                        'customer' => $req?->customer?->username ?? 'Walk-in Customer',
                        'phone' => $req?->customer?->phone ?? 'N/A',
                        'branch' => $sale->branch?->name ?? 'Central Branch',
                        'service_type' => $req?->clothType?->name ?? 'Standard Wash',
                        'weight_kg' => (float)($req?->actual_weight ?? $req?->estimated_weight ?? 0),
                        'items_pcs' => (int)($req?->actual_quantity ?? $req?->quantity ?? 0),
                        'amount_php' => (float)$sale->amount,
                        'sold_at' => $sale->sold_at ? $sale->sold_at->format('Y-m-d H:i') : '',
                    ];
                });
            @endphp

            <!-- Overall Sales Export & Download Dropdown -->
            <div class="relative inline-block text-left control-dropdown-wrapper shrink-0">
                <button type="button" onclick="toggleControlPopover('salesExportPopover', event)" class="inline-flex h-9 items-center justify-center gap-1.5 sm:gap-2 rounded-2xl border border-slate-200/80 bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition shadow-2xs cursor-pointer active:scale-95" title="Export Overall Sales Report">
                    <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span class="font-bold">Export Sales</span>
                    <svg class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Export Options Popover -->
                <div id="salesExportPopover" class="control-popover hidden absolute right-0 mt-2 w-60 rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                    <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 font-mono">
                        Overall Sales Export
                    </div>

                    <button type="button" onclick="downloadSalesCSV(); toggleControlPopover('salesExportPopover')" class="w-full flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition cursor-pointer text-left">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 leading-tight">Download CSV</p>
                            <p class="text-[10px] text-slate-400">All sales records &amp; totals</p>
                        </div>
                    </button>

                    <button type="button" onclick="window.print(); toggleControlPopover('salesExportPopover')" class="w-full flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer text-left mt-1">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-700 shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 leading-tight">Print / Save PDF</p>
                            <p class="text-[10px] text-slate-400">Formatted visual summary</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Top Row: 5 Metric Stat Cards (Responsive Grid) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-4 md:gap-4 mb-6">
        <!-- Card 1: Total Sales -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-4 md:p-5 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between">
            <div class="absolute top-0 inset-x-0 h-1 bg-blue-600"></div>
            <div class="flex items-center justify-between gap-1 mb-2">
                <p class="font-mono text-[9px] sm:text-[11px] font-semibold uppercase tracking-wider text-slate-500 truncate">
                    Total Sales
                </p>
                <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/20 shrink-0">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="my-auto min-w-0">
                <h3 class="text-base sm:text-xl md:text-2xl font-extrabold tracking-tight text-slate-900 truncate" title="₱{{ number_format($totalSales, 2) }}">
                    <span class="text-xs sm:text-sm font-bold text-slate-500 mr-0.5">₱</span>{{ number_format($totalSales, 0) }}
                </h3>
            </div>
            <div class="flex items-center text-[9px] font-bold text-blue-700 mt-2 truncate">
                <span class="inline-flex items-center gap-0.5 rounded-full bg-blue-50 px-1.5 py-0.5 border border-blue-200/50 truncate">
                    ● Revenue
                </span>
            </div>
        </div>

        <!-- Card 2: Transactions -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-4 md:p-5 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between">
            <div class="absolute top-0 inset-x-0 h-1 bg-purple-600"></div>
            <div class="flex items-center justify-between gap-1 mb-2">
                <p class="font-mono text-[9px] sm:text-[11px] font-semibold uppercase tracking-wider text-slate-500 truncate">
                    Orders
                </p>
                <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-xl bg-purple-600 text-white shadow-sm shadow-purple-500/20 shrink-0">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="my-auto min-w-0">
                <h3 class="text-base sm:text-xl md:text-2xl font-extrabold tracking-tight text-slate-900 truncate">
                    {{ number_format($transactionCount) }}
                </h3>
            </div>
            <div class="flex items-center text-[9px] font-bold text-purple-700 mt-2 truncate">
                <span class="inline-flex items-center gap-0.5 rounded-full bg-purple-50 px-1.5 py-0.5 border border-purple-200/50 truncate">
                    ● Completed
                </span>
            </div>
        </div>

        <!-- Card 3: Average Sale -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-4 md:p-5 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between">
            <div class="absolute top-0 inset-x-0 h-1 bg-teal-600"></div>
            <div class="flex items-center justify-between gap-1 mb-2">
                <p class="font-mono text-[9px] sm:text-[11px] font-semibold uppercase tracking-wider text-slate-500 truncate">
                    Average Sale
                </p>
                <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-xl bg-teal-600 text-white shadow-sm shadow-teal-500/20 shrink-0">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="my-auto min-w-0">
                <h3 class="text-base sm:text-xl md:text-2xl font-extrabold tracking-tight text-slate-900 truncate" title="₱{{ number_format($averageSale, 2) }}">
                    <span class="text-xs sm:text-sm font-bold text-slate-500 mr-0.5">₱</span>{{ number_format($averageSale, 0) }}
                </h3>
            </div>
            <div class="flex items-center text-[9px] font-bold text-teal-700 mt-2 truncate">
                <span class="inline-flex items-center gap-0.5 rounded-full bg-teal-50 px-1.5 py-0.5 border border-teal-200/50 truncate">
                    ● Per Order
                </span>
            </div>
        </div>

        <!-- Card 4: Total Laundry Load -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-4 md:p-5 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between">
            <div class="absolute top-0 inset-x-0 h-1 bg-emerald-600"></div>
            <div class="flex items-center justify-between gap-1 mb-2">
                <p class="font-mono text-[9px] sm:text-[11px] font-semibold uppercase tracking-wider text-slate-500 truncate">
                    Load Washed
                </p>
                <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm shadow-emerald-500/20 shrink-0">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                </div>
            </div>
            <div class="my-auto min-w-0">
                <h3 class="text-base sm:text-xl md:text-2xl font-extrabold tracking-tight text-slate-900 truncate">
                    {{ number_format($totalWeight, 1) }} <span class="text-xs sm:text-sm font-semibold text-slate-400">kg</span>
                </h3>
            </div>
            <div class="flex items-center text-[9px] font-bold text-emerald-700 mt-2 truncate">
                <span class="inline-flex items-center gap-0.5 rounded-full bg-emerald-50 px-1.5 py-0.5 border border-emerald-200/50 truncate">
                    ● {{ $transactionCount > 0 ? number_format($totalWeight / $transactionCount, 1) : 0 }} kg/avg
                </span>
            </div>
        </div>

        <!-- Card 5: Total Items Processed -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-4 md:p-5 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between col-span-2 sm:col-span-1">
            <div class="absolute top-0 inset-x-0 h-1 bg-amber-500"></div>
            <div class="flex items-center justify-between gap-1 mb-2">
                <p class="font-mono text-[9px] sm:text-[11px] font-semibold uppercase tracking-wider text-slate-500 truncate">
                    Items Washed
                </p>
                <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-xl bg-amber-500 text-white shadow-sm shadow-amber-500/20 shrink-0">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="my-auto min-w-0">
                <h3 class="text-base sm:text-xl md:text-2xl font-extrabold tracking-tight text-slate-900 truncate">
                    {{ number_format($totalItems) }} <span class="text-xs sm:text-sm font-semibold text-slate-400">pcs</span>
                </h3>
            </div>
            <div class="flex items-center text-[9px] font-bold text-amber-700 mt-2 truncate">
                <span class="inline-flex items-center gap-0.5 rounded-full bg-amber-50 px-1.5 py-0.5 border border-amber-200/50 truncate">
                    ● Clothes &amp; Linens
                </span>
            </div>
        </div>
    </div>

    <!-- Visual Analytics & Charts Section -->
    <div class="space-y-6 mb-8">
        <!-- Main Row: Trends Area/Bar (2/3) + Service Donut (1/3) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">
            <!-- Left: Revenue & Orders Timeline Trend -->
            <div class="lg:col-span-2 rounded-2xl sm:rounded-3xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-sm transition hover:shadow-md">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="flex h-2.5 w-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                            <h2 class="text-base sm:text-lg font-extrabold tracking-tight text-slate-900">Revenue &amp; Order Trends</h2>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">
                            @if($period === 'today')
                                Hourly revenue curve &amp; transaction velocity for today
                            @elseif($period === 'week')
                                Daily revenue &amp; order count for this week
                            @elseif($period === 'month')
                                Daily performance tracking for this month
                            @else
                                Multi-month financial progression &amp; volume
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 border border-blue-200/50">
                            <span class="text-slate-500 font-normal">Filtered Total:</span>
                            <span>₱{{ number_format($totalSales, 2) }}</span>
                        </span>
                    </div>
                </div>

                @if($sales->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">No Sales Data for this Timeframe</p>
                        <p class="text-xs text-slate-400 mt-1 max-w-xs">Try selecting a broader period or changing your branch filter above.</p>
                    </div>
                @else
                    <div id="revenueTrendChart" class="w-full min-h-[300px]"></div>
                @endif
            </div>

            <!-- Right: Service / Cloth Type Donut Chart -->
            <div class="rounded-2xl sm:rounded-3xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-sm transition hover:shadow-md flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-2 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="flex h-2.5 w-2.5 rounded-full bg-purple-600"></span>
                            <h2 class="text-base sm:text-lg font-extrabold tracking-tight text-slate-900">Service Share</h2>
                        </div>
                        <span class="text-xs font-mono font-semibold text-slate-400">{{ count($serviceLabels) }} Categories</span>
                    </div>
                    <p class="text-xs text-slate-400 mb-4">Revenue breakdown by laundry category &amp; fabric type</p>
                </div>

                @if(empty($serviceAmounts) || array_sum($serviceAmounts) == 0)
                    <div class="flex flex-col items-center justify-center py-16 text-center my-auto">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 text-purple-400 mb-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">No Category Records</p>
                        <p class="text-xs text-slate-400 mt-1">Completed orders will categorize here.</p>
                    </div>
                @else
                    <div id="serviceDonutChart" class="w-full flex items-center justify-center min-h-[260px]"></div>
                @endif

                <!-- Service Legend Mini-List -->
                @if(!empty($serviceLabels))
                    <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
                        @foreach(array_slice($serviceLabels, 0, 4) as $idx => $sLabel)
                            @php
                                $sAmt = $serviceAmounts[$idx] ?? 0;
                                $sPct = $totalSales > 0 ? round(($sAmt / $totalSales) * 100, 1) : 0;
                                $dotColors = ['bg-blue-600', 'bg-purple-600', 'bg-teal-600', 'bg-amber-500', 'bg-pink-500', 'bg-slate-500'];
                                $dot = $dotColors[$idx % count($dotColors)];
                            @endphp
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-1.5 truncate max-w-[150px]">
                                    <span class="h-2 w-2 rounded-full {{ $dot }} shrink-0"></span>
                                    <span class="font-medium text-slate-700 truncate">{{ $sLabel }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <span class="font-bold text-slate-900">₱{{ number_format($sAmt, 0) }}</span>
                                    <span class="text-[11px] font-semibold text-slate-400">({{ $sPct }}%)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Row 2: Multi-Branch Comparison (Owner View) OR Branch Operational Efficiency (Manager View) -->
        @if($isOwner && count($branchLabels) > 1)
            <div class="rounded-2xl sm:rounded-3xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-sm transition hover:shadow-md">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="flex h-2.5 w-2.5 rounded-full bg-teal-600"></span>
                            <h2 class="text-base sm:text-lg font-extrabold tracking-tight text-slate-900">Branch Performance Comparison</h2>
                            <span class="inline-flex items-center rounded-full bg-purple-50 px-2 py-0.5 text-[10px] font-bold text-purple-700 border border-purple-200/60 uppercase font-mono">Owner View</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Compare total revenue and completed order volume across branches</p>
                    </div>
                    <span class="text-xs font-semibold text-slate-500 font-mono">{{ count($branchLabels) }} Branches Active</span>
                </div>

                <div id="branchComparisonChart" class="w-full min-h-[250px]"></div>
            </div>
        @else
            <!-- Manager Operational Insight Banner -->
            <div class="rounded-2xl sm:rounded-3xl border border-slate-200/80 bg-gradient-to-r from-blue-50/70 via-indigo-50/50 to-purple-50/70 p-4 sm:p-5 shadow-2xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-sm shadow-blue-500/20">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Branch Operational Throughput</h3>
                            <p class="text-xs text-slate-500">
                                Averaging <span class="font-bold text-slate-800">{{ $transactionCount > 0 ? number_format($totalWeight / $transactionCount, 1) : 0 }} kg</span> and <span class="font-bold text-slate-800">{{ $transactionCount > 0 ? number_format($totalItems / $transactionCount, 1) : 0 }} pieces</span> per completed laundry transaction.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 self-end sm:self-center">
                        <span class="text-xs font-semibold text-blue-700 bg-white/80 border border-blue-200/60 px-3 py-1.5 rounded-xl shadow-2xs">
                            Active Scope: {{ $isOwner ? 'All Branches' : (auth('admin')->user()->branch->name ?? 'This Branch') }}
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Container: Completed Sales (Matching Service Requests List Format) -->
    <div class="rounded-2xl sm:rounded-3xl border border-slate-200/80 bg-white p-4 sm:p-6 lg:p-7 shadow-sm">
        <!-- Top Controls: Search Input (Left) + Filter/Sort/Layout (Right) -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-100 mb-6">
            <!-- Search Input (Left) -->
            <div class="relative w-full sm:w-80">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="salesTableSearch" placeholder="Search customer, ID, service..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
            </div>

            @php
                $periodLabels = [
                    'all' => 'All Time',
                    'today' => 'Today',
                    'week' => 'This Week',
                    'month' => 'This Month',
                ];

                $salesSortConfig = [
                    'date' => [
                        'label' => 'Date',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'is_active' => in_array($sort, ['newest', 'oldest']) || empty($sort),
                        'next_sort' => ($sort === 'newest' || empty($sort)) ? 'oldest' : 'newest',
                        'dir_indicator' => ($sort === 'oldest') ? '▲ Oldest' : '▼ Newest',
                    ],
                    'customer' => [
                        'label' => 'Customer',
                        'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'is_active' => in_array($sort, ['name_asc', 'name_desc']),
                        'next_sort' => ($sort === 'name_asc') ? 'name_desc' : 'name_asc',
                        'dir_indicator' => ($sort === 'name_desc') ? '▼ Z-A' : '▲ A-Z',
                    ],
                    'price' => [
                        'label' => 'Price',
                        'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                        'is_active' => in_array($sort, ['price_desc', 'price_asc']),
                        'next_sort' => ($sort === 'price_desc') ? 'price_asc' : 'price_desc',
                        'dir_indicator' => ($sort === 'price_asc') ? '▲ Lowest' : '▼ Highest',
                    ],
                    'id' => [
                        'label' => 'Request ID',
                        'icon' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14',
                        'is_active' => in_array($sort, ['id_desc', 'id_asc']),
                        'next_sort' => ($sort === 'id_desc') ? 'id_asc' : 'id_desc',
                        'dir_indicator' => ($sort === 'id_asc') ? '▲ 1-9' : '▼ 9-1',
                    ],
                    'service_type' => [
                        'label' => 'Service Type',
                        'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                        'is_active' => in_array($sort, ['service_type_asc', 'service_type_desc']),
                        'next_sort' => ($sort === 'service_type_asc') ? 'service_type_desc' : 'service_type_asc',
                        'dir_indicator' => ($sort === 'service_type_desc') ? '▼ Z-A' : '▲ A-Z',
                    ],
                    'weight' => [
                        'label' => 'Weight',
                        'icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
                        'is_active' => in_array($sort, ['weight_asc', 'weight_desc']),
                        'next_sort' => ($sort === 'weight_asc') ? 'weight_desc' : 'weight_asc',
                        'dir_indicator' => ($sort === 'weight_desc') ? '▼ Heaviest' : '▲ Lightest',
                    ],
                    'items' => [
                        'label' => 'Items',
                        'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                        'is_active' => in_array($sort, ['items_asc', 'items_desc']),
                        'next_sort' => ($sort === 'items_asc') ? 'items_desc' : 'items_asc',
                        'dir_indicator' => ($sort === 'items_desc') ? '▼ Most' : '▲ Fewest',
                    ],
                ];

                if (auth('admin')->user()->isSuperAdmin()) {
                    $salesSortConfig['branch'] = [
                        'label' => 'Branch',
                        'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                        'is_active' => in_array($sort, ['branch_asc', 'branch_desc']),
                        'next_sort' => ($sort === 'branch_asc') ? 'branch_desc' : 'branch_asc',
                        'dir_indicator' => ($sort === 'branch_desc') ? '▼ Z-A' : '▲ A-Z',
                    ];
                }

                $hasActiveSalesFilter = (!empty($period) && $period !== 'all') || !empty($branchFilter);
                $hasActiveSalesSort = !empty($sort) && $sort !== 'newest';
            @endphp

            <!-- Right Controls: Filter, Sort By, Layout Buttons -->
            <div class="flex items-center justify-end gap-2 sm:gap-2.5 min-w-0 w-full sm:w-auto ml-auto">
                <!-- 1. Filter Button (first / near center) -->
                <div class="relative inline-block text-left control-dropdown-wrapper shrink-0 z-10">
                    <button type="button" onclick="toggleControlPopover('salesFilterPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl {{ $hasActiveSalesFilter ? 'bg-blue-50 border border-blue-200 text-blue-700 font-bold' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900' }} px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Filter sales">
                        <svg class="h-4 w-4 {{ $hasActiveSalesFilter ? 'text-blue-600' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden sm:inline">Filter</span>
                        @if($hasActiveSalesFilter)
                            <span class="hidden sm:inline-block h-2 w-2 rounded-full bg-blue-600"></span>
                            <span class="sm:hidden absolute top-1 right-1 h-2 w-2 rounded-full bg-blue-600 ring-2 ring-white"></span>
                        @endif
                        <svg class="hidden sm:block h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Sales Filter Popover -->
                    <div id="salesFilterPopover" class="control-popover hidden absolute right-0 mt-2 w-64 max-w-[calc(100vw-1.5rem)] max-h-[calc(100vh-140px)] overflow-y-auto [scrollbar-width:thin] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>Time Period</span>
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @foreach(['all' => 'All Time', 'today' => 'Today', 'week' => 'This Week', 'month' => 'This Month'] as $pKey => $pLabel)
                            @php
                                $isPActive = ($period ?? 'all') === $pKey;
                            @endphp
                            <a href="{{ route('admin.sales', array_merge(request()->query(), ['period' => $pKey])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $isPActive ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <span>{{ $pLabel }}</span>
                                @if($isPActive)
                                    <span class="text-blue-600 font-bold text-xs">✓</span>
                                @endif
                            </a>
                        @endforeach

                        @if($isOwner && isset($branches) && $branches->isNotEmpty())
                            <div class="my-1.5 border-t border-slate-100"></div>
                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                <span>Branch Scope</span>
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <a href="{{ route('admin.sales', array_merge(request()->query(), ['branch' => null])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ empty($branchFilter) ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <span>All Branches (HQ)</span>
                                @if(empty($branchFilter))
                                    <span class="text-blue-600 font-bold text-xs">✓</span>
                                @endif
                            </a>
                            @foreach($branches as $b)
                                <a href="{{ route('admin.sales', array_merge(request()->query(), ['branch' => $b->id])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ ($branchFilter == $b->id) ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                    <span>{{ $b->name }}</span>
                                    @if($branchFilter == $b->id)
                                        <span class="text-blue-600 font-bold text-xs">✓</span>
                                    @endif
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- 2. Sort By Button (then sortby) -->
                <div class="relative inline-block text-left control-dropdown-wrapper shrink-0 z-10">
                    <button type="button" onclick="toggleControlPopover('salesSortPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl {{ $hasActiveSalesSort ? 'bg-blue-50 border border-blue-200 text-blue-700 font-bold' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900' }} px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Sort sales">
                        <svg class="h-4 w-4 {{ $hasActiveSalesSort ? 'text-blue-600' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                        </svg>
                        <span class="hidden sm:inline">Sort By</span>
                        @if($hasActiveSalesSort)
                            <span class="hidden sm:inline-block h-2 w-2 rounded-full bg-blue-600"></span>
                            <span class="sm:hidden absolute top-1 right-1 h-2 w-2 rounded-full bg-blue-600 ring-2 ring-white"></span>
                        @endif
                        <svg class="hidden sm:block h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Sales Sort Popover -->
                    <div id="salesSortPopover" class="control-popover hidden absolute right-0 mt-2 w-60 sm:w-64 max-w-[calc(100vw-1.5rem)] max-h-[calc(100vh-140px)] overflow-y-auto [scrollbar-width:thin] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>Sort By</span>
                            @if($hasActiveSalesSort)
                                <a href="{{ route('admin.sales', array_merge(request()->query(), ['sort' => 'newest'])) }}" class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-500 hover:text-rose-700 transition" title="Clear sort">
                                    <span>Clear</span>
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @else
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                </svg>
                            @endif
                        </div>
                        @foreach($salesSortConfig as $key => $opt)
                            <a href="{{ route('admin.sales', array_merge(request()->query(), ['sort' => $opt['next_sort']])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $opt['is_active'] ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 {{ $opt['is_active'] ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $opt['icon'] }}" />
                                    </svg>
                                    <span>{{ $opt['label'] }}</span>
                                </div>
                                @if($opt['is_active'])
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold text-blue-700 bg-blue-100/80 px-1.5 py-0.5 rounded-md">
                                            {{ $opt['dir_indicator'] }}
                                        </span>
                                        <span onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('admin.sales', array_merge(request()->query(), ['sort' => 'newest'])) }}';" class="p-0.5 rounded text-blue-500 hover:bg-rose-100 hover:text-rose-600 transition cursor-pointer" title="Clear sort">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </span>
                                    </div>
                                @else
                                    <span class="text-slate-300 text-xs font-mono">↕</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Layout Button (then layout) -->
                <div class="relative inline-block text-left control-dropdown-wrapper shrink-0 z-10">
                    <button type="button" onclick="toggleControlPopover('salesLayoutPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900 px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Switch View Layout">
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="hidden sm:inline">Layout</span>
                        <svg class="hidden sm:block h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Sales Layout Popover -->
                    <div id="salesLayoutPopover" class="control-popover hidden absolute right-0 mt-2 w-48 sm:w-52 max-w-[calc(100vw-1.5rem)] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>View Layout</span>
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </div>
                        <button type="button" onclick="switchView('cards')" class="view-opt-btn flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4z" />
                                </svg>
                                <span>Grid Cards</span>
                            </div>
                            <span id="checkCards" class="hidden text-blue-600 font-bold">✓</span>
                        </button>
                        <button type="button" onclick="switchView('table')" class="view-opt-btn flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M3 18h18M3 6h18" />
                                </svg>
                                <span>Details Table</span>
                            </div>
                            <span id="checkTable" class="text-blue-600 font-bold">✓</span>
                        </button>
                    </div>
                </div>

                <!-- Active Filter / Sort Quick-Clear (Only visible when a filter or sort is active) -->
                @if($hasActiveSalesFilter || $hasActiveSalesSort)
                    <a href="{{ route('admin.sales', ['period' => 'all', 'branch' => null, 'sort' => 'newest']) }}" class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-slate-100/80 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition cursor-pointer active:scale-95 shadow-2xs shrink-0" title="Clear filter and sort">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <!-- 3-Column Card Grid Container -->
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 hidden" id="salesCardGrid">
            @forelse($sales as $sale)
                @php
                    $req = $sale->serviceRequest;
                    $username = $req->customer->username ?? 'Walk-in Customer';
                    $clothType = $req->clothType->name ?? 'Standard Wash';
                    $weight = $req->actual_weight ?? $req->estimated_weight;
                    $quantity = $req->actual_quantity ?? $req->quantity;
                    $initial = strtoupper(substr($username, 0, 1));
                @endphp
                <div class="sales-card-item relative flex flex-col justify-between overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-5 shadow-2xs transition hover:border-blue-300 hover:shadow-md duration-200" data-id="{{ $sale->id }}" data-search="{{ strtolower($username . ' ' . $sale->service_request_id . ' ' . $sale->id . ' ' . $clothType) }}">
                    <div class="absolute top-0 inset-x-0 h-1 bg-emerald-500"></div>

                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="font-mono text-xs font-bold text-slate-500">
                                #REQ-{{ str_pad($sale->service_request_id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                @if($isOwner)
                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600 border border-slate-200/60">
                                        {{ $sale->branch->name ?? 'Central Branch' }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200/60">
                                    Completed
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 mb-4 min-w-0">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-xs shrink-0">
                                {{ $initial }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-extrabold text-sm text-slate-900 leading-snug truncate" title="{{ $username }}">{{ $username }}</p>
                                <p class="text-[11px] font-medium text-slate-400 truncate" title="{{ $clothType }}">{{ $clothType }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 rounded-2xl bg-slate-50 p-3 mb-4 border border-slate-100 text-xs">
                            <div>
                                <p class="text-[10px] uppercase font-mono text-slate-400 font-semibold">Weight</p>
                                <p class="font-bold text-slate-800 mt-0.5">{{ number_format($weight, 0) }} kg</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-mono text-slate-400 font-semibold">Items</p>
                                <p class="font-bold text-slate-800 mt-0.5">{{ $quantity }} pcs</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <div>
                            <p class="text-[10px] uppercase font-mono text-slate-400 font-semibold">Final Price</p>
                            <p class="text-base font-extrabold text-emerald-600">PHP {{ number_format($sale->amount, 0) }}</p>
                        </div>

                        <a href="{{ route('admin.service-requests.show', $sale->service_request_id) }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200/90 bg-white px-3.5 py-1.5 text-xs font-bold text-slate-800 hover:border-blue-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-2xs group">
                            <span>Details</span>
                            <svg class="h-3.5 w-3.5 text-slate-600 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <p class="text-sm font-semibold text-slate-600">No completed sales recorded</p>
                </div>
            @endforelse
        </div>

        <!-- Sales Table Container (Default View) -->
        <div class="mt-4 overflow-x-auto" id="salesTableContainer">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase tracking-wider text-slate-500 font-mono border-b border-slate-200/80">
                    <tr>
                        <!-- Request ID Header -->
                        @php
                            $idNextSort = ($sort === 'id_asc') ? 'id_desc' : 'id_asc';
                        @endphp
                        <th class="px-4 py-3.5 rounded-l-xl">
                            <a href="{{ route('admin.sales', ['period' => $period, 'sort' => $idNextSort]) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by ID">
                                <span>Request ID</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'id_asc') ▲ @elseif ($sort === 'id_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Customer Header -->
                        @php
                            $nameNextSort = ($sort === 'name_asc') ? 'name_desc' : 'name_asc';
                        @endphp
                        <th class="px-4 py-3.5">
                            <a href="{{ route('admin.sales', array_merge(request()->query(), ['sort' => $nameNextSort])) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Customer Name">
                                <span>Customer</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'name_asc') ▲ @elseif ($sort === 'name_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        @if($isOwner)
                            @php
                                $branchNextSort = ($sort === 'branch_asc') ? 'branch_desc' : 'branch_asc';
                            @endphp
                            <th class="px-4 py-3.5">
                                <a href="{{ route('admin.sales', array_merge(request()->query(), ['sort' => $branchNextSort])) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Branch">
                                    <span>Branch</span>
                                    <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                        @if ($sort === 'branch_asc') ▲ @elseif ($sort === 'branch_desc') ▼ @else ↕ @endif
                                    </span>
                                </a>
                            </th>
                        @endif

                        <!-- Service Type Header -->
                        @php
                            $serviceTypeNextSort = ($sort === 'service_type_asc') ? 'service_type_desc' : 'service_type_asc';
                        @endphp
                        <th class="px-4 py-3.5">
                            <a href="{{ route('admin.sales', array_merge(request()->query(), ['sort' => $serviceTypeNextSort])) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Service Type">
                                <span>Service Type</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'service_type_asc') ▲ @elseif ($sort === 'service_type_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>


                        <!-- Date Header -->
                        @php
                            $dateNextSort = ($sort === 'newest') ? 'oldest' : 'newest';
                        @endphp
                        <th class="px-4 py-3.5">
                            <a href="{{ route('admin.sales', ['period' => $period, 'sort' => $dateNextSort]) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Date">
                                <span>Date Sold</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'newest') ▼ @elseif ($sort === 'oldest') ▲ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Final Price Header -->
                        @php
                            $priceNextSort = ($sort === 'price_desc') ? 'price_asc' : 'price_desc';
                        @endphp
                        <th class="px-4 py-3.5">
                            <a href="{{ route('admin.sales', ['period' => $period, 'sort' => $priceNextSort]) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Price">
                                <span>Final Price</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'price_desc') ▼ @elseif ($sort === 'price_asc') ▲ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <th class="px-4 py-3.5 text-right rounded-r-xl">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="salesTableBody">
                    @forelse($sales as $sale)
                        @php
                            $req = $sale->serviceRequest;
                            $username = $req->customer->username ?? 'Walk-in Customer';
                            $clothType = $req->clothType->name ?? 'Standard Wash';
                            $weight = $req->actual_weight ?? $req->estimated_weight;
                            $quantity = $req->actual_quantity ?? $req->quantity;
                            $initial = strtoupper(substr($username, 0, 1));
                        @endphp
                        <tr class="sales-row-item hover:bg-blue-50/30 transition duration-150" data-id="{{ $sale->id }}" data-search="{{ strtolower($username . ' ' . $sale->service_request_id . ' ' . $sale->id . ' ' . $clothType) }}">
                            <!-- Request ID -->
                            <td class="px-4 py-4 whitespace-nowrap font-mono text-xs font-bold text-slate-900">
                                #REQ-{{ str_pad($sale->service_request_id, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <!-- Customer -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-xs shrink-0">
                                        {{ $initial }}
                                    </div>
                                    <span class="font-bold text-slate-900 truncate max-w-[140px] sm:max-w-[170px] inline-block" title="{{ $username }}">{{ $username }}</span>
                                </div>
                            </td>

                            @if($isOwner)
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 border border-slate-200/60 px-2 py-0.5 text-xs font-bold text-slate-600 truncate max-w-[130px]" title="{{ $sale->branch->name ?? 'Central Branch' }}">
                                        {{ $sale->branch->name ?? 'Central Branch' }}
                                    </span>
                                </td>
                            @endif

                            <!-- Service Type -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-xl bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 border border-slate-200/60 truncate max-w-[130px] sm:max-w-[160px] inline-block" title="{{ $clothType }}">
                                    {{ $clothType }}
                                </span>
                            </td>


                            <!-- Date Sold -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="font-mono text-xs font-semibold text-slate-700">{{ $sale->sold_at ? $sale->sold_at->format('m/d/y') : 'Today' }}</div>
                                <div class="font-mono text-[11px] text-slate-400">{{ $sale->sold_at ? $sale->sold_at->format('g:iA') : '' }}</div>
                            </td>

                            <!-- Final Sales Price -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center font-extrabold text-sm text-emerald-600 bg-emerald-50 border border-emerald-200/60 px-3 py-1 rounded-xl shadow-2xs">
                                    PHP {{ number_format($sale->amount, 0) }}
                                </span>
                            </td>

                            <!-- Action Link -->
                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.service-requests.show', $sale->service_request_id) }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200/90 bg-white px-3.5 py-1.5 text-xs font-bold text-slate-800 hover:border-blue-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-2xs group">
                                    <span>Details</span>
                                    <svg class="h-3.5 w-3.5 text-slate-600 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isOwner ? '9' : '8' }}" class="py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-3 text-sm font-semibold text-slate-600">No completed sales found for {{ ($period === 'all') ? 'All Time' : ucfirst($period) }}</p>
                                @if($period === 'today')
                                    <p class="text-xs text-slate-400 mt-1">No sales recorded today yet. Try switching to <a href="{{ route('admin.sales', array_merge(request()->query(), ['period' => 'all'])) }}" class="text-blue-600 font-bold underline">All Time</a> or <a href="{{ route('admin.sales', array_merge(request()->query(), ['period' => 'month'])) }}" class="text-blue-600 font-bold underline">This Month</a>.</p>
                                @else
                                    <p class="text-xs text-slate-400 mt-1">Completed orders will automatically populate here.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <x-pagination id="adminSalesPagination" :pageSize="10" :pageSizes="[10, 20, 50]" />
    </div>

    <!-- Client-side Search, View Switcher & Pagination Script -->
    <script>
        let salesPaginator;

        document.addEventListener('DOMContentLoaded', function() {
            salesPaginator = new ClientPaginator({
                containerId: 'adminSalesPagination',
                pageSize: 10,
                getItems: () => {
                    const query = (document.getElementById('salesTableSearch')?.value || '').toLowerCase().trim();
                    const cards = Array.from(document.querySelectorAll('#salesCardGrid .sales-card-item'));
                    return cards.filter(card => {
                        const searchData = card.getAttribute('data-search') || '';
                        return !query || searchData.includes(query);
                    });
                },
                onPageChange: (visibleSlice) => {
                    const visibleIds = new Set(visibleSlice.map(c => c.getAttribute('data-id')));
                    document.querySelectorAll('#salesCardGrid .sales-card-item').forEach(card => {
                        card.style.display = visibleIds.has(card.getAttribute('data-id')) ? '' : 'none';
                    });
                    document.querySelectorAll('#salesTableBody .sales-row-item').forEach(row => {
                        row.style.display = visibleIds.has(row.getAttribute('data-id')) ? '' : 'none';
                    });
                }
            });
        });

        document.getElementById('salesTableSearch')?.addEventListener('input', function(e) {
            if (salesPaginator) {
                salesPaginator.reset();
            }
        });

        function toggleControlPopover(targetId, event) {
            if (event) event.stopPropagation();
            const target = document.getElementById(targetId);
            if (!target) return;
            const isCurrentlyOpen = !target.classList.contains('hidden');

            // Close admin notification popover if open
            const adminNotif = document.getElementById('adminNotifPopover');
            if (adminNotif) adminNotif.classList.add('hidden');

            // Close all open popovers first
            document.querySelectorAll('.control-popover').forEach(p => {
                p.classList.add('hidden');
                p.style.transform = '';
            });
            document.querySelectorAll('.control-dropdown-wrapper').forEach(w => {
                w.classList.remove('z-40');
            });

            if (!isCurrentlyOpen) {
                target.classList.remove('hidden');
                target.style.transform = '';
                const wrapper = target.closest('.control-dropdown-wrapper');
                if (wrapper) wrapper.classList.add('z-40');

                if (typeof window.repositionPopover === 'function') {
                    window.repositionPopover(target);
                }
                requestAnimationFrame(() => {
                    if (typeof window.repositionPopover === 'function') {
                        window.repositionPopover(target);
                    }
                });
            }
        }

        function switchView(mode) {
            const containerTable = document.getElementById('salesTableContainer');
            const containerGrid = document.getElementById('salesCardGrid');
            const checkTable = document.getElementById('checkTable');
            const checkCards = document.getElementById('checkCards');

            if (mode === 'cards') {
                containerGrid?.classList.remove('hidden');
                containerTable?.classList.add('hidden');
                checkCards?.classList.remove('hidden');
                checkTable?.classList.add('hidden');
            } else {
                containerGrid?.classList.add('hidden');
                containerTable?.classList.remove('hidden');
                checkTable?.classList.remove('hidden');
                checkCards?.classList.add('hidden');
            }
            const layoutPopover = document.getElementById('salesLayoutPopover');
            if (layoutPopover) {
                layoutPopover.classList.add('hidden');
                layoutPopover.style.transform = '';
            }
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.control-dropdown-wrapper')) {
                document.querySelectorAll('.control-popover').forEach(p => {
                    p.classList.add('hidden');
                    p.style.transform = '';
                });
                document.querySelectorAll('.control-dropdown-wrapper').forEach(w => {
                    w.classList.remove('z-40');
                });
            }
        });

        // ==========================================
        // ApexCharts Initializations
        // ==========================================
        function initSalesCharts() {
            if (typeof ApexCharts === 'undefined') {
                setTimeout(initSalesCharts, 100);
                return;
            }

            // 1. Revenue & Orders Trend
            const trendEl = document.getElementById('revenueTrendChart');
            if (trendEl && @json(count($trendCategories)) > 0) {
                const trendCategories = @json($trendCategories);
                const trendRevenue = @json($trendRevenueSeries);
                const trendOrders = @json($trendOrdersSeries);

                const trendOptions = {
                    series: [
                        {
                            name: 'Revenue (₱)',
                            type: 'area',
                            data: trendRevenue
                        },
                        {
                            name: 'Orders',
                            type: 'column',
                            data: trendOrders
                        }
                    ],
                    chart: {
                        height: 310,
                        type: 'line',
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'Plus Jakarta Sans, sans-serif'
                    },
                    stroke: {
                        curve: 'smooth',
                        width: [3, 0]
                    },
                    fill: {
                        type: ['gradient', 'solid'],
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.45,
                            opacityTo: 0.05,
                            stops: [0, 95, 100]
                        }
                    },
                    colors: ['#2563eb', '#cbd5e1'],
                    labels: trendCategories,
                    markers: {
                        size: [4, 0],
                        hover: { size: 6 }
                    },
                    yaxis: [
                        {
                            title: {
                                text: 'Revenue (₱)',
                                style: { color: '#64748b', fontSize: '11px', fontWeight: 600 }
                            },
                            labels: {
                                formatter: function (val) {
                                    return '₱' + Number(val || 0).toLocaleString();
                                },
                                style: { colors: '#64748b', fontSize: '11px' }
                            }
                        },
                        {
                            opposite: true,
                            title: {
                                text: 'Orders',
                                style: { color: '#64748b', fontSize: '11px', fontWeight: 600 }
                            },
                            labels: {
                                formatter: function (val) {
                                    return Math.round(val || 0);
                                },
                                style: { colors: '#64748b', fontSize: '11px' }
                            }
                        }
                    ],
                    xaxis: {
                        labels: {
                            style: { colors: '#64748b', fontSize: '11px', fontWeight: 500 }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4,
                        padding: { top: 10, right: 15, bottom: 0, left: 10 }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        fontSize: '12px',
                        fontWeight: 600,
                        markers: { radius: 12 }
                    },
                    tooltip: {
                        theme: 'light',
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function (y, { seriesIndex }) {
                                if (seriesIndex === 0) {
                                    return '₱' + Number(y || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                }
                                return Math.round(y || 0) + ' orders';
                            }
                        }
                    }
                };
                const trendChart = new ApexCharts(trendEl, trendOptions);
                trendChart.render();
            }

            // 2. Service Category Donut Chart
            const donutEl = document.getElementById('serviceDonutChart');
            if (donutEl && @json(count($serviceAmounts)) > 0) {
                const serviceAmounts = @json($serviceAmounts);
                const serviceLabels = @json($serviceLabels);

                const donutOptions = {
                    series: serviceAmounts,
                    labels: serviceLabels,
                    chart: {
                        type: 'donut',
                        height: 270,
                        fontFamily: 'Plus Jakarta Sans, sans-serif'
                    },
                    colors: ['#2563eb', '#7c3aed', '#0d9488', '#f59e0b', '#ec4899', '#64748b'],
                    stroke: { width: 2, colors: ['#ffffff'] },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    name: { show: true, fontSize: '12px', fontWeight: 600, color: '#64748b' },
                                    value: {
                                        show: true,
                                        fontSize: '18px',
                                        fontWeight: 800,
                                        color: '#0f172a',
                                        formatter: function (val) {
                                            return '₱' + Number(val || 0).toLocaleString();
                                        }
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total Revenue',
                                        fontSize: '11px',
                                        fontWeight: 600,
                                        color: '#64748b',
                                        formatter: function (w) {
                                            const sum = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                            return '₱' + Number(sum || 0).toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: { enabled: false },
                    legend: {
                        position: 'bottom',
                        fontSize: '11px',
                        fontWeight: 600,
                        markers: { radius: 12 },
                        itemMargin: { horizontal: 5, vertical: 2 }
                    },
                    tooltip: {
                        theme: 'light',
                        y: {
                            formatter: function (val) {
                                return '₱' + Number(val || 0).toLocaleString(undefined, { minimumFractionDigits: 2 });
                            }
                        }
                    }
                };
                const donutChart = new ApexCharts(donutEl, donutOptions);
                donutChart.render();
            }

            // 3. Multi-Branch Comparison Bar Chart (Owner View)
            const branchEl = document.getElementById('branchComparisonChart');
            if (branchEl && @json(count($branchLabels)) > 0) {
                const branchLabels = @json($branchLabels);
                const branchRevenue = @json($branchRevenueSeries);
                const branchOrders = @json($branchOrdersSeries);

                const branchOptions = {
                    series: [
                        {
                            name: 'Revenue (₱)',
                            data: branchRevenue
                        },
                        {
                            name: 'Orders',
                            data: branchOrders
                        }
                    ],
                    chart: {
                        type: 'bar',
                        height: 260,
                        toolbar: { show: false },
                        fontFamily: 'Plus Jakarta Sans, sans-serif'
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '45%',
                            borderRadius: 6,
                            borderRadiusApplication: 'end'
                        }
                    },
                    dataLabels: { enabled: false },
                    stroke: { show: true, width: 2, colors: ['transparent'] },
                    colors: ['#3b82f6', '#8b5cf6'],
                    xaxis: {
                        categories: branchLabels,
                        labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } }
                    },
                    yaxis: [
                        {
                            title: { text: 'Revenue (₱)', style: { color: '#64748b', fontSize: '11px', fontWeight: 600 } },
                            labels: {
                                formatter: function (val) { return '₱' + Number(val || 0).toLocaleString(); },
                                style: { colors: '#64748b', fontSize: '11px' }
                            }
                        },
                        {
                            opposite: true,
                            title: { text: 'Orders', style: { color: '#64748b', fontSize: '11px', fontWeight: 600 } },
                            labels: {
                                formatter: function (val) { return Math.round(val || 0); },
                                style: { colors: '#64748b', fontSize: '11px' }
                            }
                        }
                    ],
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    tooltip: {
                        theme: 'light',
                        y: {
                            formatter: function (val, { seriesIndex }) {
                                return seriesIndex === 0 
                                    ? '₱' + Number(val || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }) 
                                    : Math.round(val || 0) + ' orders';
                            }
                        }
                    },
                    legend: { position: 'top', horizontalAlign: 'right', fontSize: '11px', fontWeight: 600 }
                };
                const branchChart = new ApexCharts(branchEl, branchOptions);
                branchChart.render();
            }
        }

        // ==========================================
        // Overall Sales CSV Exporter
        // ==========================================
        function downloadSalesCSV() {
            const records = @json($exportRecords ?? []);
            const scopeName = @json($exportScopeName);
            const periodLabel = @json($currentPeriodLabel);
            const totalSales = @json($totalSales);
            const totalOrders = @json($transactionCount);
            const avgSale = @json($averageSale);
            const totalWeight = @json($totalWeight);
            const totalItems = @json($totalItems);
            const now = new Date();
            const generatedDate = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();

            let csv = '';
            csv += 'SPINZONE LAUNDRY MANAGEMENT SYSTEM - OVERALL SALES REPORT\r\n';
            csv += 'Branch Scope,"' + scopeName + '"\r\n';
            csv += 'Period Filter,"' + periodLabel + '"\r\n';
            csv += 'Generated At,"' + generatedDate + '"\r\n';
            csv += 'Total Revenue (PHP),' + Number(totalSales).toFixed(2) + '\r\n';
            csv += 'Completed Orders,' + totalOrders + '\r\n';
            csv += 'Average Ticket (PHP),' + Number(avgSale).toFixed(2) + '\r\n';
            csv += 'Total Load Washed (kg),' + Number(totalWeight).toFixed(2) + '\r\n';
            csv += 'Total Items Washed (pcs),' + totalItems + '\r\n\r\n';

            csv += 'Sale ID,Request ID,Customer Name,Phone Number,Branch,Service Type,Weight (kg),Items (pcs),Amount (PHP),Date & Time Completed\r\n';

            records.forEach(r => {
                const escapeCsv = (str) => '"' + String(str || '').replace(/"/g, '""') + '"';
                const row = [
                    escapeCsv(r.sale_id),
                    escapeCsv(r.request_code),
                    escapeCsv(r.customer),
                    escapeCsv(r.phone),
                    escapeCsv(r.branch),
                    escapeCsv(r.service_type),
                    Number(r.weight_kg).toFixed(1),
                    r.items_pcs,
                    Number(r.amount_php).toFixed(2),
                    escapeCsv(r.sold_at)
                ];
                csv += row.join(',') + '\r\n';
            });

            const blob = new Blob(["\uFEFF" + csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            const dateStr = now.toISOString().slice(0, 10);
            link.setAttribute('href', url);
            link.setAttribute('download', `spinzone_overall_sales_${periodLabel.toLowerCase().replace(/\\s+/g, '_')}_${dateStr}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }

        document.addEventListener('DOMContentLoaded', initSalesCharts);
    </script>
</x-admin-layout>

