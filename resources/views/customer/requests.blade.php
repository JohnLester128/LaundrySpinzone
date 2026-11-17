<x-customer-layout>
    <x-slot:title>My Requests — Spinzone Laundry</x-slot:title>

    <!-- Header Section -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">My Requests</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Track and manage all your ongoing and past laundry orders.</p>
        </div>
        <a class="bg-blue-600 hover:bg-blue-700 text-white rounded-2xl py-3 px-5 text-sm font-semibold shadow-md shadow-blue-600/20 inline-flex items-center gap-2 transition active:scale-[0.99]" href="{{ route('customer.service-requests') }}">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>New Request</span>
        </a>
    </div>

    <!-- Main Card Container -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
        <!-- Top Controls: Search Input + Filter Tabs with 3-Dots View/Sort Menu -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-100">
            <!-- Left Side: Search Bar -->
            <div class="relative w-full sm:w-64 lg:w-60 xl:w-72 shrink-0">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Search ID, cloth type, status..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
            </div>

            @php
                $statusFilterOptions = [
                    'all' => ['label' => 'All Orders', 'dot' => 'bg-slate-400', 'count' => $allRequests->count()],
                    'pending' => ['label' => 'Pending', 'dot' => 'bg-amber-500', 'count' => $allRequests->where('status', 'pending')->count()],
                    'received' => ['label' => 'Received', 'dot' => 'bg-slate-500', 'count' => $allRequests->where('status', 'received')->count()],
                    'washing' => ['label' => 'Washing', 'dot' => 'bg-purple-500', 'count' => $allRequests->where('status', 'washing')->count()],
                    'drying' => ['label' => 'Drying', 'dot' => 'bg-indigo-500', 'count' => $allRequests->where('status', 'drying')->count()],
                    'ready' => ['label' => 'Ready for Pickup', 'dot' => 'bg-teal-500', 'count' => $allRequests->whereIn('status', ['ready_for_pickup', 'ready'])->count()],
                    'completed' => ['label' => 'Completed', 'dot' => 'bg-emerald-500', 'count' => $allRequests->where('status', 'completed')->count()],
                    'cancelled' => ['label' => 'Cancelled', 'dot' => 'bg-rose-500', 'count' => $allRequests->where('status', 'cancelled')->count()],
                ];

                $singleWordSorts = [
                    'date' => [
                        'label' => 'Date',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'is_active' => in_array($sort, ['newest', 'oldest']) || empty($sort),
                        'next_sort' => ($sort === 'newest' || empty($sort)) ? 'oldest' : 'newest',
                        'dir_indicator' => ($sort === 'oldest') ? '▲ Oldest' : '▼ Newest',
                    ],
                    'status' => [
                        'label' => 'Status',
                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'is_active' => in_array($sort, ['status_asc', 'status_desc']),
                        'next_sort' => ($sort === 'status_asc') ? 'status_desc' : 'status_asc',
                        'dir_indicator' => ($sort === 'status_desc') ? '▼ DESC' : '▲ ASC',
                    ],
                    'weight' => [
                        'label' => 'Weight / Size',
                        'icon' => 'M3 6l3 18h12l3-18H3z',
                        'is_active' => in_array($sort, ['weight_desc', 'weight_asc']),
                        'next_sort' => ($sort === 'weight_desc') ? 'weight_asc' : 'weight_desc',
                        'dir_indicator' => ($sort === 'weight_asc') ? '▲ Lowest' : '▼ Highest',
                    ],
                    'items' => [
                        'label' => 'Items',
                        'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                        'is_active' => in_array($sort, ['items_desc', 'items_asc']),
                        'next_sort' => ($sort === 'items_desc') ? 'items_asc' : 'items_desc',
                        'dir_indicator' => ($sort === 'items_asc') ? '▲ Lowest' : '▼ Highest',
                    ],
                ];

                $hasActiveFilter = !empty($filter) && $filter !== 'all';
                $hasActiveSort = !empty($sort) && $sort !== 'newest';
            @endphp

            <!-- Right Controls: Filter, Sort By, Layout -->
            <div class="flex items-center justify-end gap-2 sm:gap-2.5 min-w-0 w-full sm:w-auto ml-auto">
                <!-- Active Filter & Sort Quick-Clear Badges (aligned to left on mobile) -->
                @if($hasActiveFilter)
                    <div class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50/90 px-2.5 py-1.5 text-xs font-bold text-blue-700 shadow-2xs mr-auto sm:mr-0">
                        <span>{{ ucfirst(str_replace('_', ' ', $filter)) }}</span>
                        <a href="{{ route('customer.requests', ['filter' => 'all', 'sort' => $sort]) }}" class="rounded-md p-0.5 text-blue-500 hover:bg-blue-200/60 hover:text-blue-800 transition" title="Clear filter">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>
                @endif
                @if($hasActiveSort)
                    @php
                        $activeSortLabel = match($sort) {
                            'oldest' => 'Oldest',
                            'weight_desc' => 'Weight (High)',
                            'weight_asc' => 'Weight (Low)',
                            'status_asc' => 'Status (A-Z)',
                            'status_desc' => 'Status (Z-A)',
                            'id_asc' => 'ID (Low)',
                            'id_desc' => 'ID (High)',
                            'items_asc', 'quantity_asc' => 'Qty (Low)',
                            'items_desc', 'quantity_desc' => 'Qty (High)',
                            'cloth_type_asc' => 'Type (A-Z)',
                            'cloth_type_desc' => 'Type (Z-A)',
                            default => 'Sorted'
                        };
                    @endphp
                    <div class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50/90 px-2.5 py-1.5 text-xs font-bold text-blue-700 shadow-2xs mr-auto sm:mr-0">
                        <span>{{ $activeSortLabel }}</span>
                        <a href="{{ route('customer.requests', ['filter' => $filter, 'sort' => 'newest']) }}" class="rounded-md p-0.5 text-blue-500 hover:bg-blue-200/60 hover:text-blue-800 transition" title="Clear sort">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>
                @endif

                <!-- 1. Filter Button (first / near center) -->
                <div class="relative inline-block text-left control-dropdown-wrapper shrink-0">
                    <button type="button" onclick="toggleControlPopover('filterMenuPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl {{ $hasActiveFilter ? 'bg-blue-50 border border-blue-200 text-blue-700 font-bold' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900' }} px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Filter by status">
                        <svg class="h-4 w-4 {{ $hasActiveFilter ? 'text-blue-600' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden sm:inline">Filter</span>
                        @if($hasActiveFilter)
                            <span class="hidden sm:inline rounded-md bg-blue-600 px-1.5 py-0.5 text-[10px] font-bold text-white uppercase leading-none">
                                {{ ucfirst(str_replace('_', ' ', $filter)) }}
                            </span>
                            <span class="sm:hidden absolute top-1 right-1 h-2 w-2 rounded-full bg-blue-600 ring-2 ring-white"></span>
                        @endif
                        <svg class="hidden sm:block h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Filter Popover -->
                    <div id="filterMenuPopover" class="control-popover hidden absolute right-0 mt-2 w-64 sm:w-72 max-w-[calc(100vw-1.5rem)] max-h-[calc(100vh-140px)] overflow-y-auto [scrollbar-width:thin] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>Filter Status</span>
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </div>
                        @foreach($statusFilterOptions as $key => $opt)
                            @php
                                $isActiveFilter = ($filter === $key) || ($key === 'all' && (empty($filter) || $filter === 'all'));
                            @endphp
                            <a href="{{ route('customer.requests', ['filter' => $key, 'sort' => $sort]) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $isActiveFilter ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full {{ $opt['dot'] }}"></span>
                                    <span>{{ $opt['label'] }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-500">
                                        {{ $opt['count'] }}
                                    </span>
                                    @if($isActiveFilter)
                                        <span class="text-blue-600 font-bold text-xs">✓</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- 2. Sort By Button (then sortby) -->
                <div class="relative inline-block text-left control-dropdown-wrapper shrink-0">
                    <button type="button" onclick="toggleControlPopover('sortMenuPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl {{ $hasActiveSort ? 'bg-blue-50 border border-blue-200 text-blue-700 font-bold' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900' }} px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Sort orders">
                        <svg class="h-4 w-4 {{ $hasActiveSort ? 'text-blue-600' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                        </svg>
                        <span class="hidden sm:inline">Sort By</span>
                        @if($hasActiveSort)
                            <span class="hidden sm:inline-block h-2 w-2 rounded-full bg-blue-600"></span>
                            <span class="sm:hidden absolute top-1 right-1 h-2 w-2 rounded-full bg-blue-600 ring-2 ring-white"></span>
                        @endif
                        <svg class="hidden sm:block h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Sort Popover -->
                    <div id="sortMenuPopover" class="control-popover hidden absolute right-0 mt-2 w-60 sm:w-64 max-w-[calc(100vw-1.5rem)] max-h-[calc(100vh-140px)] overflow-y-auto [scrollbar-width:thin] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>Sort By</span>
                            @if($hasActiveSort)
                                <a href="{{ route('customer.requests', ['filter' => $filter, 'sort' => 'newest']) }}" class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-500 hover:text-rose-700 transition" title="Clear sort">
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
                        @foreach($singleWordSorts as $key => $opt)
                            <a href="{{ route('customer.requests', ['filter' => $filter, 'sort' => $opt['next_sort']]) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $opt['is_active'] ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
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
                                        <span onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('customer.requests', ['filter' => $filter, 'sort' => 'newest']) }}';" class="p-0.5 rounded text-blue-500 hover:bg-rose-100 hover:text-rose-600 transition cursor-pointer" title="Clear sort">
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
                <div class="relative inline-block text-left control-dropdown-wrapper shrink-0">
                    <button type="button" onclick="toggleControlPopover('layoutMenuPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900 px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Switch View Layout">
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="hidden sm:inline">Layout</span>
                        <svg class="hidden sm:block h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Layout Popover -->
                    <div id="layoutMenuPopover" class="control-popover hidden absolute right-0 mt-2 w-48 sm:w-52 max-w-[calc(100vw-1.5rem)] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>View Layout</span>
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </div>
                        <button type="button" onclick="changeView('grid')" class="view-opt-btn flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer" data-view="grid">
                            <div class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4z" />
                                </svg>
                                <span>Grid Cards</span>
                            </div>
                            <span class="view-check-grid text-blue-600 font-bold hidden">✓</span>
                        </button>
                        <button type="button" onclick="changeView('table')" class="view-opt-btn flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer" data-view="table">
                            <div class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M3 18h18M3 6h18" />
                                </svg>
                                <span>Details Table</span>
                            </div>
                            <span class="view-check-table text-blue-600 font-bold hidden">✓</span>
                        </button>
                    </div>
                </div>

                <!-- Active Filter / Sort Quick-Clear (Only visible when a filter or sort is active) -->
                @if($hasActiveFilter || $hasActiveSort)
                    <a href="{{ route('customer.requests', ['filter' => 'all', 'sort' => 'newest']) }}" class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-slate-100/80 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition cursor-pointer active:scale-95 shadow-2xs shrink-0" title="Clear filter and sort">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <!-- Requests Grid View -->
        <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3 transition-all duration-300" id="requestsGrid">
            @forelse($requests as $request)
                @php
                    $cCycle = in_array($request->status, ['washing', 'drying']) ? $request->cycle_info : null;
                    $isWashBuffer = ($request->status === 'washing' && ($request->cycle_phase === 'resting' || ($cCycle && $cCycle['phase'] === 'resting')));
                    $isFolding = ($request->status === 'drying' && ($request->cycle_phase === 'folding' || ($cCycle && $cCycle['phase'] === 'folding')));

                    $accentColor = match($request->status) {
                        'completed' => 'bg-emerald-500',
                        'washing' => $isWashBuffer ? 'bg-amber-500' : 'bg-purple-600',
                        'drying' => ($isFolding ? 'bg-teal-500' : 'bg-indigo-600'),
                        'ready_for_pickup', 'ready' => 'bg-teal-500',
                        'received' => 'bg-slate-500',
                        'cancelled' => 'bg-rose-500',
                        default => 'bg-amber-500'
                    };

                    $badgeClass = match($request->status) {
                        'completed' => 'bg-emerald-100 text-emerald-700',
                        'washing' => $isWashBuffer ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-purple-700',
                        'drying' => ($isFolding ? 'bg-teal-100 text-teal-800' : 'bg-indigo-100 text-indigo-700'),
                        'ready_for_pickup', 'ready' => 'bg-teal-100 text-teal-700',
                        'received' => 'bg-slate-100 text-slate-700',
                        'pending' => 'bg-amber-100 text-amber-700',
                        'cancelled' => 'bg-rose-100 text-rose-700',
                        default => 'bg-slate-100 text-slate-600'
                    };

                    $statusLabel = match($request->status) {
                        'completed' => 'Completed',
                        'washing' => $isWashBuffer ? 'Wash Buffer' : 'Washing',
                        'drying' => ($isFolding ? 'Folding' : 'Drying'),
                        'ready_for_pickup', 'ready' => 'Ready',
                        'received' => 'Received',
                        'pending' => 'Pending',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($request->status)
                    };

                    $statusGroup = match($request->status) {
                        'ready_for_pickup' => 'ready',
                        default => $request->status
                    };
                @endphp

                <div class="request-card relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:shadow-md duration-200 flex flex-col justify-between" data-id="{{ $request->id }}" data-status="{{ $statusGroup }}" data-search="{{ strtolower($request->id . ' ' . ($request->clothType->name ?? '') . ' ' . $request->status) }}">
                    <div class="absolute top-0 inset-x-0 h-1 {{ $accentColor }}"></div>

                    <div>
                        <!-- Request ID & Status Badge -->
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                @if(in_array($request->status, ['washing', 'drying']) && !$isFolding && $cCycle)
                                    @php 
                                        $isCBuffer = ($cCycle['phase'] === 'resting');
                                    @endphp
                                    <span class="inline-flex items-center gap-1 rounded-full {{ $isCBuffer ? 'bg-amber-600' : 'bg-slate-900' }} text-white px-2 py-0.5 text-[10px] font-mono font-bold shadow-2xs">
                                        @if($isCBuffer) Buffer: @endif<span data-cycle-timer data-remaining="{{ (int)$cCycle['remaining_seconds'] }}" data-paused="{{ $cCycle['is_paused'] ? '1' : '0' }}">{{ $cCycle['display_time'] }}</span>
                                    </span>
                                @endif
                                <span class="inline-block rounded-full px-3 py-1 text-[11px] font-semibold {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>

                        <!-- Main Item Name -->
                        <div class="mt-3">
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">
                                {{ $request->clothType->name ?? 'Standard Laundry' }}
                            </h3>
                        </div>

                        <!-- Specifications Grid -->
                        <div class="mt-4 grid grid-cols-2 gap-2 rounded-xl bg-slate-50/70 p-3 text-xs border border-slate-100">
                            <div>
                                <span class="font-mono text-[10px] text-slate-400 uppercase tracking-wider block">Quantity</span>
                                <span class="font-bold text-slate-800">{{ $request->quantity ?? 1 }} item(s)</span>
                            </div>
                            <div>
                                <span class="font-mono text-[10px] text-slate-400 uppercase tracking-wider block">Weight / Size</span>
                                <span class="font-bold text-slate-800">
                                    @php
                                        $cWeight = $request->actual_weight ?? $request->estimated_weight;
                                    @endphp
                                    @if($request->comforter_size)
                                        {{ $request->comforter_size }}
                                        @if($cWeight && $cWeight > 0)
                                            <span class="text-xs font-semibold text-slate-500 font-mono">({{ ($cWeight + 0) }} kg)</span>
                                        @endif
                                    @elseif($cWeight)
                                        {{ ($cWeight + 0) }} kg
                                    @else
                                        Standard
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Price & Action Button -->
                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                        <div>
                            <span class="font-mono text-[10px] text-slate-400 uppercase tracking-wider block">Estimated Total</span>
                            <span class="font-mono text-base font-extrabold text-blue-700">
                                ₱{{ number_format($request->estimated_price, 2) }}
                            </span>
                        </div>
                        <a href="{{ route('customer.requests.show', $request) }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200/90 bg-white px-3.5 py-1.5 text-xs font-bold text-slate-800 hover:border-blue-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-2xs group">
                            <span>Details</span>
                            <svg class="h-3.5 w-3.5 text-slate-600 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-800">No requests found</p>
                    <p class="text-xs text-slate-400 mt-1">There are no laundry requests matching the selected criteria.</p>
                </div>
            @endforelse
        </div>

        <!-- Requests Table View (Details Table) -->
        <div class="mt-6 hidden overflow-hidden rounded-2xl border border-slate-200 transition-all duration-300" id="requestsTableWrapper">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 font-mono uppercase tracking-wider text-slate-400 border-b border-slate-200/80">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Request ID</th>
                            <th class="px-5 py-3 font-semibold">Cloth Type</th>
                            <th class="px-5 py-3 font-semibold">Weight / Size</th>
                            <th class="px-5 py-3 font-semibold">Qty</th>
                            <th class="px-5 py-3 font-semibold">Price</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($requests as $request)
                            @php
                                $tCycle = in_array($request->status, ['washing', 'drying']) ? $request->cycle_info : null;
                                $isTableWashBuffer = ($request->status === 'washing' && ($request->cycle_phase === 'resting' || ($tCycle && $tCycle['phase'] === 'resting')));
                                $isTableFolding = ($request->status === 'drying' && ($request->cycle_phase === 'folding' || ($tCycle && $tCycle['phase'] === 'folding')));

                                $badgeClass = match($request->status) {
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'washing' => $isTableWashBuffer ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-purple-700',
                                    'drying' => ($isTableFolding ? 'bg-teal-100 text-teal-800' : 'bg-indigo-100 text-indigo-700'),
                                    'ready_for_pickup', 'ready' => 'bg-teal-100 text-teal-700',
                                    'received' => 'bg-slate-100 text-slate-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'cancelled' => 'bg-rose-100 text-rose-700',
                                    default => 'bg-slate-100 text-slate-600'
                                };

                                $statusLabel = match($request->status) {
                                    'completed' => 'Completed',
                                    'washing' => $isTableWashBuffer ? 'Wash Buffer' : 'Washing',
                                    'drying' => ($isTableFolding ? 'Folding' : 'Drying'),
                                    'ready_for_pickup', 'ready' => 'Ready',
                                    'received' => 'Received',
                                    'pending' => 'Pending',
                                    'cancelled' => 'Cancelled',
                                    default => ucfirst($request->status)
                                };

                                $statusGroup = match($request->status) {
                                    'ready_for_pickup' => 'ready',
                                    default => $request->status
                                };
                            @endphp
                            <tr class="request-table-row hover:bg-slate-50/60 transition" data-id="{{ $request->id }}" data-status="{{ $statusGroup }}" data-search="{{ strtolower($request->id . ' ' . ($request->clothType->name ?? '') . ' ' . $request->status) }}">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900">
                                    #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-slate-900">
                                    {{ $request->clothType->name ?? 'Standard' }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 font-medium whitespace-nowrap">
                                    @php
                                        $cWeight = $request->actual_weight ?? $request->estimated_weight;
                                    @endphp
                                    @if($request->comforter_size)
                                        <span class="font-semibold text-purple-700">{{ $request->comforter_size }}</span>
                                        @if($cWeight && $cWeight > 0)
                                            <span class="text-xs font-semibold text-slate-500 font-mono">({{ ($cWeight + 0) }} kg)</span>
                                        @endif
                                    @elseif($cWeight)
                                        {{ ($cWeight + 0) }} kg
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 font-medium">
                                    {{ $request->quantity ?? 1 }}
                                </td>
                                <td class="px-5 py-3.5 font-mono font-bold text-blue-700">
                                    ₱{{ number_format($request->estimated_price, 2) }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                        @if(in_array($request->status, ['washing', 'drying']) && !$isTableFolding && $tCycle)
                                            @php 
                                                $isTBuffer = ($tCycle['phase'] === 'resting');
                                            @endphp
                                            <span class="inline-flex items-center gap-1 rounded-md {{ $isTBuffer ? 'bg-amber-600' : 'bg-slate-900' }} text-white px-1.5 py-0.5 text-[10px] font-mono font-bold shadow-2xs">
                                                @if($isTBuffer) Buffer: @endif<span data-cycle-timer data-remaining="{{ (int)$tCycle['remaining_seconds'] }}" data-paused="{{ $tCycle['is_paused'] ? '1' : '0' }}">{{ $tCycle['display_time'] }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-right">
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
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400 font-medium">
                                    No requests found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Bar -->
        <x-pagination id="customerRequestsPagination" :pageSize="10" :pageSizes="[10, 20, 50]" />
    </div>

    <!-- Interactive Scripts for View Toggle, Instant Search & Popovers -->
    <script>
        let customerRequestsPaginator;

        // 1. Popover Menu Toggle
        function toggleControlPopover(targetId, event) {
            if (event) event.stopPropagation();
            const target = document.getElementById(targetId);
            if (!target) return;
            const isCurrentlyOpen = !target.classList.contains('hidden');

            // Close customer notification popover if open
            const notif = document.getElementById('customerNotifPopover');
            if (notif) notif.classList.add('hidden');

            // Close all open popovers first and reset wrapper z-index
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

        // Backward-compatibility alias
        function toggleDropdown(menuId, event) {
            toggleControlPopover(menuId, event);
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

        // 2. View Mode Toggle (Grid vs Table)
        function changeView(viewMode) {
            const grid = document.getElementById('requestsGrid');
            const table = document.getElementById('requestsTableWrapper');
            const checkGrid = document.querySelector('.view-check-grid');
            const checkTable = document.querySelector('.view-check-table');

            if (viewMode === 'table') {
                if (grid) grid.classList.add('hidden');
                if (table) table.classList.remove('hidden');
                if (checkGrid) checkGrid.classList.add('hidden');
                if (checkTable) checkTable.classList.remove('hidden');
                localStorage.setItem('customer_requests_view', 'table');
            } else {
                if (table) table.classList.add('hidden');
                if (grid) grid.classList.remove('hidden');
                if (checkTable) checkTable.classList.add('hidden');
                if (checkGrid) checkGrid.classList.remove('hidden');
                localStorage.setItem('customer_requests_view', 'grid');
            }

            const popover = document.getElementById('layoutMenuPopover');
            if (popover) {
                popover.classList.add('hidden');
                popover.style.transform = '';
                const wrapper = popover.closest('.control-dropdown-wrapper');
                if (wrapper) wrapper.classList.remove('z-40');
            }
        }

        // Initialize preferred view & pagination
        document.addEventListener('DOMContentLoaded', () => {
            const savedView = localStorage.getItem('customer_requests_view') || 'grid';
            changeView(savedView);

            customerRequestsPaginator = new ClientPaginator({
                containerId: 'customerRequestsPagination',
                pageSize: 10,
                getItems: () => {
                    const query = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
                    const cards = Array.from(document.querySelectorAll('#requestsGrid .request-card'));
                    return cards.filter(card => {
                        const searchData = card.getAttribute('data-search') || '';
                        return !query || searchData.includes(query);
                    });
                },
                onPageChange: (visibleSlice) => {
                    const visibleIds = new Set(visibleSlice.map(c => c.getAttribute('data-id')));
                    document.querySelectorAll('#requestsGrid .request-card').forEach(card => {
                        card.style.display = visibleIds.has(card.getAttribute('data-id')) ? '' : 'none';
                    });
                    document.querySelectorAll('#requestsTableWrapper .request-table-row').forEach(row => {
                        row.style.display = visibleIds.has(row.getAttribute('data-id')) ? '' : 'none';
                    });
                }
            });
        });

        // 3. Instant Live Search
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                if (customerRequestsPaginator) {
                    customerRequestsPaginator.reset();
                }
            });
        }

        // Live Customer Cycle Countdown Ticker
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

            // Real-time Status Sync for Order List
            const initialReqStates = {
                @foreach($allRequests->whereNotIn('status', ['completed', 'cancelled']) as $r)
                    '{{ $r->id }}': {
                        status: '{{ $r->status }}',
                        cycle_phase: '{{ $r->cycle_phase }}',
                        weight_verification_status: '{{ $r->weight_verification_status }}'
                    },
                @endforeach
            };
            const initialReqActiveCount = Object.keys(initialReqStates).length;
            let reqsReloading = false;

            window.addEventListener('spinzone:tracker-sync', function(e) {
                const list = (e.detail && e.detail.activeRequests) || [];
                if (list.length !== initialReqActiveCount) {
                    if (!reqsReloading) {
                        reqsReloading = true;
                        sessionStorage.setItem('spinzone_tracker_scroll', window.scrollY);
                        window.location.reload();
                    }
                    return;
                }
                for (let i = 0; i < list.length; i++) {
                    const item = list[i];
                    const prev = initialReqStates[String(item.id)];
                    if (!prev || prev.status !== item.status || prev.cycle_phase !== item.cycle_phase || (prev.weight_verification_status || '') !== (item.weight_verification_status || '')) {
                        if (!reqsReloading) {
                            reqsReloading = true;
                            sessionStorage.setItem('spinzone_tracker_scroll', window.scrollY);
                            window.location.reload();
                        }
                        return;
                    }
                }
            });
        })();
    </script>
</x-customer-layout>
