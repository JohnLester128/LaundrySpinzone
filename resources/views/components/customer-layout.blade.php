<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Spinzone Laundry — Customer Portal' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Seamless scroll position restoration for filter, sort, clear, and real-time updates
        (function() {
            const scrollKey = 'spinzone_preserve_scroll_' + window.location.pathname;
            const trackerKey = 'spinzone_tracker_scroll';
            const savedScroll = sessionStorage.getItem(scrollKey) || sessionStorage.getItem(trackerKey);

            if (savedScroll !== null) {
                const targetY = parseInt(savedScroll, 10);
                if (!isNaN(targetY) && targetY > 0) {
                    if ('scrollRestoration' in history) {
                        history.scrollRestoration = 'manual';
                    }
                    window.scrollTo(0, targetY);

                    const applyScroll = function() {
                        window.scrollTo({ top: targetY, behavior: 'instant' });
                    };

                    document.addEventListener('DOMContentLoaded', applyScroll);
                    window.addEventListener('load', function() {
                        applyScroll();
                        setTimeout(function() {
                            sessionStorage.removeItem(scrollKey);
                            sessionStorage.removeItem(trackerKey);
                            if ('scrollRestoration' in history) {
                                history.scrollRestoration = 'auto';
                            }
                        }, 50);
                    });

                    setTimeout(function() {
                        sessionStorage.removeItem(scrollKey);
                        sessionStorage.removeItem(trackerKey);
                        if ('scrollRestoration' in history) {
                            history.scrollRestoration = 'auto';
                        }
                    }, 1200);
                } else {
                    sessionStorage.removeItem(scrollKey);
                    sessionStorage.removeItem(trackerKey);
                }
            }
        })();
    </script>
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
                // Check if weight was verified or updated (fixed to verification time so it only alerts once)
                if (!empty($req->weight_verification_status) && $req->weight_verification_status !== 'pending') {
                    $verHist = $req->statusHistories->firstWhere('status', 'received');
                    $verTime = $verHist ? $verHist->created_at : $req->created_at;

                    $customerNotifications->push([
                        'id' => 'ver_' . $req->id,
                        'type' => 'verification',
                        'title' => 'Weight Verification Update',
                        'message' => 'Request #REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT) . ' weight has been ' . $req->weight_verification_status . ($req->actual_weight ? ' (' . $req->actual_weight . ' kg)' : '') . '.',
                        'status' => 'verified',
                        'time' => $verTime,
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

    <!-- In-App Mobile Floating Push Alert Container -->
    <div id="customerFloatingPushContainer" class="fixed top-3 inset-x-3 sm:top-5 sm:right-5 sm:left-auto sm:w-96 z-[99999] pointer-events-none flex flex-col gap-2.5"></div>

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

        <!-- Sidebar Logout -->
        <div class="absolute bottom-6 inset-x-4 space-y-3">
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
        <header class="flex h-20 items-center justify-between border-b border-slate-200/60 bg-white/70 backdrop-blur-md px-5 sm:px-8 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <!-- Mobile Brand Badge -->
                <a href="{{ route('customer.dashboard') }}" class="flex lg:hidden h-10 w-10 shrink-0 items-center justify-center rounded-xl overflow-hidden shadow-xs bg-white border border-slate-200/70 p-0.5">
                    <img src="{{ asset('images/spinzone-logo.png') }}" alt="Spinzone" class="h-full w-full object-contain rounded-lg">
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
                        <span id="customerNotifDot" class="hidden absolute top-2 right-2 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600 ring-2 ring-white"></span>
                        </span>
                    </button>

                    <!-- Notification Popover Menu -->
                    <div id="customerNotifPopover" class="hidden absolute right-0 mt-3 w-80 sm:w-96 rounded-3xl border border-slate-200/90 bg-white p-4 shadow-2xl z-50 transition-all duration-200">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-900">Notifications</h3>
                                <span id="customerNotifCountBadge" class="hidden rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-700 font-mono">
                                    0
                                </span>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" id="customerSoundToggleBtn" onclick="toggleCustomerSound(event)" class="text-slate-400 hover:text-blue-600 transition cursor-pointer p-1.5 rounded-lg hover:bg-slate-100/70" title="Sound alerts enabled (Click to mute)">
                                    <svg id="customerSoundOnIcon" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                    </svg>
                                    <svg id="customerSoundOffIcon" class="hidden h-3.5 w-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                                    </svg>
                                </button>
                                <button type="button" id="customerClearNotifsBtn" onclick="clearAllCustomerNotifs(event)" class="text-[11px] font-semibold text-slate-500 hover:text-rose-600 transition cursor-pointer flex items-center gap-1.5 px-2 py-1 rounded-lg hover:bg-slate-100/70" title="Clear all notifications">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Clear all
                                </button>
                            </div>
                        </div>

                        <div id="customerNotifList" class="space-y-2 max-h-80 overflow-y-auto pr-1 [scrollbar-width:thin]">
                            @foreach($customerNotifications as $notif)
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
                                    $notifTs = \Carbon\Carbon::parse($notif['time'])->timestamp * 1000;
                                @endphp
                                <a href="{{ $notif['url'] }}" 
                                   data-notif-id="{{ $notif['id'] }}"
                                   data-timestamp="{{ $notifTs }}"
                                   onclick="handleCustomerNotifClick(event, '{{ $notif['id'] }}', {{ $notifTs }})"
                                   class="customer-notif-item flex items-start gap-3 rounded-2xl p-2.5 transition hover:bg-slate-50 border border-transparent hover:border-slate-100">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $iconBg }} shadow-2xs mt-0.5">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            @if($notif['status'] === 'completed')
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            @elseif(in_array($notif['status'], ['ready', 'ready_for_pickup']))
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-slate-800 leading-tight truncate">{{ $notif['title'] }}</p>
                                            <span class="customer-unread-indicator hidden h-2 w-2 rounded-full bg-blue-600 shrink-0"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug line-clamp-2">{{ $notif['message'] }}</p>
                                        <span class="text-[10px] font-mono text-slate-400 mt-1 block">
                                            {{ \Carbon\Carbon::parse($notif['time'])->diffForHumans() }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach

                            <div id="customerNotifEmpty" class="py-6 text-center text-slate-400">
                                <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-700">No new notifications</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Order and status updates will appear here.</p>
                            </div>
                        </div>
                <!-- User Profile & Quick Menu Dropdown -->
                <div class="relative" id="customerUserMenuWrapper">
                    <button 
                        type="button" 
                        onclick="toggleCustomerUserMenu(event)"
                        class="flex items-center gap-2 rounded-2xl bg-slate-100/90 hover:bg-blue-50 p-1.5 sm:px-3 sm:py-2 text-slate-700 transition border border-slate-200/60 cursor-pointer shadow-2xs active:scale-95"
                        title="Account Menu"
                    >
                        <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-xs shadow-2xs">
                            {{ strtoupper(substr($customerUsername, 0, 1)) }}
                        </div>
                        <span class="hidden sm:inline-block text-xs font-bold text-slate-800 max-w-[100px] truncate">
                            {{ $customerUsername }}
                        </span>
                        <svg class="hidden sm:block h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- User Dropdown Popover -->
                    <div id="customerUserMenuPopover" class="hidden absolute right-0 mt-3 w-64 rounded-3xl border border-slate-200/90 bg-white p-3 shadow-2xl z-50 transition-all duration-200">
                        <div class="px-3 py-2.5 border-b border-slate-100 mb-2">
                            <p class="text-xs font-extrabold text-slate-900 truncate">{{ $customerUser->full_name ?? $customerUsername }}</p>
                            <p class="text-[11px] text-slate-400 font-mono truncate">{{ '@' . $customerUsername }}</p>
                            <div class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-bold text-blue-700 font-mono">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                <span class="truncate">{{ $branchName }}</span>
                            </div>
                        </div>

                        <div class="space-y-1 text-xs font-semibold">
                            <a href="{{ route('customer.profile') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>My Profile &amp; Branch</span>
                            </a>
                            <a href="{{ route('customer.feedback') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                <span>Feedback &amp; Complaints</span>
                            </a>
                            <a href="{{ route('customer.about') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>About Spinzone</span>
                            </a>
                        </div>

                        <div class="mt-2 pt-2 border-t border-slate-100">
                            <form action="{{ route('customer.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                    <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="mx-auto max-w-7xl px-4 py-5 sm:px-8 sm:py-7 pb-28 lg:pb-10">
            {{ $slot }}
        </main>
    </div>

    <!-- Mobile Bottom Navigation Bar (Modern 5-Item Navigation) -->
    <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200/80 bg-white/95 backdrop-blur-md px-2 py-1.5 shadow-lg lg:hidden">
        <div class="grid h-16 grid-cols-5 items-center justify-around">
            <!-- 1. Dashboard -->
            <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center justify-center text-[10px] font-medium transition {{ request()->routeIs('customer.dashboard') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                <svg class="h-5 w-5 mb-1 {{ request()->routeIs('customer.dashboard') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4z" />
                </svg>
                <span>Home</span>
            </a>

            <!-- 2. Requests -->
            <a href="{{ route('customer.requests') }}" class="flex flex-col items-center justify-center text-[10px] font-medium transition {{ request()->routeIs('customer.requests*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                <svg class="h-5 w-5 mb-1 {{ request()->routeIs('customer.requests*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Requests</span>
            </a>

            <!-- 3. Center Highlight: New Request -->
            <a href="{{ route('customer.service-requests') }}" class="flex flex-col items-center justify-center -mt-5 group" title="Create New Service Request">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/30 transition transform group-hover:scale-105 active:scale-95 ring-4 ring-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-blue-600 mt-1">Book</span>
            </a>

            <!-- 4. About -->
            <a href="{{ route('customer.about') }}" class="flex flex-col items-center justify-center text-[10px] font-medium transition {{ request()->routeIs('customer.about*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                <svg class="h-5 w-5 mb-1 {{ request()->routeIs('customer.about*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>About</span>
            </a>

            <!-- 5. Profile -->
            <a href="{{ route('customer.profile') }}" class="flex flex-col items-center justify-center text-[10px] font-medium transition {{ request()->routeIs('customer.profile*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                <svg class="h-5 w-5 mb-1 {{ request()->routeIs('customer.profile*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Profile</span>
            </a>
        </div>
    </nav>

    <!-- Popover clamping & Notification script -->
    <script>
        // Storage key tied to logged-in customer ID
        const CUSTOMER_STORAGE_KEY = 'spinzone_customer_notifs_{{ $customerUser->id ?? 'default' }}';

        function getCustomerNotifState() {
            try {
                const data = localStorage.getItem(CUSTOMER_STORAGE_KEY);
                return data ? JSON.parse(data) : { lastSeenTime: 0, clearedTime: 0, seenIds: [] };
            } catch (e) {
                return { lastSeenTime: 0, clearedTime: 0, seenIds: [] };
            }
        }

        function saveCustomerNotifState(state) {
            try {
                localStorage.setItem(CUSTOMER_STORAGE_KEY, JSON.stringify(state));
            } catch (e) {}
        }

        function initCustomerNotifications() {
            const state = getCustomerNotifState();
            const items = document.querySelectorAll('#customerNotifList .customer-notif-item');
            const dot = document.getElementById('customerNotifDot');
            const badge = document.getElementById('customerNotifCountBadge');
            const emptyState = document.getElementById('customerNotifEmpty');
            const clearBtn = document.getElementById('customerClearNotifsBtn');

            let visibleCount = 0;
            let unseenCount = 0;

            items.forEach(item => {
                const ts = parseInt(item.getAttribute('data-timestamp') || '0', 10);
                const id = item.getAttribute('data-notif-id');

                // If clearedTime is set and item is older or equal to clearedTime, hide it
                if (state.clearedTime && ts <= state.clearedTime) {
                    item.classList.add('hidden');
                } else {
                    item.classList.remove('hidden');
                    visibleCount++;

                    // Check if unseen: timestamp > lastSeenTime and not in seenIds
                    const isUnseen = ts > (state.lastSeenTime || 0) && (!state.seenIds || !state.seenIds.includes(id));
                    const unreadIndicator = item.querySelector('.customer-unread-indicator');
                    if (isUnseen) {
                        unseenCount++;
                        if (unreadIndicator) unreadIndicator.classList.remove('hidden');
                        item.classList.add('bg-blue-50/40');
                    } else {
                        if (unreadIndicator) unreadIndicator.classList.add('hidden');
                        item.classList.remove('bg-blue-50/40');
                    }
                }
            });

            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            }

            if (clearBtn) {
                if (visibleCount === 0) {
                    clearBtn.classList.add('opacity-40', 'pointer-events-none');
                } else {
                    clearBtn.classList.remove('opacity-40', 'pointer-events-none');
                }
            }

            if (badge) {
                if (visibleCount > 0) {
                    badge.textContent = visibleCount;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }

            // Blue dot only pops up if there is at least 1 new / unseen notification!
            if (dot) {
                if (unseenCount > 0) {
                    dot.classList.remove('hidden');
                } else {
                    dot.classList.add('hidden');
                }
            }
        }

        function markCustomerNotifsAsSeen() {
            const state = getCustomerNotifState();
            const items = document.querySelectorAll('#customerNotifList .customer-notif-item:not(.hidden)');
            const dot = document.getElementById('customerNotifDot');
            let maxTs = state.lastSeenTime || 0;
            const seenIds = state.seenIds || [];

            items.forEach(item => {
                const ts = parseInt(item.getAttribute('data-timestamp') || '0', 10);
                const id = item.getAttribute('data-notif-id');
                if (ts > maxTs) maxTs = ts;
                if (id && !seenIds.includes(id)) seenIds.push(id);

                const unreadIndicator = item.querySelector('.customer-unread-indicator');
                if (unreadIndicator) unreadIndicator.classList.add('hidden');
                item.classList.remove('bg-blue-50/40');
            });

            state.lastSeenTime = Math.max(maxTs, Date.now());
            state.seenIds = seenIds.slice(-50);
            saveCustomerNotifState(state);

            // Dot immediately disappears
            if (dot) dot.classList.add('hidden');
        }

        function handleCustomerNotifClick(event, id, timestamp) {
            const state = getCustomerNotifState();
            if (!state.seenIds.includes(id)) state.seenIds.push(id);
            if (timestamp > (state.lastSeenTime || 0)) state.lastSeenTime = timestamp;
            saveCustomerNotifState(state);
            const dot = document.getElementById('customerNotifDot');
            if (dot) dot.classList.add('hidden');
        }

        function clearAllCustomerNotifs(event) {
            if (event) event.stopPropagation();
            const state = getCustomerNotifState();
            state.clearedTime = Date.now();
            state.lastSeenTime = Date.now();
            saveCustomerNotifState(state);
            initCustomerNotifications();
        }

        // Sound and Alert State
        let customerSessionStartTime = Date.now();
        let customerSoundMuted = localStorage.getItem('spinzone_customer_sound_muted') === '1';

        function updateCustomerSoundIcon() {
            const onIcon = document.getElementById('customerSoundOnIcon');
            const offIcon = document.getElementById('customerSoundOffIcon');
            const btn = document.getElementById('customerSoundToggleBtn');
            if (customerSoundMuted) {
                if (onIcon) onIcon.classList.add('hidden');
                if (offIcon) offIcon.classList.remove('hidden');
                if (btn) btn.title = 'Sound alerts muted (Click to unmute)';
            } else {
                if (onIcon) onIcon.classList.remove('hidden');
                if (offIcon) offIcon.classList.add('hidden');
                if (btn) btn.title = 'Sound alerts enabled (Click to mute)';
            }
        }

        function toggleCustomerSound(event) {
            if (event) event.stopPropagation();
            customerSoundMuted = !customerSoundMuted;
            localStorage.setItem('spinzone_customer_sound_muted', customerSoundMuted ? '1' : '0');
            updateCustomerSoundIcon();
            if (!customerSoundMuted) {
                playCustomerChime();
                triggerCustomerDeviceVibrate();
                requestCustomerPushPermission();
            }
        }

        // Web Audio API Ring / Chime
        function playCustomerChime() {
            if (customerSoundMuted) return;
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                if (ctx.state === 'suspended') {
                    ctx.resume();
                }

                const now = ctx.currentTime;
                // Tone 1: 587.33Hz (D5)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, now);
                gain1.gain.setValueAtTime(0.28, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.35);

                // Tone 2: 880Hz (A5)
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880, now + 0.12);
                gain2.gain.setValueAtTime(0.32, now + 0.12);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.65);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(now + 0.12);
                osc2.stop(now + 0.65);
            } catch (e) {}
        }

        // Mobile Phone Vibration
        function triggerCustomerDeviceVibrate() {
            if (customerSoundMuted) return;
            try {
                if ('vibrate' in navigator) {
                    navigator.vibrate([180, 80, 180]);
                }
            } catch (e) {}
        }

        // Native Desktop / Mobile System Notification
        function requestCustomerPushPermission() {
            try {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
            } catch (e) {}
        }

        function triggerCustomerDesktopAlert(title, body, url) {
            try {
                if ('Notification' in window && Notification.permission === 'granted') {
                    const n = new Notification(title, {
                        body: body,
                        icon: '{{ asset('images/spinzone-logo.png') }}',
                    });
                    if (url) {
                        n.onclick = function() {
                            window.focus();
                            window.location.href = url;
                        };
                    }
                }
            } catch (e) {}
        }

        // In-App Mobile Floating Push Alert Banner (Works 100% on phones without HTTPS)
        function showCustomerFloatingPush(title, message, url, status) {
            const container = document.getElementById('customerFloatingPushContainer');
            if (!container) return;

            let iconBg = 'bg-amber-500 text-white';
            let iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
            if (status === 'completed') {
                iconBg = 'bg-emerald-600 text-white';
                iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />';
            } else if (status === 'ready' || status === 'ready_for_pickup') {
                iconBg = 'bg-teal-600 text-white';
                iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />';
            } else if (status === 'washing') {
                iconBg = 'bg-purple-600 text-white';
            } else if (status === 'drying') {
                iconBg = 'bg-indigo-600 text-white';
            } else if (status === 'verified') {
                iconBg = 'bg-blue-600 text-white';
                iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />';
            }

            const card = document.createElement('div');
            card.className = 'pointer-events-auto w-full rounded-2xl bg-white/95 backdrop-blur-md p-3.5 shadow-2xl border border-blue-100 ring-1 ring-black/5 transform transition-all duration-300 ease-out -translate-y-8 opacity-0';
            card.innerHTML = `
                <div class="flex items-center justify-between gap-2 pb-1.5 border-b border-slate-100/80 mb-2">
                    <div class="flex items-center gap-1.5">
                        <img src="{{ asset('images/spinzone-logo.png') }}" class="h-4 w-4 rounded-full object-cover">
                        <span class="font-mono text-[10px] font-extrabold uppercase tracking-wider text-blue-700">Spinzone Laundry</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-medium text-slate-400">Just now</span>
                        <button type="button" class="dismiss-push-btn text-slate-400 hover:text-slate-700 p-0.5 rounded cursor-pointer" title="Dismiss">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <a href="${url || '#'}" class="flex items-start gap-3 group cursor-pointer">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl ${iconBg} shadow-2xs mt-0.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            ${iconSvg}
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-slate-900 leading-tight group-hover:text-blue-600 transition truncate">${title}</h4>
                        <p class="text-[11px] text-slate-600 mt-0.5 leading-snug line-clamp-2">${message}</p>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-600 mt-1.5 group-hover:underline">
                            <span>View Tracker</span>
                            <span>→</span>
                        </span>
                    </div>
                </a>
            `;

            container.prepend(card);

            // Animate in
            requestAnimationFrame(() => {
                card.classList.remove('-translate-y-8', 'opacity-0');
                card.classList.add('translate-y-0', 'opacity-100');
            });

            function dismissCard() {
                card.classList.remove('translate-y-0', 'opacity-100');
                card.classList.add('-translate-y-8', 'opacity-0');
                setTimeout(() => {
                    if (card.parentNode) card.parentNode.removeChild(card);
                }, 350);
            }

            const closeBtn = card.querySelector('.dismiss-push-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    dismissCard();
                });
            }

            // Auto dismiss after 6.5s
            setTimeout(dismissCard, 6500);
        }

        // Realtime Polling for Customer Notifications
        let customerPollingTimer = null;

        function fetchCustomerRealtimeNotifs() {
            fetch('{{ route('customer.notifications.check') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data && Array.isArray(data.notifications)) {
                    updateCustomerNotifsUI(data.notifications);
                }
                if (data) {
                    window.dispatchEvent(new CustomEvent('spinzone:tracker-sync', {
                        detail: {
                            activeRequests: data.active_requests || [],
                            notifications: data.notifications || []
                        }
                    }));
                }
            })
            .catch(() => {});
        }

        function updateCustomerNotifsUI(notifications) {
            const state = getCustomerNotifState();
            const list = document.getElementById('customerNotifList');
            const dot = document.getElementById('customerNotifDot');
            const badge = document.getElementById('customerNotifCountBadge');
            const clearBtn = document.getElementById('customerClearNotifsBtn');

            let visibleCount = 0;
            let unseenCount = 0;
            let newlyArrivedCount = 0;
            let newestNotif = null;
            let itemsHtml = '';

            notifications.forEach(notif => {
                const ts = notif.timestamp;
                const id = notif.id;

                if (state.clearedTime && ts <= state.clearedTime) {
                    return; // cleared by user
                }

                visibleCount++;
                const isUnseen = ts > (state.lastSeenTime || 0) && (!state.seenIds || !state.seenIds.includes(id));
                if (isUnseen) {
                    unseenCount++;
                    // Detected a newly incoming alert while the tab is open!
                    if (ts > customerSessionStartTime) {
                        newlyArrivedCount++;
                        if (!newestNotif || ts > newestNotif.timestamp) {
                            newestNotif = notif;
                        }
                    }
                }

                let iconBg = 'bg-amber-100 text-amber-600';
                if (notif.status === 'completed') iconBg = 'bg-emerald-100 text-emerald-600';
                else if (notif.status === 'washing') iconBg = 'bg-purple-100 text-purple-600';
                else if (notif.status === 'drying') iconBg = 'bg-indigo-100 text-indigo-600';
                else if (notif.status === 'ready' || notif.status === 'ready_for_pickup') iconBg = 'bg-teal-100 text-teal-600';
                else if (notif.status === 'verified') iconBg = 'bg-blue-100 text-blue-600';
                else if (notif.status === 'cancelled') iconBg = 'bg-rose-100 text-rose-600';

                let iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
                if (notif.status === 'completed') {
                    iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />';
                } else if (notif.status === 'ready' || notif.status === 'ready_for_pickup') {
                    iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />';
                }

                itemsHtml += `
                    <a href="${notif.url}" 
                       data-notif-id="${id}" 
                       data-timestamp="${ts}"
                       onclick="handleCustomerNotifClick(event, '${id}', ${ts})"
                       class="customer-notif-item flex items-start gap-3 rounded-2xl p-2.5 transition hover:bg-slate-50 border border-transparent hover:border-slate-100 ${isUnseen ? 'bg-blue-50/40' : ''}">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl ${iconBg} shadow-2xs mt-0.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                ${iconSvg}
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <p class="text-xs font-bold text-slate-800 leading-tight truncate">${notif.title}</p>
                                <span class="customer-unread-indicator ${isUnseen ? '' : 'hidden'} h-2 w-2 rounded-full bg-blue-600 shrink-0"></span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-snug line-clamp-2">${notif.message}</p>
                            <span class="text-[10px] font-mono text-slate-400 mt-1 block">
                                ${notif.diff}
                            </span>
                        </div>
                    </a>
                `;
            });

            const emptyHtml = `
                <div id="customerNotifEmpty" class="${visibleCount === 0 ? '' : 'hidden'} py-6 text-center text-slate-400">
                    <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-700">No new notifications</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Order and status updates will appear here.</p>
                </div>
            `;

            if (list) {
                list.innerHTML = itemsHtml + emptyHtml;
            }

            if (clearBtn) {
                if (visibleCount === 0) {
                    clearBtn.classList.add('opacity-40', 'pointer-events-none');
                } else {
                    clearBtn.classList.remove('opacity-40', 'pointer-events-none');
                }
            }

            if (badge) {
                if (visibleCount > 0) {
                    badge.textContent = visibleCount;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }

            // Blue dot pops up if there is at least 1 new / unseen notification
            if (dot) {
                if (unseenCount > 0) {
                    dot.classList.remove('hidden');
                } else {
                    dot.classList.add('hidden');
                }
            }

            // RING, VIBRATE, AND DESKTOP ALERT on newly arrived notifications!
            if (newlyArrivedCount > 0 && newestNotif) {
                customerSessionStartTime = Math.max(customerSessionStartTime, newestNotif.timestamp);
                playCustomerChime();
                triggerCustomerDeviceVibrate();
                triggerCustomerDesktopAlert(newestNotif.title, newestNotif.message, newestNotif.url);
                showCustomerFloatingPush(newestNotif.title, newestNotif.message, newestNotif.url, newestNotif.status);
            }
        }

        function startCustomerPolling() {
            if (customerPollingTimer) clearInterval(customerPollingTimer);
            // Check every 10 seconds silently in the background
            customerPollingTimer = setInterval(fetchCustomerRealtimeNotifs, 10000);
        }

        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                fetchCustomerRealtimeNotifs();
            }
        });

        // Maintain scroll position when clicking filter, sort, layout, or any same-page link/action
        (function() {
            const scrollKey = 'spinzone_preserve_scroll_' + window.location.pathname;

            document.addEventListener('click', function(e) {
                const a = e.target.closest('a');
                if (!a || !a.href) return;
                if (e.metaKey || e.ctrlKey || e.shiftKey || a.target === '_blank' || a.hasAttribute('download')) return;

                try {
                    const url = new URL(a.href, window.location.href);
                    if (url.origin === window.location.origin && url.pathname === window.location.pathname) {
                        sessionStorage.setItem(scrollKey, String(window.scrollY));
                    }
                } catch (err) {}
            }, true);

            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (!form || (form.method && form.method.toLowerCase() === 'post')) return;
                try {
                    const url = new URL(form.action || window.location.href, window.location.href);
                    if (url.origin === window.location.origin && url.pathname === window.location.pathname) {
                        sessionStorage.setItem(scrollKey, String(window.scrollY));
                    }
                } catch (err) {}
            }, true);

            window.addEventListener('pagehide', function() {
                if (sessionStorage.getItem(scrollKey) !== null) {
                    sessionStorage.setItem(scrollKey, String(window.scrollY));
                }
            });
        })();

        // Initialize state on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                initCustomerNotifications();
                updateCustomerSoundIcon();
                startCustomerPolling();
            });
        } else {
            initCustomerNotifications();
            updateCustomerSoundIcon();
            startCustomerPolling();
        }

        // Viewport clamping helper to prevent overflow
        window.repositionPopover = function(target) {
            if (!target || target.classList.contains('hidden')) return;
            target.style.transform = '';
            const rect = target.getBoundingClientRect();
            const pad = 12;
            const vw = window.innerWidth || document.documentElement.clientWidth;
            let shiftX = 0;
            if (rect.right > vw - pad) {
                shiftX = (vw - pad) - rect.right;
            }
            if (rect.left + shiftX < pad) {
                shiftX = pad - rect.left;
            }
            if (shiftX !== 0) {
                target.style.transform = `translateX(${shiftX}px)`;
            }
        };

        window.addEventListener('resize', function() {
            document.querySelectorAll('.control-popover:not(.hidden)').forEach(p => {
                window.repositionPopover(p);
            });
            const customerNotif = document.getElementById('customerNotifPopover');
            if (customerNotif && !customerNotif.classList.contains('hidden')) {
                window.repositionPopover(customerNotif);
            }
        });

        function toggleCustomerNotifs(event) {
            if (event) event.stopPropagation();
            document.querySelectorAll('.control-popover').forEach(p => {
                p.classList.add('hidden');
                p.style.transform = '';
            });
            const popover = document.getElementById('customerNotifPopover');
            if (popover) {
                const isOpening = popover.classList.contains('hidden');
                popover.classList.toggle('hidden');
                if (isOpening) {
                    window.repositionPopover(popover);
                    requestAnimationFrame(() => window.repositionPopover(popover));
                    markCustomerNotifsAsSeen();
                }
            }
        }

        function toggleCustomerUserMenu(event) {
            if (event) event.stopPropagation();
            const notifPopover = document.getElementById('customerNotifPopover');
            if (notifPopover) {
                notifPopover.classList.add('hidden');
                notifPopover.style.transform = '';
            }
            const popover = document.getElementById('customerUserMenuPopover');
            if (popover) {
                const isOpening = popover.classList.contains('hidden');
                popover.classList.toggle('hidden');
                if (isOpening) {
                    window.repositionPopover(popover);
                    requestAnimationFrame(() => window.repositionPopover(popover));
                }
            }
        }

        document.addEventListener('click', function(e) {
            const notifPopover = document.getElementById('customerNotifPopover');
            const notifWrapper = document.getElementById('customerNotifWrapper');
            if (notifPopover && !notifPopover.classList.contains('hidden')) {
                if (notifWrapper && !notifWrapper.contains(e.target)) {
                    notifPopover.classList.add('hidden');
                    notifPopover.style.transform = '';
                }
            }

            const userPopover = document.getElementById('customerUserMenuPopover');
            const userWrapper = document.getElementById('customerUserMenuWrapper');
            if (userPopover && !userPopover.classList.contains('hidden')) {
                if (userWrapper && !userWrapper.contains(e.target)) {
                    userPopover.classList.add('hidden');
                    userPopover.style.transform = '';
                }
            }
        });

    </script>
</body>
</html>
