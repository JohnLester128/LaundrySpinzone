<x-admin-layout>
    <!-- Stat Cards Grid -->
    <div class="grid gap-6 md:grid-cols-3">
        <!-- Card 1: Total Sales -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md">
            <div class="absolute top-0 inset-x-0 h-1 bg-blue-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Total Sales</p>
                    <h3 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        ₱{{ number_format($salesTotal, 0) }}
                    </h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-slate-500">
                <span class="inline-block h-2 w-2 rounded-full bg-blue-500 mr-1.5"></span>
                <span>Branch revenue from completed orders</span>
            </div>
        </div>

        <!-- Card 2: Active vs Total Requests -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md">
            <div class="absolute top-0 inset-x-0 h-1 bg-emerald-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Active / Total Requests</p>
                    <h3 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        {{ $activeCount }} <span class="text-lg font-medium text-slate-400">/ {{ $requestCount }}</span>
                    </h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-teal-500 text-white shadow-md shadow-teal-400/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-emerald-600 transition-all duration-500" style="width: {{ $requestCount > 0 ? min(100, round(($activeCount / $requestCount) * 100)) : 0 }}%;"></div>
            </div>
        </div>

        <!-- Card 3: Received / In-Store Laundry -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md">
            <div class="absolute top-0 inset-x-0 h-1 bg-purple-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Received (In-Store)</p>
                    <h3 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        {{ $pendingCount }}
                    </h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-500 text-white shadow-md shadow-purple-500/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium {{ $pendingCount > 0 ? 'text-purple-700' : 'text-slate-400' }}">
                <span class="inline-block h-2 w-2 rounded-full {{ $pendingCount > 0 ? 'bg-purple-600 animate-pulse' : 'bg-slate-300' }} mr-1.5"></span>
                <span>{{ $pendingCount > 0 ? $pendingCount . ' orders awaiting wash / action' : 'No received orders waiting' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Section: Recent Activity Table (Left) + Quick Actions (Right) -->
    <div class="mt-7 grid gap-6 lg:grid-cols-3">
        <!-- Recent Activity Table (spans 2 columns) -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-slate-900">Recent Activity</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Latest service requests and updates at your branch.</p>
                </div>
                <a class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline transition" href="{{ route('admin.service-requests') }}">
                    View all →
                </a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Order ID</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Customer</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Category</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400 text-right">Time</th>
                            <th class="pb-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-400 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($requests as $request)
                            @php
                                $statusBadgeClass = match($request->status) {
                                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'received' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'washing' => 'bg-purple-100 text-purple-700 border-purple-200',
                                    'drying' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                    'ready', 'ready_for_pickup' => 'bg-teal-100 text-teal-700 border-teal-200',
                                    'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200'
                                };
                            @endphp
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="py-4 font-mono font-bold text-blue-900 text-xs">
                                    #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-4 text-sm font-semibold text-slate-900">
                                    {{ $request->customer->username ?? 'Walk-in Customer' }}
                                </td>
                                <td class="py-4">
                                    <span class="inline-block rounded-full px-2.5 py-0.5 text-[11px] font-bold border uppercase tracking-wider {{ $statusBadgeClass }}">
                                        {{ str_replace('_', ' ', $request->status) }}
                                    </span>
                                </td>
                                <td class="py-4 text-xs font-medium text-slate-500">
                                    {{ $request->clothType->name ?? 'Laundry' }}
                                </td>
                                <td class="py-4 text-xs text-slate-400 text-right font-mono">
                                    {{ $request->updated_at ? $request->updated_at->diffForHumans() : ($request->created_at ? $request->created_at->diffForHumans() : 'Just now') }}
                                </td>
                                <td class="py-4 text-right">
                                    <a href="{{ route('admin.service-requests.show', $request->id) }}" class="inline-flex items-center gap-1 rounded-xl bg-slate-100 hover:bg-blue-50 hover:text-blue-600 px-3 py-1.5 text-xs font-bold text-slate-700 transition shadow-2xs">
                                        <span>Manage</span>
                                        <span>→</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-xs text-slate-400">
                                    <p class="font-semibold text-slate-600">No recent orders found</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">New service requests at this branch will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions Panel (Right Column) -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 mb-5">Quick Actions</h2>
                <div class="space-y-3.5">
                    <!-- New Service Request Button -->
                    <a class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-2xl py-3.5 px-4 font-semibold text-sm flex items-center justify-center gap-2.5 shadow-md shadow-blue-600/20 transition-all duration-200 active:scale-[0.99]" href="{{ route('admin.service-requests.create') }}">
                        <div class="flex h-5 w-5 items-center justify-center rounded-full bg-white/20">
                            <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span>New Service Request</span>
                    </a>

                    <!-- Export Report Button -->
                    <a class="w-full border-2 border-blue-600 text-blue-600 hover:bg-blue-50/60 rounded-2xl py-3.5 px-4 font-semibold text-sm flex items-center justify-center gap-2.5 transition-all duration-200 active:scale-[0.99]" href="{{ route('admin.sales') }}">
                        <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>View Sales Reports</span>
                    </a>
                </div>
            </div>

            <!-- System Status Indicator Card -->
            <div class="mt-6 rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1.5">System Status</p>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-emerald-800">All Systems Operational</span>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

