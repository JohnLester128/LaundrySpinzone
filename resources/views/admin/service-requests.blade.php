<x-admin-layout>
    <!-- Header Section -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Service Requests</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Manage and track all ongoing laundry operations.</p>
        </div>
        <a class="bg-blue-600 hover:bg-blue-700 text-white rounded-2xl py-3 px-5 text-sm font-semibold shadow-md shadow-blue-600/20 inline-flex items-center gap-2 transition active:scale-[0.99]" href="{{ route('admin.service-requests.create') }}">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>New Request</span>
        </a>
    </div>

    <!-- Main Card Container -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
        <!-- Top Controls: Search Input + Filter Tabs with 3-Dots View/Sort Menu -->
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between pb-6 border-b border-slate-100">
            <!-- Left Side: Search Bar -->
            <div class="relative w-full sm:w-64 lg:w-60 xl:w-72 shrink-0">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Search customer, ID..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
            </div>

            <!-- Right Side: Filter Tabs + 3-Dots Menu Container -->
            <div class="flex items-center gap-1.5 sm:gap-2 min-w-0">
                <!-- Filter Tabs -->
                <div class="flex items-center gap-1.5 overflow-x-auto flex-nowrap max-w-full pb-1 lg:pb-0 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <a href="{{ route('admin.service-requests', ['filter' => 'all', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        All
                    </a>
                    <a href="{{ route('admin.service-requests', ['filter' => 'pending', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'pending' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Pending
                    </a>
                    <a href="{{ route('admin.service-requests', ['filter' => 'received', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'received' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Received
                    </a>
                    <a href="{{ route('admin.service-requests', ['filter' => 'washing', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'washing' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Washing
                    </a>
                    <a href="{{ route('admin.service-requests', ['filter' => 'drying', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'drying' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Drying
                    </a>
                    <a href="{{ route('admin.service-requests', ['filter' => 'ready', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'ready' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Ready
                    </a>
                    <a href="{{ route('admin.service-requests', ['filter' => 'completed', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'completed' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Completed
                    </a>
                    <a href="{{ route('admin.service-requests', ['filter' => 'cancelled', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'cancelled' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Cancelled
                    </a>
                </div>

                <!-- 3-Dots Button with View & Sort Menu Popover -->
                <div class="relative inline-block text-left shrink-0 z-30" id="optionsMenuWrapper">
                    <button type="button" id="optionsMenuBtn" onclick="toggleDropdown('optionsMenuPopover', event)" class="flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-xl bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition active:scale-95" title="View & Sort options">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01" />
                        </svg>
                    </button>

                    <!-- Light Popover Menu containing View & Sort -->
                    <div id="optionsMenuPopover" class="hidden absolute right-0 mt-2 w-64 rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                        <!-- View / Layout Section -->
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>View Layout</span>
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </div>
                        <button type="button" onclick="changeView('grid')" class="view-opt-btn flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100/80 hover:text-slate-900 transition" data-view="grid">
                            <div class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4z" />
                                </svg>
                                <span>Grid Cards</span>
                            </div>
                            <span class="view-check-grid text-blue-600 font-bold hidden">✓</span>
                        </button>
                        <button type="button" onclick="changeView('table')" class="view-opt-btn flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold hover:bg-slate-100/80 hover:text-slate-900 transition" data-view="table">
                            <div class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M3 18h18M3 6h18" />
                                </svg>
                                <span>Details Table</span>
                            </div>
                            <span class="view-check-table text-blue-600 font-bold hidden">✓</span>
                        </button>

                        <!-- Divider -->
                        <div class="my-1.5 border-t border-slate-100"></div>

                        <!-- Sort By Section -->
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>Sort By</span>
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                        </div>

                        @php
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
                                    'label' => 'Weight',
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
                        @endphp

                        @foreach($singleWordSorts as $key => $opt)
                            <a href="{{ route('admin.service-requests', ['filter' => $filter, 'sort' => $opt['next_sort']]) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $opt['is_active'] ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 {{ $opt['is_active'] ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $opt['icon'] }}" />
                                    </svg>
                                    <span>{{ $opt['label'] }}</span>
                                </div>
                                @if($opt['is_active'])
                                    <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold text-blue-700 bg-blue-100/80 px-1.5 py-0.5 rounded-md">
                                        {{ $opt['dir_indicator'] }}
                                    </span>
                                @else
                                    <span class="text-slate-300 text-xs font-mono">↕</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests Cards Container (Grid / List Views) -->
        <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3 transition-all duration-300" id="requestsGrid">
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
                        'completed' => '✓ Completed',
                        'washing' => '🧺 Washing',
                        'drying' => '💨 Drying',
                        'ready_for_pickup', 'ready' => '✓ Ready',
                        'received' => '📥 Received',
                        'pending' => '⏳ Pending',
                        'cancelled' => '✕ Cancelled',
                        default => ucfirst($request->status)
                    };
                @endphp

                <div class="request-card relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:shadow-md duration-200 flex flex-col justify-between" data-search="{{ strtolower(($request->customer->username ?? 'Walk-in') . ' ' . $request->id . ' ' . ($request->clothType->name ?? '')) }}">
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
                            <h3 class="text-xl font-bold tracking-tight text-slate-900 truncate">
                                {{ $request->customer->username ?? 'Walk-in Customer' }}
                            </h3>

                            @if($request->status === 'washing' && $request->washingMachine)
                                <span class="shrink-0 inline-flex items-center gap-1 font-mono text-[11px] font-extrabold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-xl border border-purple-200 shadow-2xs">
                                    🧼 {{ $request->washingMachine->name }}
                                </span>
                            @elseif($request->status === 'drying' && $request->dryerMachine)
                                <span class="shrink-0 inline-flex items-center gap-1 font-mono text-[11px] font-extrabold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-xl border border-indigo-200 shadow-2xs">
                                    💨 {{ $request->dryerMachine->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Weight & Items Details Grid -->
                        <div class="grid grid-cols-2 gap-4 py-3 border-t border-slate-100">
                            <div>
                                <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                    {{ $request->clothType?->requires_size || $request->comforter_size ? 'Size' : 'Weight' }}
                                </p>
                                <p class="text-sm font-bold text-slate-900 mt-0.5">
                                    @if($request->comforter_size)
                                        {{ $request->comforter_size }}
                                    @elseif($request->estimated_weight)
                                        {{ number_format($request->estimated_weight, 0) }} kg
                                    @else
                                        {{ $request->pricingTier->name ?? 'Standard' }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Items</p>
                                <p class="text-sm font-bold text-slate-900 mt-0.5 truncate">
                                    {{ $request->quantity }} {{ $request->clothType->name ?? 'Items' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer: Date + Details Link -->
                    <div class="border-t border-slate-100 mt-4 pt-3 flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-500">
                            {{ $request->created_at ? $request->created_at->format('M d, h:i A') : 'Today' }}
                        </span>
                        <a href="{{ route('admin.service-requests.show', $request) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline transition">
                            Details →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="mt-3 text-sm font-semibold text-slate-600">No service requests found</p>
                    <p class="text-xs text-slate-400 mt-1">Try switching tabs or creating a new request.</p>
                </div>
            @endforelse
        </div>

        <!-- Requests Table Container (Details Table View option) -->
        <div class="mt-6 overflow-x-auto hidden" id="requestsTable">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-mono border-b border-slate-200">
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

                        <!-- Weight Header -->
                        @php
                            $weightNextSort = ($sort === 'weight_desc') ? 'weight_asc' : 'weight_desc';
                        @endphp
                        <th class="px-4 py-3">
                            <a href="{{ route('admin.service-requests', ['filter' => $filter, 'sort' => $weightNextSort]) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Weight">
                                <span>Weight</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'weight_desc') ▼ @elseif ($sort === 'weight_asc') ▲ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Items Header -->
                        @php
                            $itemsNextSort = ($sort === 'items_desc') ? 'items_asc' : 'items_desc';
                        @endphp
                        <th class="px-4 py-3">
                            <a href="{{ route('admin.service-requests', ['filter' => $filter, 'sort' => $itemsNextSort]) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Items Quantity">
                                <span>Items</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'items_desc') ▼ @elseif ($sort === 'items_asc') ▲ @else ↕ @endif
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
                <tbody class="divide-y divide-slate-100">
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
                                'completed' => '✓ Completed',
                                'washing' => '🧺 Washing',
                                'drying' => '💨 Drying',
                                'ready_for_pickup', 'ready' => '✓ Ready',
                                'received' => '📥 Received',
                                'pending' => '⏳ Pending',
                                'cancelled' => '✕ Cancelled',
                                default => ucfirst($request->status)
                            };
                        @endphp
                        <tr class="table-row-item hover:bg-slate-50/80 transition" data-search="{{ strtolower(($request->customer->username ?? 'Walk-in') . ' ' . $request->id . ' ' . ($request->clothType->name ?? '')) }}">
                            <td class="px-4 py-3 font-mono text-xs font-semibold text-slate-500">
                                #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-900">
                                {{ $request->customer->username ?? 'Walk-in Customer' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="inline-block rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                    @if($request->status === 'washing' && $request->washingMachine)
                                        <span class="inline-flex items-center gap-1 font-mono text-[10px] font-extrabold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200 shadow-2xs">
                                            🧼 {{ $request->washingMachine->name }}
                                        </span>
                                    @elseif($request->status === 'drying' && $request->dryerMachine)
                                        <span class="inline-flex items-center gap-1 font-mono text-[10px] font-extrabold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-200 shadow-2xs">
                                            💨 {{ $request->dryerMachine->name }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-slate-800">
                                @if($request->comforter_size)
                                    <span class="text-purple-700 font-semibold">{{ $request->comforter_size }}</span>
                                @elseif($request->estimated_weight)
                                    {{ number_format($request->estimated_weight, 0) }} kg
                                @else
                                    {{ $request->pricingTier->name ?? '—' }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ $request->quantity }} {{ $request->clothType->name ?? 'Items' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                {{ $request->created_at ? $request->created_at->format('M d, h:i A') : 'Today' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.service-requests.show', $request) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                    Details →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Client-side Scripts: Dropdowns, View Switcher & Live Search -->
    <script>
        function toggleDropdown(targetId, event) {
            if (event) {
                event.stopPropagation();
            }
            const target = document.getElementById(targetId);
            if (target) {
                target.classList.toggle('hidden');
            }
        }

        // Close dropdown menus when clicking outside
        document.addEventListener('click', function(e) {
            const popover = document.getElementById('optionsMenuPopover');
            const wrapper = document.getElementById('optionsMenuWrapper');
            if (popover && !popover.classList.contains('hidden')) {
                if (!wrapper || !wrapper.contains(e.target)) {
                    popover.classList.add('hidden');
                }
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
                gridEl.className = 'mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3 transition-all duration-300';
                document.querySelector('.view-check-grid')?.classList.remove('hidden');
                mode = 'grid';
            }

            // Save preference in localStorage
            localStorage.setItem('admin_requests_view', mode);

            // Close options popover menu
            document.getElementById('optionsMenuPopover')?.classList.add('hidden');
        }

        // Load saved view on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('admin_requests_view') || 'grid';
            changeView(savedView);
        });

        // Live Search Input Filter
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            
            // Cards filter
            document.querySelectorAll('.request-card').forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                card.style.display = searchData.includes(query) ? '' : 'none';
            });

            // Table rows filter
            document.querySelectorAll('.table-row-item').forEach(row => {
                const searchData = row.getAttribute('data-search') || '';
                row.style.display = searchData.includes(query) ? '' : 'none';
            });
        });
    </script>
</x-admin-layout>
