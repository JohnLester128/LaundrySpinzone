<x-admin-layout>
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Customers</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Manage registered customer accounts and account credentials.</p>
        </div>
        <div class="flex items-center gap-2 rounded-2xl border border-blue-100 bg-blue-50/60 px-4 py-2 text-xs font-bold text-blue-700">
            <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>{{ count($customers) }} Total Customers</span>
        </div>
    </div>

    <!-- Navigation Tabs: Settings Hub -->
    <div class="mb-4">
        <x-settings-tabs />
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Main Content Container -->
    <div class="rounded-2xl sm:rounded-3xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-sm">
        <!-- Top Controls: Search Input + 3-Dots Sort Menu Popover -->
        <div class="pb-6 border-b border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-80">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="customerSearch" placeholder="Search customer, ID, or phone..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
            </div>

            @php
                $hasActiveSort = !empty($sort) && $sort !== 'newest';
            @endphp
            <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                <!-- 3-Dots Button with Sort Menu Popover -->
                <div class="relative inline-block text-left shrink-0 z-30" id="optionsMenuWrapper">
                    <button type="button" id="optionsMenuBtn" onclick="toggleDropdown('optionsMenuPopover', event)" class="flex h-9 w-9 items-center justify-center rounded-xl {{ $hasActiveSort ? 'bg-blue-50 border border-blue-200 text-blue-700 font-bold' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900' }} transition active:scale-95 cursor-pointer shadow-2xs" title="Sort options">
                        <svg class="h-4 w-4 {{ $hasActiveSort ? 'text-blue-600' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01" />
                        </svg>
                    </button>

                    <!-- Light Popover Menu containing Sort By -->
                    <div id="optionsMenuPopover" class="control-popover hidden absolute right-0 mt-2 w-64 max-w-[calc(100vw-1.5rem)] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                        <!-- Sort By Section -->
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>Sort By</span>
                            @if($hasActiveSort)
                                <a href="{{ route('admin.customers', ['sort' => 'newest']) }}" class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-500 hover:text-rose-700 transition" title="Clear sort">
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
                                'id' => [
                                    'label' => 'Account ID',
                                    'icon' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14',
                                    'is_active' => in_array($sort, ['id_asc', 'id_desc']),
                                    'next_sort' => ($sort === 'id_asc') ? 'id_desc' : 'id_asc',
                                    'dir_indicator' => ($sort === 'id_desc') ? '▼ DESC' : '▲ ASC',
                                ],
                            ];
                        @endphp

                        @foreach($singleWordSorts as $key => $opt)
                            <a href="{{ route('admin.customers', ['sort' => $opt['next_sort']]) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $opt['is_active'] ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 {{ $opt['is_active'] ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $opt['icon'] }}" />
                                    </svg>
                                    <span>{{ $opt['label'] }}</span>
                                </div>
                                @if($opt['is_active'] && $hasActiveSort && ($key !== 'date' || $sort === 'oldest'))
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold text-blue-700 bg-blue-100/80 px-1.5 py-0.5 rounded-md">
                                            {{ $opt['dir_indicator'] }}
                                        </span>
                                        <span onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('admin.customers', ['sort' => 'newest']) }}';" class="p-0.5 rounded text-blue-500 hover:bg-rose-100 hover:text-rose-600 transition cursor-pointer" title="Clear sort">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </span>
                                    </div>
                                @elseif($opt['is_active'])
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

                <!-- Reset Sort Button -->
                @if($hasActiveSort)
                    <a href="{{ route('admin.customers', ['sort' => 'newest']) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100/80 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition shadow-2xs cursor-pointer shrink-0" title="Reset sort">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <!-- Customers Table -->
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left min-w-[600px]">
                <thead>
                    <tr class="border-b border-slate-100">
                        <!-- Customer Name Header -->
                        @php
                            $nameNextSort = ($sort === 'name_asc') ? 'name_desc' : 'name_asc';
                        @endphp
                        <th class="pb-3">
                            <a href="{{ route('admin.customers', ['sort' => $nameNextSort]) }}" class="inline-flex items-center gap-1 font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Name">
                                <span>Customer</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'name_asc') ▲ @elseif ($sort === 'name_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Phone Number Header -->
                        <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Phone Number
                        </th>

                        <!-- Joined Date Header -->
                        @php
                            $dateNextSort = ($sort === 'newest') ? 'oldest' : 'newest';
                        @endphp
                        <th class="pb-3">
                            <a href="{{ route('admin.customers', ['sort' => $dateNextSort]) }}" class="inline-flex items-center gap-1 font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Date">
                                <span>Joined Date</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'newest') ▼ @elseif ($sort === 'oldest') ▲ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400 text-right">Reset Password</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="customersTableBody">
                    @forelse ($customers as $customer)
                        @php
                            $initial = strtoupper(substr($customer->username ?? 'C', 0, 1));
                        @endphp
                        <tr class="customer-row transition hover:bg-slate-50/50" data-search="{{ strtolower(($customer->username ?? '') . ' ' . $customer->id . ' ' . ($customer->phone ?? '') . ' ' . str_pad($customer->id, 4, '0', STR_PAD_LEFT) . ' registered customer') }}">
                            <!-- Customer Info -->
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-xs shadow-2xs">
                                        {{ $initial }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 leading-tight truncate max-w-[180px] sm:max-w-xs" title="{{ $customer->username }}">
                                            {{ $customer->username }}
                                        </p>
                                        <p class="text-[11px] font-mono text-slate-400">
                                            #CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Phone Number -->
                            <td class="py-4 text-xs font-semibold text-slate-700 font-mono">
                                {{ $customer->phone ?? 'N/A' }}
                            </td>

                            <!-- Joined Date -->
                            <td class="py-4 text-xs font-medium text-slate-500">
                                {{ $customer->created_at ? $customer->created_at->format('M d, Y') : 'N/A' }}
                            </td>

                            <!-- Password Reset Form -->
                            <td class="py-4 text-right">
                                <form action="{{ route('admin.customers.reset', $customer->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="password" name="password" placeholder="New password" required class="rounded-xl border border-slate-200 bg-slate-50/60 px-3.5 py-2 text-xs text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition w-36 sm:w-44">
                                    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-3.5 py-2 shadow-xs transition active:scale-[0.99] shrink-0 flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        <span>Reset</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-sm font-semibold text-slate-400">
                                No registered customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <x-pagination id="adminCustomersPagination" :pageSize="10" :pageSizes="[10, 20, 50]" />
    </div>

    <!-- Client-side Scripts: Dropdown, Live Search & Pagination -->
    <script>
        let customersPaginator;

        function toggleDropdown(targetId, event) {
            if (event) event.stopPropagation();
            const target = document.getElementById(targetId);
            if (!target) return;
            const isCurrentlyOpen = !target.classList.contains('hidden');

            const adminNotif = document.getElementById('adminNotifPopover');
            if (adminNotif) adminNotif.classList.add('hidden');

            target.classList.toggle('hidden');
            if (!isCurrentlyOpen) {
                target.style.transform = '';
                if (typeof window.repositionPopover === 'function') {
                    window.repositionPopover(target);
                }
                requestAnimationFrame(() => {
                    if (typeof window.repositionPopover === 'function') {
                        window.repositionPopover(target);
                    }
                });
            } else {
                target.style.transform = '';
            }
        }

        // Close dropdown menus when clicking outside
        document.addEventListener('click', function(e) {
            const popover = document.getElementById('optionsMenuPopover');
            const wrapper = document.getElementById('optionsMenuWrapper');
            if (popover && !popover.classList.contains('hidden')) {
                if (!wrapper || !wrapper.contains(e.target)) {
                    popover.classList.add('hidden');
                    popover.style.transform = '';
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            customersPaginator = new ClientPaginator({
                containerId: 'adminCustomersPagination',
                pageSize: 10,
                getItems: () => {
                    const query = (document.getElementById('customerSearch')?.value || '').toLowerCase().trim();
                    const rows = Array.from(document.querySelectorAll('.customer-row'));
                    return rows.filter(row => {
                        const searchData = row.getAttribute('data-search') || '';
                        return !query || searchData.includes(query);
                    });
                },
                onPageChange: (visibleSlice) => {
                    const visibleSet = new Set(visibleSlice);
                    document.querySelectorAll('.customer-row').forEach(row => {
                        row.style.display = visibleSet.has(row) ? '' : 'none';
                    });
                }
            });
        });

        // Live search filter
        document.getElementById('customerSearch')?.addEventListener('input', function(e) {
            if (customersPaginator) {
                customersPaginator.reset();
            }
        });
    </script>
</x-admin-layout>

