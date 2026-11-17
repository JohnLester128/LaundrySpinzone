<x-admin-layout>
    <x-slot:title>Service Requests - Laundry Spinzone</x-slot:title>
    <x-slot:header>Service Requests Management</x-slot:header>

    <div class="space-y-6">
        <!-- Notification Component -->
        <x-notification />

        <!-- Header Controls Row: Title + New Request Button -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Service Requests</h1>
                <p class="text-xs text-slate-500 mt-0.5">Manage and track all ongoing laundry operations.</p>
            </div>
            @if(!auth('admin')->user()?->isSuperAdmin())
            <div>
                <a class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-2.5 text-xs font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 transition cursor-pointer active:scale-95" href="{{ route('admin.service-requests.create') }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>New Request</span>
                </a>
            </div>
            @endif
        </div>

        <!-- Metrics Overview Row (Compact 4-col) -->
        <div class="grid grid-cols-4 gap-2 sm:gap-3">
            <!-- Total Requests -->
            <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white p-2 sm:p-3 lg:p-4 shadow-sm transition-all duration-200 hover:shadow-md">
                <div class="absolute top-0 inset-x-0 h-0.5 sm:h-1 bg-blue-600"></div>
                <div class="flex items-center justify-between gap-1 mb-1 sm:mb-1.5">
                    <p class="font-mono text-[8px] sm:text-[10px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">Total</p>
                    <div class="flex h-4 w-4 sm:h-6 sm:w-6 lg:h-8 lg:w-8 items-center justify-center rounded sm:rounded-lg bg-blue-600 text-white shrink-0">
                        <svg class="h-2 w-2 sm:h-3 sm:w-3 lg:h-4 lg:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm sm:text-lg lg:text-2xl font-extrabold tracking-tight text-slate-900">
                    {{ $allRequests->count() }}
                </p>
                <div class="hidden sm:flex mt-1 items-center text-[10px] lg:text-xs font-medium text-slate-400">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-500 mr-1"></span>
                    <span class="truncate">All requests</span>
                </div>
            </div>

            <!-- In Progress -->
            <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white p-2 sm:p-3 lg:p-4 shadow-sm transition-all duration-200 hover:shadow-md">
                <div class="absolute top-0 inset-x-0 h-0.5 sm:h-1 bg-purple-600"></div>
                <div class="flex items-center justify-between gap-1 mb-1 sm:mb-1.5">
                    <p class="font-mono text-[8px] sm:text-[10px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">Active</p>
                    <div class="flex h-4 w-4 sm:h-6 sm:w-6 lg:h-8 lg:w-8 items-center justify-center rounded sm:rounded-lg bg-purple-600 text-white shrink-0">
                        <svg class="h-2 w-2 sm:h-3 sm:w-3 lg:h-4 lg:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm sm:text-lg lg:text-2xl font-extrabold tracking-tight text-slate-900">
                    {{ $allRequests->whereIn('status', ['pending', 'received', 'washing', 'drying'])->count() }}
                </p>
                <div class="hidden sm:flex mt-1 items-center text-[10px] lg:text-xs font-medium text-slate-400">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-purple-500 mr-1"></span>
                    <span class="truncate">In progress</span>
                </div>
            </div>

            <!-- Ready for Pickup -->
            <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white p-2 sm:p-3 lg:p-4 shadow-sm transition-all duration-200 hover:shadow-md">
                <div class="absolute top-0 inset-x-0 h-0.5 sm:h-1 bg-teal-600"></div>
                <div class="flex items-center justify-between gap-1 mb-1 sm:mb-1.5">
                    <p class="font-mono text-[8px] sm:text-[10px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">Ready</p>
                    <div class="flex h-4 w-4 sm:h-6 sm:w-6 lg:h-8 lg:w-8 items-center justify-center rounded sm:rounded-lg bg-teal-600 text-white shrink-0">
                        <svg class="h-2 w-2 sm:h-3 sm:w-3 lg:h-4 lg:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm sm:text-lg lg:text-2xl font-extrabold tracking-tight text-slate-900">
                    {{ $allRequests->whereIn('status', ['ready_for_pickup', 'ready'])->count() }}
                </p>
                <div class="hidden sm:flex mt-1 items-center text-[10px] lg:text-xs font-medium text-slate-400">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-teal-500 mr-1"></span>
                    <span class="truncate">Pickup</span>
                </div>
            </div>

            <!-- Completed -->
            <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white p-2 sm:p-3 lg:p-4 shadow-sm transition-all duration-200 hover:shadow-md">
                <div class="absolute top-0 inset-x-0 h-0.5 sm:h-1 bg-emerald-600"></div>
                <div class="flex items-center justify-between gap-1 mb-1 sm:mb-1.5">
                    <p class="font-mono text-[8px] sm:text-[10px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">Done</p>
                    <div class="flex h-4 w-4 sm:h-6 sm:w-6 lg:h-8 lg:w-8 items-center justify-center rounded sm:rounded-lg bg-emerald-600 text-white shrink-0">
                        <svg class="h-2 w-2 sm:h-3 sm:w-3 lg:h-4 lg:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm sm:text-lg lg:text-2xl font-extrabold tracking-tight text-slate-900">
                    {{ $allRequests->where('status', 'completed')->count() }}
                </p>
                <div class="hidden sm:flex mt-1 items-center text-[10px] lg:text-xs font-medium text-slate-400">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500 mr-1"></span>
                    <span class="truncate">Completed</span>
                </div>
            </div>
        </div>

        <!-- Main Card Container -->
        <div class="rounded-2xl sm:rounded-3xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-sm">
            <!-- Top Controls: Search Input + Filter Tabs with 3-Dots View/Sort Menu -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-100 mb-6">
                <!-- Left Side: Search Bar -->
                <div class="relative w-full sm:w-80">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Search customer, ID..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                </div>

            @php
                $isOwner = auth('admin')->user()?->isSuperAdmin();
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
                    'customer' => [
                        'label' => 'Customer',
                        'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'is_active' => in_array($sort, ['name_asc', 'name_desc']),
                        'next_sort' => ($sort === 'name_asc') ? 'name_desc' : 'name_asc',
                        'dir_indicator' => ($sort === 'name_desc') ? '▼ Z-A' : '▲ A-Z',
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
                ];

                if ($isOwner) {
                    $singleWordSorts['branch'] = [
                        'label' => 'Branch',
                        'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                        'is_active' => in_array($sort, ['branch_asc', 'branch_desc']),
                        'next_sort' => ($sort === 'branch_asc') ? 'branch_desc' : 'branch_asc',
                        'dir_indicator' => ($sort === 'branch_desc') ? '▼ Z-A' : '▲ A-Z',
                    ];
                } else {
                    $singleWordSorts['quantity'] = [
                        'label' => 'Quantity',
                        'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                        'is_active' => in_array($sort, ['items_desc', 'items_asc', 'quantity_desc', 'quantity_asc']),
                        'next_sort' => ($sort === 'items_desc' || $sort === 'quantity_desc') ? 'quantity_asc' : 'quantity_desc',
                        'dir_indicator' => ($sort === 'items_asc' || $sort === 'quantity_asc') ? '▲ Lowest' : '▼ Highest',
                    ];
                }

                $singleWordSorts['cloth_type'] = [
                    'label' => 'Cloth Type',
                    'icon' => 'M7 7h10M7 11h10M7 15h6',
                    'is_active' => in_array($sort, ['cloth_type_asc', 'cloth_type_desc']),
                    'next_sort' => ($sort === 'cloth_type_asc') ? 'cloth_type_desc' : 'cloth_type_asc',
                    'dir_indicator' => ($sort === 'cloth_type_desc') ? '▼ Z-A' : '▲ A-Z',
                ];

                $hasActiveFilter = (!empty($filter) && $filter !== 'all') || !empty($branchFilter);
                $hasActiveSort = !empty($sort) && $sort !== 'newest';
            @endphp

            <!-- Right Controls: Filter, Sort By, Layout -->
            <div class="flex items-center justify-end gap-2 sm:gap-2.5 min-w-0 w-full sm:w-auto ml-auto">
                <!-- 1. Filter Button (first / near center) -->
                <div class="relative inline-block text-left control-dropdown-wrapper shrink-0">
                    <button type="button" onclick="toggleControlPopover('filterMenuPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl {{ $hasActiveFilter ? 'bg-blue-50 border border-blue-200 text-blue-700 font-bold' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900' }} px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Filter by status">
                        <svg class="h-4 w-4 {{ $hasActiveFilter ? 'text-blue-600' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden sm:inline">Filter</span>
                        @if($hasActiveFilter)
                            <span class="hidden sm:inline-block h-2 w-2 rounded-full {{ $statusFilterOptions[$filter]['dot'] ?? 'bg-blue-600' }}"></span>
                            <span class="sm:hidden absolute top-1 right-1 h-2 w-2 rounded-full {{ $statusFilterOptions[$filter]['dot'] ?? 'bg-blue-600' }} ring-2 ring-white"></span>
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
                            <a href="{{ route('admin.service-requests', array_merge(request()->query(), ['filter' => $key])) }}" onclick="localStorage.setItem('admin_sr_filter', '{{ $key }}');" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $isActiveFilter ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
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

                        @if($isOwner && isset($branches) && $branches->isNotEmpty())
                            <div class="my-1.5 border-t border-slate-100"></div>
                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                <span>Branch Scope</span>
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <a href="{{ route('admin.service-requests', array_merge(request()->query(), ['branch' => 'all'])) }}" onclick="localStorage.removeItem('admin_sr_branch');" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ empty($branchFilter) ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <span>All Branches (HQ)</span>
                                @if(empty($branchFilter))
                                    <span class="text-blue-600 font-bold text-xs">✓</span>
                                @endif
                            </a>
                            @foreach($branches as $b)
                                <a href="{{ route('admin.service-requests', array_merge(request()->query(), ['branch' => $b->id])) }}" onclick="localStorage.setItem('admin_sr_branch', '{{ $b->id }}');" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ ($branchFilter == $b->id) ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
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
                                <a href="{{ route('admin.service-requests', array_merge(request()->query(), ['sort' => 'newest'])) }}" onclick="localStorage.setItem('admin_sr_sort', 'newest');" class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-500 hover:text-rose-700 transition" title="Clear sort">
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
                            <a href="{{ route('admin.service-requests', array_merge(request()->query(), ['sort' => $opt['next_sort']])) }}" onclick="localStorage.setItem('admin_sr_sort', '{{ $opt['next_sort'] }}');" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $opt['is_active'] ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
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
                                        <span onclick="event.preventDefault(); event.stopPropagation(); localStorage.setItem('admin_sr_sort', 'newest'); window.location.href='{{ route('admin.service-requests', array_merge(request()->query(), ['sort' => 'newest'])) }}';" class="p-0.5 rounded text-blue-500 hover:bg-rose-100 hover:text-rose-600 transition cursor-pointer" title="Clear sort">
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
                    <a href="{{ route('admin.service-requests', ['filter' => 'all', 'sort' => 'newest', 'branch' => 'all']) }}" onclick="clearSavedSrControls();" class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-slate-100/80 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition cursor-pointer active:scale-95 shadow-2xs shrink-0" title="Clear filter and sort">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <!-- Requests Cards Container (Grid / List Views) -->
        <div class="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 transition-all duration-300" id="requestsGrid">
            @forelse($requests as $request)
                @php
                    $accentColor = match($request->status) {
                        'completed' => 'bg-blue-600',
                        'washing' => 'bg-purple-600',
                        'drying' => 'bg-indigo-600',
                        'ready_for_pickup', 'ready' => 'bg-teal-500',
                        'cancelled' => 'bg-rose-500',
                        default => 'bg-slate-400'
                    };

                    $badgeClass = match($request->status) {
                        'completed' => 'bg-blue-100 text-blue-700',
                        'washing' => 'bg-purple-100 text-purple-700',
                        'drying' => 'bg-indigo-100 text-indigo-700',
                        'ready_for_pickup', 'ready' => 'bg-teal-100 text-teal-700',
                        'received' => 'bg-slate-100 text-slate-700',
                        'pending' => 'bg-amber-100 text-amber-700',
                        'cancelled' => 'bg-rose-100 text-rose-700',
                        default => 'bg-slate-100 text-slate-600'
                    };

                    $statusLabel = match($request->status) {
                        'completed' => 'Completed',
                        'washing' => 'Washing',
                        'drying' => 'Drying',
                        'ready_for_pickup', 'ready' => 'Ready',
                        'received' => 'Received',
                        'pending' => 'Pending',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($request->status)
                    };
                @endphp

                <div class="request-card relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:shadow-md duration-200 flex flex-col justify-between" data-id="{{ $request->id }}" data-search="{{ strtolower(($request->customer->username ?? 'Walk-in') . ' ' . $request->id . ' ' . ($request->clothType->name ?? '') . ' ' . ($request->branch->name ?? '')) }}">
                    <div class="absolute top-0 inset-x-0 h-1 {{ $accentColor }}"></div>
                    
                    <div>
                        <!-- Request ID & Status Badge -->
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="inline-block rounded-full px-3 py-1 text-[11px] font-semibold {{ $badgeClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <!-- Customer Name & Machine Badge (Opposite Side) -->
                        <div class="flex items-center justify-between gap-2 mt-3 mb-3">
                            <div>
                                <h3 class="text-xl font-bold tracking-tight text-slate-900 truncate" title="{{ $request->customer->username ?? 'Walk-in Customer' }}">
                                    {{ $request->customer->username ?? 'Walk-in Customer' }}
                                </h3>
                                @if($isOwner && $request->branch)
                                    <p class="text-xs text-slate-500 font-medium flex items-center gap-1 mt-0.5">
                                        <svg class="h-3 w-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span class="truncate max-w-[140px] sm:max-w-[180px]">{{ $request->branch->name }}</span>
                                    </p>
                                @endif
                            </div>

                            @php
                                $rCycle = $request->cycle_info;
                            @endphp
                            @if($request->status === 'washing')
                                @if($request->cycle_phase === 'resting' || $rCycle['phase'] === 'resting')
                                    <span class="shrink-0 inline-flex items-center gap-1 font-mono text-[11px] font-extrabold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-xl border border-amber-200 shadow-2xs">
                                        Wash Buffer: <span data-cycle-timer data-remaining="{{ (int)$rCycle['remaining_seconds'] }}" data-paused="{{ $rCycle['is_paused'] ? '1' : '0' }}">{{ $rCycle['display_time'] }}</span>
                                    </span>
                                @else
                                    <span class="shrink-0 inline-flex items-center gap-1 font-mono text-[11px] font-extrabold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-xl border border-purple-200 shadow-2xs">
                                        {{ $request->washingMachine->name ?? 'Washer' }} · <span data-cycle-timer data-remaining="{{ (int)$rCycle['remaining_seconds'] }}" data-paused="{{ $rCycle['is_paused'] ? '1' : '0' }}">{{ $rCycle['display_time'] }}</span>
                                    </span>
                                @endif
                            @elseif($request->status === 'drying')
                                @if($request->cycle_phase === 'folding' || $rCycle['phase'] === 'folding')
                                    <span class="shrink-0 inline-flex items-center gap-1 font-mono text-[11px] font-extrabold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-xl border border-teal-200 shadow-2xs">
                                        Folding &amp; Packaging
                                    </span>
                                @else
                                    <span class="shrink-0 inline-flex items-center gap-1 font-mono text-[11px] font-extrabold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-xl border border-indigo-200 shadow-2xs">
                                        {{ $request->dryerMachine->name ?? 'Dryer' }} · <span data-cycle-timer data-remaining="{{ (int)$rCycle['remaining_seconds'] }}" data-paused="{{ $rCycle['is_paused'] ? '1' : '0' }}">{{ $rCycle['display_time'] }}</span>
                                    </span>
                                @endif
                            @endif
                        </div>

                        <!-- Weight / Size, Branch/Quantity & Cloth Type Details Grid -->
                        <div class="grid grid-cols-3 gap-2 py-3 border-t border-slate-100 text-left">
                            <div>
                                <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">Weight / Size</p>
                                <p class="text-xs sm:text-sm font-bold text-slate-900 mt-0.5 truncate">
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
                                        {{ $request->pricingTier->name ?? 'Standard' }}
                                    @endif
                                </p>
                            </div>
                            @if($isOwner)
                                <div>
                                    <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">Branch</p>
                                    <p class="text-xs sm:text-sm font-bold text-slate-900 mt-0.5 truncate" title="{{ $request->branch->name ?? 'Main Branch' }}">
                                        {{ $request->branch->name ?? 'Main Branch' }}
                                    </p>
                                </div>
                            @else
                                <div>
                                    <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">Quantity</p>
                                    <p class="text-xs sm:text-sm font-bold text-slate-900 mt-0.5">
                                        {{ $request->quantity ?? 1 }}
                                    </p>
                                </div>
                            @endif
                            <div>
                                <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">Cloth Type</p>
                                <p class="text-xs sm:text-sm font-bold text-slate-900 mt-0.5 truncate" title="{{ $request->clothType->name ?? 'Standard' }}">
                                    {{ $request->clothType->name ?? 'Standard' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer: Date + Details Link -->
                    <div class="border-t border-slate-100 mt-4 pt-3 flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-500 font-mono">
                            {{ $request->created_at ? $request->created_at->format('m/d/y · g:iA') : 'Today' }}
                        </span>
                        <a href="{{ route('admin.service-requests.show', $request) }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200/90 bg-white px-3.5 py-1.5 text-xs font-bold text-slate-800 hover:border-blue-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-2xs group">
                            <span>Details</span>
                            <svg class="h-3.5 w-3.5 text-slate-600 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="mt-3 text-sm font-semibold text-slate-600">No service requests found</p>
                    <p class="text-xs text-slate-400 mt-1">{{ auth('admin')->user()?->isSuperAdmin() ? 'Try switching filter tabs or clearing your search.' : 'Try switching tabs or creating a new request.' }}</p>
                </div>
            @endforelse
        </div>

        <!-- Requests Table Container (Details Table View option) -->
        <div class="overflow-x-auto hidden rounded-2xl border border-slate-200/80 bg-white shadow-2xs" id="requestsTable">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono">
                    <tr>
                        <!-- ID Header -->
                        @php
                            $idNextSort = ($sort === 'id_asc') ? 'id_desc' : 'id_asc';
                        @endphp
                        <th class="px-4 py-3 rounded-l-xl">
                            <a href="{{ route('admin.service-requests', ['filter' => $filter, 'sort' => $idNextSort]) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by ID">
                                <span>ID</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'id_asc') ▲ @elseif ($sort === 'id_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Customer Header -->
                        @php
                            $nameNextSort = ($sort === 'name_asc') ? 'name_desc' : 'name_asc';
                        @endphp
                        <th class="px-4 py-3">
                            <a href="{{ route('admin.service-requests', ['filter' => $filter, 'sort' => $nameNextSort]) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Customer Name">
                                <span>Customer</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'name_asc') ▲ @elseif ($sort === 'name_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Status Header -->
                        @php
                            $statusNextSort = ($sort === 'status_asc') ? 'status_desc' : 'status_asc';
                        @endphp
                        <th class="px-4 py-3">
                            <a href="{{ route('admin.service-requests', ['filter' => $filter, 'sort' => $statusNextSort]) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Status">
                                <span>Status</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'status_asc') ▲ @elseif ($sort === 'status_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Weight / Size Header -->
                        @php
                            $weightNextSort = ($sort === 'weight_desc') ? 'weight_asc' : 'weight_desc';
                        @endphp
                        <th class="px-4 py-3">
                            <a href="{{ route('admin.service-requests', ['filter' => $filter, 'sort' => $weightNextSort]) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Weight / Size">
                                <span>Weight / Size</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'weight_desc') ▼ @elseif ($sort === 'weight_asc') ▲ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        @if($isOwner)
                            <!-- Branch Header -->
                            @php
                                $branchNextSort = ($sort === 'branch_asc') ? 'branch_desc' : 'branch_asc';
                            @endphp
                            <th class="px-4 py-3">
                                <a href="{{ route('admin.service-requests', array_merge(request()->query(), ['sort' => $branchNextSort])) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Branch">
                                    <span>Branch</span>
                                    <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                        @if ($sort === 'branch_asc') ▲ @elseif ($sort === 'branch_desc') ▼ @else ↕ @endif
                                    </span>
                                </a>
                            </th>
                        @else
                            <!-- Quantity Header -->
                            @php
                                $itemsNextSort = ($sort === 'items_desc' || $sort === 'quantity_desc') ? 'quantity_asc' : 'quantity_desc';
                            @endphp
                            <th class="px-4 py-3">
                                <a href="{{ route('admin.service-requests', array_merge(request()->query(), ['sort' => $itemsNextSort])) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Quantity">
                                    <span>Quantity</span>
                                    <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                        @if ($sort === 'items_desc' || $sort === 'quantity_desc') ▼ @elseif ($sort === 'items_asc' || $sort === 'quantity_asc') ▲ @else ↕ @endif
                                    </span>
                                </a>
                            </th>
                        @endif

                        <!-- Cloth Type Header -->
                        @php
                            $clothTypeNextSort = ($sort === 'cloth_type_asc') ? 'cloth_type_desc' : 'cloth_type_asc';
                        @endphp
                        <th class="px-4 py-3">
                            <a href="{{ route('admin.service-requests', array_merge(request()->query(), ['sort' => $clothTypeNextSort])) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Cloth Type">
                                <span>Cloth Type</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'cloth_type_asc') ▲ @elseif ($sort === 'cloth_type_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Date Header -->
                        @php
                            $dateNextSort = ($sort === 'newest') ? 'oldest' : 'newest';
                        @endphp
                        <th class="px-4 py-3">
                            <a href="{{ route('admin.service-requests', ['filter' => $filter, 'sort' => $dateNextSort]) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Date">
                                <span>Date</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'newest') ▼ @elseif ($sort === 'oldest') ▲ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <th class="px-4 py-3 text-right rounded-r-xl">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach($requests as $request)
                        @php
                            $badgeClass = match($request->status) {
                                'completed' => 'bg-blue-100 text-blue-700',
                                'washing' => 'bg-purple-100 text-purple-700',
                                'drying' => 'bg-indigo-100 text-indigo-700',
                                'ready_for_pickup', 'ready' => 'bg-teal-100 text-teal-700',
                                'received' => 'bg-slate-100 text-slate-700',
                                'pending' => 'bg-amber-100 text-amber-700',
                                'cancelled' => 'bg-rose-100 text-rose-700',
                                default => 'bg-slate-100 text-slate-600'
                            };
                            $statusLabel = match($request->status) {
                                'completed' => 'Completed',
                                'washing' => 'Washing',
                                'drying' => 'Drying',
                                'ready_for_pickup', 'ready' => 'Ready',
                                'received' => 'Received',
                                'pending' => 'Pending',
                                'cancelled' => 'Cancelled',
                                default => ucfirst($request->status)
                            };
                        @endphp
                        <tr class="table-row-item hover:bg-slate-50/80 transition" data-id="{{ $request->id }}" data-search="{{ strtolower(($request->customer->username ?? 'Walk-in') . ' ' . $request->id . ' ' . ($request->clothType->name ?? '') . ' ' . ($request->branch->name ?? '')) }}">
                            <td class="px-4 py-3 font-mono text-xs font-semibold text-slate-500">
                                #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-900 whitespace-nowrap">
                                <span class="truncate max-w-[140px] sm:max-w-[170px] inline-block align-middle" title="{{ $request->customer->username ?? 'Walk-in Customer' }}">
                                    {{ $request->customer->username ?? 'Walk-in Customer' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="inline-block rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                    @php
                                        $rCycle = $request->cycle_info;
                                    @endphp
                                    @if($request->status === 'washing')
                                        @if($request->cycle_phase === 'resting' || $rCycle['phase'] === 'resting')
                                            <span class="inline-flex items-center gap-1 font-mono text-[10px] font-extrabold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 shadow-2xs">
                                                Wash Buffer (<span data-cycle-timer data-remaining="{{ (int)$rCycle['remaining_seconds'] }}" data-paused="{{ $rCycle['is_paused'] ? '1' : '0' }}">{{ $rCycle['display_time'] }}</span>)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 font-mono text-[10px] font-extrabold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200 shadow-2xs">
                                                {{ $request->washingMachine->name ?? 'Washer' }} (<span data-cycle-timer data-remaining="{{ (int)$rCycle['remaining_seconds'] }}" data-paused="{{ $rCycle['is_paused'] ? '1' : '0' }}">{{ $rCycle['display_time'] }}</span>)
                                            </span>
                                        @endif
                                    @elseif($request->status === 'drying')
                                        @if($request->cycle_phase === 'folding' || $rCycle['phase'] === 'folding')
                                            <span class="inline-flex items-center gap-1 font-mono text-[10px] font-extrabold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-200 shadow-2xs">
                                                Folding
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 font-mono text-[10px] font-extrabold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-200 shadow-2xs">
                                                {{ $request->dryerMachine->name ?? 'Dryer' }} (<span data-cycle-timer data-remaining="{{ (int)$rCycle['remaining_seconds'] }}" data-paused="{{ $rCycle['is_paused'] ? '1' : '0' }}">{{ $rCycle['display_time'] }}</span>)
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-slate-800 whitespace-nowrap">
                                @php
                                    $cWeight = $request->actual_weight ?? $request->estimated_weight;
                                @endphp
                                @if($request->comforter_size)
                                    <span class="text-purple-700 font-semibold">{{ $request->comforter_size }}</span>
                                    @if($cWeight && $cWeight > 0)
                                        <span class="text-xs font-semibold text-slate-500 font-mono">({{ ($cWeight + 0) }} kg)</span>
                                    @endif
                                @elseif($cWeight)
                                    {{ ($cWeight + 0) }} kg
                                @else
                                    {{ $request->pricingTier->name ?? '—' }}
                                @endif
                            </td>
                            @if($isOwner)
                                <td class="px-4 py-3 font-semibold text-slate-800 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500 shrink-0"></span>
                                        <span class="truncate max-w-[130px] sm:max-w-[160px]" title="{{ $request->branch->name ?? 'Main Branch' }}">
                                            {{ $request->branch->name ?? 'Main Branch' }}
                                        </span>
                                    </span>
                                </td>
                            @else
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    {{ $request->quantity ?? 1 }}
                                </td>
                            @endif
                            <td class="px-4 py-3 text-slate-700 font-medium whitespace-nowrap">
                                <span class="truncate max-w-[130px] sm:max-w-[160px] inline-block align-middle" title="{{ $request->clothType->name ?? 'Standard' }}">
                                    {{ $request->clothType->name ?? 'Standard' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="font-mono text-xs font-semibold text-slate-700">{{ $request->created_at ? $request->created_at->format('m/d/y') : 'Today' }}</div>
                                <div class="font-mono text-[11px] text-slate-400">{{ $request->created_at ? $request->created_at->format('g:iA') : '' }}</div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.service-requests.show', $request) }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200/90 bg-white px-3.5 py-1.5 text-xs font-bold text-slate-800 hover:border-blue-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-2xs group">
                                    <span>Details</span>
                                    <svg class="h-3.5 w-3.5 text-slate-600 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <x-pagination id="adminRequestsPagination" :pageSize="10" :pageSizes="[10, 20, 50]" />
    </div>
</div>

    <!-- Client-side Scripts: Dropdowns, View Switcher, Live Search & Pagination -->
    <script>
        let requestsPaginator;

        function toggleControlPopover(targetId, event) {
            if (event) {
                event.stopPropagation();
            }
            const target = document.getElementById(targetId);
            if (!target) return;
            const isCurrentlyOpen = !target.classList.contains('hidden');

            // Close admin notification popover if open
            const adminNotif = document.getElementById('adminNotifPopover');
            if (adminNotif) adminNotif.classList.add('hidden');

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
        function toggleDropdown(targetId, event) {
            toggleControlPopover(targetId, event);
        }

        // Close dropdown menus when clicking outside
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

        // View layout switcher (Grid Cards / Details Table)
        function changeView(mode) {
            const gridEl = document.getElementById('requestsGrid');
            const tableEl = document.getElementById('requestsTable');

            // Hide checkmarks
            document.querySelectorAll('[class*="view-check-"]').forEach(el => el.classList.add('hidden'));

            if (mode === 'table') {
                gridEl.classList.add('hidden');
                tableEl.classList.remove('hidden');
                document.querySelector('.view-check-table')?.classList.remove('hidden');
            } else {
                // Grid mode
                tableEl.classList.add('hidden');
                gridEl.classList.remove('hidden');
                gridEl.className = 'grid gap-6 md:grid-cols-2 lg:grid-cols-3 transition-all duration-300';
                document.querySelector('.view-check-grid')?.classList.remove('hidden');
                mode = 'grid';
            }

            // Save preference in localStorage
            localStorage.setItem('admin_requests_view', mode);

            // Close layout popover menu
            const layoutPopover = document.getElementById('layoutMenuPopover');
            if (layoutPopover) {
                layoutPopover.classList.add('hidden');
                layoutPopover.style.transform = '';
                const wrapper = layoutPopover.closest('.control-dropdown-wrapper');
                if (wrapper) wrapper.classList.remove('z-40');
            }
        }

        function clearSavedSrControls() {
            localStorage.setItem('admin_sr_filter', 'all');
            localStorage.setItem('admin_sr_sort', 'newest');
            localStorage.removeItem('admin_sr_branch');
        }

        // Synchronize and persist filter and sort across windows/tabs
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            const hasFilter = urlParams.has('filter');
            const hasSort = urlParams.has('sort');
            const hasBranch = urlParams.has('branch');

            // Save active parameters to localStorage
            if (hasFilter) {
                localStorage.setItem('admin_sr_filter', urlParams.get('filter') || 'all');
            }
            if (hasSort) {
                localStorage.setItem('admin_sr_sort', urlParams.get('sort') || 'newest');
            }
            if (hasBranch) {
                const b = urlParams.get('branch');
                if (b === 'all' || !b) {
                    localStorage.removeItem('admin_sr_branch');
                } else {
                    localStorage.setItem('admin_sr_branch', b);
                }
            }

            // If visited without parameters and localStorage has saved preferences
            if (!hasFilter && !hasSort && !hasBranch && !urlParams.has('reset')) {
                const savedFilter = localStorage.getItem('admin_sr_filter');
                const savedSort = localStorage.getItem('admin_sr_sort');
                const savedBranch = localStorage.getItem('admin_sr_branch');

                const redirectParams = new URLSearchParams();
                if (savedFilter && savedFilter !== 'all') {
                    redirectParams.set('filter', savedFilter);
                }
                if (savedSort && savedSort !== 'newest') {
                    redirectParams.set('sort', savedSort);
                }
                @if($isOwner)
                if (savedBranch && savedBranch !== 'all') {
                    redirectParams.set('branch', savedBranch);
                }
                @endif

                if ([...redirectParams.keys()].length > 0) {
                    window.location.replace('{{ route("admin.service-requests") }}?' + redirectParams.toString());
                }
            }
        })();

        // Load saved view & initialize pagination on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('admin_requests_view') || 'grid';
            changeView(savedView);

            requestsPaginator = new ClientPaginator({
                containerId: 'adminRequestsPagination',
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
                    document.querySelectorAll('#requestsTable .table-row-item').forEach(row => {
                        row.style.display = visibleIds.has(row.getAttribute('data-id')) ? '' : 'none';
                    });
                }
            });
        });

        // Live Search Input Filter
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            if (requestsPaginator) {
                requestsPaginator.reset();
            }
        });

        // Live Cycle Countdown Ticker
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
    </script>
</x-admin-layout>
