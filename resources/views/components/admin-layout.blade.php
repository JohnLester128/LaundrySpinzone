<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'LaundrySpinzone - Admin' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-800 bg-gradient-to-br from-slate-50 via-blue-50/20 to-purple-50/20 min-h-screen antialiased">
    @php
        $adminUser = auth('admin')->user();
        $adminUsername = $adminUser->username ?? 'Admin';
        $branchName = $adminUser->branch->name ?? 'Central Branch';
        $adminBranchId = $adminUser->branch_id ?? null;

        // Aggregate step-by-step admin notifications
        $adminNotifications = collect();
        if ($adminUser) {
            $recentReqs = \App\Models\ServiceRequest::when($adminBranchId, fn ($q) => $q->where('branch_id', $adminBranchId))
                ->with(['customer', 'clothType', 'statusHistories', 'branch'])
                ->latest('updated_at')
                ->take(10)
                ->get();

            foreach ($recentReqs as $req) {
                $customerName = $req->customer->username ?? 'Walk-in Customer';
                $reqNum = '#REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT);

                // 1. New Pending requests
                if ($req->status === 'pending') {
                    $adminNotifications->push([
                        'id' => 'new_' . $req->id,
                        'type' => 'new_request',
                        'title' => 'New Service Request ' . $reqNum,
                        'message' => $customerName . ' requested ' . ($req->clothType->name ?? 'Laundry') . '.',
                        'status' => 'pending',
                        'time' => $req->created_at,
                        'url' => route('admin.service-requests.show', $req->id),
                    ]);
                }

                // 2. Status histories and progress updates
                if ($req->statusHistories->isNotEmpty()) {
                    foreach ($req->statusHistories->sortByDesc('created_at')->take(1) as $hist) {
                        if ($hist->status !== 'pending') {
                            $adminNotifications->push([
                                'id' => 'hist_' . $hist->id,
                                'type' => 'status_update',
                                'title' => 'Order ' . ucfirst(str_replace('_', ' ', $hist->status)),
                                'message' => $reqNum . ' (' . $customerName . ') is ' . ucfirst(str_replace('_', ' ', $hist->status)) . '.',
                                'status' => $hist->status,
                                'time' => $hist->created_at,
                                'url' => route('admin.service-requests.show', $req->id),
                            ]);
                        }
                    }
                } else {
                    $adminNotifications->push([
                        'id' => 'req_' . $req->id,
                        'type' => 'status_update',
                        'title' => 'Order ' . ucfirst(str_replace('_', ' ', $req->status)),
                        'message' => $reqNum . ' (' . $customerName . ') is ' . ucfirst(str_replace('_', ' ', $req->status)) . '.',
                        'status' => $req->status,
                        'time' => $req->updated_at,
                        'url' => route('admin.service-requests.show', $req->id),
                    ]);
                }
            }

            $adminNotifications = $adminNotifications->sortByDesc('time')->take(7)->values();
        }
    @endphp

    <!-- Sidebar Navigation -->
    <aside class="fixed inset-y-0 left-0 hidden w-64 border-r border-slate-200/70 bg-white/90 backdrop-blur-md lg:block z-30 shadow-xs">
        <!-- Sidebar Brand / Branch Header -->
        <div class="flex h-20 items-center gap-3.5 border-b border-slate-100 px-6">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl overflow-hidden shadow-xs bg-white border border-slate-200/70 p-0.5">
                <img src="{{ asset('images/spinzone-logo.png') }}" alt="Spin-zone" class="h-full w-full object-cover rounded-xl">
            </div>
            <div class="overflow-hidden">
                <h2 class="text-base font-bold tracking-tight text-blue-950 leading-tight truncate">
                    Spinzone
                </h2>
                <p class="text-[11px] font-medium text-slate-400 truncate">
                    {{ \Illuminate\Support\Str::contains(strtolower($branchName), 'branch') ? $branchName : $branchName . ' Branch' }}
                </p>
            </div>
        </div>

        <!-- Sidebar Navigation Items -->
        <nav class="space-y-1.5 px-4 py-6 overflow-y-auto max-h-[calc(100vh-160px)]">
            <!-- Dashboard Link -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100/70 text-blue-700 shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('admin.dashboard') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4z" />
                </svg>
                Dashboard
            </a>

            <!-- Service Requests -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.service-requests*') ? 'bg-blue-100/70 text-blue-700 font-semibold shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('admin.service-requests') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('admin.service-requests*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Service Requests
            </a>

            <!-- Machines -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.machines*') ? 'bg-blue-100/70 text-blue-700 font-semibold shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('admin.machines') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('admin.machines*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
                Machines
            </a>

            <!-- Sales -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.sales*') ? 'bg-blue-100/70 text-blue-700 font-semibold shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('admin.sales') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('admin.sales*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Sales
            </a>

            <!-- Customers -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.customers*') ? 'bg-blue-100/70 text-blue-700 font-semibold shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('admin.customers') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('admin.customers*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Customers
            </a>

            <!-- Login Logs -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.login-logs*') ? 'bg-blue-100/70 text-blue-700 font-semibold shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('admin.login-logs') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('admin.login-logs*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Login Logs
            </a>

            <!-- Settings -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings*') ? 'bg-blue-100/70 text-blue-700 font-semibold shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('admin.settings') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('admin.settings*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
            </a>

            <!-- Profile -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.profile*') ? 'bg-blue-100/70 text-blue-700 font-semibold shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('admin.profile') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('admin.profile*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile
            </a>
        </nav>

        <!-- Sidebar Footer / Logout -->
        <div class="absolute bottom-6 inset-x-4">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl border border-slate-200/80 bg-slate-50/80 py-2.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 cursor-pointer">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="lg:ml-64 min-h-screen">
        <!-- Top Bar for mobile/desktop -->
        <header class="flex h-20 items-center justify-between border-b border-slate-200/60 bg-white/70 backdrop-blur-md px-6 sm:px-8 sticky top-0 z-20">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-blue-950 tracking-tight">
                    Hello, {{ $branchName }}
                </h1>
            </div>

            <!-- Top Right: Admin Notification Bell -->
            <div class="flex items-center gap-3">
                <div class="relative" id="adminNotifWrapper">
                    <button 
                        type="button" 
                        id="adminNotifBtn" 
                        onclick="toggleAdminNotifs(event)" 
                        class="relative flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100/90 text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition border border-slate-200/60 cursor-pointer shadow-2xs active:scale-95" 
                        title="Admin Notifications"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        
                        @if($adminNotifications->isNotEmpty())
                            <span class="absolute top-2 right-2 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600 ring-2 ring-white"></span>
                            </span>
                        @endif
                    </button>

                    <!-- Admin Notification Popover Menu -->
                    <div id="adminNotifPopover" class="hidden absolute right-0 mt-3 w-80 sm:w-96 rounded-3xl border border-slate-200/90 bg-white p-4 shadow-2xl z-50 transition-all duration-200">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-900">Admin Alerts</h3>
                                @if($adminNotifications->isNotEmpty())
                                    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-700 font-mono">
                                        {{ $adminNotifications->count() }}
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('admin.service-requests') }}" class="text-[11px] font-semibold text-blue-600 hover:underline">
                                View Service Requests →
                            </a>
                        </div>

                        <div class="space-y-2 max-h-80 overflow-y-auto pr-1 [scrollbar-width:thin]">
                            @forelse($adminNotifications as $notif)
                                @php
                                    $iconBg = match($notif['status']) {
                                        'pending' => 'bg-amber-100 text-amber-600',
                                        'received' => 'bg-slate-100 text-slate-600',
                                        'washing' => 'bg-purple-100 text-purple-600',
                                        'drying' => 'bg-indigo-100 text-indigo-600',
                                        'ready', 'ready_for_pickup' => 'bg-teal-100 text-teal-600',
                                        'completed' => 'bg-emerald-100 text-emerald-600',
                                        'cancelled' => 'bg-rose-100 text-rose-600',
                                        default => 'bg-blue-100 text-blue-600'
                                    };
                                @endphp
                                <a href="{{ $notif['url'] }}" class="flex items-start gap-3 rounded-2xl p-2.5 transition hover:bg-slate-50 border border-transparent hover:border-slate-100">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $iconBg }} text-xs font-bold shadow-2xs mt-0.5">
                                        @if($notif['type'] === 'new_request')
                                            ✨
                                        @elseif($notif['status'] === 'completed')
                                            ✓
                                        @elseif($notif['status'] === 'washing')
                                            🧺
                                        @elseif($notif['status'] === 'drying')
                                            💨
                                        @elseif(in_array($notif['status'], ['ready', 'ready_for_pickup']))
                                            🔔
                                        @elseif($notif['status'] === 'received')
                                            📥
                                        @else
                                            ⏳
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800 leading-tight truncate">{{ $notif['title'] }}</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug line-clamp-2">{{ $notif['message'] }}</p>
                                        <span class="text-[10px] font-mono text-slate-400 mt-1 block">
                                            {{ \Carbon\Carbon::parse($notif['time'])->diffForHumans() }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <div class="py-6 text-center text-slate-400">
                                    <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-700">No new alerts</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Incoming requests and order updates will appear here.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Slot -->
        <main class="mx-auto max-w-7xl px-5 py-7 sm:px-8">
            {{ $slot }}
        </main>
    </div>

    <!-- Dropdown Script -->
    <script>
        function toggleAdminNotifs(event) {
            if (event) event.stopPropagation();
            const popover = document.getElementById('adminNotifPopover');
            if (popover) {
                popover.classList.toggle('hidden');
            }
        }

        document.addEventListener('click', function(e) {
            const popover = document.getElementById('adminNotifPopover');
            const wrapper = document.getElementById('adminNotifWrapper');
            if (popover && !popover.classList.contains('hidden')) {
                if (wrapper && !wrapper.contains(e.target)) {
                    popover.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>
