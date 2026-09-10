<x-admin-layout>
    <!-- Page Header & Period Switcher Bar -->
    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <div>
            <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Financial Overview</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">Sales Dashboard</h1>
        </div>

        <!-- Period Filter Pills + Export Button -->
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1 rounded-2xl border border-slate-200/80 bg-white p-1 shadow-2xs">
                <a href="{{ route('admin.sales', ['period' => 'today']) }}" class="rounded-xl px-4 py-1.5 text-xs font-bold transition {{ ($period ?? 'today') === 'today' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Today
                </a>
                <a href="{{ route('admin.sales', ['period' => 'week']) }}" class="rounded-xl px-4 py-1.5 text-xs font-semibold transition {{ ($period ?? '') === 'week' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Week
                </a>
                <a href="{{ route('admin.sales', ['period' => 'month']) }}" class="rounded-xl px-4 py-1.5 text-xs font-semibold transition {{ ($period ?? '') === 'month' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Month
                </a>
            </div>

            <button type="button" onclick="window.print()" class="flex h-9 w-9 items-center justify-center rounded-2xl border border-slate-200/80 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition shadow-2xs" title="Export / Print Report">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Top Row: 3 Metric Stat Cards -->
    <div class="grid gap-6 sm:grid-cols-3 mb-6">
        <!-- Card 1: Total Sales -->
        <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition hover:shadow-md duration-200">
            <div class="absolute top-0 inset-x-0 h-1.5 bg-blue-600"></div>
            <div class="flex items-center justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100/80 text-blue-600 shadow-2xs">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200/50">
                    ↗ +12.5%
                </span>
            </div>
            <p class="text-xs font-semibold text-slate-400 mt-4 uppercase tracking-wider font-mono">Total Sales</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 tracking-tight">
                PHP {{ number_format($totalSales, 0) }}
            </p>
        </div>

        <!-- Card 2: Transactions -->
        <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition hover:shadow-md duration-200">
            <div class="absolute top-0 inset-x-0 h-1.5 bg-purple-600"></div>
            <div class="flex items-center justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-purple-100/80 text-purple-600 shadow-2xs">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200/50">
                    ↗ +5.2%
                </span>
            </div>
            <p class="text-xs font-semibold text-slate-400 mt-4 uppercase tracking-wider font-mono">Transactions</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 tracking-tight">
                {{ number_format($transactionCount) }}
            </p>
        </div>

        <!-- Card 3: Average Sale -->
        <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition hover:shadow-md duration-200">
            <div class="absolute top-0 inset-x-0 h-1.5 bg-teal-500"></div>
            <div class="flex items-center justify-between">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-teal-100/80 text-teal-600 shadow-2xs">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 border border-rose-200/50">
                    ↘ -1.1%
                </span>
            </div>
            <p class="text-xs font-semibold text-slate-400 mt-4 uppercase tracking-wider font-mono">Average Sale</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 tracking-tight">
                PHP {{ number_format($averageSale, 0) }}
            </p>
        </div>
    </div>

    <!-- Main Content Container: Completed Sales (Matching Service Requests List Format) -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
        <!-- Section Header & Controls Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-slate-900">Completed Sales</h2>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">All completed transactions for verified service requests.</p>
            </div>

            <!-- Controls: Search + 3-Dots Options Menu -->
            <div class="flex items-center gap-3">
                <!-- Search Input -->
                <div class="relative w-full sm:w-64">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="salesTableSearch" placeholder="Search customer, ID, service..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-9 pr-4 py-2 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs">
                </div>

                <!-- 3-Dots Options Menu Popover -->
                <div class="relative inline-block text-left" id="salesOptionsWrapper">
                    <button type="button" onclick="document.getElementById('salesOptionsPopover').classList.toggle('hidden')" class="flex h-9 w-9 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition shadow-2xs active:scale-95" title="View Options & Sort">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01" />
                        </svg>
                    </button>

                    <!-- Light Popover Panel -->
                    <div id="salesOptionsPopover" class="hidden absolute right-0 mt-2 w-64 rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                        <!-- View / Layout Section -->
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>View Layout</span>
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </div>
                        <button type="button" onclick="switchView('cards')" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100/80 hover:text-slate-900 transition">
                            <div class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4z" />
                                </svg>
                                <span>Grid Cards</span>
                            </div>
                            <span id="checkCards" class="hidden text-blue-600 font-bold">✓</span>
                        </button>
                        <button type="button" onclick="switchView('table')" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100/80 hover:text-slate-900 transition">
                            <div class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M3 18h18M3 6h18" />
                                </svg>
                                <span>Details Table</span>
                            </div>
                            <span id="checkTable" class="text-blue-600 font-bold">✓</span>
                        </button>

                        <!-- Divider -->
                        <div class="my-1.5 border-t border-slate-100"></div>

                        <!-- Sort Order Section -->
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>Sort By</span>
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                        </div>
                        @php
                            $salesSortOpts = [
                                'newest' => 'Date (Newest first)',
                                'oldest' => 'Date (Oldest first)',
                                'name_asc' => 'Customer Name (A to Z)',
                                'name_desc' => 'Customer Name (Z to A)',
                                'price_desc' => 'Price (Highest first)',
                                'price_asc' => 'Price (Lowest first)',
                                'id_asc' => 'Request ID (Ascending)',
                                'id_desc' => 'Request ID (Descending)',
                            ];
                        @endphp
                        @foreach($salesSortOpts as $key => $lbl)
                            <a href="{{ route('admin.sales', ['period' => $period, 'sort' => $key]) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ ($sort ?? 'newest') === $key ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <span>{{ $lbl }}</span>
                                @if(($sort ?? 'newest') === $key)
                                    <span class="text-blue-600 font-bold text-sm">✓</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
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
                <div class="sales-card-item relative flex flex-col justify-between overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-5 shadow-2xs transition hover:border-blue-300 hover:shadow-md duration-200" data-search="{{ strtolower($username . ' ' . $sale->service_request_id . ' ' . $sale->id . ' ' . $clothType) }}">
                    <div class="absolute top-0 inset-x-0 h-1 bg-emerald-500"></div>

                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="font-mono text-xs font-bold text-slate-500">
                                #REQ-{{ str_pad($sale->service_request_id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200/60">
                                ✓ Completed
                            </span>
                        </div>

                        <div class="flex items-center gap-2.5 mb-4">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-xs">
                                {{ $initial }}
                            </div>
                            <div>
                                <p class="font-extrabold text-sm text-slate-900 leading-snug">{{ $username }}</p>
                                <p class="text-[11px] font-medium text-slate-400">{{ $clothType }}</p>
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

                        <a href="{{ route('admin.service-requests.show', $sale->service_request_id) }}" class="inline-flex items-center gap-1 rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-2xs">
                            <span>Details</span>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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
                            <a href="{{ route('admin.sales', ['period' => $period, 'sort' => $nameNextSort]) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Customer Name">
                                <span>Customer</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'name_asc') ▲ @elseif ($sort === 'name_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <th class="px-4 py-3.5">Service Type</th>
                        <th class="px-4 py-3.5">Weight</th>
                        <th class="px-4 py-3.5">Items</th>

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
                        <tr class="sales-row-item hover:bg-blue-50/30 transition duration-150" data-search="{{ strtolower($username . ' ' . $sale->service_request_id . ' ' . $sale->id . ' ' . $clothType) }}">
                            <!-- Request ID -->
                            <td class="px-4 py-4 whitespace-nowrap font-mono text-xs font-bold text-slate-900">
                                #REQ-{{ str_pad($sale->service_request_id, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <!-- Customer -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-xs">
                                        {{ $initial }}
                                    </div>
                                    <span class="font-bold text-slate-900">{{ $username }}</span>
                                </div>
                            </td>

                            <!-- Service Type -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-xl bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 border border-slate-200/60">
                                    {{ $clothType }}
                                </span>
                            </td>

                            <!-- Weight -->
                            <td class="px-4 py-4 whitespace-nowrap font-mono text-xs text-slate-600">
                                {{ number_format($weight, 0) }} kg
                            </td>

                            <!-- Items -->
                            <td class="px-4 py-4 whitespace-nowrap font-mono text-xs text-slate-600">
                                {{ $quantity }} pcs
                            </td>

                            <!-- Date Sold -->
                            <td class="px-4 py-4 whitespace-nowrap text-xs text-slate-500">
                                {{ $sale->sold_at ? $sale->sold_at->format('M d, Y · h:i A') : 'Today' }}
                            </td>

                            <!-- Final Sales Price -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center font-extrabold text-sm text-emerald-600 bg-emerald-50 border border-emerald-200/60 px-3 py-1 rounded-xl shadow-2xs">
                                    PHP {{ number_format($sale->amount, 0) }}
                                </span>
                            </td>

                            <!-- Action Link -->
                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.service-requests.show', $sale->service_request_id) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600 transition shadow-2xs">
                                    <span>Details</span>
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-3 text-sm font-semibold text-slate-600">No completed sales found</p>
                                <p class="text-xs text-slate-400 mt-1">Completed orders will automatically populate here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary Footer Bar (Matching Service Requests List) -->
        <div class="flex flex-wrap items-center justify-between gap-4 pt-5 mt-4 border-t border-slate-100">
            <p class="text-xs font-semibold text-slate-500">
                Showing <span class="font-extrabold text-slate-900" id="salesVisibleCount">{{ count($sales) }}</span> of <span class="font-extrabold text-slate-900">{{ count($sales) }}</span> sales entries
            </p>

            <div class="flex items-center gap-1">
                <button type="button" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-400 cursor-not-allowed" disabled>
                    Previous
                </button>
                <button type="button" class="rounded-xl bg-blue-600 px-3 py-1.5 text-xs font-bold text-white shadow-2xs">
                    1
                </button>
                <button type="button" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-400 cursor-not-allowed" disabled>
                    Next
                </button>
            </div>
        </div>
    </div>

    <!-- Client-side Search & View Switcher Script -->
    <script>
        document.getElementById('salesTableSearch')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            let count = 0;
            document.querySelectorAll('.sales-row-item').forEach(row => {
                const searchData = row.getAttribute('data-search') || '';
                const match = searchData.includes(query);
                row.style.display = match ? '' : 'none';
                if (match) count++;
            });
            document.querySelectorAll('.sales-card-item').forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                card.style.display = searchData.includes(query) ? '' : 'none';
            });
            const visibleCountEl = document.getElementById('salesVisibleCount');
            if (visibleCountEl) visibleCountEl.textContent = count;
        });

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
            document.getElementById('salesOptionsPopover')?.classList.add('hidden');
        }

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('salesOptionsWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('salesOptionsPopover')?.classList.add('hidden');
            }
        });
    </script>
</x-admin-layout>

