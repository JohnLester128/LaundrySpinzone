<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spinzone Laundry — Smart Laundry Care &amp; Portals</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased selection:bg-blue-500 selection:text-white relative overflow-x-hidden">
    
    <!-- Ambient Background Lighting -->
    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        <div class="absolute -top-40 -left-40 h-[500px] w-[500px] rounded-full bg-blue-100/60 blur-3xl"></div>
        <div class="absolute top-1/4 -right-40 h-[500px] w-[500px] rounded-full bg-indigo-100/50 blur-3xl"></div>
        <div class="absolute bottom-10 left-1/3 h-[500px] w-[500px] rounded-full bg-teal-100/40 blur-3xl"></div>
    </div>

    <!-- Main Wrapper -->
    <div class="relative z-10 flex min-h-screen flex-col">
        
        <!-- Navigation Bar -->
        <header class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-slate-200/70 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
                
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                        <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.69c0 0-6.36 7.42-6.36 11.66A6.36 6.36 0 0 0 12 20.71a6.36 6.36 0 0 0 6.36-6.36C18.36 10.11 12 2.69 12 2.69zM12 18.7a4.36 4.36 0 0 1-4.36-4.35c0-2.31 3.12-6.52 4.36-8.08 1.24 1.56 4.36 5.76 4.36 8.08A4.36 4.36 0 0 1 12 18.7z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-extrabold tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">
                            Spinzone <span class="text-blue-600">Laundry</span>
                        </span>
                        <span class="block text-[9px] font-bold uppercase tracking-[0.2em] text-slate-400 font-mono -mt-1">
                            Fresh &amp; Clean
                        </span>
                    </div>
                </a>

                <!-- Quick Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="#portals" class="hover:text-blue-600 transition-colors">Portals</a>
                    <a href="#branches" class="hover:text-blue-600 transition-colors">Branches</a>
                    <a href="#features" class="hover:text-blue-600 transition-colors">Services</a>
                    <a href="{{ route('customer.about') }}" class="hover:text-blue-600 transition-colors">About</a>
                </nav>

                <!-- Actions Header -->
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <a href="{{ route('admin.login') }}" class="hidden sm:inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white/80 px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-all shadow-2xs">
                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        Staff Login
                    </a>

                    <a href="{{ route('customer.choose-branch') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-xs sm:text-sm font-bold text-white hover:bg-blue-700 transition shadow-sm shadow-blue-600/20 active:scale-98">
                        <span>Choose Branch</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>

            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1">

            <!-- HERO SECTION (Option 2 Landing Element) -->
            <section class="relative pt-12 pb-16 sm:pt-20 sm:pb-24 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="text-center max-w-3xl mx-auto">
                        <!-- Top Tagline Badge -->
                        <div class="inline-flex items-center gap-2 rounded-full border border-blue-200/80 bg-blue-50/80 px-4 py-1.5 text-xs font-extrabold text-blue-700 shadow-2xs backdrop-blur-xs mb-6">
                            <span class="inline-block h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                            <span>SMART &amp; AUTOMATED LAUNDRY SYSTEM</span>
                        </div>

                        <!-- Main Heading -->
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.15]">
                            Fresh &amp; Clean Laundry, <br>
                            <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-teal-600 bg-clip-text text-transparent">
                                Made Effortless.
                            </span>
                        </h1>

                        <!-- Subtitle -->
                        <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                            Track your laundry online step-by-step with Spinzone. Select your branch, book service requests, and follow your order's progress from verification and washing to drying and ready for pickup.
                        </p>

                        <!-- Primary CTA Buttons -->
                        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                            <a href="{{ route('customer.choose-branch') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-600/25 hover:from-blue-700 hover:to-indigo-700 hover:-translate-y-0.5 transition duration-200">
                                <svg class="h-5 w-5 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                                    <rect x="4" y="3" width="16" height="18" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="13" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="8" cy="6" r="1" fill="currentColor"/>
                                    <circle cx="11" cy="6" r="1" fill="currentColor"/>
                                    <path d="M10.5 13.5a2 2 0 0 1 2.5-1" stroke-linecap="round"/>
                                </svg>
                                <span>Choose Branch &amp; Get Started</span>
                            </a>

                            <a href="{{ route('customer.login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white/90 px-6 py-3.5 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300 transition duration-200 shadow-2xs">
                                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Track My Laundry / Login</span>
                            </a>
                        </div>

                        <!-- Stats Bar -->
                        <div class="mt-12 pt-8 border-t border-slate-200/80 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto">
                            <div class="p-3">
                                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">100%</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Fabric Care</p>
                            </div>
                            <div class="p-3">
                                <p class="text-2xl sm:text-3xl font-extrabold text-blue-600">Step-by-Step</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Order Tracking</p>
                            </div>
                            <div class="p-3">
                                <p class="text-2xl sm:text-3xl font-extrabold text-indigo-600">Express</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Turnaround Option</p>
                            </div>
                            <div class="p-3">
                                <p class="text-2xl sm:text-3xl font-extrabold text-teal-600">Multiple</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Active Branches</p>
                            </div>
                        </div>

                    </div>

                </div>
            </section>

            <!-- DUAL PORTAL GATEWAY / HUB (Option 3 Element) -->
            <section id="portals" class="py-14 sm:py-18 bg-white/60 border-y border-slate-200/80 backdrop-blur-xs">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="text-center max-w-2xl mx-auto mb-10">
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-blue-600 font-mono">
                            <span>✦</span> Access Gateways
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mt-2">
                            Select Your Access Portal
                        </h2>
                        <p class="text-sm text-slate-500 mt-1.5">
                            Whether you are dropping off laundry or managing branch operations, select your portal below.
                        </p>
                    </div>

                    <!-- Dual Portal Grid -->
                    <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                        
                        <!-- PORTAL 1: CUSTOMER PORTAL -->
                        <div class="relative overflow-hidden rounded-3xl border-2 border-blue-200/80 bg-gradient-to-b from-white to-blue-50/40 p-7 sm:p-8 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex h-13 w-13 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-500/25 group-hover:scale-105 transition-transform">
                                        <!-- Washing Machine Icon -->
                                        <svg class="h-6 w-6 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                                            <rect x="4" y="3" width="16" height="18" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="12" cy="13" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="8" cy="6" r="1" fill="currentColor"/>
                                            <circle cx="11" cy="6" r="1" fill="currentColor"/>
                                            <path d="M10.5 13.5a2 2 0 0 1 2.5-1" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-extrabold text-blue-700">
                                        For Customers
                                    </span>
                                </div>

                                <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors">
                                    Customer Laundry Portal
                                </h3>
                                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                                    Book laundry requests online, track your clothes through every live stage (Received, Washing, Drying, Ready), and manage order receipts.
                                </p>

                                <!-- Features list -->
                                <ul class="mt-5 space-y-2.5 text-xs font-semibold text-slate-600">
                                    <li class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        Choose branch (Baybay, Panay &amp; more)
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        Create online wash, dry &amp; fold requests
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        Step-by-step tracking: Pending → Washing → Drying → Ready
                                    </li>
                                </ul>
                            </div>

                            <!-- Portal CTAs -->
                            <div class="mt-7 pt-5 border-t border-slate-200/80 flex flex-col sm:flex-row gap-2.5">
                                <a href="{{ route('customer.choose-branch') }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-blue-700 transition shadow-xs">
                                    <span>Choose Branch</span>
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </a>
                                <a href="{{ route('customer.login') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                                    Login
                                </a>
                                <a href="{{ route('customer.register') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                                    Register
                                </a>
                            </div>
                        </div>

                        <!-- PORTAL 2: STAFF & ADMIN PORTAL -->
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-b from-white to-slate-50/50 p-7 sm:p-8 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-slate-700 via-slate-800 to-indigo-900"></div>

                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex h-13 w-13 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-md shadow-slate-900/20 group-hover:scale-105 transition-transform">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3" />
                                        </svg>
                                    </div>
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-700 border border-slate-200/80">
                                        Staff &amp; Admin
                                    </span>
                                </div>

                                <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                    Management &amp; Staff Portal
                                </h3>
                                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                                    Manage walk-in services, weigh and verify laundry requests, assign washing machines, control queues, and inspect daily branch sales.
                                </p>

                                <!-- Features list -->
                                <ul class="mt-5 space-y-2.5 text-xs font-semibold text-slate-600">
                                    <li class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        Live queue &amp; order status workflow
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        Commercial washer/dryer machine controls
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        Sales analytics, customer records &amp; logs
                                    </li>
                                </ul>
                            </div>

                            <!-- Portal CTAs -->
                            <div class="mt-7 pt-5 border-t border-slate-200/80 flex gap-2.5">
                                <a href="{{ route('admin.login') }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition shadow-xs">
                                    <span>Staff &amp; Admin Sign In</span>
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>
            </section>

            <!-- BRANCH SELECTOR SHOWCASE SECTION -->
            <section id="branches" class="py-14 sm:py-18">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                        <div>
                            <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-blue-600 font-mono">
                                <span>✦</span> Our Locations
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mt-1">
                                Available Branches
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Click any branch to select it and proceed to the customer portal.
                            </p>
                        </div>

                        <a href="{{ route('customer.choose-branch') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                            <span>Open Full Branch Chooser</span>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    </div>

                    <!-- Branches Grid -->
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @forelse($branches as $branch)
                            @php
                                $displayName = \Illuminate\Support\Str::replaceLast(' Branch', '', $branch->name);
                                $lowerName = strtolower($displayName);
                                $isBaybay = str_contains($lowerName, 'baybay');
                                $isPanay = str_contains($lowerName, 'panay');
                                
                                $subtext = match(true) {
                                    $isBaybay => 'Main Branch · Full Service & Drop-Off',
                                    $isPanay => 'Express Service Branch · Fast Turnaround',
                                    default => $branch->address ? $branch->address : 'Spinzone Laundry Location'
                                };
                            @endphp

                            <form method="POST" action="{{ route('branch.select') }}" class="h-full">
                                @csrf
                                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                <button type="submit" class="group w-full h-full text-left rounded-2xl border border-slate-200/80 bg-white p-6 shadow-2xs hover:border-blue-400 hover:shadow-lg hover:-translate-y-1 active:translate-y-0 transition-all duration-200 flex flex-col justify-between cursor-pointer">
                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <!-- Uniform Washing Machine Icon (Matching Panay) -->
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white shadow-2xs">
                                                <svg class="h-6 w-6 stroke-current fill-none" viewBox="0 0 24 24" stroke-width="2">
                                                    <rect x="4" y="3" width="16" height="18" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <circle cx="12" cy="13" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <circle cx="8" cy="6" r="1" fill="currentColor"/>
                                                    <circle cx="11" cy="6" r="1" fill="currentColor"/>
                                                    <path d="M10.5 13.5a2 2 0 0 1 2.5-1" stroke-linecap="round"/>
                                                </svg>
                                            </div>

                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-extrabold text-emerald-600 border border-emerald-200/60">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Open
                                            </span>
                                        </div>

                                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">
                                            {{ $displayName }} Branch
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                            {{ $subtext }}
                                        </p>

                                        @if($branch->address)
                                            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-slate-400">
                                                <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                                <span class="truncate">{{ $branch->address }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-600 group-hover:text-blue-700">
                                        <span>Select this branch</span>
                                        <svg class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                    </div>
                                </button>
                            </form>
                        @empty
                            <div class="col-span-full text-center py-12 rounded-2xl border border-dashed border-slate-200 bg-white p-6">
                                <p class="text-xs font-semibold text-slate-400">No active branches currently found.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </section>

            <!-- SERVICE FEATURES (Option 2 Pillar Highlights) -->
            <section id="features" class="py-14 sm:py-18 bg-white/60 border-t border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="text-center max-w-2xl mx-auto mb-12">
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-blue-600 font-mono">
                            <span>✦</span> Why Spinzone
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mt-2">
                            Engineered for Cleanliness &amp; Convenience
                        </h2>
                        <p class="text-sm text-slate-500 mt-1.5">
                            Everything you need for effortless laundry management in one platform.
                        </p>
                    </div>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Feature 1 -->
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-2xs">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 mb-4">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Step-by-Step Tracking</h3>
                            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                                Always know whether your clothes are pending, in the wash, drying, or ready for pickup.
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-2xs">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-50 text-teal-600 mb-4">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Custom Fabric Care</h3>
                            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                                Specify delicate garments, premium detergents, softener scents, and folding preferences.
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-2xs">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 mb-4">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Multiple Locations</h3>
                            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                                Switch between Baybay, Panay, or any nearby branch with a single tap in your profile.
                            </p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-2xs">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600 mb-4">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Express Speed</h3>
                            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                                Rapid wash-and-dry services designed for busy individuals, professionals, and students.
                            </p>
                        </div>
                    </div>

                </div>
            </section>

        </main>

        <!-- FOOTER -->
        <footer class="bg-white border-t border-slate-200/80 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white">
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.69c0 0-6.36 7.42-6.36 11.66A6.36 6.36 0 0 0 12 20.71a6.36 6.36 0 0 0 6.36-6.36C18.36 10.11 12 2.69 12 2.69zM12 18.7a4.36 4.36 0 0 1-4.36-4.35c0-2.31 3.12-6.52 4.36-8.08 1.24 1.56 4.36 5.76 4.36 8.08A4.36 4.36 0 0 1 12 18.7z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-extrabold text-slate-900">
                        Spinzone <span class="text-blue-600">Laundry</span>
                    </span>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-6 text-xs font-semibold text-slate-500">
                    <a href="{{ route('customer.choose-branch') }}" class="hover:text-blue-600 transition">Choose Branch</a>
                    <a href="{{ route('customer.login') }}" class="hover:text-blue-600 transition">Customer Login</a>
                    <a href="{{ route('customer.about') }}" class="hover:text-blue-600 transition">About Us</a>
                    <a href="{{ route('admin.login') }}" class="hover:text-blue-600 transition">Staff Portal</a>
                </div>

                <p class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} Spinzone Laundry. All rights reserved.
                </p>

            </div>
        </footer>

    </div>

</body>
</html>
