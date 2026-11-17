<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'LaundrySpinzone - Admin' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Seamless scroll position restoration for filter, sort, clear, and real-time updates
        (function() {
            const scrollKey = 'spinzone_preserve_scroll_' + window.location.pathname;
            const adminKey = 'spinzone_admin_scroll';
            const savedScroll = sessionStorage.getItem(scrollKey) || sessionStorage.getItem(adminKey);

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
                            sessionStorage.removeItem(adminKey);
                            if ('scrollRestoration' in history) {
                                history.scrollRestoration = 'auto';
                            }
                        }, 50);
                    });

                    setTimeout(function() {
                        sessionStorage.removeItem(scrollKey);
                        sessionStorage.removeItem(adminKey);
                        if ('scrollRestoration' in history) {
                            history.scrollRestoration = 'auto';
                        }
                    }, 1200);
                } else {
                    sessionStorage.removeItem(scrollKey);
                    sessionStorage.removeItem(adminKey);
                }
            }
        })();
    </script>
</head>
<body class="font-sans text-slate-800 bg-gradient-to-br from-slate-50 via-blue-50/20 to-purple-50/20 min-h-screen antialiased">
    @php
        $adminUser = auth('admin')->user();
        $adminUsername = $adminUser->username ?? 'Admin';
        $isOwner = $adminUser?->isSuperAdmin() ?? false;
        $branchName = $isOwner ? 'Headquarters' : ($adminUser?->branch?->name ?? 'Central Branch');
        $adminBranchId = $adminUser?->branch_id ?? null;
        $roleBadge = $adminUser?->getRoleBadgeLabel() ?? 'Branch Manager';
        $roleClasses = $adminUser?->getRoleBadgeClasses() ?? 'bg-blue-100/90 text-blue-700 border-blue-200/80 shadow-xs';

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

    <!-- In-App Mobile Floating Push Alert Container -->
    <div id="adminFloatingPushContainer" class="fixed top-3 inset-x-3 sm:top-5 sm:right-5 sm:left-auto sm:w-96 z-[99999] pointer-events-none flex flex-col gap-2.5"></div>

    <!-- Mobile Sidebar Backdrop -->
    <div id="adminSidebarBackdrop" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm hidden lg:hidden transition-opacity duration-300" onclick="closeAdminSidebar()"></div>

    <!-- Sidebar Navigation (Desktop: always visible, Mobile: slide-out drawer) -->
    <aside id="adminSidebar" class="fixed inset-y-0 left-0 w-64 border-r border-slate-200/70 bg-white/90 backdrop-blur-md z-50 shadow-xs transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
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
                    Laundry Management System
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

            <!-- Feedback -->
            <a class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.feedback*') ? 'bg-blue-100/70 text-blue-700 font-semibold shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('admin.feedback') }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('admin.feedback*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                Feedback &amp; Complaints
            </a>

            @if($isOwner)
            <!-- Settings Group (Owner Only: Cycle & Pricing, Branches, Customers, Login Logs) -->
            <div class="space-y-1">
                <a class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.settings*', 'admin.branches*', 'admin.customers*', 'admin.login-logs*') ? 'bg-blue-100/70 text-blue-700 shadow-2xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}" href="{{ route('admin.settings') }}">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-3 {{ request()->routeIs('admin.settings*', 'admin.branches*', 'admin.customers*', 'admin.login-logs*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Settings</span>
                    </div>
                    <svg class="h-4 w-4 transition-transform {{ request()->routeIs('admin.settings*', 'admin.branches*', 'admin.customers*', 'admin.login-logs*') ? 'rotate-90 text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                @if(request()->routeIs('admin.settings*', 'admin.branches*', 'admin.customers*', 'admin.login-logs*'))
                <!-- Sub-items under Settings -->
                <div class="pl-9 pr-1 space-y-1 text-xs">
                    <a href="{{ route('admin.settings') }}" class="flex items-center rounded-xl px-3 py-2 font-medium transition {{ request()->routeIs('admin.settings*') ? 'bg-blue-600 text-white font-bold shadow-2xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>Cycle &amp; Pricing</span>
                    </a>
                    <a href="{{ route('admin.branches') }}" class="flex items-center rounded-xl px-3 py-2 font-medium transition {{ request()->routeIs('admin.branches*') ? 'bg-blue-600 text-white font-bold shadow-2xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>Branches &amp; Managers</span>
                    </a>
                    <a href="{{ route('admin.customers') }}" class="flex items-center rounded-xl px-3 py-2 font-medium transition {{ request()->routeIs('admin.customers*') ? 'bg-blue-600 text-white font-bold shadow-2xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>Customers</span>
                    </a>
                    <a href="{{ route('admin.login-logs') }}" class="flex items-center rounded-xl px-3 py-2 font-medium transition {{ request()->routeIs('admin.login-logs*') ? 'bg-blue-600 text-white font-bold shadow-2xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>Login Logs</span>
                    </a>
                </div>
                @endif
            </div>
            @endif

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
        <header class="flex h-16 lg:h-20 items-center justify-between border-b border-slate-200/60 bg-white/70 backdrop-blur-md px-4 sm:px-6 lg:px-8 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <!-- Mobile Hamburger Menu Button -->
                <button type="button" onclick="toggleAdminSidebar()" class="flex lg:hidden h-10 w-10 items-center justify-center rounded-xl bg-slate-100/90 text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition border border-slate-200/60 cursor-pointer shadow-2xs active:scale-95" title="Open Menu">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <!-- Mobile Brand Badge -->
                <a href="{{ route('admin.dashboard') }}" class="flex lg:hidden h-10 w-10 items-center justify-center rounded-xl overflow-hidden bg-white border border-slate-200/70 shadow-2xs p-0.5">
                    <img src="{{ asset('images/spinzone-logo.png') }}" alt="Spinzone" class="h-full w-full object-cover rounded-lg">
                </a>
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
                        
                        <span id="adminNotifDot" class="hidden absolute top-2 right-2 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600 ring-2 ring-white"></span>
                        </span>
                    </button>

                    <!-- Admin Notification Popover Menu -->
                    <div id="adminNotifPopover" class="hidden absolute right-0 mt-3 w-80 sm:w-96 rounded-3xl border border-slate-200/90 bg-white p-4 shadow-2xl z-50 transition-all duration-200">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-900">Admin Alerts</h3>
                                <span id="adminNotifCountBadge" class="hidden rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-700 font-mono">
                                    0
                                </span>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" id="adminSoundToggleBtn" onclick="toggleAdminSound(event)" class="text-slate-400 hover:text-blue-600 transition cursor-pointer p-1.5 rounded-lg hover:bg-slate-100/70" title="Sound alerts enabled (Click to mute)">
                                    <svg id="adminSoundOnIcon" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                    </svg>
                                    <svg id="adminSoundOffIcon" class="hidden h-3.5 w-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                                    </svg>
                                </button>
                                <button type="button" id="adminClearNotifsBtn" onclick="clearAllAdminNotifs(event)" class="text-[11px] font-semibold text-slate-500 hover:text-rose-600 transition cursor-pointer flex items-center gap-1.5 px-2 py-1 rounded-lg hover:bg-slate-100/70" title="Clear all notifications">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Clear all
                                </button>
                            </div>
                        </div>

                        <div id="adminNotifList" class="space-y-2 max-h-80 overflow-y-auto pr-1 [scrollbar-width:thin]">
                            @foreach($adminNotifications as $notif)
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
                                    $notifTs = \Carbon\Carbon::parse($notif['time'])->timestamp * 1000;
                                @endphp
                                <a href="{{ $notif['url'] }}" 
                                   data-notif-id="{{ $notif['id'] }}" 
                                   data-timestamp="{{ $notifTs }}"
                                   onclick="handleAdminNotifClick(event, '{{ $notif['id'] }}', {{ $notifTs }})"
                                   class="admin-notif-item flex items-start gap-3 rounded-2xl p-2.5 transition hover:bg-slate-50 border border-transparent hover:border-slate-100">
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
                                            <span class="admin-unread-indicator hidden h-2 w-2 rounded-full bg-blue-600 shrink-0"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug line-clamp-2">{{ $notif['message'] }}</p>
                                        <span class="text-[10px] font-mono text-slate-400 mt-1 block">
                                            {{ \Carbon\Carbon::parse($notif['time'])->diffForHumans() }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach

                            <div id="adminNotifEmpty" class="py-6 text-center text-slate-400">
                                <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-700">No new alerts</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Incoming requests and order updates will appear here.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Slot -->
        <main class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8 sm:py-7">
            {{ $slot }}
        </main>
    </div>

    <!-- Dropdown & Mobile Sidebar Scripts -->
    <script>
        // Storage key tied to logged-in admin user ID
        const ADMIN_STORAGE_KEY = 'spinzone_admin_notifs_{{ $adminUser->id ?? 'default' }}';

        function getAdminNotifState() {
            try {
                const data = localStorage.getItem(ADMIN_STORAGE_KEY);
                return data ? JSON.parse(data) : { lastSeenTime: 0, clearedTime: 0, seenIds: [] };
            } catch (e) {
                return { lastSeenTime: 0, clearedTime: 0, seenIds: [] };
            }
        }

        function saveAdminNotifState(state) {
            try {
                localStorage.setItem(ADMIN_STORAGE_KEY, JSON.stringify(state));
            } catch (e) {}
        }

        function initAdminNotifications() {
            const state = getAdminNotifState();
            const items = document.querySelectorAll('#adminNotifList .admin-notif-item');
            const dot = document.getElementById('adminNotifDot');
            const badge = document.getElementById('adminNotifCountBadge');
            const emptyState = document.getElementById('adminNotifEmpty');
            const clearBtn = document.getElementById('adminClearNotifsBtn');

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
                    const unreadIndicator = item.querySelector('.admin-unread-indicator');
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

        function markAdminNotifsAsSeen() {
            const state = getAdminNotifState();
            const items = document.querySelectorAll('#adminNotifList .admin-notif-item:not(.hidden)');
            const dot = document.getElementById('adminNotifDot');
            let maxTs = state.lastSeenTime || 0;
            const seenIds = state.seenIds || [];

            items.forEach(item => {
                const ts = parseInt(item.getAttribute('data-timestamp') || '0', 10);
                const id = item.getAttribute('data-notif-id');
                if (ts > maxTs) maxTs = ts;
                if (id && !seenIds.includes(id)) seenIds.push(id);

                const unreadIndicator = item.querySelector('.admin-unread-indicator');
                if (unreadIndicator) unreadIndicator.classList.add('hidden');
                item.classList.remove('bg-blue-50/40');
            });

            state.lastSeenTime = Math.max(maxTs, Date.now());
            state.seenIds = seenIds.slice(-50);
            saveAdminNotifState(state);

            // Dot immediately disappears
            if (dot) dot.classList.add('hidden');
        }

        function handleAdminNotifClick(event, id, timestamp) {
            const state = getAdminNotifState();
            if (!state.seenIds.includes(id)) state.seenIds.push(id);
            if (timestamp > (state.lastSeenTime || 0)) state.lastSeenTime = timestamp;
            saveAdminNotifState(state);
            const dot = document.getElementById('adminNotifDot');
            if (dot) dot.classList.add('hidden');
        }

        function clearAllAdminNotifs(event) {
            if (event) event.stopPropagation();
            const state = getAdminNotifState();
            state.clearedTime = Date.now();
            state.lastSeenTime = Date.now();
            saveAdminNotifState(state);
            initAdminNotifications();
        }

        // Sound and Alert State
        let adminSessionStartTime = Date.now();
        let adminSoundMuted = localStorage.getItem('spinzone_admin_sound_muted') === '1';

        function updateAdminSoundIcon() {
            const onIcon = document.getElementById('adminSoundOnIcon');
            const offIcon = document.getElementById('adminSoundOffIcon');
            const btn = document.getElementById('adminSoundToggleBtn');
            if (adminSoundMuted) {
                if (onIcon) onIcon.classList.add('hidden');
                if (offIcon) offIcon.classList.remove('hidden');
                if (btn) btn.title = 'Sound alerts muted (Click to unmute)';
            } else {
                if (onIcon) onIcon.classList.remove('hidden');
                if (offIcon) offIcon.classList.add('hidden');
                if (btn) btn.title = 'Sound alerts enabled (Click to mute)';
            }
        }

        function toggleAdminSound(event) {
            if (event) event.stopPropagation();
            adminSoundMuted = !adminSoundMuted;
            localStorage.setItem('spinzone_admin_sound_muted', adminSoundMuted ? '1' : '0');
            updateAdminSoundIcon();
            if (!adminSoundMuted) {
                playAdminChime();
                triggerDeviceVibrate();
                requestPushPermission();
            }
        }

        // Web Audio API Ring / Chime
        function playAdminChime() {
            if (adminSoundMuted) return;
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
        function triggerDeviceVibrate() {
            if (adminSoundMuted) return;
            try {
                if ('vibrate' in navigator) {
                    navigator.vibrate([180, 80, 180]);
                }
            } catch (e) {}
        }

        // Native Desktop / Mobile System Notification
        function requestPushPermission() {
            try {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
            } catch (e) {}
        }

        function triggerDesktopAlert(title, body, url) {
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
        function showAdminFloatingPush(title, message, url, status) {
            const container = document.getElementById('adminFloatingPushContainer');
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
            }

            const card = document.createElement('div');
            card.className = 'pointer-events-auto w-full rounded-2xl bg-white/95 backdrop-blur-md p-3.5 shadow-2xl border border-blue-100 ring-1 ring-black/5 transform transition-all duration-300 ease-out -translate-y-8 opacity-0';
            card.innerHTML = `
                <div class="flex items-center justify-between gap-2 pb-1.5 border-b border-slate-100/80 mb-2">
                    <div class="flex items-center gap-1.5">
                        <img src="{{ asset('images/spinzone-logo.png') }}" class="h-4 w-4 rounded-full object-cover">
                        <span class="font-mono text-[10px] font-extrabold uppercase tracking-wider text-blue-700">Spinzone Staff Alert</span>
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
                            <span>Open Request</span>
                            <span>→</span>
                        </span>
                    </div>
                </a>
            `;

            container.prepend(card);

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

            setTimeout(dismissCard, 6500);
        }

        // Realtime Polling for Admin Notifications
        let adminPollingTimer = null;

        function fetchAdminRealtimeNotifs() {
            fetch('{{ route('admin.notifications.check') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data && Array.isArray(data.notifications)) {
                    updateAdminNotifsUI(data.notifications);
                }
            })
            .catch(() => {});
        }

        function updateAdminNotifsUI(notifications) {
            const state = getAdminNotifState();
            const list = document.getElementById('adminNotifList');
            const dot = document.getElementById('adminNotifDot');
            const badge = document.getElementById('adminNotifCountBadge');
            const clearBtn = document.getElementById('adminClearNotifsBtn');

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
                    if (ts > adminSessionStartTime) {
                        newlyArrivedCount++;
                        if (!newestNotif || ts > newestNotif.timestamp) {
                            newestNotif = notif;
                        }
                    }
                }

                let iconBg = 'bg-blue-100 text-blue-600';
                if (notif.status === 'pending') iconBg = 'bg-amber-100 text-amber-600';
                else if (notif.status === 'received') iconBg = 'bg-slate-100 text-slate-600';
                else if (notif.status === 'washing') iconBg = 'bg-purple-100 text-purple-600';
                else if (notif.status === 'drying') iconBg = 'bg-indigo-100 text-indigo-600';
                else if (notif.status === 'ready' || notif.status === 'ready_for_pickup') iconBg = 'bg-teal-100 text-teal-600';
                else if (notif.status === 'completed') iconBg = 'bg-emerald-100 text-emerald-600';
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
                       onclick="handleAdminNotifClick(event, '${id}', ${ts})"
                       class="admin-notif-item flex items-start gap-3 rounded-2xl p-2.5 transition hover:bg-slate-50 border border-transparent hover:border-slate-100 ${isUnseen ? 'bg-blue-50/40' : ''}">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl ${iconBg} shadow-2xs mt-0.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                ${iconSvg}
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <p class="text-xs font-bold text-slate-800 leading-tight truncate">${notif.title}</p>
                                <span class="admin-unread-indicator ${isUnseen ? '' : 'hidden'} h-2 w-2 rounded-full bg-blue-600 shrink-0"></span>
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
                <div id="adminNotifEmpty" class="${visibleCount === 0 ? '' : 'hidden'} py-6 text-center text-slate-400">
                    <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-700">No new alerts</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Incoming requests and order updates will appear here.</p>
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
                adminSessionStartTime = Math.max(adminSessionStartTime, newestNotif.timestamp);
                playAdminChime();
                triggerDeviceVibrate();
                triggerDesktopAlert(newestNotif.title, newestNotif.message, newestNotif.url);
                showAdminFloatingPush(newestNotif.title, newestNotif.message, newestNotif.url, newestNotif.status);
            }
        }

        function startAdminPolling() {
            if (adminPollingTimer) clearInterval(adminPollingTimer);
            // Check every 10 seconds silently in the background
            adminPollingTimer = setInterval(fetchAdminRealtimeNotifs, 10000);
        }

        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                fetchAdminRealtimeNotifs();
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
                initAdminNotifications();
                updateAdminSoundIcon();
                startAdminPolling();
            });
        } else {
            initAdminNotifications();
            updateAdminSoundIcon();
            startAdminPolling();
        }

        // Global popover viewport clamping helper to prevent off-screen overflow on mobile
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
            const adminNotif = document.getElementById('adminNotifPopover');
            if (adminNotif && !adminNotif.classList.contains('hidden')) {
                window.repositionPopover(adminNotif);
            }
        });

        function toggleAdminNotifs(event) {
            if (event) event.stopPropagation();
            document.querySelectorAll('.control-popover').forEach(p => {
                p.classList.add('hidden');
                p.style.transform = '';
            });
            const popover = document.getElementById('adminNotifPopover');
            if (popover) {
                const isOpening = popover.classList.contains('hidden');
                popover.classList.toggle('hidden');
                if (isOpening) {
                    window.repositionPopover(popover);
                    requestAnimationFrame(() => window.repositionPopover(popover));
                    markAdminNotifsAsSeen();
                }
            }
        }

        document.addEventListener('click', function(e) {
            const popover = document.getElementById('adminNotifPopover');
            const wrapper = document.getElementById('adminNotifWrapper');
            if (popover && !popover.classList.contains('hidden')) {
                if (wrapper && !wrapper.contains(e.target)) {
                    popover.classList.add('hidden');
                    popover.style.transform = '';
                }
            }
        });


        // Mobile sidebar drawer
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            if (sidebar && backdrop) {
                const isOpen = !sidebar.classList.contains('-translate-x-full');
                if (isOpen) {
                    closeAdminSidebar();
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }
        }

        function closeAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            if (sidebar && backdrop) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    </script>
</body>
</html>
