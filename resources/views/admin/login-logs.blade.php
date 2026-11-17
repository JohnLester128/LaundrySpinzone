<x-admin-layout>
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Login Logs</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Review authentication history, active sessions, and IP log entries.</p>
        </div>
        <div class="flex items-center gap-2 rounded-2xl border border-purple-100 bg-purple-50/60 px-4 py-2 text-xs font-bold text-purple-700">
            <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span>{{ count($logs) }} Log Entries</span>
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
        @php
            $loginLogSortConfig = [
                'date' => [
                    'label' => 'Time / Date',
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'is_active' => in_array($sort, ['newest', 'oldest']) || empty($sort),
                    'next_sort' => ($sort === 'newest' || empty($sort)) ? 'oldest' : 'newest',
                    'dir_indicator' => ($sort === 'oldest') ? '▲ Oldest' : '▼ Newest',
                ],
                'username' => [
                    'label' => 'User Identity',
                    'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                    'is_active' => in_array($sort, ['user_asc', 'user_desc']),
                    'next_sort' => ($sort === 'user_asc') ? 'user_desc' : 'user_asc',
                    'dir_indicator' => ($sort === 'user_desc') ? '▼ Z-A' : '▲ A-Z',
                ],
                'type' => [
                    'label' => 'User Type',
                    'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                    'is_active' => in_array($sort, ['type_admin', 'type_customer']),
                    'next_sort' => ($sort === 'type_admin') ? 'type_customer' : 'type_admin',
                    'dir_indicator' => ($sort === 'type_admin') ? 'Admins 1st' : 'Customers 1st',
                ],
                'ip' => [
                    'label' => 'IP Address',
                    'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9',
                    'is_active' => in_array($sort, ['ip_asc', 'ip_desc']),
                    'next_sort' => ($sort === 'ip_asc') ? 'ip_desc' : 'ip_asc',
                    'dir_indicator' => ($sort === 'ip_desc') ? '▼ DESC' : '▲ ASC',
                ],
                'status' => [
                    'label' => 'Status',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'is_active' => in_array($sort, ['status_asc', 'status_desc']),
                    'next_sort' => ($sort === 'status_asc') ? 'status_desc' : 'status_asc',
                    'dir_indicator' => ($sort === 'status_asc') ? '▲ Failed' : '▼ Success',
                ],
            ];

            $hasActiveSort = !empty($sort) && $sort !== 'newest';
            $hasActiveFilter = !empty($filter) && $filter !== 'all';
        @endphp

        <!-- Top Controls Bar: Search + Filter Tabs + Sort Popover -->
        <div class="pb-6 border-b border-slate-100 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-80">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="logSearch" placeholder="Search username, IP, or type..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
            </div>

            <!-- Right Controls: Filter Pills & Sort Popover -->
            <div class="flex flex-wrap items-center justify-between sm:justify-end gap-2.5 min-w-0 w-full lg:w-auto">
                <!-- Filter Pills -->
                <div class="flex flex-wrap items-center gap-1.5">
                    <a href="{{ route('admin.login-logs', ['filter' => 'all', 'sort' => $sort]) }}" class="rounded-xl px-3 sm:px-3.5 py-1.5 text-xs font-semibold transition {{ ($filter ?? 'all') === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        All
                    </a>
                    <a href="{{ route('admin.login-logs', ['filter' => 'success', 'sort' => $sort]) }}" class="rounded-xl px-3 sm:px-3.5 py-1.5 text-xs font-semibold transition {{ ($filter ?? '') === 'success' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Success
                    </a>
                    <a href="{{ route('admin.login-logs', ['filter' => 'failed', 'sort' => $sort]) }}" class="rounded-xl px-3 sm:px-3.5 py-1.5 text-xs font-semibold transition {{ ($filter ?? '') === 'failed' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Failed
                    </a>
                    <a href="{{ route('admin.login-logs', ['filter' => 'active', 'sort' => $sort]) }}" class="rounded-xl px-3 sm:px-3.5 py-1.5 text-xs font-semibold transition {{ ($filter ?? '') === 'active' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Active
                    </a>
                </div>

                <!-- 2. Sort By Button & Popover -->
                <div class="relative inline-block text-left control-dropdown-wrapper shrink-0 z-10">
                    <button type="button" onclick="toggleControlPopover('loginLogsSortPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl {{ $hasActiveSort ? 'bg-blue-50 border border-blue-200 text-blue-700 font-bold' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900' }} px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Sort logs">
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
                    <div id="loginLogsSortPopover" class="control-popover hidden absolute right-0 mt-2 w-60 sm:w-64 max-w-[calc(100vw-1.5rem)] max-h-[calc(100vh-140px)] overflow-y-auto [scrollbar-width:thin] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>Sort By</span>
                            @if($hasActiveSort)
                                <a href="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => 'newest']) }}" class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-500 hover:text-rose-700 transition" title="Clear sort">
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
                        @foreach($loginLogSortConfig as $key => $opt)
                            <a href="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => $opt['next_sort']]) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $opt['is_active'] ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
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
                                        <span onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('admin.login-logs', ['filter' => $filter, 'sort' => 'newest']) }}';" class="p-0.5 rounded text-blue-500 hover:bg-rose-100 hover:text-rose-600 transition cursor-pointer" title="Clear sort">
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

                <!-- Reset Filters & Sort Button -->
                @if($hasActiveFilter || $hasActiveSort)
                    <a href="{{ route('admin.login-logs') }}" class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-slate-100/80 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition cursor-pointer active:scale-95 shadow-2xs shrink-0" title="Reset filter & sort">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <!-- Login Logs Table -->
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left min-w-[700px]">
                <thead>
                    <tr class="border-b border-slate-100">
                        <!-- User Identity Header -->
                        @php
                            $userNextSort = ($sort === 'user_asc') ? 'user_desc' : 'user_asc';
                        @endphp
                        <th class="pb-3">
                            <a href="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => $userNextSort]) }}" class="inline-flex items-center gap-1 font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by User Identity">
                                <span>User Identity</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'user_asc') ▲ @elseif ($sort === 'user_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Type Header -->
                        @php
                            $typeNextSort = ($sort === 'type_admin') ? 'type_customer' : 'type_admin';
                        @endphp
                        <th class="pb-3">
                            <a href="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => $typeNextSort]) }}" class="inline-flex items-center gap-1 font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by User Type">
                                <span>Type</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'type_admin') ▲ @elseif ($sort === 'type_customer') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- IP Address Header -->
                        @php
                            $ipNextSort = ($sort === 'ip_asc') ? 'ip_desc' : 'ip_asc';
                        @endphp
                        <th class="pb-3">
                            <a href="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => $ipNextSort]) }}" class="inline-flex items-center gap-1 font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by IP Address">
                                <span>IP Address</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'ip_asc') ▲ @elseif ($sort === 'ip_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Login Time Header -->
                        @php
                            $timeNextSort = ($sort === 'newest') ? 'oldest' : 'newest';
                        @endphp
                        <th class="pb-3">
                            <a href="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => $timeNextSort]) }}" class="inline-flex items-center gap-1 font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Login Time">
                                <span>Login Time</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'newest') ▼ @elseif ($sort === 'oldest') ▲ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Session Logout Header -->
                        <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Session Logout
                        </th>

                        <!-- Status Header -->
                        @php
                            $statusNextSort = ($sort === 'status_asc') ? 'status_desc' : 'status_asc';
                        @endphp
                        <th class="pb-3 text-right">
                            <a href="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => $statusNextSort]) }}" class="inline-flex items-center justify-end gap-1 font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Status">
                                <span>Status</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'status_asc') ▲ @elseif ($sort === 'status_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="logsTableBody">
                    @forelse ($logs as $log)
                        @php
                            $initial = strtoupper(substr($log->username ?? 'G', 0, 1));
                            $typeBadgeClass = match(strtolower($log->user_type ?? '')) {
                                'admin' => 'bg-purple-100 text-purple-700',
                                'customer' => 'bg-blue-100 text-blue-700',
                                default => 'bg-slate-100 text-slate-600'
                            };
                        @endphp
                        <tr class="log-row transition hover:bg-slate-50/50" data-search="{{ strtolower(($log->username ?? '') . ' ' . ($log->ip_address ?? '') . ' ' . ($log->user_type ?? '') . ' ' . ($log->status ?? '')) }}">
                            <!-- User Identity -->
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-700 font-bold text-xs shadow-2xs">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 leading-tight">
                                            {{ $log->username ?? 'Guest User' }}
                                        </p>
                                        <p class="text-[11px] text-slate-400 font-mono">Log ID #{{ $log->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- User Type -->
                            <td class="py-4">
                                <span class="inline-block rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-wider {{ $typeBadgeClass }}">
                                    {{ $log->user_type ?? 'guest' }}
                                </span>
                            </td>

                            <!-- IP Address -->
                            <td class="py-4 font-mono text-xs font-semibold text-slate-600">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>

                            <!-- Login Time -->
                            <td class="py-4 text-xs font-medium text-slate-600">
                                {{ $log->login_at ? \Carbon\Carbon::parse($log->login_at)->format('M d, Y h:i A') : 'N/A' }}
                            </td>

                            <!-- Session Logout -->
                            <td class="py-4 text-xs font-medium text-slate-600">
                                @if ($log->status === 'failed')
                                    <span class="text-slate-400 font-mono">—</span>
                                @elseif ($log->logout_at)
                                    <span>{{ \Carbon\Carbon::parse($log->logout_at)->format('M d, Y h:i A') }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 border border-emerald-200/60">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Active Session</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-4 text-right">
                                @if ($log->status === 'success')
                                    <span class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                        Success
                                    </span>
                                @else
                                    <span class="inline-block rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">
                                        Failed
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-sm font-semibold text-slate-400">
                                No login log entries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <x-pagination id="adminLoginLogsPagination" :pageSize="10" :pageSizes="[10, 20, 50]" />
    </div>

    <!-- Client-side live search & Pagination script -->
    <script>
        let logsPaginator;

        document.addEventListener('DOMContentLoaded', function() {
            logsPaginator = new ClientPaginator({
                containerId: 'adminLoginLogsPagination',
                pageSize: 10,
                getItems: () => {
                    const query = (document.getElementById('logSearch')?.value || '').toLowerCase().trim();
                    const rows = Array.from(document.querySelectorAll('.log-row'));
                    return rows.filter(row => {
                        const searchData = row.getAttribute('data-search') || '';
                        return !query || searchData.includes(query);
                    });
                },
                onPageChange: (visibleSlice) => {
                    const visibleSet = new Set(visibleSlice);
                    document.querySelectorAll('.log-row').forEach(row => {
                        row.style.display = visibleSet.has(row) ? '' : 'none';
                    });
                }
            });
        });

        document.getElementById('logSearch')?.addEventListener('input', function(e) {
            if (logsPaginator) {
                logsPaginator.reset();
            }
        });

        // Popover Controls
        function toggleControlPopover(targetId, event) {
            if (event) event.stopPropagation();
            const target = document.getElementById(targetId);
            if (!target) return;
            const isCurrentlyOpen = !target.classList.contains('hidden');

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
    </script>
</x-admin-layout>
