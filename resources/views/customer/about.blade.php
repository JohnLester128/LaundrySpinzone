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
                    <div class="flex h-24 w-24 sm:h-28 sm:w-28 items-center justify-center rounded-3xl bg-white border border-slate-200/80 shadow-xl overflow-hidden p-2">
                        <img src="{{ asset('images/spinzone-logo.png') }}" alt="Spinzone" class="h-full w-full object-contain rounded-2xl">
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

        <!-- Pricing & Service Rates Section -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-7 sm:p-9 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-extrabold text-emerald-700 mb-2 border border-emerald-100">
                        <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>TRANSPARENT PRICING &amp; RATES</span>
                    </div>
                    <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Service Rates &amp; Pricing</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Simple, honest per-kilogram and piece rates with professional wash, dry, and fold care.</p>
                </div>
                <a href="{{ route('customer.service-requests') }}" class="inline-flex items-center gap-1.5 self-start sm:self-auto rounded-2xl bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-xs font-bold shadow-md shadow-blue-600/20 transition active:scale-95 shrink-0">
                    <span>Book Service</span>
                    <span>→</span>
                </a>
            </div>

            <!-- Pricing Cards Grid -->
            <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @if(isset($clothTypes) && $clothTypes->isNotEmpty())
                    @foreach($clothTypes as $cType)
                        @php
                            $isRegular = str_contains(strtolower($cType->name), 'regular');
                            $isComforter = (bool)$cType->requires_size || str_contains(strtolower($cType->name), 'comforter');
                            $cardAccent = $isRegular ? 'border-blue-200 bg-gradient-to-b from-blue-50/40 to-white' : ($isComforter ? 'border-purple-200 bg-gradient-to-b from-purple-50/40 to-white' : 'border-teal-200 bg-gradient-to-b from-teal-50/40 to-white');
                            $badgeColor = $isRegular ? 'bg-blue-100 text-blue-800' : ($isComforter ? 'bg-purple-100 text-purple-800' : 'bg-teal-100 text-teal-800');
                            $dotColor = $isRegular ? 'bg-blue-500' : ($isComforter ? 'bg-purple-500' : 'bg-teal-500');
                        @endphp
                        <div class="rounded-2xl border {{ $cardAccent }} p-5 shadow-xs flex flex-col justify-between hover:shadow-md transition">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full {{ $dotColor }}"></span>
                                        <h3 class="text-base font-bold text-slate-900">{{ $cType->name }}</h3>
                                    </div>
                                    @if($isRegular)
                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider {{ $badgeColor }}">Popular</span>
                                    @elseif($isComforter)
                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider {{ $badgeColor }}">Bulky Item</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                                    {{ $isRegular ? 'Everyday shirts, pants, undergarments, and daily clothing.' : ($isComforter ? 'Thick blankets, quilts, and large bed covers.' : 'Bed sheets, pillowcases, bath towels, and curtains.') }}
                                </p>

                                <div class="space-y-2 pt-2 border-t border-slate-100">
                                    @forelse($cType->pricingTiers as $pt)
                                        <div class="flex items-center justify-between rounded-xl bg-white/80 p-2.5 border border-slate-100/90 text-xs shadow-2xs">
                                            <span class="font-bold text-slate-700">
                                                {{ $pt->name }}
                                                @if($pt->size)
                                                    <span class="text-slate-400 font-normal">({{ $pt->size }})</span>
                                                @endif
                                            </span>
                                            <span class="font-mono text-sm font-extrabold text-blue-700">
                                                ₱{{ number_format($pt->price, 2) }}
                                            </span>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 italic">No pricing tiers defined.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100/80 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                                <span>Includes Wash, Dry &amp; Fold</span>
                                <span class="text-emerald-600 font-bold">✓ Ready-to-wear</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Default Static Fallback Rates if DB is empty -->
                    <div class="rounded-2xl border border-blue-200 bg-gradient-to-b from-blue-50/40 to-white p-5 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <h3 class="text-base font-bold text-slate-900">Regular Clothes</h3>
                                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-extrabold uppercase text-blue-800">Popular</span>
                            </div>
                            <p class="text-xs text-slate-500 mb-4">Everyday shirts, pants, socks, and normal daily garments.</p>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between rounded-xl bg-white p-2.5 border border-slate-100 text-xs">
                                    <span class="font-bold text-slate-700">1 – 6 kg Load</span>
                                    <span class="font-mono font-extrabold text-blue-700">₱95.00</span>
                                </div>
                                <div class="flex items-center justify-between rounded-xl bg-white p-2.5 border border-slate-100 text-xs">
                                    <span class="font-bold text-slate-700">7 – 8 kg Load</span>
                                    <span class="font-mono font-extrabold text-blue-700">₱120.00</span>
                                </div>
                                <div class="flex items-center justify-between rounded-xl bg-white p-2.5 border border-slate-100 text-xs">
                                    <span class="font-bold text-slate-700">9 kg Load</span>
                                    <span class="font-mono font-extrabold text-blue-700">₱145.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Optional Add-ons Bar -->
            @if(isset($addons) && $addons->isNotEmpty())
                <div class="mt-6 pt-5 border-t border-slate-100">
                    <p class="font-mono text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-3">Optional Quality Add-ons</p>
                    <div class="flex flex-wrap items-center gap-3">
                        @foreach($addons as $addon)
                            <div class="inline-flex items-center gap-2 rounded-xl bg-slate-50 border border-slate-200/80 px-3.5 py-2 text-xs">
                                <span class="font-semibold text-slate-700">{{ $addon->name }}</span>
                                <span class="font-mono font-bold text-blue-700">+₱{{ number_format($addon->price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Turnaround Time Guide (1kg - 8kg) & Overall Process Timeline -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-7 sm:p-9 shadow-sm">
            <div class="max-w-3xl mb-6">
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-[11px] font-extrabold text-blue-700 mb-2 border border-blue-100">
                    <svg class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>TIME ESTIMATION &amp; OVERALL PROCESS</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Estimated Turnaround: 1 kg – 8 kg</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">
                    Our high-speed industrial washers and multi-airflow dryers finish your standard laundry within <strong class="text-slate-800">1 to 1.75 hours</strong>. Check the exact estimated time per load weight below:
                </p>
            </div>

            <!-- 1kg to 8kg Timeline Cards Grid -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- 1kg - 2kg -->
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-4 sm:p-5 flex flex-col justify-between hover:border-blue-300 hover:bg-blue-50/20 transition shadow-2xs">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-[11px] font-extrabold text-blue-700 bg-blue-100/70 px-2 py-0.5 rounded-md">1 – 2 kg</span>
                            <span class="text-[11px] font-bold text-slate-400">Light Load</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900">~60 – 70 Mins</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Approx. 1 hr to 1 hr 10 min</p>

                        <div class="mt-4 space-y-1.5 pt-3 border-t border-slate-200/70 text-[11px]">
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Wash Cycle:</span>
                                <span class="font-mono font-bold text-slate-800">33 mins</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Dryer Cycle:</span>
                                <span class="font-mono font-bold text-slate-800">25 mins</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Fold &amp; Pack:</span>
                                <span class="font-mono font-bold text-slate-800">~5–10 mins</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400 font-mono uppercase">Tier Price</span>
                        <span class="font-mono text-xs font-extrabold text-blue-700">₱95.00</span>
                    </div>
                </div>

                <!-- 3kg - 4kg -->
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-4 sm:p-5 flex flex-col justify-between hover:border-blue-300 hover:bg-blue-50/20 transition shadow-2xs">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-[11px] font-extrabold text-blue-700 bg-blue-100/70 px-2 py-0.5 rounded-md">3 – 4 kg</span>
                            <span class="text-[11px] font-bold text-slate-400">Medium Load</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900">~70 – 80 Mins</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Approx. 1 hr 10m – 1 hr 20m</p>

                        <div class="mt-4 space-y-1.5 pt-3 border-t border-slate-200/70 text-[11px]">
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Wash Cycle:</span>
                                <span class="font-mono font-bold text-slate-800">33 mins</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Dryer Cycle:</span>
                                <span class="font-mono font-bold text-slate-800">30 mins</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Fold &amp; Pack:</span>
                                <span class="font-mono font-bold text-slate-800">~10 mins</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400 font-mono uppercase">Tier Price</span>
                        <span class="font-mono text-xs font-extrabold text-blue-700">₱95.00</span>
                    </div>
                </div>

                <!-- 5kg - 6kg -->
                <div class="rounded-2xl border border-blue-200 bg-blue-50/30 p-4 sm:p-5 flex flex-col justify-between hover:border-blue-400 transition shadow-2xs relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-blue-600 text-white font-extrabold text-[9px] uppercase px-2 py-0.5 rounded-bl-lg tracking-wider">Best Value</div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-[11px] font-extrabold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-md">5 – 6 kg</span>
                            <span class="text-[11px] font-bold text-slate-400">Full Standard</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900">~80 – 90 Mins</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Approx. 1 hr 20m – 1 hr 30m</p>

                        <div class="mt-4 space-y-1.5 pt-3 border-t border-blue-100 text-[11px]">
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Wash Cycle:</span>
                                <span class="font-mono font-bold text-slate-800">33 mins</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Dryer Cycle:</span>
                                <span class="font-mono font-bold text-slate-800">35 mins</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Fold &amp; Pack:</span>
                                <span class="font-mono font-bold text-slate-800">~12 mins</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-blue-100 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400 font-mono uppercase">Tier Price</span>
                        <span class="font-mono text-xs font-extrabold text-blue-700">₱95.00</span>
                    </div>
                </div>

                <!-- 7kg - 8kg -->
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-4 sm:p-5 flex flex-col justify-between hover:border-blue-300 hover:bg-blue-50/20 transition shadow-2xs">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-[11px] font-extrabold text-indigo-700 bg-indigo-100/70 px-2 py-0.5 rounded-md">7 – 8 kg</span>
                            <span class="text-[11px] font-bold text-slate-400">Heavy Load</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900">~95 – 110 Mins</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Approx. 1 hr 35m – 1 hr 50m</p>

                        <div class="mt-4 space-y-1.5 pt-3 border-t border-slate-200/70 text-[11px]">
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Wash Cycle:</span>
                                <span class="font-mono font-bold text-slate-800">35 mins</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Dryer Cycle:</span>
                                <span class="font-mono font-bold text-slate-800">40–45 mins</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Fold &amp; Pack:</span>
                                <span class="font-mono font-bold text-slate-800">~15 mins</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400 font-mono uppercase">Tier Price</span>
                        <span class="font-mono text-xs font-extrabold text-indigo-700">₱120.00</span>
                    </div>
                </div>
            </div>

            <!-- Overall 5-Step Process Visual Flow -->
            <div class="mt-8 pt-7 border-t border-slate-100">
                <h3 class="text-base font-bold text-slate-900 mb-1">The 5-Step Automated Laundry Process</h3>
                <p class="text-xs text-slate-500 mb-5">From the minute you drop off your clothes until they are neatly packaged and ready for pickup:</p>

                <div class="grid gap-3 sm:grid-cols-5">
                    <!-- Step 1 -->
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 text-left relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-xs font-extrabold text-blue-600">STEP 1</span>
                            <span class="text-[10px] font-bold text-slate-400 font-mono">~3-5m</span>
                        </div>
                        <h5 class="text-xs font-bold text-slate-900">Weigh &amp; Tag</h5>
                        <p class="text-[11px] text-slate-500 mt-1 leading-snug">Precise digital scale check and garment inspection.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="rounded-2xl border border-purple-100 bg-purple-50/40 p-4 text-left relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-xs font-extrabold text-purple-600">STEP 2</span>
                            <span class="text-[10px] font-bold text-purple-700 font-mono">33m</span>
                        </div>
                        <h5 class="text-xs font-bold text-slate-900">Commercial Wash</h5>
                        <p class="text-[11px] text-slate-500 mt-1 leading-snug">Deep spin wash with premium detergents &amp; softener.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="rounded-2xl border border-amber-100 bg-amber-50/40 p-4 text-left relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-xs font-extrabold text-amber-600">STEP 3</span>
                            <span class="text-[10px] font-bold text-amber-700 font-mono">5m</span>
                        </div>
                        <h5 class="text-xs font-bold text-slate-900">Unload &amp; Buffer</h5>
                        <p class="text-[11px] text-slate-500 mt-1 leading-snug">Resting phase, garment check, and transfer to dryer.</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50/40 p-4 text-left relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-xs font-extrabold text-indigo-600">STEP 4</span>
                            <span class="text-[10px] font-bold text-indigo-700 font-mono">25-45m</span>
                        </div>
                        <h5 class="text-xs font-bold text-slate-900">High-Heat Dry</h5>
                        <p class="text-[11px] text-slate-500 mt-1 leading-snug">Sanitizing dry cycle with anti-wrinkle moisture control.</p>
                    </div>

                    <!-- Step 5 -->
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/40 p-4 text-left relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-xs font-extrabold text-emerald-600">STEP 5</span>
                            <span class="text-[10px] font-bold text-emerald-700 font-mono">10-15m</span>
                        </div>
                        <h5 class="text-xs font-bold text-slate-900">Fold &amp; Ready</h5>
                        <p class="text-[11px] text-slate-500 mt-1 leading-snug">Crisp professional folding and clean plastic wrap packaging.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 Feature Pillars Grid -->
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
