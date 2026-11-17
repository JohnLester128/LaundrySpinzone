<x-admin-layout>
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Feedback &amp; Complaints</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">
                Monitor customer satisfaction ratings, suggestions, and reported operational issues across branches.
            </p>
        </div>
        <div class="flex items-center gap-2 rounded-2xl border border-blue-100 bg-blue-50/70 px-4 py-2 text-xs font-bold text-blue-700 shadow-2xs">
            <span>{{ $totalCount }} Total Submissions</span>
        </div>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Top Metric Cards Grid -->
    <div class="grid gap-4 sm:gap-5 grid-cols-2 lg:grid-cols-4 mb-5 sm:mb-7">
        <!-- Card 1: Average Rating (Feedback) -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <div class="absolute top-0 inset-x-0 h-1 bg-amber-500"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Avg Rating</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <h3 class="text-3xl font-black tracking-tight text-slate-900">
                            {{ $avgRating > 0 ? number_format($avgRating, 1) : '—' }}
                        </h3>
                        <span class="text-xs font-bold text-slate-400">/ 5.0</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-500 border border-amber-100 shadow-2xs">
                    <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-slate-500 font-mono">
                <span>{{ $feedbackCount }} feedback reviews</span>
            </div>
        </div>

        <!-- Card 2: 5-Star Satisfaction -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <div class="absolute top-0 inset-x-0 h-1 bg-emerald-500"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Satisfaction</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <h3 class="text-3xl font-black tracking-tight text-emerald-600">
                            {{ $fiveStarPct }}%
                        </h3>
                    </div>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-emerald-700 font-medium">
                <span>Rated 5-star experience</span>
            </div>
        </div>

        <!-- Card 3: Feedbacks Total -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <div class="absolute top-0 inset-x-0 h-1 bg-blue-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Feedbacks</p>
                    <h3 class="mt-2 text-3xl font-black tracking-tight text-blue-900">
                        {{ $feedbackCount }}
                    </h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-blue-600 font-medium">
                <span>Total reviews &amp; ratings</span>
            </div>
        </div>

        <!-- Card 4: Complaints Total -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <div class="absolute top-0 inset-x-0 h-1 bg-rose-500"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Complaints</p>
                    <h3 class="mt-2 text-3xl font-black tracking-tight text-rose-900">
                        {{ $complaintCount }}
                    </h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-600 border border-rose-100 shadow-2xs">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-rose-600 font-medium">
                <span>Reported customer issues</span>
            </div>
        </div>
    </div>

    <div class="rounded-2xl sm:rounded-3xl border border-slate-200/80 bg-white p-4 sm:p-6 shadow-sm">
        @php
            $hasActiveFilter = request()->filled('nature') || request()->filled('type') || request()->filled('rating') || request()->filled('branch_id') || request()->filled('status');
            $hasActiveSort = !empty($sort) && $sort !== 'newest';

            $complaintLabels = [
                'damaged_item' => 'Damaged Clothes',
                'missing_clothes' => 'Missing Items',
                'machine_issue' => 'Machine Problem',
                'staff_delay' => 'Staff Delay',
                'other' => 'Other Issue',
            ];

            $sortOptions = [
                'date' => [
                    'label' => 'Date Submitted',
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'is_active' => in_array($sort, ['newest', 'oldest']) || empty($sort),
                    'next_sort' => ($sort === 'newest' || empty($sort)) ? 'oldest' : 'newest',
                    'dir_indicator' => ($sort === 'oldest') ? '▲ Oldest' : '▼ Newest',
                ],
                'customer' => [
                    'label' => 'Customer Name',
                    'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                    'is_active' => in_array($sort, ['name_asc', 'name_desc']),
                    'next_sort' => ($sort === 'name_asc') ? 'name_desc' : 'name_asc',
                    'dir_indicator' => ($sort === 'name_desc') ? '▼ Z-A' : '▲ A-Z',
                ],
                'rating' => [
                    'label' => 'Rating Score',
                    'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                    'is_active' => in_array($sort, ['rating_desc', 'rating_asc', 'rating_high', 'rating_low']),
                    'next_sort' => in_array($sort, ['rating_desc', 'rating_high']) ? 'rating_asc' : 'rating_desc',
                    'dir_indicator' => in_array($sort, ['rating_asc', 'rating_low']) ? '▲ Lowest' : '▼ Highest',
                ],
                'type' => [
                    'label' => 'Category Type',
                    'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                    'is_active' => in_array($sort, ['type_asc', 'type_desc']),
                    'next_sort' => ($sort === 'type_asc') ? 'type_desc' : 'type_asc',
                    'dir_indicator' => ($sort === 'type_desc') ? '▼ Z-A' : '▲ A-Z',
                ],
            ];

            if ($isOwner) {
                $sortOptions['branch'] = [
                    'label' => 'Branch',
                    'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                    'is_active' => in_array($sort, ['branch_asc', 'branch_desc']),
                    'next_sort' => ($sort === 'branch_asc') ? 'branch_desc' : 'branch_asc',
                    'dir_indicator' => ($sort === 'branch_desc') ? '▼ Z-A' : '▲ A-Z',
                ];
            }
        @endphp

        <!-- Quick Filter Tabs -->
        <div class="mb-5 flex flex-wrap items-center gap-1.5 border-b border-slate-100 pb-4">
            <a href="{{ route('admin.feedback', request()->except(['nature', 'status', 'page'])) }}" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-bold transition {{ (!request('nature') && !request('status')) ? 'bg-blue-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>All Submissions</span>
                <span class="rounded-md {{ (!request('nature') && !request('status')) ? 'bg-blue-500/40 text-white' : 'bg-slate-200 text-slate-700' }} px-1.5 py-0.5 font-mono text-[10px]">{{ $totalCount }}</span>
            </a>

            <a href="{{ route('admin.feedback', array_merge(request()->except(['page']), ['nature' => 'complaint', 'status' => 'open'])) }}" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-bold transition {{ (request('nature') === 'complaint' && request('status') === 'open') ? 'bg-amber-500 text-white shadow-2xs' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200/80' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ (request('nature') === 'complaint' && request('status') === 'open') ? 'bg-white' : 'bg-amber-500 animate-pulse' }}"></span>
                <span>Open Complaints</span>
                <span class="rounded-md {{ (request('nature') === 'complaint' && request('status') === 'open') ? 'bg-amber-400 text-white' : 'bg-amber-100 text-amber-900' }} px-1.5 py-0.5 font-mono text-[10px]">{{ $openComplaintCount }}</span>
            </a>

            <a href="{{ route('admin.feedback', array_merge(request()->except(['page']), ['nature' => 'complaint', 'status' => 'in_progress'])) }}" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-bold transition {{ (request('nature') === 'complaint' && request('status') === 'in_progress') ? 'bg-blue-600 text-white shadow-2xs' : 'bg-blue-50 text-blue-800 hover:bg-blue-100 border border-blue-200/80' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ (request('nature') === 'complaint' && request('status') === 'in_progress') ? 'bg-white' : 'bg-blue-500 animate-pulse' }}"></span>
                <span>In Progress</span>
                <span class="rounded-md {{ (request('nature') === 'complaint' && request('status') === 'in_progress') ? 'bg-blue-500 text-white' : 'bg-blue-100 text-blue-900' }} px-1.5 py-0.5 font-mono text-[10px]">{{ $inProgressComplaintCount }}</span>
            </a>

            <a href="{{ route('admin.feedback', array_merge(request()->except(['page']), ['nature' => 'complaint', 'status' => 'resolved'])) }}" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-bold transition {{ (request('nature') === 'complaint' && in_array(request('status'), ['resolved', 'closed'])) ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200/80' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ (request('nature') === 'complaint' && in_array(request('status'), ['resolved', 'closed'])) ? 'bg-white' : 'bg-emerald-500' }}"></span>
                <span>Resolved Complaints</span>
                <span class="rounded-md {{ (request('nature') === 'complaint' && in_array(request('status'), ['resolved', 'closed'])) ? 'bg-emerald-500 text-white' : 'bg-emerald-100 text-emerald-900' }} px-1.5 py-0.5 font-mono text-[10px]">{{ $resolvedComplaintCount }}</span>
            </a>

            <a href="{{ route('admin.feedback', array_merge(request()->except(['status', 'page']), ['nature' => 'feedback'])) }}" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-bold transition {{ (request('nature') === 'feedback') ? 'bg-slate-800 text-white shadow-2xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <span>Feedback Reviews</span>
                <span class="rounded-md {{ (request('nature') === 'feedback') ? 'bg-slate-700 text-white' : 'bg-slate-200 text-slate-700' }} px-1.5 py-0.5 font-mono text-[10px]">{{ $feedbackCount }}</span>
            </a>
        </div>

        <!-- Top Controls: Search Input (Left) + Filter / Sort Buttons (Right) -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-100 mb-5">
            <!-- Search Input -->
            <form method="GET" action="{{ route('admin.feedback') }}" class="relative w-full sm:w-80">
                @if(request('nature')) <input type="hidden" name="nature" value="{{ request('nature') }}"> @endif
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                @if(request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
                @if(request('rating')) <input type="hidden" name="rating" value="{{ request('rating') }}"> @endif
                @if(request('branch_id')) <input type="hidden" name="branch_id" value="{{ request('branch_id') }}"> @endif
                @if($sort !== 'newest') <input type="hidden" name="sort" value="{{ $sort }}"> @endif
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    id="feedbackSearchInput"
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search customer, topic, or comment..." 
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition"
                >
            </form>

            <!-- Right Controls: Filter & Sort Popovers -->
            <div class="flex items-center justify-end gap-2 sm:gap-2.5 min-w-0 w-full sm:w-auto ml-auto">
                <!-- 1. Filter Button & Popover -->
                <div class="relative inline-block text-left control-dropdown-wrapper shrink-0 z-10">
                    <button type="button" onclick="toggleControlPopover('feedbackFilterPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl {{ $hasActiveFilter ? 'bg-blue-50 border border-blue-200 text-blue-700 font-bold' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900' }} px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Filter submissions">
                        <svg class="h-4 w-4 {{ $hasActiveFilter ? 'text-blue-600' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden sm:inline">Filter</span>
                        @if($hasActiveFilter)
                            <span class="hidden sm:inline-block h-2 w-2 rounded-full bg-blue-600"></span>
                            <span class="sm:hidden absolute top-1 right-1 h-2 w-2 rounded-full bg-blue-600 ring-2 ring-white"></span>
                        @endif
                        <svg class="hidden sm:block h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Filter Popover -->
                    <div id="feedbackFilterPopover" class="control-popover hidden absolute right-0 mt-2 {{ $isOwner ? 'w-72 sm:w-[520px]' : 'w-64' }} max-w-[calc(100vw-1.5rem)] max-h-[calc(100vh-140px)] overflow-y-auto [scrollbar-width:thin] rounded-2xl border border-slate-200 bg-white p-3 text-slate-700 shadow-xl z-50">
                        @if($isOwner)
                            <!-- 2-Column Grid Layout for Owner / Super Admin -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <!-- Column 1: Nature & Category -->
                                <div class="space-y-3">
                                    <!-- Submission Nature Filter -->
                                    <div>
                                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                            <span>Nature</span>
                                        </div>
                                        <a href="{{ route('admin.feedback', request()->except(['nature', 'page'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ !request('nature') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2">
                                                <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                                <span>All Submissions</span>
                                            </div>
                                            <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-500">{{ $totalCount }}</span>
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['nature', 'page']), ['nature' => 'feedback'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('nature') === 'feedback' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2">
                                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                                <span>Feedback</span>
                                            </div>
                                            <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-500">{{ $feedbackCount }}</span>
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['nature', 'page']), ['nature' => 'complaint'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('nature') === 'complaint' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2">
                                                <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                                <span>Complaints</span>
                                            </div>
                                            <span class="rounded-md bg-rose-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-rose-600">{{ $complaintCount }}</span>
                                        </a>
                                    </div>

                                    <!-- Status Filter Section -->
                                    <div class="pt-1">
                                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                            <span>Status</span>
                                        </div>
                                        <a href="{{ route('admin.feedback', request()->except(['status', 'page'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ !request('status') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <span>All Statuses</span>
                                            @if(!request('status')) <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['status', 'page']), ['status' => 'open'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('status') === 'open' ? 'bg-amber-50 text-amber-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-amber-500"></span><span>Open</span></div>
                                            <span class="rounded-md bg-amber-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-amber-700">{{ $openComplaintCount }}</span>
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['status', 'page']), ['status' => 'in_progress'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('status') === 'in_progress' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-blue-500"></span><span>In Progress</span></div>
                                            <span class="rounded-md bg-blue-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-blue-700">{{ $inProgressComplaintCount }}</span>
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['status', 'page']), ['status' => 'resolved'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('status') === 'resolved' ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-emerald-500"></span><span>Resolved</span></div>
                                            <span class="rounded-md bg-emerald-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-emerald-700">{{ $resolvedComplaintCount }}</span>
                                        </a>
                                    </div>

                                    <!-- Category Filter Section -->
                                    <div class="pt-1">
                                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                            <span>Category</span>
                                        </div>
                                        <a href="{{ route('admin.feedback', request()->except(['type', 'page'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ !request('type') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <span>All Categories</span>
                                            @if(!request('type')) <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'service'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'service' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-blue-500"></span><span>Laundry Service</span></div>
                                            @if(request('type') === 'service') <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'app'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'app' ? 'bg-purple-50 text-purple-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-purple-500"></span><span>App &amp; Website</span></div>
                                            @if(request('type') === 'app') <span class="text-purple-600 font-bold text-xs">✓</span> @endif
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'general'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'general' ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-teal-500"></span><span>General Inquiries</span></div>
                                            @if(request('type') === 'general') <span class="text-teal-600 font-bold text-xs">✓</span> @endif
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'damaged_item'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'damaged_item' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-500"></span><span>Damaged Clothes</span></div>
                                            @if(request('type') === 'damaged_item') <span class="text-rose-600 font-bold text-xs">✓</span> @endif
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'missing_clothes'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'missing_clothes' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-500"></span><span>Missing Items</span></div>
                                            @if(request('type') === 'missing_clothes') <span class="text-rose-600 font-bold text-xs">✓</span> @endif
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'staff_delay'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'staff_delay' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-500"></span><span>Staff Delay</span></div>
                                            @if(request('type') === 'staff_delay') <span class="text-rose-600 font-bold text-xs">✓</span> @endif
                                        </a>
                                        <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'other'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'other' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-500"></span><span>Other Issue</span></div>
                                            @if(request('type') === 'other') <span class="text-rose-600 font-bold text-xs">✓</span> @endif
                                        </a>
                                    </div>
                                </div>

                                <!-- Column 2: Branch & Rating -->
                                <div class="space-y-3 sm:border-l sm:border-slate-100 sm:pl-3.5">
                                    <!-- Branch Filter Section (Owner Only) -->
                                    @if(isset($branches) && $branches->isNotEmpty())
                                        <div>
                                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                                <span>Branch</span>
                                            </div>
                                            <a href="{{ route('admin.feedback', request()->except(['branch_id', 'page'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ !request('branch_id') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                                <span>All Branches (HQ)</span>
                                                @if(!request('branch_id')) <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                                            </a>
                                            @foreach($branches as $b)
                                                @php $isBActive = (string)request('branch_id') === (string)$b->id; @endphp
                                                <a href="{{ route('admin.feedback', array_merge(request()->except(['branch_id', 'page']), ['branch_id' => $b->id])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $isBActive ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                                    <span class="truncate max-w-[150px]" title="{{ $b->name }}">{{ $b->name }}</span>
                                                    @if($isBActive) <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Rating Filter Section -->
                                    <div class="pt-1">
                                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                            <span>Rating</span>
                                        </div>
                                        <a href="{{ route('admin.feedback', request()->except(['rating', 'page'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ !request('rating') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                            <span>All Ratings</span>
                                            @if(!request('rating')) <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                                        </a>
                                        @foreach([5, 4, 3, 2, 1] as $rVal)
                                            @php $isRActive = (string)request('rating') === (string)$rVal; @endphp
                                            <a href="{{ route('admin.feedback', array_merge(request()->except(['rating', 'page']), ['rating' => $rVal])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $isRActive ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                                <div class="flex items-center gap-2">
                                                    <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                                    <span>{{ $rVal }}.0 Rating</span>
                                                </div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-500">
                                                        {{ $ratingCounts[$rVal] ?? 0 }}
                                                    </span>
                                                    @if($isRActive) <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- 1-Column Layout for Branch Manager -->
                            <!-- Submission Nature Filter -->
                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                <span>Nature</span>
                            </div>
                            <a href="{{ route('admin.feedback', request()->except(['nature', 'page'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ !request('nature') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                    <span>All Submissions</span>
                                </div>
                                <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-500">{{ $totalCount }}</span>
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['nature', 'page']), ['nature' => 'feedback'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('nature') === 'feedback' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                    <span>Feedback</span>
                                </div>
                                <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-500">{{ $feedbackCount }}</span>
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['nature', 'page']), ['nature' => 'complaint'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('nature') === 'complaint' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                    <span>Complaints</span>
                                </div>
                                <span class="rounded-md bg-rose-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-rose-600">{{ $complaintCount }}</span>
                            </a>

                            <!-- Status Filter Section -->
                            <div class="my-1.5 border-t border-slate-100"></div>
                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                <span>Status</span>
                            </div>
                            <a href="{{ route('admin.feedback', request()->except(['status', 'page'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ !request('status') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <span>All Statuses</span>
                                @if(!request('status')) <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['status', 'page']), ['status' => 'open'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('status') === 'open' ? 'bg-amber-50 text-amber-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-amber-500"></span><span>Open</span></div>
                                <span class="rounded-md bg-amber-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-amber-700">{{ $openComplaintCount }}</span>
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['status', 'page']), ['status' => 'in_progress'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('status') === 'in_progress' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-blue-500"></span><span>In Progress</span></div>
                                <span class="rounded-md bg-blue-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-blue-700">{{ $inProgressComplaintCount }}</span>
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['status', 'page']), ['status' => 'resolved'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('status') === 'resolved' ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-emerald-500"></span><span>Resolved</span></div>
                                <span class="rounded-md bg-emerald-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-emerald-700">{{ $resolvedComplaintCount }}</span>
                            </a>

                            <!-- Category Filter Section -->
                            <div class="my-1.5 border-t border-slate-100"></div>
                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                <span>Category</span>
                            </div>
                            <a href="{{ route('admin.feedback', request()->except(['type', 'page'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ !request('type') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <span>All Categories</span>
                                @if(!request('type')) <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'service'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'service' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-blue-500"></span><span>Laundry Service</span></div>
                                @if(request('type') === 'service') <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'app'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'app' ? 'bg-purple-50 text-purple-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-purple-500"></span><span>App &amp; Website</span></div>
                                @if(request('type') === 'app') <span class="text-purple-600 font-bold text-xs">✓</span> @endif
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'general'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'general' ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-teal-500"></span><span>General Inquiries</span></div>
                                @if(request('type') === 'general') <span class="text-teal-600 font-bold text-xs">✓</span> @endif
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'damaged_item'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'damaged_item' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-500"></span><span>Damaged Clothes</span></div>
                                @if(request('type') === 'damaged_item') <span class="text-rose-600 font-bold text-xs">✓</span> @endif
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'missing_clothes'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'missing_clothes' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-500"></span><span>Missing Items</span></div>
                                @if(request('type') === 'missing_clothes') <span class="text-rose-600 font-bold text-xs">✓</span> @endif
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'staff_delay'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'staff_delay' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-500"></span><span>Staff Delay</span></div>
                                @if(request('type') === 'staff_delay') <span class="text-rose-600 font-bold text-xs">✓</span> @endif
                            </a>
                            <a href="{{ route('admin.feedback', array_merge(request()->except(['type', 'page']), ['type' => 'other'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ request('type') === 'other' ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-500"></span><span>Other Issue</span></div>
                                @if(request('type') === 'other') <span class="text-rose-600 font-bold text-xs">✓</span> @endif
                            </a>

                            <!-- Rating Filter Section -->
                            <div class="my-1.5 border-t border-slate-100"></div>
                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                                <span>Rating</span>
                            </div>
                            <a href="{{ route('admin.feedback', request()->except(['rating', 'page'])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ !request('rating') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <span>All Ratings</span>
                                @if(!request('rating')) <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                            </a>
                            @foreach([5, 4, 3, 2, 1] as $rVal)
                                @php $isRActive = (string)request('rating') === (string)$rVal; @endphp
                                <a href="{{ route('admin.feedback', array_merge(request()->except(['rating', 'page']), ['rating' => $rVal])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $isRActive ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                        <span>{{ $rVal }}.0 Rating</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-500">
                                            {{ $ratingCounts[$rVal] ?? 0 }}
                                        </span>
                                        @if($isRActive) <span class="text-blue-600 font-bold text-xs">✓</span> @endif
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- 2. Sort By Button & Popover -->
                <div class="relative inline-block text-left control-dropdown-wrapper shrink-0 z-10">
                    <button type="button" onclick="toggleControlPopover('feedbackSortPopover', event)" class="relative inline-flex h-9 items-center justify-center sm:justify-start gap-1.5 sm:gap-2 rounded-xl {{ $hasActiveSort ? 'bg-blue-50 border border-blue-200 text-blue-700 font-bold' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200 hover:text-slate-900' }} px-2.5 sm:px-3 text-xs font-semibold transition active:scale-95 cursor-pointer shadow-2xs" title="Sort submissions">
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
                    <div id="feedbackSortPopover" class="control-popover hidden absolute right-0 mt-2 w-56 max-w-[calc(100vw-1.5rem)] rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl z-50">
                        <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1 flex items-center justify-between font-mono">
                            <span>Sort By</span>
                            @if($hasActiveSort)
                                <a href="{{ route('admin.feedback', array_merge(request()->query(), ['sort' => 'newest'])) }}" class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-500 hover:text-rose-700 transition" title="Clear sort">
                                    <span>Clear</span>
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-[9px] text-slate-400 font-normal lowercase">(click to reverse)</span>
                            @endif
                        </div>
                        @foreach($sortOptions as $key => $opt)
                            <a href="{{ route('admin.feedback', array_merge(request()->query(), ['sort' => $opt['next_sort']])) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold {{ $opt['is_active'] ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }} transition">
                                <div class="flex items-center gap-2">
                                    <svg class="h-3.5 w-3.5 {{ $opt['is_active'] ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $opt['icon'] }}" />
                                    </svg>
                                    <span>{{ $opt['label'] }}</span>
                                </div>
                                @if($opt['is_active'])
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[10px] font-mono font-bold text-blue-600">{{ $opt['dir_indicator'] }}</span>
                                        <span onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('admin.feedback', array_merge(request()->query(), ['sort' => 'newest'])) }}';" class="p-0.5 rounded text-blue-500 hover:bg-rose-100 hover:text-rose-600 transition cursor-pointer" title="Clear sort">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </span>
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Reset Filters Button (Only X) -->
                @if($hasActiveFilter || $hasActiveSort || request('search'))
                    <a href="{{ route('admin.feedback') }}" class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-slate-100/80 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition cursor-pointer active:scale-95 shadow-2xs shrink-0" title="Reset all filters">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="border-b border-slate-100 text-xs font-mono uppercase tracking-wider text-slate-400 select-none">
                    <tr>
                        <!-- Customer Header (A-Z / Z-A) -->
                        @php
                            $customerNextSort = ($sort === 'name_asc') ? 'name_desc' : 'name_asc';
                        @endphp
                        <th class="pb-3 pl-2 font-semibold whitespace-nowrap min-w-[140px]">
                            <a href="{{ route('admin.feedback', array_merge(request()->query(), ['sort' => $customerNextSort])) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort A-Z / Z-A">
                                <span>Customer</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'name_asc') ▲ @elseif ($sort === 'name_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Nature Header (Only Nature) -->
                        @php
                            $typeNextSort = ($sort === 'type_asc') ? 'type_desc' : 'type_asc';
                        @endphp
                        <th class="pb-3 font-semibold whitespace-nowrap min-w-[120px]">
                            <a href="{{ route('admin.feedback', array_merge(request()->query(), ['sort' => $typeNextSort])) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Nature">
                                <span>Nature</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'type_asc') ▲ @elseif ($sort === 'type_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Status Header -->
                        <th class="pb-3 font-semibold whitespace-nowrap min-w-[120px]">Status</th>

                        <!-- Rating Header (Only Rating) -->
                        @php
                            $ratingNextSort = in_array($sort, ['rating_desc', 'rating_high']) ? 'rating_asc' : 'rating_desc';
                        @endphp
                        <th class="pb-3 font-semibold whitespace-nowrap min-w-[90px]">
                            <a href="{{ route('admin.feedback', array_merge(request()->query(), ['sort' => $ratingNextSort])) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Rating">
                                <span>Rating</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if (in_array($sort, ['rating_desc', 'rating_high'])) ▼ @elseif (in_array($sort, ['rating_asc', 'rating_low'])) ▲ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Summary Header (Only Summary) -->
                        <th class="pb-3 font-semibold max-w-[260px] whitespace-nowrap">Summary</th>

                        <!-- Branch & Order Header: If Owner, both; If Branch Manager, Order only -->
                        @php
                            $branchNextSort = ($sort === 'branch_asc') ? 'branch_desc' : 'branch_asc';
                        @endphp
                        <th class="pb-3 font-semibold whitespace-nowrap min-w-[130px]">
                            <a href="{{ route('admin.feedback', array_merge(request()->query(), ['sort' => $branchNextSort])) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort">
                                <span>{{ $isOwner ? 'Branch & Order' : 'Order' }}</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'branch_asc') ▲ @elseif ($sort === 'branch_desc') ▼ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <!-- Submitted Date Header (Newest / Oldest) -->
                        @php
                            $dateNextSort = ($sort === 'newest' || empty($sort)) ? 'oldest' : 'newest';
                        @endphp
                        <th class="pb-3 font-semibold whitespace-nowrap min-w-[110px]">
                            <a href="{{ route('admin.feedback', array_merge(request()->query(), ['sort' => $dateNextSort])) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition group cursor-pointer" title="Click to sort by Date">
                                <span>Submitted</span>
                                <span class="text-xs text-slate-400 group-hover:text-blue-600">
                                    @if ($sort === 'newest' || empty($sort)) ▼ @elseif ($sort === 'oldest') ▲ @else ↕ @endif
                                </span>
                            </a>
                        </th>

                        <th class="pb-3 font-semibold text-right pr-2 whitespace-nowrap min-w-[80px]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="feedbackTableBody">
                    @forelse($feedbacks as $fb)
                        <tr data-feedback-id="{{ $fb->id }}" class="feedback-row-item transition hover:bg-slate-50/70 group" data-search="{{ strtolower(($fb->isAnonymous() ? 'anonymous' : ($fb->customer->username ?? '')) . ' ' . ($fb->isAnonymous() ? '' : $fb->customer_id) . ' ' . ($fb->subject ?? '') . ' ' . ($fb->comment ?? '') . ' ' . $fb->type . ' ' . ($fb->nature ?? '') . ' ' . ($fb->branch->name ?? '')) }}">
                            <!-- Customer Info -->
                            <td class="py-4 pl-2 whitespace-nowrap">
                                @if($fb->isAnonymous())
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-200 text-slate-600 font-bold text-xs shadow-2xs">
                                            <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-700 leading-tight">
                                                Anonymous
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $fb->isComplaint() ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700' }} font-bold text-xs uppercase shadow-2xs font-mono">
                                            {{ substr($fb->customer->username ?? 'C', 0, 2) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 leading-tight truncate max-w-[120px]" title="{{ $fb->customer->username ?? 'Customer' }}">
                                                {{ Str::limit($fb->customer->username ?? 'Unknown Customer', 16) }}
                                            </p>
                                            <p class="text-[11px] text-slate-400 font-mono mt-0.5">
                                                ID #{{ $fb->customer_id }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <!-- Nature Column -->
                            <td class="py-4 whitespace-nowrap">
                                @if($fb->isComplaint())
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide bg-rose-50 text-rose-700 border border-rose-200">
                                            Complaint
                                        </span>
                                        <span class="text-[11px] font-bold text-slate-700">
                                            {{ $complaintLabels[$fb->type] ?? ucfirst(str_replace('_', ' ', $fb->type)) }}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-200">
                                            Feedback
                                        </span>
                                        @php
                                            $typeColor = match($fb->type) {
                                                'app' => 'text-purple-700',
                                                'service' => 'text-blue-700',
                                                default => 'text-teal-700'
                                            };
                                        @endphp
                                        <span class="text-[11px] font-bold uppercase tracking-wide {{ $typeColor }}">
                                            {{ $fb->type }}
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <!-- Status Column -->
                            <td class="feedback-status-cell py-4 whitespace-nowrap">
                                @if($fb->isComplaint())
                                    @php
                                        $statusClass = match($fb->status) {
                                            'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'closed' => 'bg-slate-100 text-slate-600 border-slate-200',
                                            default => 'bg-amber-50 text-amber-700 border-amber-200',
                                        };
                                        $statusDot = match($fb->status) {
                                            'in_progress' => 'bg-blue-500 animate-pulse',
                                            'resolved' => 'bg-emerald-500',
                                            'closed' => 'bg-slate-400',
                                            default => 'bg-amber-500 animate-pulse',
                                        };
                                    @endphp
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide border {{ $statusClass }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                            <span>{{ $fb->status_label }}</span>
                                        </span>
                                        @if($fb->replies->count() > 0)
                                            <span class="inline-flex items-center gap-1 text-[10px] font-mono text-slate-500 font-semibold">
                                                <svg class="h-3 w-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                <span>{{ $fb->replies->count() }} {{ Str::plural('reply', $fb->replies->count()) }}</span>
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide bg-slate-100 text-slate-600 border border-slate-200">
                                        Review
                                    </span>
                                @endif
                            </td>

                            <!-- Rating Column (Number for feedback, dash for complaint) -->
                            <td class="py-4 whitespace-nowrap">
                                @if($fb->isComplaint())
                                    <span class="font-mono text-xs text-slate-400 font-bold">—</span>
                                @else
                                    <span class="font-mono text-xs font-bold text-slate-800">{{ number_format($fb->rating, 1) }}</span>
                                @endif
                            </td>

                            <!-- Summary Column (Only Summary) -->
                            <td class="py-4 max-w-[260px] overflow-hidden" style="max-width: 260px;">
                                <div class="w-full max-w-[260px] overflow-hidden">
                                    <p class="font-bold text-slate-900 text-xs leading-snug truncate" title="{{ $fb->subject ?: ($fb->comment ?: 'No summary') }}">
                                        {{ $fb->subject ?: (Str::limit($fb->comment, 32) ?: 'No summary') }}
                                    </p>
                                </div>
                            </td>

                            <!-- Branch & Order (Owner sees both; Branch Manager sees Order only) -->
                            <td class="py-4 text-xs whitespace-nowrap">
                                @if($isOwner)
                                    <p class="font-semibold text-slate-800 truncate max-w-[130px]" title="{{ $fb->branch->name ?? 'Central Branch' }}">
                                        {{ Str::limit($fb->branch->name ?? 'Central Branch', 18) }}
                                    </p>
                                    @if($fb->service_request_id)
                                        <a href="{{ route('admin.service-requests.show', $fb->service_request_id) }}" class="inline-flex items-center gap-1 font-mono text-[11px] font-bold text-blue-600 hover:underline mt-0.5">
                                            <span>#REQ-{{ str_pad($fb->service_request_id, 4, '0', STR_PAD_LEFT) }}</span>
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-slate-400 font-mono">No order linked</span>
                                    @endif
                                @else
                                    @if($fb->service_request_id)
                                        <a href="{{ route('admin.service-requests.show', $fb->service_request_id) }}" class="inline-flex items-center gap-1 font-mono text-xs font-bold text-blue-600 hover:underline">
                                            <span>#REQ-{{ str_pad($fb->service_request_id, 4, '0', STR_PAD_LEFT) }}</span>
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 font-mono">No order linked</span>
                                    @endif
                                @endif
                            </td>

                            <!-- Date (09/19/26 format) -->
                            <td class="py-4 whitespace-nowrap">
                                <div class="font-mono text-xs font-semibold text-slate-700">{{ $fb->created_at->format('m/d/y') }}</div>
                                <div class="font-mono text-[11px] text-slate-400">{{ $fb->created_at->format('g:iA') }}</div>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 pr-2 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Details Button -->
                                    <button 
                                        type="button" 
                                        onclick="openFeedbackModal({{ $fb->id }})"
                                        class="inline-flex items-center gap-1.5 rounded-full border border-slate-200/90 bg-white px-3.5 py-1.5 text-xs font-bold text-slate-800 hover:border-blue-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-2xs group cursor-pointer"
                                        title="View Full Details"
                                    >
                                        <span>Details</span>
                                        <svg class="h-3.5 w-3.5 text-slate-600 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>

                                    <!-- Delete Button (Owner Only) -->
                                    @if($isOwner)
                                        <form action="{{ route('admin.feedback.destroy', $fb->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this submission?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition shadow-2xs cursor-pointer"
                                                title="Delete Submission (Owner Only)"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-700">No Submissions Found</p>
                                <p class="text-xs text-slate-400 mt-1">
                                    No customer feedback or complaints match the current search or filters.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        @if($feedbacks->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $feedbacks->links() }}
            </div>
        @endif
    </div>

    <!-- Details & Resolution Chat Modal -->
    <div id="feedbackModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4">
        <div class="relative w-full max-w-3xl rounded-3xl border border-slate-200/90 bg-white p-5 sm:p-7 shadow-2xl transition-all my-8 max-h-[92vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-start justify-between border-b border-slate-100 pb-4 shrink-0">
                <div class="flex items-center gap-3">
                    <div id="modalHeaderIcon" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 border border-blue-100">
                        <svg id="modalFeedbackIcon" class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <svg id="modalComplaintIcon" class="h-5 w-5 hidden text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900" id="modalCustomerTitle">Customer Submission</h3>
                            <span id="modalAnonBadge" class="hidden inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 border border-amber-200/70">
                                <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                Anonymous Customer
                            </span>
                            <span id="modalStatusBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold"></span>
                        </div>
                        <p class="text-xs text-slate-400 font-mono mt-0.5" id="modalDate"></p>
                    </div>
                </div>
                <button type="button" onclick="closeFeedbackModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Loading State -->
            <div id="modalLoadingState" class="py-16 text-center">
                <svg class="inline h-8 w-8 animate-spin text-blue-600" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-xs font-semibold text-slate-400 mt-2 font-mono">Loading details...</p>
            </div>

            <!-- Modal Scrollable Body -->
            <div id="modalBody" class="hidden overflow-y-auto space-y-4 pr-1 text-sm mt-4 flex-1">
                <!-- Submission Info Bar -->
                <div class="flex items-center justify-between rounded-2xl bg-slate-50/80 p-3.5 border border-slate-100">
                    <div id="modalRatingWrapper">
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">Rating</span>
                        <div class="mt-1" id="modalRatingContainer"></div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">Category</span>
                        <span id="modalTypeBadge" class="inline-block font-extrabold text-xs mt-1 text-slate-800"></span>
                    </div>
                </div>

                <!-- Summary & Details -->
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/40 p-4 space-y-2">
                    <div>
                        <span class="text-[10px] font-mono uppercase font-bold tracking-wider text-slate-400 block">Summary</span>
                        <p class="text-sm font-bold text-slate-900 mt-0.5" id="modalSubject"></p>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-[10px] font-mono uppercase font-bold tracking-wider text-slate-400 block">Customer Statement</span>
                        <div class="mt-1 text-xs text-slate-700 leading-relaxed whitespace-pre-wrap break-words" id="modalComment"></div>
                    </div>
                </div>

                <!-- Aspects (for feedback) -->
                <div id="modalAspectsSection" class="hidden">
                    <h4 class="text-xs font-mono uppercase font-bold tracking-wider text-slate-400 mb-1.5">Aspects Mentioned</h4>
                    <div class="flex flex-wrap gap-1.5" id="modalAspectsList"></div>
                </div>

                <!-- Branch & Order Metadata -->
                <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl bg-slate-100/60 px-3.5 py-2 text-xs text-slate-600 font-mono">
                    <div id="modalBranchWrapper">
                        <span class="text-slate-400">Branch: </span>
                        <span class="font-bold text-slate-800" id="modalBranch"></span>
                    </div>
                    <div id="modalOrderWrapper"></div>
                </div>

                <!-- Complaint Management & Two-Way Chat Section -->
                <div id="modalComplaintSection" class="hidden pt-3 border-t border-slate-200 space-y-4">
                    <!-- Status & Resolution Control Panel -->
                    <div class="rounded-2xl border border-slate-200 bg-linear-to-b from-white to-slate-50/70 p-4 shadow-2xs">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="flex h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 font-mono">Complaint Lifecycle & Resolution</h4>
                            </div>
                            <div id="modalResolvedInfo" class="hidden text-[11px] font-mono text-emerald-700 font-semibold bg-emerald-50 border border-emerald-200/80 rounded-lg px-2 py-0.5"></div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            <div>
                                <label class="text-[11px] font-mono font-bold text-slate-500 block mb-1">Update Status</label>
                                <select id="modalStatusSelect" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-800 shadow-2xs focus:border-blue-500 focus:outline-hidden focus:ring-1 focus:ring-blue-500">
                                    <option value="open">🔴 Open (Pending Action)</option>
                                    <option value="in_progress">🟡 In Progress (Investigating / In Contact)</option>
                                    <option value="resolved">🟢 Resolved (Addressed / Satisfied)</option>
                                    <option value="closed">⚪ Closed (Archived)</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-[11px] font-mono font-bold text-slate-500 block mb-1">Resolution / Action Note (Optional)</label>
                                <div class="flex gap-2">
                                    <input 
                                        type="text" 
                                        id="modalResolutionNote" 
                                        placeholder="e.g., Refund issued, clothes rewashed, item verified..." 
                                        class="flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 shadow-2xs focus:border-blue-500 focus:outline-hidden focus:ring-1 focus:ring-blue-500"
                                    />
                                    <button 
                                        type="button" 
                                        onclick="submitStatusUpdate()" 
                                        id="modalSaveStatusBtn"
                                        class="shrink-0 rounded-xl bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 transition cursor-pointer shadow-2xs inline-flex items-center gap-1.5"
                                    >
                                        <span>Update</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conversation Thread Box -->
                    <div class="rounded-2xl border border-slate-200/90 bg-white overflow-hidden shadow-2xs">
                        <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider font-mono">Two-Way Conversation Thread</h4>
                            </div>
                            <span id="modalReplyCountPill" class="text-[11px] font-mono text-slate-400 font-semibold">0 messages</span>
                        </div>

                        <!-- Chat Messages Container -->
                        <div id="modalChatMessages" class="max-h-64 sm:max-h-72 overflow-y-auto p-4 space-y-3 bg-slate-50/50">
                            <!-- Populated dynamically via JS -->
                        </div>

                        <!-- Reply Composer -->
                        <div class="p-3 bg-white border-t border-slate-100">
                            <div class="flex items-end gap-2">
                                <textarea 
                                    id="modalReplyInput" 
                                    rows="2" 
                                    placeholder="Type a message or response to the customer..." 
                                    class="flex-1 rounded-2xl border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-800 resize-none focus:bg-white focus:border-blue-500 focus:outline-hidden focus:ring-1 focus:ring-blue-500"
                                ></textarea>
                                <button 
                                    type="button" 
                                    onclick="submitAdminReply()" 
                                    id="modalSendReplyBtn"
                                    class="shrink-0 rounded-2xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-blue-700 transition cursor-pointer shadow-2xs inline-flex items-center gap-1.5"
                                >
                                    <span>Send</span>
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </button>
                            </div>
                            <p class="text-[10px] text-slate-400 font-mono mt-1 px-1">Customer will be alerted and can read this in their complaint portal.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between shrink-0">
                <span class="text-[11px] text-slate-400 font-mono" id="modalFooterHint">Press Esc or Close to dismiss</span>
                <button type="button" onclick="closeFeedbackModal()" class="rounded-2xl border border-slate-200/80 bg-slate-100 px-5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        let currentFeedbackId = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        function getStatusBadgeHtml(status, label) {
            switch(status) {
                case 'in_progress':
                    return `<span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-700 border border-amber-200/80"><span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>${label || 'In Progress'}</span>`;
                case 'resolved':
                    return `<span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 border border-emerald-200/80"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>${label || 'Resolved'}</span>`;
                case 'closed':
                    return `<span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-600 border border-slate-200"><span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>${label || 'Closed'}</span>`;
                default:
                    return `<span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700 border border-rose-200/80"><span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>${label || 'Open'}</span>`;
            }
        }

        async function openFeedbackModal(feedbackId) {
            currentFeedbackId = feedbackId;
            const modal = document.getElementById('feedbackModal');
            const loading = document.getElementById('modalLoadingState');
            const body = document.getElementById('modalBody');

            modal.classList.remove('hidden');
            loading.classList.remove('hidden');
            body.classList.add('hidden');

            try {
                const res = await fetch(`/admin/feedback/${feedbackId}/details`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) throw new Error('Failed to load feedback details');
                const data = await res.json();

                // Populate Header
                const isComplaint = data.nature === 'complaint';
                document.getElementById('modalCustomerTitle').textContent = data.customer + (isComplaint ? ' (Complaint)' : ' (Review)');
                document.getElementById('modalDate').textContent = data.date;
                document.getElementById('modalTypeBadge').textContent = data.type;
                document.getElementById('modalSubject').textContent = data.subject || 'No Summary Provided';

                // Anonymous Badge
                const anonBadge = document.getElementById('modalAnonBadge');
                if (data.is_anonymous) {
                    anonBadge.classList.remove('hidden');
                } else {
                    anonBadge.classList.add('hidden');
                }

                // Status Badge in Header
                const statusBadge = document.getElementById('modalStatusBadge');
                if (isComplaint) {
                    statusBadge.innerHTML = getStatusBadgeHtml(data.status, data.status_label);
                    statusBadge.classList.remove('hidden');
                } else {
                    statusBadge.classList.add('hidden');
                }

                // Header Icon
                const headerIcon = document.getElementById('modalHeaderIcon');
                const fbIcon = document.getElementById('modalFeedbackIcon');
                const compIcon = document.getElementById('modalComplaintIcon');

                if (isComplaint) {
                    headerIcon.className = 'flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 border border-rose-100';
                    fbIcon.classList.add('hidden');
                    compIcon.classList.remove('hidden');
                } else {
                    headerIcon.className = 'flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 border border-blue-100';
                    fbIcon.classList.remove('hidden');
                    compIcon.classList.add('hidden');
                }

                // Comment
                const commentEl = document.getElementById('modalComment');
                if (data.comment) {
                    commentEl.textContent = data.comment;
                    commentEl.classList.remove('italic', 'text-slate-400');
                    commentEl.classList.add('text-slate-700');
                } else {
                    commentEl.textContent = 'No detailed statement provided.';
                    commentEl.classList.add('italic', 'text-slate-400');
                    commentEl.classList.remove('text-slate-700');
                }

                // Branch visibility
                const branchWrapper = document.getElementById('modalBranchWrapper');
                if (data.is_owner) {
                    branchWrapper.classList.remove('hidden');
                    document.getElementById('modalBranch').textContent = data.branch;
                } else {
                    branchWrapper.classList.add('hidden');
                }

                // Rating
                const ratingWrapper = document.getElementById('modalRatingWrapper');
                const ratingContainer = document.getElementById('modalRatingContainer');
                ratingContainer.innerHTML = '';

                if (isComplaint) {
                    ratingWrapper.classList.add('hidden');
                } else {
                    ratingWrapper.classList.remove('hidden');
                    const ratingNumber = document.createElement('span');
                    ratingNumber.setAttribute('class', 'text-xs font-bold text-slate-800 font-mono bg-slate-100 px-2.5 py-1 rounded-xl');
                    ratingNumber.textContent = (data.rating ? data.rating + '.0' : '—') + ' / 5.0 Rating Score';
                    ratingContainer.appendChild(ratingNumber);
                }

                // Aspects
                const aspectsContainer = document.getElementById('modalAspectsList');
                aspectsContainer.innerHTML = '';
                if (!isComplaint && data.aspects && Array.isArray(data.aspects) && data.aspects.length > 0) {
                    document.getElementById('modalAspectsSection').classList.remove('hidden');
                    data.aspects.forEach(asp => {
                        const span = document.createElement('span');
                        span.setAttribute('class', 'rounded-lg bg-blue-50 border border-blue-200 px-2.5 py-1 text-xs font-semibold text-blue-800');
                        span.textContent = asp;
                        aspectsContainer.appendChild(span);
                    });
                } else {
                    document.getElementById('modalAspectsSection').classList.add('hidden');
                }

                // Order link
                const orderWrapper = document.getElementById('modalOrderWrapper');
                orderWrapper.innerHTML = '';
                if (data.order && data.order_url) {
                    const a = document.createElement('a');
                    a.setAttribute('href', data.order_url);
                    a.setAttribute('class', 'font-bold text-blue-600 hover:underline inline-flex items-center gap-1');
                    a.innerHTML = `<span>${data.order}</span> <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>`;
                    orderWrapper.appendChild(a);
                } else {
                    orderWrapper.innerHTML = '<span class="text-slate-400">No order linked</span>';
                }

                // Complaint Section setup
                const compSection = document.getElementById('modalComplaintSection');
                if (isComplaint) {
                    compSection.classList.remove('hidden');
                    document.getElementById('modalStatusSelect').value = data.status || 'open';
                    document.getElementById('modalResolutionNote').value = data.resolution_note || '';

                    const resolvedInfo = document.getElementById('modalResolvedInfo');
                    if (data.resolved_at) {
                        resolvedInfo.textContent = `✓ Resolved ${data.resolved_at} by ${data.resolved_by_name || 'Staff'}`;
                        resolvedInfo.classList.remove('hidden');
                    } else {
                        resolvedInfo.classList.add('hidden');
                    }

                    renderChatMessages(data.replies || []);
                    startAdminChatPolling();
                } else {
                    compSection.classList.add('hidden');
                    stopAdminChatPolling();
                }

                loading.classList.add('hidden');
                body.classList.remove('hidden');

            } catch (err) {
                console.error(err);
                alert('Could not load feedback details. Please try again.');
                closeFeedbackModal();
            }
        }

        let adminChatPollInterval = null;
        let isSendingAdminReply = false;
        let isUpdatingStatus = false;

        function startAdminChatPolling() {
            stopAdminChatPolling();
            adminChatPollInterval = setInterval(async () => {
                if (!currentFeedbackId || document.hidden || isSendingAdminReply || isUpdatingStatus) return;
                try {
                    const res = await fetch(`/admin/feedback/${currentFeedbackId}/details`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (currentFeedbackId !== data.id) return;

                    // Update status badge
                    const statusBadge = document.getElementById('modalStatusBadge');
                    if (data.nature === 'complaint' && statusBadge) {
                        statusBadge.innerHTML = getStatusBadgeHtml(data.status, data.status_label);
                        updateTableRowBadge(currentFeedbackId, data.status, data.status_label);
                    }

                    const currentCount = parseInt(document.getElementById('modalReplyCountPill')?.getAttribute('data-count') || '-1', 10);
                    const newReplies = data.replies || [];
                    if (newReplies.length !== currentCount) {
                        renderChatMessages(newReplies);
                    }
                } catch (e) {
                    // Silent fail for next poll
                }
            }, 2500);
        }

        function stopAdminChatPolling() {
            if (adminChatPollInterval) {
                clearInterval(adminChatPollInterval);
                adminChatPollInterval = null;
            }
        }

        function renderChatMessages(replies) {
            const container = document.getElementById('modalChatMessages');
            const countPill = document.getElementById('modalReplyCountPill');
            container.innerHTML = '';
            countPill.textContent = `${replies.length} ${replies.length === 1 ? 'message' : 'messages'}`;
            countPill.setAttribute('data-count', replies.length);

            if (!replies || replies.length === 0) {
                container.innerHTML = `
                    <div class="py-8 text-center text-slate-400">
                        <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-600">No replies yet</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Start the conversation by sending a response below.</p>
                    </div>
                `;
                return;
            }

            replies.forEach(reply => {
                const isStaff = reply.sender_type === 'admin';
                const isSystem = reply.message.startsWith('📋 Status changed to');
                const div = document.createElement('div');

                if (isSystem) {
                    div.className = 'flex justify-center my-2';
                    div.innerHTML = `
                        <div class="inline-flex items-center gap-1.5 rounded-full bg-slate-200/90 px-3.5 py-1 text-[11px] font-mono font-medium text-slate-700 shadow-2xs">
                            <span>${escapeHtml(reply.message)}</span>
                            <span class="text-slate-400">• ${escapeHtml(reply.date)}</span>
                        </div>
                    `;
                } else if (isStaff) {
                    div.className = 'flex justify-end';
                    div.innerHTML = `
                        <div class="max-w-[85%] rounded-2xl rounded-tr-xs bg-blue-600 px-4 py-2.5 text-white shadow-xs">
                            <div class="flex items-center justify-between gap-3 text-[10px] font-mono text-blue-100 mb-1">
                                <span class="font-bold flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    ${escapeHtml(reply.sender_name)}
                                </span>
                                <span>${escapeHtml(reply.date)}</span>
                            </div>
                            <p class="text-xs leading-relaxed whitespace-pre-wrap break-words">${escapeHtml(reply.message)}</p>
                        </div>
                    `;
                } else {
                    div.className = 'flex justify-start';
                    div.innerHTML = `
                        <div class="max-w-[85%] rounded-2xl rounded-tl-xs bg-white border border-slate-200/90 px-4 py-2.5 text-slate-800 shadow-2xs">
                            <div class="flex items-center justify-between gap-3 text-[10px] font-mono text-slate-400 mb-1">
                                <span class="font-bold text-slate-700 flex items-center gap-1">
                                    <svg class="h-3 w-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    ${escapeHtml(reply.sender_name)}
                                </span>
                                <span>${escapeHtml(reply.date)}</span>
                            </div>
                            <p class="text-xs leading-relaxed text-slate-700 whitespace-pre-wrap break-words">${escapeHtml(reply.message)}</p>
                        </div>
                    `;
                }

                container.appendChild(div);
            });

            container.scrollTop = container.scrollHeight;
        }

        async function submitStatusUpdate() {
            if (!currentFeedbackId) return;
            const statusSelect = document.getElementById('modalStatusSelect');
            const noteInput = document.getElementById('modalResolutionNote');
            const saveBtn = document.getElementById('modalSaveStatusBtn');

            const status = statusSelect.value;
            const resolution_note = noteInput.value.trim();

            isUpdatingStatus = true;
            saveBtn.disabled = true;
            saveBtn.innerHTML = `
                <svg class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Saving...</span>
            `;

            try {
                const res = await fetch(`/admin/feedback/${currentFeedbackId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ status, resolution_note })
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Status update failed');

                // Update modal status badge
                document.getElementById('modalStatusBadge').innerHTML = getStatusBadgeHtml(data.status, data.status_label);

                const resolvedInfo = document.getElementById('modalResolvedInfo');
                if (data.resolved_at) {
                    resolvedInfo.textContent = `✓ Resolved ${data.resolved_at} by ${data.resolved_by_name || 'Staff'}`;
                    resolvedInfo.classList.remove('hidden');
                } else {
                    resolvedInfo.classList.add('hidden');
                }

                // If note was included, reload details to update chat history
                if (resolution_note) {
                    noteInput.value = '';
                    await reloadModalReplies();
                }

                // Update table row status badge if visible on page
                updateTableRowBadge(currentFeedbackId, data.status, data.status_label);

            } catch (err) {
                console.error(err);
                alert(err.message || 'Error updating status. Please try again.');
            } finally {
                isUpdatingStatus = false;
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<span>Update</span>';
            }
        }

        async function submitAdminReply() {
            if (!currentFeedbackId) return;
            const input = document.getElementById('modalReplyInput');
            const sendBtn = document.getElementById('modalSendReplyBtn');
            const message = input.value.trim();

            if (!message) {
                input.focus();
                return;
            }

            isSendingAdminReply = true;
            sendBtn.disabled = true;
            sendBtn.innerHTML = `
                <svg class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;

            try {
                const res = await fetch(`/admin/feedback/${currentFeedbackId}/reply`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ message })
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to send reply');

                input.value = '';

                // If auto-advanced to in_progress
                if (data.status) {
                    document.getElementById('modalStatusSelect').value = data.status;
                    document.getElementById('modalStatusBadge').innerHTML = getStatusBadgeHtml(data.status, data.status_label);
                    updateTableRowBadge(currentFeedbackId, data.status, data.status_label);
                }

                await reloadModalReplies();

            } catch (err) {
                console.error(err);
                alert(err.message || 'Error sending reply. Please try again.');
            } finally {
                isSendingAdminReply = false;
                sendBtn.disabled = false;
                sendBtn.innerHTML = `
                    <span>Send</span>
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                `;
            }
        }

        async function reloadModalReplies() {
            if (!currentFeedbackId) return;
            try {
                const res = await fetch(`/admin/feedback/${currentFeedbackId}/details`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (res.ok) {
                    const data = await res.json();
                    renderChatMessages(data.replies || []);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function updateTableRowBadge(feedbackId, status, label) {
            const row = document.querySelector(`tr[data-feedback-id="${feedbackId}"]`);
            if (!row) return;
            const badgeCell = row.querySelector('.feedback-status-cell');
            if (badgeCell) {
                badgeCell.innerHTML = getStatusBadgeHtml(status, label);
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function closeFeedbackModal() {
            stopAdminChatPolling();
            document.getElementById('feedbackModal').classList.add('hidden');
            currentFeedbackId = null;
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('feedbackModal').classList.contains('hidden')) {
                closeFeedbackModal();
            }
        });

        // Live Search Input Filter
        document.getElementById('feedbackSearchInput')?.addEventListener('input', function(e) {
            const val = e.target.value.toLowerCase().trim();
            document.querySelectorAll('#feedbackTableBody .feedback-row-item').forEach(row => {
                const searchData = (row.getAttribute('data-search') || '').toLowerCase();
                row.style.display = searchData.includes(val) ? '' : 'none';
            });
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
