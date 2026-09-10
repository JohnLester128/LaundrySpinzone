<x-admin-layout>
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Login Logs</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Review authentication history, active sessions, and IP log entries.</p>
        </div>
        <div class="flex items-center gap-2 rounded-2xl border border-purple-100 bg-purple-50/60 px-4 py-2 text-xs font-bold text-purple-700">
            <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span>{{ count($logs) }} Log Entries</span>
        </div>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Main Content Container -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
        <!-- Top Controls Bar: Search + Filter Tabs + Sort Dropdown -->
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

            <!-- Right Controls: Filter Pills & Sort Select -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Filter Pills -->
                <div class="flex flex-wrap items-center gap-1.5">
                    <a href="{{ route('admin.login-logs', ['filter' => 'all', 'sort' => $sort]) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-semibold transition {{ ($filter ?? 'all') === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        All
                    </a>
                    <a href="{{ route('admin.login-logs', ['filter' => 'success', 'sort' => $sort]) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-semibold transition {{ ($filter ?? '') === 'success' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Success
                    </a>
                    <a href="{{ route('admin.login-logs', ['filter' => 'failed', 'sort' => $sort]) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-semibold transition {{ ($filter ?? '') === 'failed' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Failed
                    </a>
                    <a href="{{ route('admin.login-logs', ['filter' => 'active', 'sort' => $sort]) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-semibold transition {{ ($filter ?? '') === 'active' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/60' }}">
                        Active Sessions
                    </a>
                </div>

                <!-- Sort Dropdown -->
                <div class="flex items-center gap-2">
                    <select id="sortSelect" onchange="window.location.href = this.value" class="rounded-2xl border border-slate-200 bg-slate-50/60 px-3 py-1.5 text-xs font-semibold text-slate-700 focus:bg-white focus:border-blue-500 outline-none transition cursor-pointer">
                        <option value="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => 'newest']) }}" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => 'oldest']) }}" {{ ($sort ?? '') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => 'type_admin']) }}" {{ ($sort ?? '') === 'type_admin' ? 'selected' : '' }}>User Type (Admins first)</option>
                        <option value="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => 'type_customer']) }}" {{ ($sort ?? '') === 'type_customer' ? 'selected' : '' }}>User Type (Customers first)</option>
                        <option value="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => 'user_asc']) }}" {{ ($sort ?? '') === 'user_asc' ? 'selected' : '' }}>Username (A to Z)</option>
                        <option value="{{ route('admin.login-logs', ['filter' => $filter, 'sort' => 'user_desc']) }}" {{ ($sort ?? '') === 'user_desc' ? 'selected' : '' }}>Username (Z to A)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Login Logs Table -->
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left">
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
                                        ✓ Success
                                    </span>
                                @else
                                    <span class="inline-block rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">
                                        ✕ Failed
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
    </div>

    <!-- Client-side live search script -->
    <script>
        document.getElementById('logSearch')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            document.querySelectorAll('.log-row').forEach(row => {
                const searchData = row.getAttribute('data-search') || '';
                row.style.display = searchData.includes(query) ? '' : 'none';
            });
        });
    </script>
</x-admin-layout>
