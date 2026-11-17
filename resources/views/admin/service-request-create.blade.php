<x-admin-layout>
    <!-- Back Link & Page Title Header -->
    <div class="mb-6">
        <a href="{{ route('admin.service-requests') }}" class="inline-flex items-center gap-2 font-mono text-xs font-bold text-slate-500 hover:text-blue-600 transition group mb-3">
            <svg class="h-4 w-4 transform transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Service Requests</span>
        </a>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">New Walk-In Request</p>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">Add Service Request</h1>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3.5 py-1.5 text-xs font-bold text-blue-700 border border-blue-200/60 shadow-2xs">
                <span class="h-2 w-2 rounded-full bg-blue-500 animate-ping"></span>
                <span>Walk-in Counter Order</span>
            </span>
        </div>
    </div>

    <!-- Validation Errors Alert -->
    @if(isset($errors) && $errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50/90 p-4 text-sm text-red-700 shadow-2xs">
            <div class="flex items-center gap-2 font-bold mb-1">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Please fix the following errors:</span>
            </div>
            <ul class="list-disc pl-5 space-y-1 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.service-requests.store') }}" id="walkInForm">
        @csrf

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Main Form Section (2 Columns) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Card 1: Customer Information -->
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                        <div class="flex h-10 w-10 shrink-0 aspect-square items-center justify-center rounded-2xl bg-blue-100 text-blue-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Customer</h2>
                            <p class="text-xs text-slate-400 font-medium">Select registered customer username or leave blank for Walk-in</p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <!-- Customer Searchable Dropdown -->
                        <div class="relative" id="customerCombobox">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 font-mono">
                                Customer Username
                            </label>
                            
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" 
                                       id="customerSearchInput" 
                                       placeholder="Search username or leave blank..." 
                                       value="{{ old('customer_name') }}"
                                       autocomplete="off"
                                       class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-9 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs font-semibold">
                                <input type="hidden" name="customer_name" id="inputCustomerName" value="{{ old('customer_name') }}">

                                <button type="button" id="toggleCustomerDropdown" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <svg class="h-4 w-4 transition-transform duration-200" id="comboboxChevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Dropdown Menu List -->
                            <div id="customerDropdownMenu" class="absolute left-0 right-0 top-full mt-1.5 z-50 hidden rounded-2xl border border-slate-200 bg-white p-1.5 shadow-xl max-h-56 overflow-y-auto">
                                <div class="px-2 py-1 text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1">
                                    Select Customer
                                </div>
                                <button type="button" 
                                        onclick="selectCustomer('', '')" 
                                        class="customer-option flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition cursor-pointer text-left">
                                    <span class="flex items-center gap-2">
                                        <span class="flex h-5 w-5 items-center justify-center rounded-lg bg-slate-100 text-slate-500 text-[11px] font-bold">W</span>
                                        <span>Walk-in Customer</span>
                                    </span>
                                    <span class="text-[10px] font-mono text-slate-400 font-normal">Default</span>
                                </button>
                                @if(isset($customers) && $customers->count() > 0)
                                    @foreach($customers as $cust)
                                        <button type="button" 
                                                onclick="selectCustomer('{{ addslashes($cust->username) }}', '{{ addslashes($cust->phone ?? '') }}')" 
                                                data-search="{{ strtolower($cust->username . ' ' . ($cust->phone ?? '')) }}"
                                                class="customer-option flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition cursor-pointer text-left">
                                            <span class="flex items-center gap-2">
                                                <span class="flex h-5 w-5 items-center justify-center rounded-lg bg-blue-100 text-blue-600 text-[11px] font-bold">{{ strtoupper(substr($cust->username, 0, 1)) }}</span>
                                                <span class="font-bold text-slate-900">{{ $cust->username }}</span>
                                            </span>
                                            @if($cust->phone)
                                                <span class="text-[11px] font-mono text-slate-500">{{ $cust->phone }}</span>
                                            @endif
                                        </button>
                                    @endforeach
                                @endif
                                <div id="noCustomerFound" class="hidden px-3 py-3 text-center text-xs text-slate-400 font-medium">
                                    No registered customer matches. Will save as Walk-in.
                                </div>
                            </div>
                        </div>

                        <!-- Phone Number (Optional) -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 font-mono">
                                    Phone Number
                                </label>
                                <span class="text-[10px] font-mono text-slate-400">Optional</span>
                            </div>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1.01 1.01 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <input type="text" name="phone" id="inputCustomerPhone" value="{{ old('phone') }}" placeholder="e.g. 0917 123 4567" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Laundry Details -->
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                        <div class="flex h-10 w-10 shrink-0 aspect-square items-center justify-center rounded-2xl bg-blue-100/80 text-blue-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Laundry Details</h2>
                            <p class="text-xs text-slate-400 font-medium">Clothing type, weight, and quantity</p>
                        </div>
                    </div>

                    <!-- Clothing Type Selector -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 font-mono">
                            Type of Clothing <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach($clothTypes as $type)
                                <label class="relative flex flex-col justify-between rounded-2xl border-2 p-3.5 cursor-pointer transition duration-150 cloth-type-tile border-slate-200 bg-slate-50/40 hover:bg-white hover:border-blue-400">
                                    <input type="radio" name="cloth_type_id" value="{{ $type->id }}" class="sr-only cloth-radio" {{ (old('cloth_type_id', $clothTypes->first()->id ?? null) == $type->id) ? 'checked' : '' }} data-name="{{ $type->name }}" data-requires-size="{{ $type->requires_size ? '1' : '0' }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-blue-100/80 text-blue-600 font-bold text-xs">
                                            {{ strtoupper(substr($type->name, 0, 1)) }}
                                        </div>
                                        <div class="h-4 w-4 rounded-full border border-slate-300 flex items-center justify-center radio-circle">
                                            <div class="h-2 w-2 rounded-full bg-blue-600 hidden radio-dot"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-extrabold text-slate-900">{{ $type->name }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5 font-mono">
                                            {{ $type->requires_size ? 'Per Piece (by Size)' : 'By Weight Tier' }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Dynamic Weight & Tier Grid -->
                    <div class="grid gap-4 sm:grid-cols-2" id="specsGrid">
                        <!-- 1. Weight (kg) -->
                        <div id="weightInputContainer">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 font-mono">
                                Weight (kg) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" min="0" max="8" step="1" name="estimated_weight" id="inputWeight" value="{{ old('estimated_weight', '0') }}" placeholder="0" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-4 pr-12 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs font-mono font-bold text-base">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-mono font-bold text-slate-400">
                                    kg
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 font-mono" id="weightHint">Allowed weight: 1 - 8 kg</p>
                        </div>

                        <!-- 2. Dynamic Tier Selection (Weight Tier for Clothes/Beddings, Size for Comforters) -->
                        <div id="tierSelectContainer">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 font-mono" id="tierLabel">
                                Weight Tier (kg) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="pricing_tier_id" id="selectPricingTier" required class="w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50/50 pl-4 pr-10 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs font-semibold">
                                    <!-- Populated dynamically via JS -->
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 font-mono" id="tierHint">Rate configured in settings</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Add-ons & Quantity -->
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                        <div class="flex h-10 w-10 shrink-0 aspect-square items-center justify-center rounded-2xl bg-blue-100/80 text-blue-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Add-ons & Quantity</h2>
                            <p class="text-xs text-slate-400 font-medium">Extra wash treatments, cloth piece count, and special instructions</p>
                        </div>
                    </div>

                    <!-- Add-ons Tiles -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2.5 font-mono">
                            Select Add-On Treatments
                        </label>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach($addons as $addon)
                                @if($addon->is_active)
                                    <label class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-3.5 transition hover:border-blue-300 hover:bg-blue-50/20 cursor-pointer shadow-2xs has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 addon-tile">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900">{{ $addon->name }}</span>
                                            <span class="text-xs font-semibold text-blue-600 mt-0.5">+PHP {{ number_format($addon->price, 0) }}</span>
                                        </div>
                                        <input type="checkbox" name="addon_ids[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}" data-name="{{ $addon->name }}" class="h-5 w-5 accent-blue-600 rounded-lg addon-checkbox cursor-pointer" {{ is_array(old('addon_ids')) && in_array($addon->id, old('addon_ids')) ? 'checked' : '' }}>
                                    </label>
                                @else
                                    <div class="flex items-center justify-between rounded-2xl border border-slate-200/60 bg-slate-50 p-3.5 opacity-50 cursor-not-allowed select-none">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-slate-400 line-through">{{ $addon->name }}</span>
                                            <span class="text-xs text-slate-400">Unavailable</span>
                                        </div>
                                        <input type="checkbox" disabled class="h-5 w-5 accent-slate-400">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Items Quantity Field & Itemized Cloth Counter -->
                    <div class="mb-5 space-y-3" id="quantityContainer">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 font-mono" id="quantityLabel">
                                Total Items Quantity (pcs)
                            </label>
                            <button type="button" id="toggleAdminItemBreakdownBtn" onclick="toggleAdminItemBreakdown()" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 hover:text-blue-600 hover:border-blue-300 transition cursor-pointer shadow-2xs group">
                                <svg id="toggleAdminItemBreakdownChevron" class="h-3.5 w-3.5 text-slate-400 group-hover:text-blue-600 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                                <span id="toggleAdminItemBreakdownText">Itemized Cloth Intake</span>
                                <span id="adminItemBreakdownBadge" class="hidden rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-extrabold text-blue-700 font-mono">0 items</span>
                            </button>
                        </div>

                        <div class="relative">
                            <input type="number" min="1" step="1" name="quantity" id="inputQuantity" value="{{ old('quantity', '1') }}" placeholder="e.g. 10" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-bold font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-mono font-bold text-slate-400" id="quantityUnit">
                                pcs
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-mono" id="quantityHint">Total count. Open checklist below to log specific item types (pants, polos, etc.) for anti-loss tracking.</p>

                        <!-- Collapsible Cloth Counter Box for Admin -->
                        <div id="adminItemBreakdownBox" class="hidden rounded-3xl border border-slate-200/90 bg-slate-50/70 p-5 transition-all shadow-xs space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-200/80">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100/80 text-blue-600 shadow-2xs">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-extrabold text-slate-900">Walk-In Intake Breakdown</h4>
                                        <p class="text-[11px] text-slate-400 font-mono">Counts automatically sync with total pieces.</p>
                                    </div>
                                </div>
                                <button type="button" onclick="clearAdminItemBreakdown()" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-mono font-bold text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition cursor-pointer shadow-2xs">
                                    <span>Reset</span>
                                </button>
                            </div>

                            <!-- Preset Clothes Counter Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @php
                                    $presets = [
                                        't_shirt' => 'T-Shirt / Tops',
                                        'polo' => 'Polo / Collared Shirt',
                                        'pants' => 'Pants / Jeans',
                                        'shorts' => 'Shorts',
                                        'uniform' => 'Uniform (School/Work)',
                                        'jacket' => 'Jacket / Hoodie',
                                        'linens' => 'Towel / Blanket / Linens',
                                    ];
                                @endphp

                                @foreach($presets as $key => $label)
                                    <div class="flex items-center justify-between rounded-2xl bg-white p-3.5 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition">
                                        <div class="flex items-center gap-2.5 truncate pr-2">
                                            <span class="h-2 w-2 rounded-full bg-blue-500/80 shrink-0"></span>
                                            <span class="text-xs font-bold text-slate-800 truncate">{{ $label }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <button type="button" onclick="adjustAdminPresetQty('{{ $key }}', -1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">
                                                -
                                            </button>
                                            <input type="number" min="0" max="500" name="items[{{ $key }}]" id="admin_preset_{{ $key }}" value="{{ old('items.' . $key, 0) }}" oninput="recalcAdminBreakdown()" class="admin-cloth-preset-input w-12 rounded-xl border border-slate-200 bg-white py-1.5 text-center font-mono text-xs font-bold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                                            <button type="button" onclick="adjustAdminPresetQty('{{ $key }}', 1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Custom Items Section -->
                            <div class="pt-3 border-t border-slate-200/80">
                                <div class="flex items-center justify-between mb-2.5">
                                    <div>
                                        <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Other Specific Clothes</p>
                                        <p class="text-[10px] text-slate-400 font-mono">Log custom garments (Barong, Curtains, etc.)</p>
                                    </div>
                                    <button type="button" onclick="addAdminCustomClothRow()" class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100 transition cursor-pointer shadow-2xs">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                        </svg>
                                        <span>Add Other Item</span>
                                    </button>
                                </div>

                                <div id="adminCustomClothItemsContainer" class="space-y-2.5">
                                    <!-- Dynamic custom rows -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 font-mono">
                            Special Instructions (Optional)
                        </label>
                        <textarea name="notes" rows="3" placeholder="e.g. Separate whites from darks, handle delicate wool gently..." class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-xs text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs font-medium">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Live Order Summary Card (1 Column) -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-700 font-bold text-xs">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900">Order Summary</h3>
                            <p class="text-[11px] text-slate-400 font-medium">Walk-in Order Breakdown</p>
                        </div>
                    </div>

                    <!-- Breakdown List -->
                    <div class="space-y-3 text-xs mb-6">
                        <div class="flex justify-between items-center py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Customer:</span>
                            <span class="font-bold text-slate-800 truncate max-w-[170px]" id="summaryCustomer">Walk-in Customer</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Service Type:</span>
                            <span class="font-bold text-slate-800" id="summaryClothType">Standard Wash</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium" id="summaryTierLabel">Weight & Tier:</span>
                            <span class="font-mono font-bold text-slate-800 text-right" id="summaryTier">-</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Items Quantity:</span>
                            <span class="font-mono font-bold text-slate-800" id="summaryQuantity">1 pcs</span>
                        </div>
                        <div class="flex justify-between items-start py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Add-ons:</span>
                            <div class="text-right font-bold text-slate-800" id="summaryAddons">
                                <span class="text-slate-400 font-normal italic">None</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-xs font-bold text-slate-900">Total Price:</span>
                            <span class="font-mono text-base font-black text-blue-600" id="summaryTotalPrice">PHP 0.00</span>
                        </div>
                    </div>

                    <!-- Initial Status Badge -->
                    <div class="rounded-2xl bg-blue-50/70 p-3.5 mb-6 border border-blue-100">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                            <span class="text-xs font-bold text-blue-900">Initial Status: Received</span>
                        </div>
                        <p class="text-[11px] text-blue-700/80 mt-1 leading-snug">
                            Walk-in orders are automatically saved as Received and ready for machine processing.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 py-3.5 px-4 text-xs font-extrabold text-white shadow-md hover:bg-blue-700 active:scale-95 transition duration-150 cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Create Service Request</span>
                    </button>

                    <a href="{{ route('admin.service-requests') }}" class="mt-3 block text-center text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                        Cancel & Return
                    </a>
                </div>
            </div>
        </div>
    </form>

    <!-- Dynamic Tiers Data & Script -->
    <script>
        const clothTypesData = @json($clothTypes);
        const oldTierId = @json(old('pricing_tier_id'));

        function updateClothTypeVisuals() {
            document.querySelectorAll('.cloth-type-tile').forEach(tile => {
                const radio = tile.querySelector('.cloth-radio');
                const circle = tile.querySelector('.radio-circle');
                const dot = tile.querySelector('.radio-dot');
                if (radio && radio.checked) {
                    tile.classList.add('border-blue-600', 'bg-blue-50/30');
                    tile.classList.remove('border-slate-200', 'bg-slate-50/40');
                    if (circle) {
                        circle.classList.add('border-blue-600');
                        circle.classList.remove('border-slate-300');
                    }
                    if (dot) {
                        dot.classList.remove('hidden');
                    }
                } else {
                    tile.classList.remove('border-blue-600', 'bg-blue-50/30');
                    tile.classList.add('border-slate-200', 'bg-slate-50/40');
                    if (circle) {
                        circle.classList.remove('border-blue-600');
                        circle.classList.add('border-slate-300');
                    }
                    if (dot) {
                        dot.classList.add('hidden');
                    }
                }
            });
        }

        function populateTiers() {
            const activeRadio = document.querySelector('.cloth-radio:checked');
            if (!activeRadio) return;

            const selectedTypeId = parseInt(activeRadio.value);
            const clothType = clothTypesData.find(t => t.id === selectedTypeId);
            const selectTier = document.getElementById('selectPricingTier');
            const tierLabel = document.getElementById('tierLabel');
            const tierHint = document.getElementById('tierHint');
            const weightContainer = document.getElementById('weightInputContainer');
            const tierContainer = document.getElementById('tierSelectContainer');
            const qtyContainer = document.getElementById('quantityContainer');
            const qtyInput = document.getElementById('inputQuantity');
            const quantityLabel = document.getElementById('quantityLabel');
            const quantityHint = document.getElementById('quantityHint');
            const quantityUnit = document.getElementById('quantityUnit');

            if (!clothType || !selectTier) return;

            // Clear current options
            selectTier.innerHTML = '';

            const isSizeBased = clothType.requires_size || (clothType.pricing_tiers && clothType.pricing_tiers.some(tier => tier.size !== null));

            const weightInput = document.getElementById('inputWeight');
            const weightHint = document.getElementById('weightHint');
            const maxWeight = (clothType.id === 2) ? 5 : 8;

            if (weightInput) {
                weightInput.min = "1";
                weightInput.max = maxWeight.toString();
                weightInput.placeholder = `1-${maxWeight}`;
                if (parseFloat(weightInput.value) > maxWeight) {
                    weightInput.value = maxWeight;
                }
            }
            if (weightHint) {
                weightHint.textContent = `Allowed weight: 1 - ${maxWeight} kg (auto-selects tier)`;
            }

            if (isSizeBased) {
                if (weightContainer) weightContainer.classList.remove('hidden');
                if (weightInput) {
                    weightInput.disabled = false;
                    weightInput.min = "0";
                    weightInput.max = "8";
                    weightInput.placeholder = "0";
                    if (weightInput.value === '' || isNaN(parseFloat(weightInput.value))) {
                        weightInput.value = '0';
                    }
                }
                if (weightHint) {
                    weightHint.textContent = 'Scale weight (1 - 8 kg) used for cycle timer';
                }
                if (qtyContainer) qtyContainer.classList.add('hidden');
                if (qtyInput) qtyInput.value = '1';
                if (tierLabel) tierLabel.innerHTML = 'Comforter Size <span class="text-rose-500">*</span>';
                if (tierHint) tierHint.textContent = 'Select comforter dimension & rate (1 pc)';
            } else {
                if (weightContainer) weightContainer.classList.remove('hidden');
                if (weightInput) {
                    weightInput.disabled = false;
                    weightInput.min = "0";
                    if (weightInput.value === '' || isNaN(parseFloat(weightInput.value))) {
                        weightInput.value = '0';
                    }
                }
                if (qtyContainer) qtyContainer.classList.remove('hidden');
                if (tierLabel) tierLabel.innerHTML = 'Weight Tier (kg) <span class="text-rose-500">*</span>';
                if (tierHint) tierHint.textContent = 'Auto-selected by entered weight or choose manually';
                if (quantityLabel) quantityLabel.textContent = 'Items Quantity (pcs)';
                if (quantityHint) quantityHint.textContent = 'Number of individual items';
                if (quantityUnit) quantityUnit.textContent = 'pcs';
            }

            const tiers = clothType.pricing_tiers || [];
            tiers.forEach((tier, index) => {
                const opt = document.createElement('option');
                opt.value = tier.id;
                opt.dataset.price = tier.price;
                opt.dataset.name = tier.name;
                opt.dataset.size = tier.size || '';
                opt.dataset.minWeight = tier.min_weight !== null ? tier.min_weight : '';
                opt.dataset.maxWeight = tier.max_weight !== null ? tier.max_weight : '';
                
                const formattedPrice = Math.round(parseFloat(tier.price));
                opt.textContent = `${tier.name} — ₱${formattedPrice}`;

                if (oldTierId && parseInt(oldTierId) === tier.id) {
                    opt.selected = true;
                } else if (!oldTierId && index === 0) {
                    opt.selected = true;
                }

                selectTier.appendChild(opt);
            });

            // Update radio indicator dots and tile highlights
            updateClothTypeVisuals();

            // If not size based, sync tier with current weight input
            if (!isSizeBased) {
                matchTierByWeight();
            } else {
                updateSummary();
            }
        }

        function matchTierByWeight() {
            const activeRadio = document.querySelector('.cloth-radio:checked');
            if (!activeRadio) return;

            const selectedTypeId = parseInt(activeRadio.value);
            const clothType = clothTypesData.find(t => t.id === selectedTypeId);
            if (!clothType || clothType.requires_size) {
                updateSummary();
                return;
            }

            const weightInput = document.getElementById('inputWeight');
            const weightVal = parseFloat(weightInput ? weightInput.value : 0);
            const selectTier = document.getElementById('selectPricingTier');
            const tiers = clothType.pricing_tiers || [];

            if (tiers.length === 0) {
                updateSummary();
                return;
            }

            let matchedTier = null;
            if (!isNaN(weightVal) && weightVal > 0) {
                matchedTier = tiers.find(t => {
                    const min = t.min_weight !== null ? parseFloat(t.min_weight) : 0;
                    const max = t.max_weight !== null ? parseFloat(t.max_weight) : Infinity;
                    return weightVal >= min && weightVal <= max;
                });

                // If exact range not matched, choose closest tier
                if (!matchedTier) {
                    let minDiff = Infinity;
                    tiers.forEach(t => {
                        const min = t.min_weight !== null ? parseFloat(t.min_weight) : 0;
                        const max = t.max_weight !== null ? parseFloat(t.max_weight) : Infinity;
                        let diff = 0;
                        if (weightVal < min) diff = min - weightVal;
                        else if (weightVal > max) diff = weightVal - max;
                        if (diff < minDiff) {
                            minDiff = diff;
                            matchedTier = t;
                        }
                    });
                }
            }

            if (!matchedTier && tiers.length > 0) {
                matchedTier = tiers[0];
            }

            if (matchedTier && selectTier) {
                selectTier.value = matchedTier.id;
            }

            updateSummary();
        }

        function updateSummary() {
            const customerInput = document.getElementById('inputCustomerName');
            const searchInput = document.getElementById('customerSearchInput');
            const summaryCustomer = document.getElementById('summaryCustomer');
            if (summaryCustomer) {
                const name = (customerInput ? customerInput.value.trim() : '') || (searchInput ? searchInput.value.trim() : '');
                summaryCustomer.textContent = name || 'Walk-in Customer';
            }

            const activeRadio = document.querySelector('.cloth-radio:checked');
            const selectTier = document.getElementById('selectPricingTier');
            const weightInput = document.getElementById('inputWeight');
            const summaryClothType = document.getElementById('summaryClothType');
            const summaryTier = document.getElementById('summaryTier');
            const summaryTierLabel = document.getElementById('summaryTierLabel');
            const qtyInput = document.getElementById('inputQuantity');
            const summaryQuantity = document.getElementById('summaryQuantity');
            const summaryTotalPrice = document.getElementById('summaryTotalPrice');

            let tierPrice = 0;
            let clothTypeName = 'Standard Wash';
            let isSizeBased = false;

            if (activeRadio) {
                clothTypeName = activeRadio.getAttribute('data-name') || 'Standard Wash';
                isSizeBased = activeRadio.getAttribute('data-requires-size') === '1';
                if (summaryTierLabel) {
                    summaryTierLabel.textContent = isSizeBased ? 'Comforter Size:' : 'Weight & Tier:';
                }
            }
            if (summaryClothType) summaryClothType.textContent = clothTypeName;

            const weightVal = parseFloat(weightInput ? weightInput.value : 0) || 0;

            if (selectTier && selectTier.selectedOptions.length > 0) {
                const selectedOpt = selectTier.selectedOptions[0];
                tierPrice = parseFloat(selectedOpt.dataset.price) || 0;
                if (summaryTier) {
                    if (isSizeBased) {
                        summaryTier.textContent = `${weightVal > 0 ? Math.round(weightVal) + ' kg · ' : ''}${selectedOpt.dataset.name} (₱${Math.round(tierPrice)})`;
                    } else {
                        summaryTier.textContent = `${weightVal > 0 ? Math.round(weightVal) + ' kg · ' : '0 kg · '}${selectedOpt.dataset.name} (₱${Math.round(tierPrice)})`;
                    }
                }
            } else if (summaryTier) {
                summaryTier.textContent = '-';
            }

            const qty = isSizeBased ? 1 : (parseInt(qtyInput ? qtyInput.value : 1) || 1);
            if (summaryQuantity) {
                summaryQuantity.textContent = isSizeBased ? '1 pc' : `${qty} pcs`;
            }

            // Calculate Add-ons
            let addonsTotal = 0;
            const checkedAddons = document.querySelectorAll('.addon-checkbox:checked');
            const summaryAddons = document.getElementById('summaryAddons');
            if (summaryAddons) {
                if (checkedAddons.length === 0) {
                    summaryAddons.innerHTML = '<span class="text-slate-400 font-normal italic">None</span>';
                } else {
                    const addonBadges = [];
                    checkedAddons.forEach(cb => {
                        const p = parseFloat(cb.getAttribute('data-price')) || 0;
                        addonsTotal += p;
                        const n = cb.getAttribute('data-name');
                        addonBadges.push(`<span class="inline-block bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-md border border-blue-200/60 ml-1 mb-1">+₱${Math.round(p)} ${n}</span>`);
                    });
                    summaryAddons.innerHTML = addonBadges.join('');
                }
            }

            // Total Price: Base tier + add-ons
            const baseTotal = isSizeBased ? tierPrice : tierPrice;
            const grandTotal = baseTotal + addonsTotal;
            if (summaryTotalPrice) {
                summaryTotalPrice.textContent = `PHP ${Math.round(grandTotal).toLocaleString('en-US')}`;
            }

            // Update radio indicator dots and tile highlights
            updateClothTypeVisuals();
        }

        const customerCombobox = document.getElementById('customerCombobox');
        const customerSearchInput = document.getElementById('customerSearchInput');
        const inputCustomerName = document.getElementById('inputCustomerName');
        const customerDropdownMenu = document.getElementById('customerDropdownMenu');
        const comboboxChevron = document.getElementById('comboboxChevron');
        const noCustomerFound = document.getElementById('noCustomerFound');

        function openCustomerDropdown() {
            customerDropdownMenu?.classList.remove('hidden');
            comboboxChevron?.classList.add('rotate-180');
            filterCustomerList();
        }

        function closeCustomerDropdown() {
            customerDropdownMenu?.classList.add('hidden');
            comboboxChevron?.classList.remove('rotate-180');
        }

        function filterCustomerList() {
            const q = customerSearchInput ? customerSearchInput.value.trim().toLowerCase() : '';
            const options = document.querySelectorAll('.customer-option[data-search]');
            let matchCount = 0;
            options.forEach(opt => {
                const text = opt.getAttribute('data-search') || '';
                if (!q || text.includes(q)) {
                    opt.classList.remove('hidden');
                    matchCount++;
                } else {
                    opt.classList.add('hidden');
                }
            });

            if (noCustomerFound) {
                if (q && matchCount === 0) {
                    noCustomerFound.classList.remove('hidden');
                } else {
                    noCustomerFound.classList.add('hidden');
                }
            }
        }

        function selectCustomer(username, phone) {
            if (customerSearchInput) {
                customerSearchInput.value = username;
            }
            if (inputCustomerName) {
                inputCustomerName.value = username;
            }
            const phoneInput = document.getElementById('inputCustomerPhone');
            if (phoneInput) {
                if (phone) {
                    phoneInput.value = phone;
                } else if (!username) {
                    phoneInput.value = '';
                }
            }
            closeCustomerDropdown();
            updateSummary();
        }

        customerSearchInput?.addEventListener('focus', openCustomerDropdown);
        customerSearchInput?.addEventListener('input', function() {
            if (inputCustomerName) {
                inputCustomerName.value = this.value;
            }
            openCustomerDropdown();
            filterCustomerList();
            updateSummary();
        });

        document.getElementById('toggleCustomerDropdown')?.addEventListener('click', function(e) {
            e.stopPropagation();
            if (customerDropdownMenu?.classList.contains('hidden')) {
                openCustomerDropdown();
                customerSearchInput?.focus();
            } else {
                closeCustomerDropdown();
            }
        });

        document.addEventListener('click', function(e) {
            if (customerCombobox && !customerCombobox.contains(e.target)) {
                closeCustomerDropdown();
            }
        });
        
        const inputWeightEl = document.getElementById('inputWeight');
        if (inputWeightEl) {
            ['input', 'change', 'keyup'].forEach(evt => {
                inputWeightEl.addEventListener(evt, function() {
                    const maxVal = parseFloat(this.max) || 8;
                    const minVal = parseFloat(this.min) || 1;
                    let val = parseFloat(this.value);
                    if (!isNaN(val) && val > maxVal) {
                        this.value = maxVal;
                    }
                    matchTierByWeight();
                });
            });
        }

        function onPricingTierChange() {
            const selectTier = document.getElementById('selectPricingTier');
            const weightInput = document.getElementById('inputWeight');
            if (!selectTier || !weightInput) {
                updateSummary();
                return;
            }

            const activeRadio = document.querySelector('.cloth-radio:checked');
            const isSizeBased = activeRadio ? (activeRadio.getAttribute('data-requires-size') === '1') : false;

            if (!isSizeBased && selectTier.selectedOptions.length > 0) {
                const selectedOpt = selectTier.selectedOptions[0];
                const minWeight = selectedOpt.dataset.minWeight;
                if (minWeight !== undefined && minWeight !== '' && !isNaN(parseFloat(minWeight))) {
                    weightInput.value = Math.round(parseFloat(minWeight));
                }
            }

            updateSummary();
        }

        document.getElementById('inputQuantity')?.addEventListener('input', updateSummary);
        document.getElementById('selectPricingTier')?.addEventListener('change', onPricingTierChange);
        
        document.querySelectorAll('.cloth-radio').forEach(r => {
            r.addEventListener('change', populateTiers);
        });

        document.querySelectorAll('.cloth-type-tile').forEach(tile => {
            tile.addEventListener('click', function() {
                const radio = tile.querySelector('.cloth-radio');
                if (radio && !radio.checked) {
                    radio.checked = true;
                    populateTiers();
                }
            });
        });

        document.querySelectorAll('.addon-checkbox').forEach(cb => cb.addEventListener('change', updateSummary));

        // Admin Cloth Counter & Breakdown Logic
        let adminCustomClothIndex = 0;

        function toggleAdminItemBreakdown() {
            const box = document.getElementById('adminItemBreakdownBox');
            const chevron = document.getElementById('toggleAdminItemBreakdownChevron');
            const text = document.getElementById('toggleAdminItemBreakdownText');
            if (!box) return;

            if (box.classList.contains('hidden')) {
                box.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
                if (text) text.textContent = 'Hide Intake Breakdown';
            } else {
                box.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
                if (text) text.textContent = 'Itemized Cloth Intake';
            }
        }

        function adjustAdminPresetQty(key, delta) {
            const input = document.getElementById('admin_preset_' + key);
            if (!input) return;
            let current = parseInt(input.value) || 0;
            current = Math.max(0, current + delta);
            input.value = current;
            recalcAdminBreakdown();
        }

        function recalcAdminBreakdown() {
            let total = 0;
            // Presets
            document.querySelectorAll('.admin-cloth-preset-input').forEach(inp => {
                const val = parseInt(inp.value) || 0;
                total += val;
            });

            // Custom Items
            document.querySelectorAll('.admin-custom-cloth-qty').forEach(inp => {
                const val = parseInt(inp.value) || 0;
                total += val;
            });

            // Update badge
            const badge = document.getElementById('adminItemBreakdownBadge');
            if (badge) {
                if (total > 0) {
                    badge.classList.remove('hidden');
                    badge.textContent = `${total} pcs counted`;
                } else {
                    badge.classList.add('hidden');
                }
            }

            // Sync total quantity field
            const qtyInput = document.getElementById('inputQuantity');
            if (qtyInput && total > 0) {
                qtyInput.value = total;
                updateSummary();
            }
        }

        function clearAdminItemBreakdown() {
            document.querySelectorAll('.admin-cloth-preset-input').forEach(inp => {
                inp.value = 0;
            });
            const customContainer = document.getElementById('adminCustomClothItemsContainer');
            if (customContainer) customContainer.innerHTML = '';
            recalcAdminBreakdown();
        }

        function addAdminCustomClothRow(name = '', qty = 1) {
            const container = document.getElementById('adminCustomClothItemsContainer');
            if (!container) return;

            const idx = adminCustomClothIndex++;
            const row = document.createElement('div');
            row.id = `admin_custom_row_${idx}`;
            row.className = 'flex items-center gap-2 rounded-2xl bg-white p-3 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition';
            row.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="custom_items[${idx}][name]" value="${name}" placeholder="Garment name (e.g. Barong, Curtains)" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="button" onclick="adjustAdminCustomQty(${idx}, -1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">-</button>
                    <input type="number" min="1" max="500" name="custom_items[${idx}][qty]" id="admin_custom_qty_${idx}" value="${qty}" oninput="recalcAdminBreakdown()" class="admin-custom-cloth-qty w-12 rounded-xl border border-slate-200 bg-white py-1.5 text-center font-mono text-xs font-bold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    <button type="button" onclick="adjustAdminCustomQty(${idx}, 1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">+</button>
                </div>
                <button type="button" onclick="removeAdminCustomClothRow(${idx})" class="flex h-8 w-8 items-center justify-center rounded-xl border border-transparent text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-100 transition cursor-pointer" title="Remove item">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(row);
            recalcAdminBreakdown();
        }

        function adjustAdminCustomQty(idx, delta) {
            const input = document.getElementById(`admin_custom_qty_${idx}`);
            if (!input) return;
            let current = parseInt(input.value) || 1;
            current = Math.max(1, current + delta);
            input.value = current;
            recalcAdminBreakdown();
        }

        function removeAdminCustomClothRow(idx) {
            const row = document.getElementById(`admin_custom_row_${idx}`);
            if (row) {
                row.remove();
                recalcAdminBreakdown();
            }
        }

        // Initial trigger
        populateTiers();
        recalcAdminBreakdown();
    </script>
</x-admin-layout>
