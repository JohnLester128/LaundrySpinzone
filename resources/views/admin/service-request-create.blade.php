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
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">1. Customer & Location</h2>
                            <p class="text-xs text-slate-400 font-medium">Walk-in customer details & assigned branch</p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <!-- Customer Name -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 font-mono">
                                Customer Name <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" name="customer_name" id="inputCustomerName" value="{{ old('customer_name') }}" placeholder="e.g. Juan Dela Cruz" required class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs font-semibold">
                            </div>
                        </div>

                        <!-- Phone Number (Optional) -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 font-mono">
                                Phone Number <span class="text-slate-400 font-normal lowercase">(optional)</span>
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1.01 1.01 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 0917 123 4567" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Laundry Specifications -->
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-purple-100 text-purple-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">2. Laundry Specifications</h2>
                            <p class="text-xs text-slate-400 font-medium">Clothing type, weight, and quantity</p>
                        </div>
                    </div>

                    <!-- Clothing Type Selector -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 font-mono">
                            Type of Clothing / Fabrics <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach($clothTypes as $type)
                                @php
                                    $icon = match($type->id) {
                                        1 => '👕',
                                        2 => '🛏️',
                                        3 => '✨',
                                        default => '🧺'
                                    };
                                @endphp
                                <label class="relative flex flex-col justify-between rounded-2xl border-2 p-3.5 cursor-pointer transition duration-150 cloth-type-tile border-slate-200 bg-slate-50/40 hover:bg-white hover:border-blue-400">
                                    <input type="radio" name="cloth_type_id" value="{{ $type->id }}" class="sr-only cloth-radio" {{ (old('cloth_type_id', $clothTypes->first()->id ?? null) == $type->id) ? 'checked' : '' }} data-name="{{ $type->name }}" data-requires-size="{{ $type->requires_size ? '1' : '0' }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-blue-100/80 text-blue-600 font-bold text-xs">
                                            {{ $icon }}
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
                                <input type="number" min="1" max="8" step="1" name="estimated_weight" id="inputWeight" value="{{ old('estimated_weight', '5') }}" placeholder="1-8" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-4 pr-12 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs font-mono font-bold text-base">
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

                <!-- Card 3: Add-ons & Instructions -->
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">3. Add-ons & Instructions</h2>
                            <p class="text-xs text-slate-400 font-medium">Extra wash treatments, cloth piece count, and custom customer requests</p>
                        </div>
                    </div>

                    <!-- Add-ons Tiles -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2 font-mono">
                            Available Add-on Treatments
                        </label>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach($addons as $addon)
                                @if($addon->is_active)
                                    <label class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50/40 p-3.5 cursor-pointer hover:bg-white hover:border-emerald-400 transition duration-150 addon-tile">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="addon_ids[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}" data-name="{{ $addon->name }}" class="h-4 w-4 rounded-lg border-slate-300 text-emerald-600 focus:ring-emerald-500 addon-checkbox" {{ is_array(old('addon_ids')) && in_array($addon->id, old('addon_ids')) ? 'checked' : '' }}>
                                            <span class="text-xs font-bold text-slate-800">{{ $addon->name }}</span>
                                        </div>
                                        <span class="font-mono text-xs font-extrabold text-emerald-600 bg-emerald-50 border border-emerald-200/60 px-2.5 py-0.5 rounded-lg">
                                            +PHP {{ number_format($addon->price, 0) }}
                                        </span>
                                    </label>
                                @else
                                    <div class="flex items-center justify-between rounded-2xl border border-slate-200/60 bg-slate-100/70 p-3.5 opacity-60 cursor-not-allowed select-none">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" disabled class="h-4 w-4 rounded-lg border-slate-300 bg-slate-200 text-slate-400 cursor-not-allowed">
                                            <span class="text-xs font-bold text-slate-500 line-through decoration-slate-400">{{ $addon->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono text-[11px] font-bold text-slate-400 bg-slate-200/80 border border-slate-300/50 px-2 py-0.5 rounded-lg">
                                                Unavailable
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Cloth Quantity Field (Moved here into instructions/notes section) -->
                    <div class="mb-5" id="quantityContainer">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 font-mono" id="quantityLabel">
                            Clothes / Items Quantity (pcs)
                        </label>
                        <div class="relative">
                            <input type="number" min="1" step="1" name="quantity" id="inputQuantity" value="{{ old('quantity', '1') }}" placeholder="e.g. 10" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-4 pr-12 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs font-mono font-bold text-sm">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-mono font-bold text-slate-400" id="quantityUnit">
                                pcs
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1 font-mono" id="quantityHint">Number of individual clothes or items</p>
                    </div>

                    <!-- Special Notes -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 font-mono">
                            Special Instructions / Notes
                        </label>
                        <textarea name="notes" rows="3" placeholder="e.g. Separate whites from darks, handle delicate wool gently..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 p-3 text-xs text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs font-medium">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Live Order Summary Card (1 Column) -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-700 font-bold text-xs">
                            🧾
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
                            <span class="text-slate-400 font-medium">Cloth Count:</span>
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
                if (weightContainer) weightContainer.classList.add('hidden');
                if (weightInput) {
                    weightInput.value = '';
                    weightInput.disabled = true;
                }
                if (qtyContainer) qtyContainer.classList.add('hidden');
                if (qtyInput) qtyInput.value = '1';
                tierLabel.innerHTML = 'Comforter Size <span class="text-rose-500">*</span>';
                tierHint.textContent = 'Select comforter dimension & rate (1 pc)';
            } else {
                if (weightContainer) weightContainer.classList.remove('hidden');
                if (weightInput) {
                    weightInput.disabled = false;
                    if (!weightInput.value || parseFloat(weightInput.value) <= 0) {
                        weightInput.value = '5';
                    }
                }
                if (qtyContainer) qtyContainer.classList.remove('hidden');
                tierLabel.innerHTML = 'Weight Tier <span class="text-rose-500">*</span>';
                tierHint.textContent = 'Auto-selected by entered weight or choose manually';
                if (quantityLabel) quantityLabel.textContent = 'Clothes / Items Quantity (pcs)';
                if (quantityHint) quantityHint.textContent = 'Number of individual clothes or items';
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

            if (isNaN(weightVal) || weightVal <= 0 || tiers.length === 0) {
                updateSummary();
                return;
            }

            // Find exact tier matching min_weight and max_weight
            let matchedTier = tiers.find(t => {
                const min = t.min_weight !== null ? parseFloat(t.min_weight) : 0;
                const max = t.max_weight !== null ? parseFloat(t.max_weight) : Infinity;
                return weightVal >= min && weightVal <= max;
            });

            // If not found in range, pick closest tier
            if (!matchedTier) {
                const firstMin = tiers[0].min_weight !== null ? parseFloat(tiers[0].min_weight) : 0;
                const lastMax = tiers[tiers.length - 1].max_weight !== null ? parseFloat(tiers[tiers.length - 1].max_weight) : Infinity;
                if (weightVal < firstMin) {
                    matchedTier = tiers[0];
                } else if (weightVal > lastMax) {
                    matchedTier = tiers[tiers.length - 1];
                }
            }

            if (matchedTier && selectTier) {
                selectTier.value = matchedTier.id;
            }

            updateSummary();
        }

        function updateSummary() {
            const customerInput = document.getElementById('inputCustomerName');
            const summaryCustomer = document.getElementById('summaryCustomer');
            if (customerInput && summaryCustomer) {
                summaryCustomer.textContent = customerInput.value.trim() || 'Walk-in Customer';
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
                        summaryTier.textContent = `${selectedOpt.dataset.name} (₱${Math.round(tierPrice)})`;
                    } else {
                        summaryTier.textContent = `${weightVal > 0 ? Math.round(weightVal) + ' kg · ' : ''}${selectedOpt.dataset.name} (₱${Math.round(tierPrice)})`;
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
                        addonBadges.push(`<span class="inline-block bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-md border border-emerald-200/60 ml-1 mb-1">+₱${Math.round(p)} ${n}</span>`);
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

            // Radio button tile styles
            document.querySelectorAll('.cloth-type-tile').forEach(tile => {
                const radio = tile.querySelector('.cloth-radio');
                const circle = tile.querySelector('.radio-circle');
                const dot = tile.querySelector('.radio-dot');
                if (radio && radio.checked) {
                    tile.classList.add('border-blue-600', 'bg-blue-50/30');
                    tile.classList.remove('border-slate-200', 'bg-slate-50/40');
                    if (circle) circle.classList.add('border-blue-600');
                    if (dot) dot.classList.remove('hidden');
                } else {
                    tile.classList.remove('border-blue-600', 'bg-blue-50/30');
                    tile.classList.add('border-slate-200', 'bg-slate-50/40');
                    if (circle) circle.classList.remove('border-blue-600');
                    if (dot) dot.classList.add('hidden');
                }
            });
        }

        document.getElementById('inputCustomerName')?.addEventListener('input', updateSummary);
        
        const inputWeightEl = document.getElementById('inputWeight');
        inputWeightEl?.addEventListener('input', function() {
            const maxVal = parseFloat(this.max) || 8;
            const minVal = parseFloat(this.min) || 1;
            let val = parseFloat(this.value);
            if (!isNaN(val) && val > maxVal) {
                this.value = maxVal;
            }
            matchTierByWeight();
        });

        document.getElementById('inputQuantity')?.addEventListener('input', updateSummary);
        document.getElementById('selectPricingTier')?.addEventListener('change', updateSummary);
        document.querySelectorAll('.cloth-radio').forEach(r => r.addEventListener('change', populateTiers));
        document.querySelectorAll('.addon-checkbox').forEach(cb => cb.addEventListener('change', updateSummary));

        // Initial trigger
        populateTiers();
    </script>
</x-admin-layout>
