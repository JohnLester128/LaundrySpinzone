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
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between pb-6 border-b border-slate-100">
            <!-- Left Side: Search Bar -->
            <div class="relative w-full sm:w-64 lg:w-60 xl:w-72 shrink-0">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Search ID, type, branch..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
            </div>

            <!-- Right Side: Filter Tabs + 3-Dots Menu Container -->
            <div class="flex items-center gap-1.5 sm:gap-2 min-w-0">
                <!-- Filter Tabs: All to Cancelled -->
                <div class="flex items-center gap-1.5 overflow-x-auto flex-nowrap max-w-full pb-1 lg:pb-0 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <a href="{{ route('customer.requests', ['filter' => 'all', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        All
                    </a>
                    <a href="{{ route('customer.requests', ['filter' => 'pending', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'pending' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Pending
                    </a>
                    <a href="{{ route('customer.requests', ['filter' => 'received', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'received' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Received
                    </a>
                    <a href="{{ route('customer.requests', ['filter' => 'washing', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'washing' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Washing
                    </a>
                    <a href="{{ route('customer.requests', ['filter' => 'drying', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'drying' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Drying
                    </a>
                    <a href="{{ route('customer.requests', ['filter' => 'ready', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'ready' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Ready
                    </a>
                    <a href="{{ route('customer.requests', ['filter' => 'completed', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'completed' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Completed
                    </a>
                    <a href="{{ route('customer.requests', ['filter' => 'cancelled', 'sort' => $sort]) }}" class="rounded-xl px-3 py-2 text-xs font-semibold whitespace-nowrap shrink-0 transition {{ $filter === 'cancelled' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Cancelled
                    </a>
                </div>

                <!-- 3-Dots Button with View & Sort Menu Popover -->
                <div class="relative inline-block text-left shrink-0 z-30" id="optionsMenuWrapper">
                    <button type="button" id="optionsMenuBtn" onclick="toggleDropdown('optionsMenuPopover', event)" class="flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-xl bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition active:scale-95 cursor-pointer" title="View & Sort options">
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
                            <a href="{{ route('customer.requests', ['filter' => $filter, 'sort' => $opt['next_sort']]) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $opt['is_active'] ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
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

        <!-- Requests Grid View -->
        <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3 transition-all duration-300" id="requestsGrid">
            @forelse($requests as $request)
                @php
                    $accentColor = match($request->status) {
                        'completed' => 'bg-emerald-500',
                        'washing' => 'bg-purple-600',
                        'drying' => 'bg-indigo-600',
                        'ready_for_pickup', 'ready' => 'bg-teal-500',
                        'received' => 'bg-slate-500',
                        'cancelled' => 'bg-rose-500',
                        default => 'bg-amber-500'
                    };

                    $badgeClass = match($request->status) {
                        'completed' => 'bg-emerald-100 text-emerald-700',
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

                    $statusGroup = match($request->status) {
                        'ready_for_pickup' => 'ready',
                        default => $request->status
                    };
                @endphp

                <div class="request-card relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:shadow-md duration-200 flex flex-col justify-between" data-status="{{ $statusGroup }}" data-search="{{ strtolower($request->id . ' ' . ($request->clothType->name ?? '') . ' ' . ($request->branch->name ?? '') . ' ' . $request->status) }}">
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

                        <!-- Main Item Name -->
                        <div class="mt-3">
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">
                                {{ $request->clothType->name ?? 'Standard Laundry' }}
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $request->branch->name ?? 'Main Branch' }}
                            </p>
                        </div>

                        <!-- Specifications Grid -->
                        <div class="mt-4 grid grid-cols-2 gap-2 rounded-xl bg-slate-50/70 p-3 text-xs border border-slate-100">
                            <div>
                                <span class="font-mono text-[10px] text-slate-400 uppercase tracking-wider block">Quantity</span>
                                <span class="font-bold text-slate-800">{{ $request->quantity ?? 1 }} item(s)</span>
                            </div>
                            <div>
                                <span class="font-mono text-[10px] text-slate-400 uppercase tracking-wider block">Est. Weight</span>
                                <span class="font-bold text-slate-800">
                                    {{ $request->estimated_weight ? $request->estimated_weight . ' kg' : ($request->comforter_size ?: 'Standard') }}
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
                        <a href="{{ route('customer.requests.show', $request) }}" class="rounded-xl bg-slate-100 px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-blue-600 hover:text-white transition flex items-center gap-1">
                            <span>Track Order</span>
                            <span>→</span>
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
                            <th class="px-5 py-3 font-semibold">Branch</th>
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
                                $badgeClass = match($request->status) {
                                    'completed' => 'bg-emerald-100 text-emerald-700',
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

                                $statusGroup = match($request->status) {
                                    'ready_for_pickup' => 'ready',
                                    default => $request->status
                                };
                            @endphp
                            <tr class="request-table-row hover:bg-slate-50/60 transition" data-status="{{ $statusGroup }}" data-search="{{ strtolower($request->id . ' ' . ($request->clothType->name ?? '') . ' ' . ($request->branch->name ?? '') . ' ' . $request->status) }}">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900">
                                    #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-slate-900">
                                    {{ $request->clothType->name ?? 'Standard' }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 font-medium">
                                    {{ $request->branch->name ?? 'Main Branch' }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 font-medium">
                                    {{ $request->estimated_weight ? $request->estimated_weight . ' kg' : ($request->comforter_size ?: '—') }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 font-medium">
                                    {{ $request->quantity ?? 1 }}
                                </td>
                                <td class="px-5 py-3.5 font-mono font-bold text-blue-700">
                                    ₱{{ number_format($request->estimated_price, 2) }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('customer.requests.show', $request) }}" class="rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-blue-600 hover:text-white transition">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-slate-400 font-medium">
                                    No requests found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Interactive Scripts for View Toggle, Instant Search & Popovers -->
    <script>
        // 1. Popover Menu Toggle
        function toggleDropdown(menuId, event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById(menuId);
            if (!menu) return;
            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            const popover = document.getElementById('optionsMenuPopover');
            const wrapper = document.getElementById('optionsMenuWrapper');
            if (popover && !popover.classList.contains('hidden')) {
                if (wrapper && !wrapper.contains(e.target)) {
                    popover.classList.add('hidden');
                }
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

            const popover = document.getElementById('optionsMenuPopover');
            if (popover) popover.classList.add('hidden');
        }

        // Initialize preferred view
        document.addEventListener('DOMContentLoaded', () => {
            const savedView = localStorage.getItem('customer_requests_view') || 'grid';
            changeView(savedView);
        });

        // 3. Instant Live Search
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase().trim();
                
                // Filter Grid Cards
                document.querySelectorAll('.request-card').forEach(card => {
                    const searchData = card.getAttribute('data-search') || '';
                    if (searchData.includes(term)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Filter Table Rows
                document.querySelectorAll('.request-table-row').forEach(row => {
                    const searchData = row.getAttribute('data-search') || '';
                    if (searchData.includes(term)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    </script>
</x-customer-layout>
