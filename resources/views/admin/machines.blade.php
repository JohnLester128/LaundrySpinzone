<x-admin-layout>
    <x-slot:title>Manage Machines - Laundry Spinzone</x-slot:title>
    <x-slot:header>Machines Management</x-slot:header>

    @php
        $isOwner = auth('admin')->user()?->isSuperAdmin() ?? false;
    @endphp

    <div class="space-y-6">
        <!-- Notification Component -->
        <x-notification />

        <!-- Header Controls Row: Title/Breadcrumb + Add Machine Button -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Branch Machines</h1>
                <p class="text-xs text-slate-500 mt-0.5">Click any machine unit to view details, update specifications, set maintenance{{ $isOwner ? ', or delete' : '' }}.</p>
            </div>
            @if($isOwner)
            <div>
                <button type="button" onclick="openAddMachineModal()" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-2.5 text-xs font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 transition cursor-pointer active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Machine</span>
                </button>
            </div>
            @endif
        </div>

        <!-- Metrics Overview Row (Compact 4-col) -->
        <div class="grid grid-cols-4 gap-2 sm:gap-3">
            <!-- Total Machines -->
            <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white p-2 sm:p-3 lg:p-4 shadow-sm transition-all duration-200 hover:shadow-md">
                <div class="absolute top-0 inset-x-0 h-0.5 sm:h-1 bg-blue-600"></div>
                <div class="flex items-center justify-between gap-1 mb-1 sm:mb-1.5">
                    <p class="font-mono text-[8px] sm:text-[10px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">Total</p>
                    <div class="flex h-4 w-4 sm:h-6 sm:w-6 lg:h-8 lg:w-8 items-center justify-center rounded sm:rounded-lg bg-blue-600 text-white shrink-0">
                        <svg class="h-2 w-2 sm:h-3 sm:w-3 lg:h-4 lg:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <p id="metricTotalMachines" class="text-sm sm:text-lg lg:text-2xl font-extrabold tracking-tight text-slate-900">
                    {{ $machines->count() }}
                </p>
                <div class="hidden sm:flex mt-1 items-center text-[10px] lg:text-xs font-medium text-slate-400">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-500 mr-1"></span>
                    <span class="truncate">All units</span>
                </div>
            </div>

            <!-- Washers Count -->
            <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white p-2 sm:p-3 lg:p-4 shadow-sm transition-all duration-200 hover:shadow-md">
                <div class="absolute top-0 inset-x-0 h-0.5 sm:h-1 bg-indigo-600"></div>
                <div class="flex items-center justify-between gap-1 mb-1 sm:mb-1.5">
                    <p class="font-mono text-[8px] sm:text-[10px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">Wash</p>
                    <div class="flex h-4 w-4 sm:h-6 sm:w-6 lg:h-8 lg:w-8 items-center justify-center rounded sm:rounded-lg bg-indigo-600 text-white shrink-0">
                        <svg class="h-2 w-2 sm:h-3 sm:w-3 lg:h-4 lg:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                </div>
                <p id="metricTotalWashers" class="text-sm sm:text-lg lg:text-2xl font-extrabold tracking-tight text-slate-900">
                    {{ $machines->where('type', 'washer')->count() }}
                </p>
                <div class="hidden sm:flex mt-1 items-center text-[10px] lg:text-xs font-medium text-slate-400">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-indigo-500 mr-1"></span>
                    <span class="truncate">Washers</span>
                </div>
            </div>

            <!-- Dryers Count -->
            <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white p-2 sm:p-3 lg:p-4 shadow-sm transition-all duration-200 hover:shadow-md">
                <div class="absolute top-0 inset-x-0 h-0.5 sm:h-1 bg-amber-600"></div>
                <div class="flex items-center justify-between gap-1 mb-1 sm:mb-1.5">
                    <p class="font-mono text-[8px] sm:text-[10px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">Dry</p>
                    <div class="flex h-4 w-4 sm:h-6 sm:w-6 lg:h-8 lg:w-8 items-center justify-center rounded sm:rounded-lg bg-amber-600 text-white shrink-0">
                        <svg class="h-2 w-2 sm:h-3 sm:w-3 lg:h-4 lg:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                        </svg>
                    </div>
                </div>
                <p id="metricTotalDryers" class="text-sm sm:text-lg lg:text-2xl font-extrabold tracking-tight text-slate-900">
                    {{ $machines->where('type', 'dryer')->count() }}
                </p>
                <div class="hidden sm:flex mt-1 items-center text-[10px] lg:text-xs font-medium text-slate-400">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-amber-500 mr-1"></span>
                    <span class="truncate">Dryers</span>
                </div>
            </div>

            <!-- Available Status -->
            <div class="relative overflow-hidden rounded-xl border border-slate-200/80 bg-white p-2 sm:p-3 lg:p-4 shadow-sm transition-all duration-200 hover:shadow-md">
                <div class="absolute top-0 inset-x-0 h-0.5 sm:h-1 bg-emerald-600"></div>
                <div class="flex items-center justify-between gap-1 mb-1 sm:mb-1.5">
                    <p class="font-mono text-[8px] sm:text-[10px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">Avail</p>
                    <div class="flex h-4 w-4 sm:h-6 sm:w-6 lg:h-8 lg:w-8 items-center justify-center rounded sm:rounded-lg bg-emerald-600 text-white shrink-0">
                        <svg class="h-2 w-2 sm:h-3 sm:w-3 lg:h-4 lg:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p id="metricTotalAvailable" class="text-sm sm:text-lg lg:text-2xl font-extrabold tracking-tight text-slate-900">
                    {{ $machines->where('status', 'available')->count() }}
                </p>
                <div class="hidden sm:flex mt-1 items-center text-[10px] lg:text-xs font-medium text-slate-400">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500 mr-1"></span>
                    <span class="truncate">Ready</span>
                </div>
            </div>
        </div>

        <!-- Main Card Container -->
        <div class="rounded-2xl sm:rounded-3xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-sm">
            <!-- Top Controls: Search Input + Filter Tabs -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-100 mb-6">
                <!-- Search Bar -->
                <div class="relative w-full sm:w-80">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="machineSearch" onkeyup="filterMachines()" placeholder="Search machine name, brand, model..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                </div>

                <!-- Right Controls: Filter, Sort By, Layout -->
                <div class="flex items-center justify-end gap-2 sm:gap-2.5 min-w-0 w-full sm:w-auto ml-auto">
                    <!-- 1. Filter Button (first / near center) -->
                    <div class="relative inline-block text-left control-dropdown-wrapper shrink-0 z-10">
                        <button type="button" onclick="toggleControlPopover('machineFilterPopover', event)" id="machineFilterBtn" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900 px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Filter machines">
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="hidden sm:inline">Filter</span>
                            <span id="machineFilterDot" class="hidden h-2 w-2 rounded-full"></span>
                            <svg class="hidden sm:block h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Filter Popover -->
                        <div id="machineFilterPopover" class="control-popover hidden absolute right-0 mt-2 w-60 sm:w-64 max-w-[calc(100vw-1.5rem)] max-h-[calc(100vh-140px)] overflow-y-auto [scrollbar-width:thin] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                <span>Filter Units</span>
                                <span class="text-slate-400 font-normal">({{ $machines->count() }} total)</span>
                            </div>
                            @php
                                $mFilterOpts = [
                                    'all' => ['label' => 'All Units', 'dot' => 'bg-slate-400', 'count' => $machines->count()],
                                    'washer' => ['label' => 'Washers', 'dot' => 'bg-blue-500', 'count' => $machines->where('type', 'washer')->count()],
                                    'dryer' => ['label' => 'Dryers', 'dot' => 'bg-amber-500', 'count' => $machines->where('type', 'dryer')->count()],
                                    'available' => ['label' => 'Available', 'dot' => 'bg-emerald-500', 'count' => $machines->where('status', 'available')->count()],
                                    'in_use' => ['label' => 'In Use', 'dot' => 'bg-blue-600', 'count' => $machines->where('status', 'in_use')->count()],
                                    'maintenance' => ['label' => 'Maintenance', 'dot' => 'bg-amber-600', 'count' => $machines->where('status', 'maintenance')->count()],
                                ];
                            @endphp
                            @foreach($mFilterOpts as $fKey => $fOpt)
                                <button type="button" onclick="setMachineFilter('{{ $fKey }}', '{{ $fOpt['label'] }}')" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full {{ $fOpt['dot'] }}"></span>
                                        <span>{{ $fOpt['label'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span id="filterCount-{{ $fKey }}" class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-500">
                                            {{ $fOpt['count'] }}
                                        </span>
                                        <span class="filter-check-item filter-check-{{ $fKey }} text-blue-600 font-bold text-xs {{ $fKey === 'all' ? '' : 'hidden' }}">✓</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @if($isOwner && isset($branches) && $branches->count() > 1)
                    <!-- 2. Branch Button (Owner only, next to Filter and Sort By) -->
                    <div class="relative inline-block text-left control-dropdown-wrapper shrink-0 z-10">
                        <button type="button" onclick="toggleControlPopover('machineBranchPopover', event)" id="machineBranchBtn" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900 px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Filter by Branch">
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="hidden sm:inline" id="machineBranchBtnLabel">Branch</span>
                            <span id="machineBranchActiveDot" class="hidden h-2 w-2 rounded-full bg-purple-600"></span>
                            <svg class="hidden sm:block h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Branch Popover -->
                        <div id="machineBranchPopover" class="control-popover hidden absolute right-0 mt-2 w-60 sm:w-64 max-w-[calc(100vw-1.5rem)] max-h-[calc(100vh-140px)] overflow-y-auto [scrollbar-width:thin] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                <span>Filter By Branch</span>
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>

                            <!-- All Branches -->
                            <button type="button" onclick="setMachineBranch('all', 'All Branches')" id="machineBranchItem-all" class="machine-branch-item flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                    <span>All Branches</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-500">{{ $machines->count() }}</span>
                                    <span class="branch-check-item branch-check-all text-purple-600 font-bold text-xs">✓</span>
                                </div>
                            </button>

                            @foreach($branches as $b)
                                @php
                                    $bCount = $machines->where('branch_id', $b->id)->count();
                                @endphp
                                <button type="button" onclick="setMachineBranch('{{ $b->id }}', '{{ addslashes($b->name) }}')" id="machineBranchItem-{{ $b->id }}" class="machine-branch-item flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="h-2 w-2 rounded-full bg-purple-500 shrink-0"></span>
                                        <span class="truncate max-w-[130px]">{{ $b->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-500">{{ $bCount }}</span>
                                        <span class="branch-check-item branch-check-{{ $b->id }} text-purple-600 font-bold text-xs hidden">✓</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- 3. Sort By Button (then sortby) -->
                    <div class="relative inline-block text-left control-dropdown-wrapper shrink-0 z-10">
                        <button type="button" id="machineSortBtn" onclick="toggleControlPopover('machineSortPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900 px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Sort machines">
                            <svg id="machineSortBtnIcon" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                            <span class="hidden sm:inline">Sort By</span>
                            <span id="machineSortActiveDot" class="hidden h-2 w-2 rounded-full bg-blue-600"></span>
                            <svg class="hidden sm:block h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Sort Popover (1-Word Toggle Architecture) -->
                        <div id="machineSortPopover" class="control-popover hidden absolute right-0 mt-2 w-60 sm:w-64 max-w-[calc(100vw-1.5rem)] max-h-[calc(100vh-140px)] overflow-y-auto [scrollbar-width:thin] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                <span>Sort By</span>
                                <button type="button" id="machineSortClearBtn" onclick="setMachineSort('id_asc')" class="hidden items-center gap-1 text-[10px] font-bold text-rose-500 hover:text-rose-700 transition cursor-pointer" title="Reset sort">
                                    <span>Clear</span>
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <svg id="machineSortDefaultIcon" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                </svg>
                            </div>

                            <!-- 1. Name -->
                            <button type="button" onclick="toggleMachineSort('name')" id="machineSortItem-name" class="machine-sort-item flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-slate-400 machine-sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Name</span>
                                </div>
                                <span class="machine-sort-indicator text-slate-300 text-xs font-mono">↕</span>
                            </button>

                            <!-- 2. Type -->
                            <button type="button" onclick="toggleMachineSort('type')" id="machineSortItem-type" class="machine-sort-item flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-slate-400 machine-sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span>Type</span>
                                </div>
                                <span class="machine-sort-indicator text-slate-300 text-xs font-mono">↕</span>
                            </button>

                            <!-- 3. Status -->
                            <button type="button" onclick="toggleMachineSort('status')" id="machineSortItem-status" class="machine-sort-item flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-slate-400 machine-sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Status</span>
                                </div>
                                <span class="machine-sort-indicator text-slate-300 text-xs font-mono">↕</span>
                            </button>

                            <!-- 4. ID -->
                            <button type="button" onclick="toggleMachineSort('id')" id="machineSortItem-id" class="machine-sort-item flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-slate-400 machine-sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                    <span>ID</span>
                                </div>
                                <span class="machine-sort-indicator text-slate-300 text-xs font-mono">↕</span>
                            </button>
                        </div>
                    </div>

                    <!-- 3. Layout Button (then layout) -->
                    <div class="relative inline-block text-left control-dropdown-wrapper shrink-0 z-10">
                        <button type="button" onclick="toggleControlPopover('machineLayoutPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900 px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Switch View Layout">
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <span class="hidden sm:inline">Layout</span>
                            <svg class="hidden sm:block h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Layout Popover -->
                        <div id="machineLayoutPopover" class="control-popover hidden absolute right-0 mt-2 w-48 sm:w-52 max-w-[calc(100vw-1.5rem)] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                <span>View Layout</span>
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </div>
                            <button type="button" onclick="switchMachineView('grid')" class="view-opt-btn flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4z" />
                                    </svg>
                                    <span>Grid Cards</span>
                                </div>
                                <span id="checkMachineGrid" class="text-blue-600 font-bold">✓</span>
                            </button>
                            <button type="button" onclick="switchMachineView('table')" class="view-opt-btn flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M3 18h18M3 6h18" />
                                    </svg>
                                    <span>Details Table</span>
                                </div>
                                <span id="checkMachineTable" class="hidden text-blue-600 font-bold">✓</span>
                            </button>
                        </div>
                    </div>

                    <!-- Active Filter / Sort Quick-Clear (Only visible when a filter or sort is active) -->
                    <button type="button" id="machineActiveClearPill" onclick="clearActiveMachineControls()" style="display: none;" class="items-center justify-center h-9 w-9 rounded-xl bg-slate-100/80 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition cursor-pointer active:scale-95 shadow-2xs shrink-0" title="Clear filter and sort">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Machines Grid -->
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3" id="machinesGrid">
                @forelse($machines as $machine)
                    @php
                        $statusRaw = strtolower($machine->status);
                        $isAvailable = $statusRaw === 'available';
                        $isInUse = $statusRaw === 'in_use';
                        $isMaintenance = !$isAvailable && !$isInUse;
                        $isWasher = strtolower($machine->type) === 'washer';
                        $activeReq = $machine->active_service_request;

                        $cardBorderClass = $isAvailable 
                            ? 'border-emerald-200/80 bg-emerald-50/40 hover:bg-emerald-50/70 hover:border-emerald-300' 
                            : ($isInUse 
                                ? 'border-blue-200/80 bg-blue-50/40 hover:bg-blue-50/70 hover:border-blue-300' 
                                : 'border-amber-200/80 bg-amber-50/40 hover:bg-amber-50/70 hover:border-amber-300');

                        $badgeClass = $isAvailable 
                            ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' 
                            : ($isInUse 
                                ? 'bg-blue-100 text-blue-700 border border-blue-200' 
                                : 'bg-amber-100 text-amber-800 border border-amber-200');

                        $statusLabel = $isAvailable ? 'AVAILABLE' : ($isInUse ? 'IN_USE' : 'MAINTENANCE');
                    @endphp
                    <article class="machine-card group relative overflow-hidden rounded-2xl border-2 {{ $cardBorderClass }} p-5 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 cursor-pointer"
                             onclick="openMachineModal({{ $machine->id }}, '{{ addslashes($machine->name) }}', '{{ $machine->type }}', '{{ addslashes($machine->brand ?? '') }}', '{{ addslashes($machine->model ?? '') }}', '{{ $isAvailable ? 'available' : ($isInUse ? 'in_use' : 'maintenance') }}', '{{ addslashes($machine->branch->name ?? 'Main Branch') }}', {{ $machine->branch_id ?? 'null' }}, '{{ $machine->created_at ? $machine->created_at->format('M d, Y h:i A') : 'N/A' }}', {{ json_encode($activeReq ? ['id' => $activeReq->id, 'customer' => $activeReq->customer->username ?? 'Walk-in', 'status' => $activeReq->status, 'url' => route('admin.service-requests.show', $activeReq)] : null) }})"
                             data-id="{{ $machine->id }}"
                             data-name="{{ strtolower($machine->name) }}"
                             data-brand="{{ strtolower($machine->brand ?? '') }}"
                             data-model="{{ strtolower($machine->model ?? '') }}"
                             data-branch="{{ strtolower($machine->branch->name ?? '') }}"
                             data-branch-id="{{ $machine->branch_id ?? '' }}"
                             data-type="{{ strtolower($machine->type) }}"
                             data-status="{{ $isAvailable ? 'available' : ($isInUse ? 'in_use' : 'maintenance') }}"
                             data-request="{{ strtolower(($activeReq->customer->username ?? '') . ' ' . ($activeReq ? '#req-' . str_pad($activeReq->id, 4, '0', STR_PAD_LEFT) : '')) }}">
                        
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $isWasher ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-amber-500 text-white shadow-md shadow-amber-500/20' }}">
                                    @if($isWasher)
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    @else
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-mono text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        {{ $machine->type }}
                                    </span>
                                    <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-blue-600 transition truncate max-w-[140px] sm:max-w-[170px]" title="{{ $machine->name }}">{{ $machine->name }}</h3>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider shadow-2xs {{ $badgeClass }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $isAvailable ? 'bg-emerald-500 animate-pulse' : ($isInUse ? 'bg-blue-500 animate-pulse' : 'bg-amber-500') }}"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <!-- Brand & Model Tags (Top Row) -->
                        <div class="mt-3 flex flex-wrap items-center gap-1.5 min-h-[26px]">
                            @if($machine->brand)
                                <span class="inline-flex items-center rounded-lg bg-white/80 border border-slate-200 px-2.5 py-0.5 text-xs font-semibold text-slate-700">
                                    {{ $machine->brand }}
                                </span>
                            @endif
                            @if($machine->model)
                                <span class="inline-flex items-center rounded-lg bg-white/80 border border-slate-200 px-2.5 py-0.5 text-xs font-mono font-medium text-slate-600">
                                    Mod: {{ $machine->model }}
                                </span>
                            @endif
                        </div>

                        <!-- Active Service Request Info & Timer (Row Below) -->
                        @if($activeReq)
                            @php
                                $activeCycle = $activeReq->cycle_info;
                            @endphp
                            <div class="mt-2.5 flex items-center gap-2 z-10">
                                @if(in_array($activeReq->status, ['washing', 'drying']))
                                    <span class="inline-flex items-center gap-1 rounded-xl bg-slate-900 text-white px-2 py-1 text-[11px] font-mono font-bold shadow-xs" title="Time Remaining">
                                        <span data-cycle-timer data-remaining="{{ (int)$activeCycle['remaining_seconds'] }}" data-paused="{{ $activeCycle['is_paused'] ? '1' : '0' }}">{{ $activeCycle['display_time'] }}</span>
                                    </span>
                                @endif
                                <a href="{{ route('admin.service-requests.show', $activeReq) }}" onclick="event.stopPropagation();" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-2.5 py-1 text-xs font-mono font-bold shadow-xs hover:shadow-md transition" title="Click to view request details">
                                    <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                                    <span>#REQ-{{ str_pad($activeReq->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </a>
                            </div>
                        @endif
                        
                        <div class="mt-4 pt-3 border-t border-slate-200/50 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                                <span class="flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ $machine->branch->name ?? 'Main Branch' }}
                                </span>
                                <span class="font-mono text-[10px] text-slate-400">#{{ str_pad($machine->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <button type="button" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200/90 bg-white px-3.5 py-1.5 text-xs font-bold text-slate-800 hover:border-blue-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-2xs group">
                                <span>Details</span>
                                <svg class="h-3.5 w-3.5 text-slate-600 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border-2 border-dashed border-slate-200 p-12 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-slate-900">No Machines Added Yet</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            @if($isOwner)
                                Get started by clicking the "Add Machine" button above.
                            @else
                                There are currently no machines registered for this branch.
                            @endif
                        </p>
                        @if($isOwner)
                        <button type="button" onclick="openAddMachineModal()" class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-2.5 text-xs font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 transition cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add Machine Now</span>
                        </button>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Machines Details Table View -->
            <div class="overflow-x-auto hidden rounded-2xl border border-slate-200/80 bg-white shadow-2xs" id="machinesTableContainer">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 font-mono">
                            <th class="py-3.5 pl-5 pr-3">
                                <button type="button" onclick="toggleMachineSort('name')" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Machine Name">
                                    <span>Machine Unit</span>
                                    <span id="thSortIndicator-name" class="text-xs text-slate-400 group-hover:text-blue-600">↕</span>
                                </button>
                            </th>
                            <th class="py-3.5 px-3">
                                <button type="button" onclick="toggleMachineSort('type')" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Type">
                                    <span>Type</span>
                                    <span id="thSortIndicator-type" class="text-xs text-slate-400 group-hover:text-blue-600">↕</span>
                                </button>
                            </th>
                            <th class="py-3.5 px-3">
                                <button type="button" onclick="toggleMachineSort('status')" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Status">
                                    <span>Status</span>
                                    <span id="thSortIndicator-status" class="text-xs text-slate-400 group-hover:text-blue-600">↕</span>
                                </button>
                            </th>
                            <th class="py-3.5 px-3">Active Order / Cycle</th>
                            <th class="py-3.5 px-3">Branch</th>
                            <th class="py-3.5 pl-3 pr-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($machines as $machine)
                            @php
                                $statusRaw = strtolower($machine->status);
                                $isAvailable = $statusRaw === 'available';
                                $isInUse = $statusRaw === 'in_use';
                                $isMaintenance = !$isAvailable && !$isInUse;
                                $isWasher = strtolower($machine->type) === 'washer';
                                $activeReq = $machine->active_service_request;
                                $activeCycle = $activeReq ? $activeReq->cycle_info : null;

                                $badgeClass = $isAvailable 
                                    ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' 
                                    : ($isInUse 
                                        ? 'bg-blue-100 text-blue-700 border border-blue-200' 
                                        : 'bg-amber-100 text-amber-800 border border-amber-200');

                                $statusLabel = $isAvailable ? 'AVAILABLE' : ($isInUse ? 'IN USE' : 'MAINTENANCE');
                            @endphp
                            <tr class="machine-table-row hover:bg-slate-50/80 transition cursor-pointer"
                                onclick="openMachineModal({{ $machine->id }}, '{{ addslashes($machine->name) }}', '{{ $machine->type }}', '{{ addslashes($machine->brand ?? '') }}', '{{ addslashes($machine->model ?? '') }}', '{{ $isAvailable ? 'available' : ($isInUse ? 'in_use' : 'maintenance') }}', '{{ addslashes($machine->branch->name ?? 'Main Branch') }}', {{ $machine->branch_id ?? 'null' }}, '{{ $machine->created_at ? $machine->created_at->format('M d, Y h:i A') : 'N/A' }}', {{ json_encode($activeReq ? ['id' => $activeReq->id, 'customer' => $activeReq->customer->username ?? 'Walk-in', 'status' => $activeReq->status, 'url' => route('admin.service-requests.show', $activeReq)] : null) }})"
                                data-id="{{ $machine->id }}"
                                data-name="{{ strtolower($machine->name) }}"
                                data-brand="{{ strtolower($machine->brand ?? '') }}"
                                data-model="{{ strtolower($machine->model ?? '') }}"
                                data-branch="{{ strtolower($machine->branch->name ?? '') }}"
                                data-branch-id="{{ $machine->branch_id ?? '' }}"
                                data-type="{{ strtolower($machine->type) }}"
                                data-status="{{ $isAvailable ? 'available' : ($isInUse ? 'in_use' : 'maintenance') }}"
                                data-request="{{ strtolower(($activeReq->customer->username ?? '') . ' ' . ($activeReq ? '#req-' . str_pad($activeReq->id, 4, '0', STR_PAD_LEFT) : '')) }}">
                                <td class="py-3.5 pl-5 pr-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $isWasher ? 'bg-blue-600 text-white shadow-xs' : 'bg-amber-500 text-white shadow-xs' }} shrink-0">
                                            @if($isWasher)
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                            @else
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-extrabold text-slate-900 leading-tight truncate max-w-[140px] sm:max-w-[170px]" title="{{ $machine->name }}">{{ $machine->name }}</p>
                                            <p class="text-[11px] text-slate-400 font-medium mt-0.5 truncate max-w-[140px] sm:max-w-[170px]" title="{{ $machine->brand ?? 'Generic' }} {{ $machine->model ? '· ' . $machine->model : '' }}">
                                                {{ $machine->brand ?? 'Generic' }} {{ $machine->model ? '· ' . $machine->model : '' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-flex items-center rounded-lg px-2 py-0.5 text-[11px] font-bold uppercase {{ $isWasher ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $isWasher ? 'Washer' : 'Dryer' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[10px] font-extrabold tracking-wider {{ $badgeClass }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $isAvailable ? 'bg-emerald-500' : ($isInUse ? 'bg-blue-600 animate-pulse' : 'bg-amber-600') }}"></span>
                                        <span>{{ $statusLabel }}</span>
                                    </span>
                                </td>
                                <td class="py-3.5 px-3">
                                    @if($activeReq)
                                        <div class="flex items-center gap-2">
                                            @if($activeCycle)
                                                <span class="inline-flex items-center gap-1 font-mono text-[10px] font-extrabold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200">
                                                    ⏱ <span data-cycle-timer data-remaining="{{ (int)$activeCycle['remaining_seconds'] }}" data-paused="{{ $activeCycle['is_paused'] ? '1' : '0' }}">{{ $activeCycle['display_time'] }}</span>
                                                </span>
                                            @endif
                                            <a href="{{ route('admin.service-requests.show', $activeReq) }}" onclick="event.stopPropagation();" class="inline-flex items-center gap-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-white px-2 py-0.5 text-[11px] font-mono font-bold shadow-2xs">
                                                #REQ-{{ str_pad($activeReq->id, 4, '0', STR_PAD_LEFT) }}
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-slate-400 font-mono text-[11px]">Idle / Standby</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-slate-500 text-xs">
                                    {{ $machine->branch->name ?? 'Main Branch' }}
                                </td>
                                <td class="py-3.5 pl-3 pr-5 text-right">
                                    <button type="button" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200/90 bg-white px-3.5 py-1.5 text-xs font-bold text-slate-800 hover:border-blue-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-2xs group">
                                        <span>Details</span>
                                        <svg class="h-3.5 w-3.5 text-slate-600 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">No machines found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            <x-pagination id="adminMachinesPagination" :pageSize="10" :pageSizes="[10, 20, 50]" />
        </div>
    </div>

    <!-- Machine Details, Edit & Delete Modal Overlay -->
    <div id="machineDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden transition-opacity duration-200 overflow-y-auto">
        <!-- Modal Card -->
        <div class="relative w-full max-w-lg rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-2xl space-y-6 animate-in fade-in zoom-in-95 duration-150 my-8 max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-start justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div id="modalMachineIcon" class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-500/20">
                        <!-- Icon swapped via JS -->
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 id="modalMachineTitle" class="text-xl font-extrabold tracking-tight text-slate-900">Machine Details</h2>
                            <span id="modalMachineStatusBadge" class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider"></span>
                        </div>
                        <p id="modalMachineSubtitle" class="text-xs text-slate-500 mt-0.5">Main Branch · ID: #001</p>
                    </div>
                </div>
                <button type="button" onclick="closeMachineModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Active Service Request Callout (If currently in use) -->
            <div id="modalActiveReqContainer" class="hidden rounded-2xl bg-blue-50 p-4 border border-blue-200">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="h-3 w-3 rounded-full bg-blue-600 animate-ping"></div>
                        <div>
                            <p class="text-xs font-mono font-bold uppercase tracking-wider text-blue-600">Currently In Cycle</p>
                            <p id="modalActiveReqText" class="text-sm font-extrabold text-blue-950 truncate mt-0.5">Customer: Walk-in</p>
                        </div>
                    </div>
                    <a id="modalActiveReqLink" href="#" class="inline-flex items-center gap-1 rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 text-xs font-bold shadow-sm transition shrink-0">
                        <span>View Order</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Quick Maintenance / Availability Toggle Box -->
            <div class="rounded-2xl bg-slate-50 p-4 border border-slate-200/80 flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-bold text-slate-900">Unit Operational Status</p>
                    <p class="text-xs text-slate-500 mt-0.5">Quickly toggle machine between Available and Maintenance.</p>
                </div>
                <form id="toggleMachineStatusForm" method="POST" action="">
                    @csrf
                    <button type="submit" id="modalToggleStatusBtn" class="inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold shadow-xs transition cursor-pointer active:scale-95">
                        <!-- Button content populated by JS -->
                    </button>
                </form>
            </div>

            <!-- Edit Machine Form -->
            <form id="editMachineForm" method="POST" action="" class="space-y-4 pt-2 border-t border-slate-100">
                @csrf
                @method('PUT')

                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-mono">Edit Machine Information</h3>

                @if($isOwner)
                <!-- Branch Field (Owner Only) -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Branch Location</label>
                    <div class="relative">
                        <select name="branch_id" id="editMachineBranch" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer appearance-none">
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }} @if($b->address)· {{ $b->address }}@endif</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Name Field -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Machine Name</label>
                    <input type="text" name="name" id="editMachineName" required class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-bold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                </div>

                <!-- Grid for Type & Status -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Type Field -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Type</label>
                        <select name="type" id="editMachineType" required class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer">
                            <option value="washer">Washer</option>
                            <option value="dryer">Dryer</option>
                        </select>
                    </div>

                    <!-- Status Select -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Status</label>
                        <select name="status" id="editMachineStatus" required class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer">
                            <option value="available">Available</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="in_use">In Use</option>
                        </select>
                    </div>
                </div>

                <!-- Grid for Brand & Model -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Brand Field -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Brand <span class="text-[10px] text-slate-400 font-normal">(Opt)</span></label>
                        <input type="text" name="brand" id="editMachineBrand" list="brandSuggestions" placeholder="e.g. Whirlpool" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>

                    <!-- Model Field -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Model <span class="text-[10px] text-slate-400 font-normal">(Opt)</span></label>
                        <input type="text" name="model" id="editMachineModel" placeholder="e.g. 9/11" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>
                </div>

                <!-- Save Changes Submit Button -->
                <div class="pt-2 flex justify-end">
                    <button type="submit" class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 transition cursor-pointer active:scale-98">
                        Save Changes
                    </button>
                </div>
            </form>

            @if($isOwner)
            <!-- Danger Zone: Delete Machine Form -->
            <div class="pt-4 border-t border-rose-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-rose-700">Delete Machine Unit</p>
                    <p class="text-[11px] text-slate-400">Permanently remove this unit from inventory.</p>
                </div>
                <form id="deleteMachineForm" method="POST" action="" onsubmit="return confirm('Are you sure you want to permanently delete this machine? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2 text-xs font-bold text-rose-700 hover:bg-rose-600 hover:text-white transition cursor-pointer active:scale-95 shadow-2xs">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Delete Machine</span>
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    @if($isOwner)
    <!-- Add Machine Modal Overlay -->
    <div id="addMachineModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 hidden transition-opacity duration-200">
        <!-- Modal Card -->
        <div class="relative w-full max-w-md rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-2xl space-y-6 animate-in fade-in zoom-in-95 duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Add New Machine</h2>
                        <p class="text-xs text-slate-400">Register new equipment to branch inventory</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddMachineModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('admin.machines.store') }}" class="space-y-4">
                @csrf

                <!-- Branch Location Selector (Owner Only) -->
                @if($isOwner)
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        Target Branch <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="branch_id" id="newMachineBranchSelect" required onchange="updateAutoMachineName()" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer appearance-none">
                            @foreach($branches as $branchOption)
                                <option value="{{ $branchOption->id }}" {{ (old('branch_id', auth('admin')->user()->branch_id) == $branchOption->id) ? 'selected' : '' }}>
                                    {{ $branchOption->name }} @if($branchOption->address)· {{ $branchOption->address }}@endif
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1 font-mono">Select which branch location this machine belongs to.</p>
                </div>
                @endif

                <!-- Machine Type -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        Machine Type <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center gap-3 rounded-2xl border border-slate-200 p-3 hover:bg-slate-50 transition cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/40">
                            <input type="radio" name="type" value="washer" checked onchange="updateAutoMachineName()" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                            <div>
                                <p class="text-xs font-bold text-slate-900">Washer</p>
                                <p class="text-[10px] text-slate-400">Washing machine</p>
                            </div>
                        </label>
                        <label class="relative flex items-center gap-3 rounded-2xl border border-slate-200 p-3 hover:bg-slate-50 transition cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/40">
                            <input type="radio" name="type" value="dryer" onchange="updateAutoMachineName()" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                            <div>
                                <p class="text-xs font-bold text-slate-900">Dryer</p>
                                <p class="text-[10px] text-slate-400">Tumble dryer</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Suggested / Custom Machine Name -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        Machine Identifier <span class="text-slate-400 font-normal">(Auto-generated)</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="name" id="newMachineNameInput" readonly tabindex="-1" onkeydown="return false;" placeholder="e.g. Washer #01" class="w-full rounded-2xl border border-slate-200 bg-slate-100/90 px-4 py-2.5 text-sm font-bold font-mono text-slate-700 cursor-not-allowed select-none focus:outline-none transition">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1 font-mono">Automatically assigned based on the selected branch and machine type.</p>
                </div>

                <!-- Brand & Model Grid -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Brand <span class="text-slate-400 font-normal">(Opt)</span></label>
                        <input type="text" name="brand" placeholder="e.g. LG, Whirlpool" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Model <span class="text-slate-400 font-normal">(Opt)</span></label>
                        <input type="text" name="model" placeholder="e.g. Commercial Pro" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeAddMachineModal()" class="rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-2xl bg-blue-600 px-5 py-2.5 text-xs font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 transition cursor-pointer">
                        Create Machine
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Client-side Scripts -->
    <script>
        @if($isOwner)
        // Auto-generation logic for Add Machine
        @php
            $branchNextNumbers = [];
            foreach ($branches as $b) {
                // Calculate next washer number
                $branchWashers = $machines->where('branch_id', $b->id)->where('type', 'washer');
                $maxW = 0;
                foreach ($branchWashers as $m) {
                    if (preg_match('/(\d+)/', $m->name, $matches)) {
                        $num = (int)$matches[1];
                        if ($num > $maxW) $maxW = $num;
                    }
                }
                $nextW = max($maxW, $branchWashers->count()) + 1;

                // Calculate next dryer number
                $branchDryers = $machines->where('branch_id', $b->id)->where('type', 'dryer');
                $maxD = 0;
                foreach ($branchDryers as $m) {
                    if (preg_match('/(\d+)/', $m->name, $matches)) {
                        $num = (int)$matches[1];
                        if ($num > $maxD) $maxD = $num;
                    }
                }
                $nextD = max($maxD, $branchDryers->count()) + 1;

                $branchNextNumbers[$b->id] = [
                    'washer' => 'Washer #' . str_pad($nextW, 2, '0', STR_PAD_LEFT),
                    'dryer' => 'Dryer #' . str_pad($nextD, 2, '0', STR_PAD_LEFT),
                ];
            }
        @endphp
        const branchNextNumbers = {!! json_encode($branchNextNumbers) !!};

        function updateAutoMachineName() {
            const branchSelect = document.getElementById('newMachineBranchSelect');
            const branchId = branchSelect ? branchSelect.value : '{{ $branches->first()->id ?? 1 }}';
            const typeRadio = document.querySelector('input[name="type"]:checked');
            const type = typeRadio ? typeRadio.value : 'washer';
            const nameInput = document.getElementById('newMachineNameInput');

            if (nameInput) {
                if (branchNextNumbers[branchId] && branchNextNumbers[branchId][type]) {
                    nameInput.value = branchNextNumbers[branchId][type];
                } else {
                    const fallbackType = type === 'washer' ? 'Washer' : 'Dryer';
                    nameInput.value = fallbackType + ' #01';
                }
            }
        }

        // Add Machine Modal controls
        function openAddMachineModal() {
            const modal = document.getElementById('addMachineModal');
            if (modal) {
                modal.classList.remove('hidden');
                updateAutoMachineName();
            }
        }

        function closeAddMachineModal() {
            const modal = document.getElementById('addMachineModal');
            if (modal) modal.classList.add('hidden');
        }
        @endif

        // Machine Details, Edit & Delete Modal controls
        function openMachineModal(id, name, type, brand, model, status, branch, branchId, createdAt, activeReq) {
            const modal = document.getElementById('machineDetailsModal');
            if (!modal) return;

            // Update Header & Icons
            document.getElementById('modalMachineTitle').textContent = name;
            document.getElementById('modalMachineSubtitle').textContent = `${branch} · ID: #${String(id).padStart(3, '0')}`;

            const iconContainer = document.getElementById('modalMachineIcon');
            const isWasher = type.toLowerCase() === 'washer';
            if (isWasher) {
                iconContainer.className = 'flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-500/20';
                iconContainer.innerHTML = '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>';
            } else {
                iconContainer.className = 'flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-md shadow-amber-500/20';
                iconContainer.innerHTML = '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /></svg>';
            }

            // Status Badge
            const statusBadge = document.getElementById('modalMachineStatusBadge');
            const statusNorm = status.toLowerCase();
            const isAvail = statusNorm === 'available';
            const isInUse = statusNorm === 'in_use';

            if (isAvail) {
                statusBadge.className = 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200';
                statusBadge.textContent = 'AVAILABLE';
            } else if (isInUse) {
                statusBadge.className = 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200';
                statusBadge.textContent = 'IN_USE';
            } else {
                statusBadge.className = 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200';
                statusBadge.textContent = 'MAINTENANCE';
            }

            // Active Request Callout
            const reqContainer = document.getElementById('modalActiveReqContainer');
            if (activeReq) {
                reqContainer.classList.remove('hidden');
                document.getElementById('modalActiveReqText').textContent = `Order #REQ-${String(activeReq.id).padStart(4, '0')} · Customer: ${activeReq.customer}`;
                document.getElementById('modalActiveReqLink').href = activeReq.url;
            } else {
                reqContainer.classList.add('hidden');
            }

            // Quick Toggle Status Button (Available <-> Maintenance)
            const toggleBtn = document.getElementById('modalToggleStatusBtn');
            const toggleForm = document.getElementById('toggleMachineStatusForm');
            toggleForm.action = `/admin/machines/${id}/toggle-status`;

            if (isAvail) {
                toggleBtn.className = 'inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold shadow-xs transition cursor-pointer bg-amber-100 text-amber-900 hover:bg-amber-200 border border-amber-300';
                toggleBtn.innerHTML = '<span>Mark as Maintenance</span>';
            } else {
                toggleBtn.className = 'inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold shadow-xs transition cursor-pointer bg-emerald-100 text-emerald-800 hover:bg-emerald-200 border border-emerald-300';
                toggleBtn.innerHTML = '<span>Mark as Available</span>';
            }

            // Populate Edit Form
            const editForm = document.getElementById('editMachineForm');
            editForm.action = `/admin/machines/${id}`;
            document.getElementById('editMachineName').value = name;
            document.getElementById('editMachineType').value = type.toLowerCase();
            document.getElementById('editMachineBrand').value = brand;
            document.getElementById('editMachineModel').value = model;

            const editBranchSelect = document.getElementById('editMachineBranch');
            if (editBranchSelect && branchId) {
                editBranchSelect.value = branchId;
            }

            // Map status in select
            const statusSelect = document.getElementById('editMachineStatus');
            if (isAvail) {
                statusSelect.value = 'available';
            } else if (isInUse) {
                statusSelect.value = 'in_use';
            } else {
                statusSelect.value = 'maintenance';
            }

            // Populate Delete Form
            const deleteForm = document.getElementById('deleteMachineForm');
            if (deleteForm) {
                deleteForm.action = `/admin/machines/${id}`;
            }

            modal.classList.remove('hidden');
        }

        function closeMachineModal() {
            const modal = document.getElementById('machineDetailsModal');
            if (modal) modal.classList.add('hidden');
        }

        // Close modals on backdrop click & ESC key
        document.getElementById('addMachineModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeAddMachineModal();
        });

        document.getElementById('machineDetailsModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeMachineModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddMachineModal();
                closeMachineModal();
            }
        });

        // Toolbar Dropdown / Popover controls
        let currentFilter = 'all';
        let currentBranch = 'all';
        let currentSort = 'id_asc';
        let currentView = localStorage.getItem('admin_machines_view') || 'grid';

        function toggleControlPopover(targetId, event) {
            if (event) event.stopPropagation();
            const target = document.getElementById(targetId);
            if (!target) return;
            const isCurrentlyOpen = !target.classList.contains('hidden');

            // Close admin notification popover if open
            const adminNotif = document.getElementById('adminNotifPopover');
            if (adminNotif) adminNotif.classList.add('hidden');

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

        // Active Filter & Sort Pill display manager
        const machineSortConfig = {
            name: {
                label: 'Name',
                ascKey: 'name_asc',
                descKey: 'name_desc',
                ascText: '▲ A-Z',
                descText: '▼ Z-A',
                ascLabel: 'Name A-Z',
                descLabel: 'Name Z-A',
            },
            type: {
                label: 'Type',
                ascKey: 'type_washer',
                descKey: 'type_dryer',
                ascText: '▲ Washers',
                descText: '▼ Dryers',
                ascLabel: 'Washers first',
                descLabel: 'Dryers first',
            },
            status: {
                label: 'Status',
                ascKey: 'status_avail',
                descKey: 'status_maint',
                ascText: '▲ Avail',
                descText: '▼ Maint',
                ascLabel: 'Available first',
                descLabel: 'Maintenance first',
            },
            id: {
                label: 'ID',
                ascKey: 'id_asc',
                descKey: 'id_desc',
                ascText: '▲ 1-9',
                descText: '▼ 9-1',
                ascLabel: 'ID Lowest',
                descLabel: 'ID Highest',
            },
        };

        function toggleMachineSort(category) {
            const config = machineSortConfig[category];
            if (!config) return;

            let nextSort;
            if (currentSort === config.ascKey) {
                nextSort = config.descKey;
            } else if (currentSort === config.descKey) {
                nextSort = config.ascKey;
            } else {
                nextSort = config.ascKey;
            }

            setMachineSort(nextSort);
        }

        function updateMachineSortUI() {
            const isCustomSort = currentSort && currentSort !== 'id_asc';

            // Keep Sort By button in normal slate color (not highlighted blue)
            const sortBtn = document.getElementById('machineSortBtn');
            const sortIcon = document.getElementById('machineSortBtnIcon');
            const sortDot = document.getElementById('machineSortActiveDot');

            if (sortBtn) {
                sortBtn.className = 'relative inline-flex h-9 items-center gap-1.5 sm:gap-2 rounded-xl bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900 px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs';
            }
            if (sortIcon) {
                sortIcon.className = 'h-4 w-4 text-slate-500';
            }

            // Dot on Sort By button shows only the color of the selected sort attribute
            if (sortDot) {
                if (isCustomSort) {
                    const sortDotColors = {
                        'type_washer': 'bg-blue-500',
                        'type_dryer': 'bg-amber-500', // orange dot
                        'status_avail': 'bg-emerald-500',
                        'status_maint': 'bg-rose-500',
                        'name_asc': 'bg-blue-500',
                        'name_desc': 'bg-blue-500',
                        'id_desc': 'bg-blue-500',
                    };
                    const dotColor = sortDotColors[currentSort] || 'bg-blue-600';
                    sortDot.className = 'h-2 w-2 rounded-full ' + dotColor;
                } else {
                    sortDot.className = 'hidden h-2 w-2 rounded-full';
                }
            }

            // Toggle Clear X button in sort popover header
            const sortClearBtn = document.getElementById('machineSortClearBtn');
            const sortDefIcon = document.getElementById('machineSortDefaultIcon');
            if (sortClearBtn) {
                if (isCustomSort) {
                    sortClearBtn.classList.remove('hidden');
                    sortClearBtn.classList.add('inline-flex');
                } else {
                    sortClearBtn.classList.add('hidden');
                    sortClearBtn.classList.remove('inline-flex');
                }
            }
            if (sortDefIcon) {
                if (isCustomSort) {
                    sortDefIcon.classList.add('hidden');
                } else {
                    sortDefIcon.classList.remove('hidden');
                }
            }

            // Update Popover items
            Object.keys(machineSortConfig).forEach(cat => {
                const conf = machineSortConfig[cat];
                const btn = document.getElementById('machineSortItem-' + cat);
                if (!btn) return;

                const icon = btn.querySelector('.machine-sort-icon');
                const indicator = btn.querySelector('.machine-sort-indicator');

                const isAsc = currentSort === conf.ascKey;
                const isDesc = currentSort === conf.descKey;
                const isActive = isAsc || isDesc;

                if (isActive) {
                    btn.className = 'machine-sort-item flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-bold bg-blue-50 text-blue-700 transition cursor-pointer';
                    if (icon) icon.className = 'h-4 w-4 text-blue-600 machine-sort-icon';
                    if (indicator) {
                        indicator.className = 'machine-sort-indicator inline-flex items-center gap-1 font-mono text-[10px] font-bold text-blue-700 bg-blue-100/80 px-1.5 py-0.5 rounded-md';
                        indicator.textContent = isAsc ? conf.ascText : conf.descText;
                    }
                } else {
                    btn.className = 'machine-sort-item flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition cursor-pointer';
                    if (icon) icon.className = 'h-4 w-4 text-slate-400 machine-sort-icon';
                    if (indicator) {
                        indicator.className = 'machine-sort-indicator text-slate-300 text-xs font-mono';
                        indicator.textContent = '↕';
                    }
                }

                // Also update table header indicators if present
                const thInd = document.getElementById('thSortIndicator-' + cat);
                if (thInd) {
                    if (isAsc) {
                        thInd.className = 'text-xs text-blue-600 font-bold';
                        thInd.textContent = '▲';
                    } else if (isDesc) {
                        thInd.className = 'text-xs text-blue-600 font-bold';
                        thInd.textContent = '▼';
                    } else {
                        thInd.className = 'text-xs text-slate-400 group-hover:text-blue-600';
                        thInd.textContent = '↕';
                    }
                }
            });
        }

        function updateActiveControlsPill() {
            const pill = document.getElementById('machineActiveClearPill');
            if (!pill) return;

            const hasFilter = currentFilter && currentFilter !== 'all';
            const hasBranch = currentBranch && currentBranch !== 'all';
            const hasSort = currentSort && currentSort !== 'id_asc';

            // Show isolated X only when a filter, branch, or non-default sort is active
            if (!hasFilter && !hasBranch && !hasSort) {
                pill.style.display = 'none';
            } else {
                pill.style.display = 'inline-flex';
            }
        }

        function clearActiveMachineControls() {
            setMachineFilter('all', 'All Units');
            if (typeof setMachineBranch === 'function') {
                setMachineBranch('all', 'All Branches');
            }
            setMachineSort('id_asc');
        }

        // Branch filter functionality (Owner only)
        function setMachineBranch(branchId, branchLabel) {
            currentBranch = String(branchId);

            // Checkmark update in popover
            document.querySelectorAll('.branch-check-item').forEach(el => el.classList.add('hidden'));
            const activeCheck = document.querySelector('.branch-check-' + branchId);
            if (activeCheck) activeCheck.classList.remove('hidden');

            // Button styling & Dot update
            const branchDot = document.getElementById('machineBranchActiveDot');
            const branchBtn = document.getElementById('machineBranchBtn');
            const branchLabelEl = document.getElementById('machineBranchBtnLabel');

            if (branchId !== 'all') {
                if (branchDot) branchDot.classList.remove('hidden');
                if (branchBtn) {
                    branchBtn.className = 'relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl bg-purple-50 border border-purple-200 text-purple-700 font-bold px-2.5 sm:px-3 text-xs transition active:scale-95 cursor-pointer shadow-2xs';
                }
                if (branchLabelEl) {
                    branchLabelEl.textContent = branchLabel.length > 12 ? branchLabel.substring(0, 10) + '...' : branchLabel;
                }
            } else {
                if (branchDot) branchDot.classList.add('hidden');
                if (branchBtn) {
                    branchBtn.className = 'relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900 px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs';
                }
                if (branchLabelEl) {
                    branchLabelEl.textContent = 'Branch';
                }
            }

            // Close popover
            document.getElementById('machineBranchPopover')?.classList.add('hidden');

            // Update square metric cards dynamically
            updateMetricSquares();

            // Update clear pill visibility
            updateActiveControlsPill();

            // Filter the machines table and grid
            filterMachines();
        }

        function updateMetricSquares() {
            const cards = Array.from(document.querySelectorAll('#machinesGrid .machine-card'));
            
            // Filter cards by currentBranch
            const branchCards = cards.filter(card => {
                if (!currentBranch || currentBranch === 'all') return true;
                return (card.getAttribute('data-branch-id') || '') === String(currentBranch);
            });

            const totalCount = branchCards.length;
            const washersCount = branchCards.filter(c => c.getAttribute('data-type') === 'washer').length;
            const dryersCount = branchCards.filter(c => c.getAttribute('data-type') === 'dryer').length;
            const availableCount = branchCards.filter(c => c.getAttribute('data-status') === 'available').length;
            const inUseCount = branchCards.filter(c => c.getAttribute('data-status') === 'in_use').length;
            const maintCount = branchCards.filter(c => c.getAttribute('data-status') === 'maintenance').length;

            const elTotal = document.getElementById('metricTotalMachines');
            const elWashers = document.getElementById('metricTotalWashers');
            const elDryers = document.getElementById('metricTotalDryers');
            const elAvailable = document.getElementById('metricTotalAvailable');

            if (elTotal) elTotal.textContent = totalCount;
            if (elWashers) elWashers.textContent = washersCount;
            if (elDryers) elDryers.textContent = dryersCount;
            if (elAvailable) elAvailable.textContent = availableCount;

            // Also update counts in filter popover
            const fcAll = document.getElementById('filterCount-all');
            const fcWasher = document.getElementById('filterCount-washer');
            const fcDryer = document.getElementById('filterCount-dryer');
            const fcAvail = document.getElementById('filterCount-available');
            const fcInUse = document.getElementById('filterCount-in_use');
            const fcMaint = document.getElementById('filterCount-maintenance');

            if (fcAll) fcAll.textContent = totalCount;
            if (fcWasher) fcWasher.textContent = washersCount;
            if (fcDryer) fcDryer.textContent = dryersCount;
            if (fcAvail) fcAvail.textContent = availableCount;
            if (fcInUse) fcInUse.textContent = inUseCount;
            if (fcMaint) fcMaint.textContent = maintCount;
        }

        // Filter functionality
        function setMachineFilter(filterKey, label) {
            currentFilter = filterKey;

            // Checkmark update in popover
            document.querySelectorAll('.filter-check-item').forEach(el => el.classList.add('hidden'));
            const activeCheck = document.querySelector('.filter-check-' + filterKey);
            if (activeCheck) activeCheck.classList.remove('hidden');

            // Keep Filter button in normal slate color (not highlighted blue)
            const filterBtn = document.getElementById('machineFilterBtn');
            if (filterBtn) {
                filterBtn.className = 'relative inline-flex h-9 items-center gap-1.5 sm:gap-2 rounded-xl bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900 px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs';
            }

            // Dot on Filter button shows only the color of the selected attribute (e.g. orange dot for dryers)
            const filterDot = document.getElementById('machineFilterDot');
            if (filterDot) {
                const filterDotColors = {
                    'washer': 'bg-blue-500',
                    'dryer': 'bg-amber-500', // orange dot for dryer
                    'available': 'bg-emerald-500',
                    'in_use': 'bg-blue-600',
                    'maintenance': 'bg-rose-500',
                };
                if (filterKey !== 'all' && filterDotColors[filterKey]) {
                    filterDot.className = 'h-2 w-2 rounded-full ' + filterDotColors[filterKey];
                } else {
                    filterDot.className = 'hidden h-2 w-2 rounded-full';
                }
            }

            // Close popover
            document.getElementById('machineFilterPopover')?.classList.add('hidden');

            // Update clear pill visibility (isolated X)
            updateActiveControlsPill();

            filterMachines();
        }

        // Sort functionality
        function setMachineSort(sortKey) {
            currentSort = sortKey;

            updateMachineSortUI();

            // Reorder Grid Cards
            sortElements('#machinesGrid', '.machine-card', sortKey);
            // Reorder Table Rows
            sortElements('#machinesTableContainer tbody', '.machine-table-row', sortKey);

            // Update clear pill visibility
            updateActiveControlsPill();

            if (machinesPaginator) {
                machinesPaginator.update();
            }
        }

        function sortElements(containerSelector, itemSelector, sortKey) {
            const container = document.querySelector(containerSelector);
            if (!container) return;
            const items = Array.from(container.querySelectorAll(itemSelector));
            if (items.length === 0) return;

            items.sort((a, b) => {
                const idA = parseInt(a.getAttribute('data-id') || '0', 10);
                const idB = parseInt(b.getAttribute('data-id') || '0', 10);
                const nameA = (a.getAttribute('data-name') || '').toLowerCase();
                const nameB = (b.getAttribute('data-name') || '').toLowerCase();
                const typeA = a.getAttribute('data-type') || '';
                const typeB = b.getAttribute('data-type') || '';
                const statusA = a.getAttribute('data-status') || '';
                const statusB = b.getAttribute('data-status') || '';

                if (sortKey === 'name_asc') return nameA.localeCompare(nameB);
                if (sortKey === 'name_desc') return nameB.localeCompare(nameA);
                if (sortKey === 'type_washer') {
                    if (typeA === typeB) return idA - idB;
                    return typeA === 'washer' ? -1 : 1;
                }
                if (sortKey === 'type_dryer') {
                    if (typeA === typeB) return idA - idB;
                    return typeA === 'dryer' ? -1 : 1;
                }
                if (sortKey === 'status_avail') {
                    const priority = { 'available': 1, 'in_use': 2, 'maintenance': 3 };
                    const pA = priority[statusA] || 99;
                    const pB = priority[statusB] || 99;
                    if (pA !== pB) return pA - pB;
                    return idA - idB;
                }
                if (sortKey === 'status_maint') {
                    const priority = { 'maintenance': 1, 'in_use': 2, 'available': 3 };
                    const pA = priority[statusA] || 99;
                    const pB = priority[statusB] || 99;
                    if (pA !== pB) return pA - pB;
                    return idA - idB;
                }
                if (sortKey === 'id_desc') return idB - idA;
                return idA - idB; // default id_asc
            });

            items.forEach(item => container.appendChild(item));
        }

        // View layout switching
        function switchMachineView(mode) {
            currentView = mode;
            localStorage.setItem('admin_machines_view', mode);

            const grid = document.getElementById('machinesGrid');
            const table = document.getElementById('machinesTableContainer');
            const checkGrid = document.getElementById('checkMachineGrid');
            const checkTable = document.getElementById('checkMachineTable');

            if (mode === 'table') {
                grid?.classList.add('hidden');
                table?.classList.remove('hidden');
                checkGrid?.classList.add('hidden');
                checkTable?.classList.remove('hidden');
            } else {
                table?.classList.add('hidden');
                grid?.classList.remove('hidden');
                checkTable?.classList.add('hidden');
                checkGrid?.classList.remove('hidden');
            }

            document.getElementById('machineLayoutPopover')?.classList.add('hidden');
        }

        // Client-side Search, Filter & Pagination for both Grid and Table
        let machinesPaginator;

        function filterMachines() {
            if (machinesPaginator) {
                machinesPaginator.reset();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            switchMachineView(currentView);
            updateMachineSortUI();
            updateActiveControlsPill();

            machinesPaginator = new ClientPaginator({
                containerId: 'adminMachinesPagination',
                pageSize: 10,
                getItems: () => {
                    const query = (document.getElementById('machineSearch')?.value || '').toLowerCase().trim();
                    const cards = Array.from(document.querySelectorAll('#machinesGrid .machine-card'));

                    return cards.filter(el => {
                        const name = el.getAttribute('data-name') || '';
                        const brand = el.getAttribute('data-brand') || '';
                        const model = el.getAttribute('data-model') || '';
                        const branch = el.getAttribute('data-branch') || '';
                        const type = el.getAttribute('data-type') || '';
                        const status = el.getAttribute('data-status') || '';
                        const request = el.getAttribute('data-request') || '';

                        const matchesSearch = !query || name.includes(query) || brand.includes(query) || model.includes(query) || branch.includes(query) || request.includes(query);
                        let matchesFilter = true;

                        if (currentFilter === 'washer') matchesFilter = (type === 'washer');
                        else if (currentFilter === 'dryer') matchesFilter = (type === 'dryer');
                        else if (currentFilter === 'available') matchesFilter = (status === 'available');
                        else if (currentFilter === 'in_use') matchesFilter = (status === 'in_use');
                        else if (currentFilter === 'maintenance') matchesFilter = (status === 'maintenance');

                        let matchesBranch = true;
                        if (currentBranch && currentBranch !== 'all') {
                            matchesBranch = (el.getAttribute('data-branch-id') || '') === String(currentBranch);
                        }

                        return matchesSearch && matchesFilter && matchesBranch;
                    });
                },
                onPageChange: (visibleSlice) => {
                    const visibleIds = new Set(visibleSlice.map(c => c.getAttribute('data-id')));
                    document.querySelectorAll('#machinesGrid .machine-card').forEach(card => {
                        if (visibleIds.has(card.getAttribute('data-id'))) {
                            card.classList.remove('hidden');
                        } else {
                            card.classList.add('hidden');
                        }
                    });
                    document.querySelectorAll('#machinesTableContainer .machine-table-row').forEach(row => {
                        if (visibleIds.has(row.getAttribute('data-id'))) {
                            row.classList.remove('hidden');
                        } else {
                            row.classList.add('hidden');
                        }
                    });
                }
            });
        });

        @if($isOwner && isset($errors) && $errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                openAddMachineModal();
            });
        @endif

        // Live Machine Cycle Countdown Ticker
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
