<x-customer-layout>
    <x-slot:title>About — Spinzone Laundry</x-slot:title>

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">About Spinzone</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">Learn more about our automated laundry services, branches, and quality commitments.</p>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Main Content Container -->
    <div class="space-y-6">
        <!-- Hero Brand Card with Stats -->
        <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-7 sm:p-9 shadow-sm">
            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-teal-500"></div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <!-- Top Tagline Badge -->
                    <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3.5 py-1.5 text-xs font-extrabold text-blue-700 mb-4 border border-blue-100 shadow-2xs">
                        <span class="inline-block h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                        <span>SMART &amp; AUTOMATED LAUNDRY SYSTEM</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900 leading-tight">
                        Fresh &amp; Clean Laundry, <br>
                        <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-teal-600 bg-clip-text text-transparent">Made Effortless.</span>
                    </h2>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed max-w-xl">
                        Track your laundry online step-by-step with Spinzone. Select your branch, book service requests, and follow your order's progress from verification and washing to drying and ready for pickup.
                    </p>
                </div>

                <div class="flex shrink-0 items-center justify-center">
                    <div class="flex h-24 w-24 sm:h-28 sm:w-28 items-center justify-center rounded-3xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-xl shadow-blue-500/25">
                        <svg class="h-12 w-12 sm:h-14 sm:w-14 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.69c0 0-6.36 7.42-6.36 11.66A6.36 6.36 0 0 0 12 20.71a6.36 6.36 0 0 0 6.36-6.36C18.36 10.11 12 2.69 12 2.69zM12 18.7a4.36 4.36 0 0 1-4.36-4.35c0-2.31 3.12-6.52 4.36-8.08 1.24 1.56 4.36 5.76 4.36 8.08A4.36 4.36 0 0 1 12 18.7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stats Highlights -->
            <div class="mt-8 pt-6 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center sm:text-left">
                <div class="rounded-2xl bg-slate-50/70 border border-slate-100/80 p-3.5">
                    <p class="text-lg sm:text-xl font-extrabold tracking-tight text-slate-900">100%</p>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Fabric Care</p>
                </div>
                <div class="rounded-2xl bg-blue-50/50 border border-blue-100/60 p-3.5">
                    <p class="text-lg sm:text-xl font-extrabold tracking-tight text-blue-600">Step-by-Step</p>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Order Tracking</p>
                </div>
                <div class="rounded-2xl bg-indigo-50/50 border border-indigo-100/60 p-3.5">
                    <p class="text-lg sm:text-xl font-extrabold tracking-tight text-indigo-600">Express</p>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Turnaround Option</p>
                </div>
                <div class="rounded-2xl bg-teal-50/50 border border-teal-100/60 p-3.5">
                    <p class="text-lg sm:text-xl font-extrabold tracking-tight text-teal-600">Multiple</p>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Active Branches</p>
                </div>
            </div>
        </div>

        <!-- 4 Feature Pillars Grid (Between Hero and Branch info) -->
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Feature 1 -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 mb-4 shadow-2xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Step-by-Step Tracking</h3>
                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                        Always know whether your clothes are pending, in the wash, drying, or ready for pickup.
                    </p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-50 text-teal-600 mb-4 shadow-2xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Custom Fabric Care</h3>
                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                        Specify delicate garments, premium detergents, softener scents, and folding preferences.
                    </p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 mb-4 shadow-2xs">
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
            </div>

            <!-- Feature 4 -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600 mb-4 shadow-2xs">
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

        <!-- Branch Information & Support Section -->
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Branch Location Card -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 shadow-2xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Your Current Branch</h3>
                        <p class="text-xs text-slate-400">Assigned drop-off and pickup center.</p>
                    </div>
                </div>

                <div class="mt-4 space-y-3">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                        <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">Branch Name</p>
                        <p class="text-base font-bold text-slate-900 mt-0.5">{{ $branch->name ?? 'Central Branch' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                        <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">Location Address</p>
                        <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $branch->address ?? 'Main Branch Location' }}</p>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Need to switch branch?</span>
                    <a href="{{ route('customer.profile') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                        Change Branch in Profile →
                    </a>
                </div>
            </div>

            <!-- Operating Hours & Contact Card -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Operating Schedule</h3>
                            <p class="text-xs text-slate-400">Store hours and customer assistance.</p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5 text-xs">
                            <span class="font-bold text-slate-700">Monday – Sunday</span>
                            <span class="font-mono font-bold text-blue-600">7:00 AM – 9:00 PM</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5 text-xs">
                            <span class="font-bold text-slate-700">Daily Last Wash Call</span>
                            <span class="font-mono font-bold text-amber-600">8:00 PM</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('customer.requests') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 text-xs shadow-md shadow-blue-600/20 transition active:scale-[0.99]">
                        <span>View My Active Laundry Requests</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-customer-layout>
