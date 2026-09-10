<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Spinzone Laundry — Customer Portal' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-800 bg-gradient-to-br from-slate-50 via-blue-50/20 to-purple-50/20 min-h-screen antialiased">
    @php
        $customerUser = auth('customer')->user();
        $customerUsername = $customerUser->username ?? 'Customer';
        $customerBranch = $customerUser->branch ?? null;
        $branchName = $customerBranch->name ?? 'Central Branch';
        $branchInitial = strtoupper(substr($branchName, 0, 1));

        // Aggregate customer notifications from recent service requests & status events
        $customerNotifications = collect();
        if ($customerUser) {
            $recentReqs = \App\Models\ServiceRequest::where('customer_id', $customerUser->id)
                ->with(['branch', 'clothType', 'statusHistories'])
                ->latest('updated_at')
                ->take(8)
                ->get();

            foreach ($recentReqs as $req) {
                // Check if weight was verified or updated
                if (!empty($req->weight_verification_status) && $req->weight_verification_status !== 'pending') {
                    $customerNotifications->push([
                        'id' => 'ver_' . $req->id,
                        'type' => 'verification',
                        'title' => 'Weight Verification Update',
                        'message' => 'Request #REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT) . ' weight has been ' . $req->weight_verification_status . ($req->actual_weight ? ' (' . $req->actual_weight . ' kg)' : '') . '.',
                        'status' => 'verified',
                        'time' => $req->updated_at,
                        'url' => route('customer.requests.show', $req->id),
                    ]);
                }

                // Check status updates
                if ($req->statusHistories->isNotEmpty()) {
                    foreach ($req->statusHistories->sortByDesc('created_at')->take(2) as $hist) {
                        $customerNotifications->push([
                            'id' => 'hist_' . $hist->id,
                            'type' => 'status_update',
                            'title' => 'Order Status: ' . ucfirst(str_replace('_', ' ', $hist->status)),
                            'message' => 'Request #REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT) . ' is now ' . ucfirst(str_replace('_', ' ', $hist->status)) . '.',
                            'status' => $hist->status,
                            'time' => $hist->created_at,
                            'url' => route('customer.requests.show', $req->id),
                        ]);
                    }
                } else {
                    $customerNotifications->push([
                        'id' => 'req_' . $req->id,
                        'type' => 'status_update',
                        'title' => 'Order ' . ucfirst(str_replace('_', ' ', $req->status)),
                        'message' => 'Request #REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT) . ' (' . ($req->clothType->name ?? 'Laundry') . ') status is ' . ucfirst(str_replace('_', ' ', $req->status)) . '.',
                        'status' => $req->status,
                        'time' => $req->updated_at,
                        'url' => route('customer.requests.show', $req->id),
                    ]);
                }
            }

            $customerNotifications = $customerNotifications->sortByDesc('time')->take(6)->values();
        }
    @endphp

    <!-- Desktop Sidebar Navigation -->
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
                <p class="text-[11px] font-medium text-slate-400">Customer Portal</p>
            </div>
        </div>

        <!-- Sidebar Navigation Items -->
        <nav class="space-y-1.5 px-4 py-6">
            <!-- Dashboard Link -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('customer.dashboard') ? 'bg-blue-100/70 text-blue-700 shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('customer.dashboard') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('customer.dashboard') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4z" />
                </svg>
                Dashboard
            </a>

            <!-- Service Requests -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('customer.requests*') ? 'bg-blue-100/70 text-blue-700 font-semibold shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('customer.requests') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('customer.requests*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                My Requests
            </a>

            <!-- About -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('customer.about*') ? 'bg-blue-100/70 text-blue-700 font-semibold shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('customer.about') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('customer.about*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                About
            </a>

            <!-- Profile -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('customer.profile*') ? 'bg-blue-100/70 text-blue-700 font-semibold shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('customer.profile') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('customer.profile*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile
            </a>
        </nav>

        <!-- Sidebar Branch Info & Logout -->
        <div class="absolute bottom-6 inset-x-4 space-y-3">
            <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-3 text-left">
                <div class="flex items-center gap-2">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 font-bold text-[10px]">
                        {{ $branchInitial }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $branchName }}</p>
                        <a href="{{ route('customer.profile') }}" class="text-[10px] font-semibold text-blue-600 hover:underline">Change branch →</a>
                    </div>
                </div>
            </div>

            <form action="{{ route('customer.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl border border-slate-200/80 bg-white py-2.5 text-xs font-semibold text-slate-600 transition hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 cursor-pointer">
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
        <header class="flex h-20 items-center justify-between border-b border-slate-200/60 bg-white/70 backdrop-blur-md px-5 sm:px-8 sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <!-- Mobile Brand Badge -->
                <a href="{{ route('customer.dashboard') }}" class="flex lg:hidden h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/20">
                    <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2.69c0 0-6.36 7.42-6.36 11.66A6.36 6.36 0 0 0 12 20.71a6.36 6.36 0 0 0 6.36-6.36C18.36 10.11 12 2.69 12 2.69zM12 18.7a4.36 4.36 0 0 1-4.36-4.35c0-2.31 3.12-6.52 4.36-8.08 1.24 1.56 4.36 5.76 4.36 8.08A4.36 4.36 0 0 1 12 18.7z"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-blue-950 tracking-tight">
                        Hello, {{ $customerUsername }}
                    </h1>
                </div>
            </div>

            <!-- Top Right: Notification Bell & Avatar -->
            <div class="flex items-center gap-3">
                <!-- Notification Bell Dropdown -->
                <div class="relative" id="customerNotifWrapper">
                    <button 
                        type="button" 
                        id="customerNotifBtn" 
                        onclick="toggleCustomerNotifs(event)" 
                        class="relative flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100/90 text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition border border-slate-200/60 cursor-pointer shadow-2xs active:scale-95" 
                        title="Notifications"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        
                        @if($customerNotifications->isNotEmpty())
                            <span class="absolute top-2 right-2 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600 ring-2 ring-white"></span>
                            </span>
                        @endif
                    </button>

                    <!-- Notification Popover Menu -->
                    <div id="customerNotifPopover" class="hidden absolute right-0 mt-3 w-80 sm:w-96 rounded-3xl border border-slate-200/90 bg-white p-4 shadow-2xl z-50 transition-all duration-200">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-900">Notifications</h3>
                                @if($customerNotifications->isNotEmpty())
                                    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-700 font-mono">
                                        {{ $customerNotifications->count() }}
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('customer.requests') }}" class="text-[11px] font-semibold text-blue-600 hover:underline">
                                View Requests →
                            </a>
                        </div>

                        <div class="space-y-2 max-h-80 overflow-y-auto pr-1 [scrollbar-width:thin]">
                            @forelse($customerNotifications as $notif)
                                @php
                                    $iconBg = match($notif['status']) {
                                        'completed' => 'bg-emerald-100 text-emerald-600',
                                        'washing' => 'bg-purple-100 text-purple-600',
                                        'drying' => 'bg-indigo-100 text-indigo-600',
                                        'ready', 'ready_for_pickup' => 'bg-teal-100 text-teal-600',
                                        'verified' => 'bg-blue-100 text-blue-600',
                                        'cancelled' => 'bg-rose-100 text-rose-600',
                                        default => 'bg-amber-100 text-amber-600'
                                    };
                                @endphp
                                <a href="{{ $notif['url'] }}" class="flex items-start gap-3 rounded-2xl p-2.5 transition hover:bg-slate-50 border border-transparent hover:border-slate-100">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $iconBg }} text-xs font-bold shadow-2xs mt-0.5">
                                        @if($notif['type'] === 'verification')
                                            ⚖️
                                        @elseif($notif['status'] === 'completed')
                                            ✓
                                        @elseif($notif['status'] === 'washing')
                                            🧺
                                        @elseif($notif['status'] === 'drying')
                                            💨
                                        @elseif(in_array($notif['status'], ['ready', 'ready_for_pickup']))
                                            🔔
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
                                    <p class="text-xs font-semibold text-slate-700">No new notifications</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Order and status updates will appear here.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="mx-auto max-w-7xl px-5 py-7 sm:px-8 pb-28 lg:pb-10">
            {{ $slot }}
        </main>
    </div>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200/80 bg-white/95 backdrop-blur-md px-3 py-2 shadow-lg lg:hidden">
        <div class="mx-auto flex max-w-md items-center justify-around">
            <a class="flex flex-col items-center gap-1 px-3 py-1 text-xs font-semibold {{ request()->routeIs('customer.dashboard') ? 'text-blue-600' : 'text-slate-500 hover:text-slate-900' }}" href="{{ route('customer.dashboard') }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Home</span>
            </a>

            <a class="flex flex-col items-center gap-1 px-3 py-1 text-xs font-semibold {{ request()->routeIs('customer.requests*') ? 'text-blue-600' : 'text-slate-500 hover:text-slate-900' }}" href="{{ route('customer.requests') }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Requests</span>
            </a>

            <a class="flex flex-col items-center gap-1 px-3 py-1 text-xs font-semibold {{ request()->routeIs('customer.about*') ? 'text-blue-600' : 'text-slate-500 hover:text-slate-900' }}" href="{{ route('customer.about') }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>About</span>
            </a>

            <a class="flex flex-col items-center gap-1 px-3 py-1 text-xs font-semibold {{ request()->routeIs('customer.profile*') ? 'text-blue-600' : 'text-slate-500 hover:text-slate-900' }}" href="{{ route('customer.profile') }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Profile</span>
            </a>
        </div>
    </nav>

    <!-- Notification Dropdown Scripts -->
    <script>
        function toggleCustomerNotifs(event) {
            if (event) event.stopPropagation();
            const popover = document.getElementById('customerNotifPopover');
            if (popover) {
                popover.classList.toggle('hidden');
            }
        }

        document.addEventListener('click', function(e) {
            const popover = document.getElementById('customerNotifPopover');
            const wrapper = document.getElementById('customerNotifWrapper');
            if (popover && !popover.classList.contains('hidden')) {
                if (wrapper && !wrapper.contains(e.target)) {
                    popover.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>
