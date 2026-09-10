<x-customer-layout>
    <x-slot:title>Create Service Request — Spinzone Laundry</x-slot:title>

    <!-- Page Header -->
    <div class="mb-6">
        <a href="{{ route('customer.requests') }}" class="inline-flex items-center gap-2 font-mono text-xs font-bold text-slate-500 hover:text-blue-600 transition group mb-3">
            <svg class="h-4 w-4 transform transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to My Requests</span>
        </a>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Create Service Request</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">Specify your laundry load details, add-on treatments, and special notes.</p>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <form method="POST" action="{{ route('customer.service-requests.store') }}" id="customerRequestForm">
        @csrf

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Left Side: Main Form Fields (Spans 2 columns) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Section 1: Laundry Specifications Card -->
                <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-blue-600"></div>

                    <div class="flex items-center gap-3 pb-5 border-b border-slate-100">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100/80 text-blue-600 shadow-2xs">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold tracking-tight text-slate-900">Laundry Details</h2>
                            <p class="text-xs font-medium text-slate-400">Select clothing category, estimated weight, and rate tier.</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-5">
                        <!-- Cloth Type Select -->
                        <div>
                            <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Type of Clothes <span class="text-rose-500">*</span>
                            </label>
                            <select class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer" name="cloth_type_id" id="customerClothType" required>
                                @foreach($clothTypes as $type)
                                    <option value="{{ $type->id }}" @selected(old('cloth_type_id', $clothTypes->first()->id ?? null) == $type->id) data-requires-size="{{ $type->requires_size ? '1' : '0' }}">
                                        {{ $type->name }} ({{ $type->requires_size ? 'Per Piece / Size Based' : 'Weight Based' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dynamic Weight & Tier Grid -->
                        <div class="grid gap-4 sm:grid-cols-2">
                            <!-- Weight Input -->
                            <div id="customerWeightContainer">
                                <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Weight (kg) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-bold font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" type="number" name="estimated_weight" id="customerWeight" min="1" max="8" step="1" placeholder="1-8" value="{{ old('estimated_weight', '5') }}">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-mono font-bold text-slate-400">
                                        kg
                                    </div>
                                </div>
                                <p class="mt-1 text-[11px] text-slate-400 font-mono" id="customerWeightHint">Allowed weight: 1 - 8 kg (auto-matches tier)</p>
                            </div>

                            <!-- Pricing Tier Select -->
                            <div id="customerTierContainer">
                                <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5" id="customerTierLabel">
                                    Weight Tier <span class="text-rose-500">*</span>
                                </label>
                                <select class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer" name="pricing_tier_id" id="customerPricingTier" required>
                                    <!-- Populated dynamically via JS -->
                                </select>
                                <p class="mt-1 text-[11px] text-slate-400 font-mono" id="customerTierHint">Select your rate tier.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Items Quantity & Add-Ons Card -->
                <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                    <div class="flex items-center gap-3 pb-5 border-b border-slate-100">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-100/80 text-indigo-600 shadow-2xs">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold tracking-tight text-slate-900">Add-Ons & Quantity</h2>
                            <p class="text-xs font-medium text-slate-400">Choose extra laundry treatments and item quantity.</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-5">
                        <!-- Add-ons Selection -->
                        <div>
                            <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2.5">
                                Select Add-On Treatments
                            </label>
                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach($addons as $addon)
                                    @if($addon->is_active)
                                        <label class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-3.5 transition hover:border-blue-300 hover:bg-blue-50/20 cursor-pointer shadow-2xs has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-900">{{ $addon->name }}</span>
                                                <span class="text-xs font-semibold text-blue-600 mt-0.5">+PHP {{ number_format($addon->price, 0) }}</span>
                                            </div>
                                            <input class="h-5 w-5 accent-blue-600 rounded-lg customer-addon-checkbox cursor-pointer" type="checkbox" name="addon_ids[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}" data-name="{{ $addon->name }}" @checked(in_array($addon->id, old('addon_ids', [])))>
                                        </label>
                                    @else
                                        <div class="flex items-center justify-between rounded-2xl border border-slate-200/60 bg-slate-50 p-3.5 opacity-50 cursor-not-allowed select-none">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-slate-400 line-through">{{ $addon->name }}</span>
                                                <span class="text-xs text-slate-400">Unavailable</span>
                                            </div>
                                            <input class="h-5 w-5 accent-slate-400" type="checkbox" disabled>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Quantity Input -->
                        <div id="customerQuantityContainer">
                            <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5" id="customerQuantityLabel">
                                Clothes / Items Quantity (pcs)
                            </label>
                            <div class="relative">
                                <input class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-bold font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" type="number" name="quantity" id="customerQuantity" min="1" step="1" placeholder="e.g. 10" value="{{ old('quantity', 1) }}">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-mono font-bold text-slate-400">
                                    pcs
                                </div>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-400 font-mono" id="customerQuantityHint">Estimated number of individual items</p>
                        </div>

                        <!-- Special Instructions -->
                        <div>
                            <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Special Instructions (Optional)
                            </label>
                            <textarea class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition min-h-24" name="notes" placeholder="e.g., Handle delicate items gently, separate colored clothes...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Live Calculation Summary & Submission Card (1 column) -->
            <div class="space-y-6">
                <!-- Branch Destination Card -->
                <div class="rounded-3xl border border-blue-100 bg-blue-50/50 p-6 shadow-xs">
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-600 text-white shadow-xs">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">Destination Branch</h3>
                    </div>
                    <p class="text-base font-bold text-blue-950">{{ auth('customer')->user()->branch->name ?? 'Central Branch' }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ auth('customer')->user()->branch->address ?? 'Main Branch Location' }}</p>
                    <a href="{{ route('customer.profile') }}" class="inline-block mt-2 text-xs font-semibold text-blue-600 hover:underline">Change Branch in Profile →</a>
                </div>

                <!-- Live Summary Calculation Card -->
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm sticky top-28">
                    <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100">Order Summary</h3>

                    <dl class="mt-4 space-y-3 text-xs">
                        <div class="flex justify-between items-center">
                            <dt class="text-slate-400 font-mono uppercase">Base Rate Tier</dt>
                            <dd class="font-bold text-slate-800" id="summaryTierDisplay">-</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-slate-400 font-mono uppercase">Base Price</dt>
                            <dd class="font-bold text-slate-800 font-mono" id="summaryBasePriceDisplay">PHP 0</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-slate-400 font-mono uppercase">Add-Ons Total</dt>
                            <dd class="font-bold text-blue-600 font-mono" id="summaryAddonPriceDisplay">PHP 0</dd>
                        </div>
                    </dl>

                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-mono font-bold uppercase tracking-wider text-slate-500">Estimated Total</span>
                            <span class="text-2xl font-black text-blue-600 font-mono" id="customerTotalPrice">PHP 0</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1 text-right" id="customerSummaryDetails">-</p>
                    </div>

                    <button class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white rounded-2xl py-3.5 px-4 font-semibold text-sm shadow-md shadow-blue-600/20 transition-all duration-200 active:scale-[0.99] cursor-pointer flex items-center justify-center gap-2" type="submit">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Submit Service Request</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Script for Dynamic Tiers and Live Calculation -->
    <script>
        const customerClothTypes = @json($clothTypes);
        const oldCustomerTierId = @json(old('pricing_tier_id'));

        function populateCustomerTiers() {
            const selectClothType = document.getElementById('customerClothType');
            const selectTier = document.getElementById('customerPricingTier');
            const selectedTypeId = parseInt(selectClothType.value);
            const clothType = customerClothTypes.find(t => t.id === selectedTypeId);
            const tierLabel = document.getElementById('customerTierLabel');
            const tierHint = document.getElementById('customerTierHint');
            const weightContainer = document.getElementById('customerWeightContainer');
            const quantityContainer = document.getElementById('customerQuantityContainer');
            const quantityLabel = document.getElementById('customerQuantityLabel');
            const quantityHint = document.getElementById('customerQuantityHint');
            const quantityInput = document.getElementById('customerQuantity');

            if (!clothType || !selectTier) return;

            selectTier.innerHTML = '';

            const isSizeBased = clothType.requires_size || (clothType.pricing_tiers && clothType.pricing_tiers.some(tier => tier.size !== null));

            const weightInput = document.getElementById('customerWeight');
            const weightHint = document.getElementById('customerWeightHint');
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
                if (quantityContainer) quantityContainer.classList.add('hidden');
                if (quantityInput) quantityInput.value = '1';
                tierLabel.innerHTML = 'Comforter Size <span class="text-rose-500">*</span>';
                tierHint.textContent = 'Select comforter dimension and rate (1 pc)';
            } else {
                if (weightContainer) weightContainer.classList.remove('hidden');
                if (weightInput) {
                    weightInput.disabled = false;
                    if (!weightInput.value || parseFloat(weightInput.value) <= 0) {
                        weightInput.value = '5';
                    }
                }
                if (quantityContainer) quantityContainer.classList.remove('hidden');
                tierLabel.innerHTML = 'Weight Tier <span class="text-rose-500">*</span>';
                tierHint.textContent = 'Auto-selected by entered weight or choose manually.';
                if (quantityLabel) quantityLabel.textContent = 'Clothes / Items Quantity (pcs)';
                if (quantityHint) quantityHint.textContent = 'Estimated number of individual clothes or items';
            }

            const tiers = clothType.pricing_tiers || [];
            tiers.forEach((tier, index) => {
                const opt = document.createElement('option');
                opt.value = tier.id;
                opt.dataset.price = tier.price;
                opt.dataset.name = tier.name;
                opt.dataset.minWeight = tier.min_weight !== null ? tier.min_weight : '';
                opt.dataset.maxWeight = tier.max_weight !== null ? tier.max_weight : '';
                
                const formattedPrice = Math.round(parseFloat(tier.price));
                opt.textContent = `${tier.name} — PHP ${formattedPrice}`;

                if (oldCustomerTierId && parseInt(oldCustomerTierId) === tier.id) {
                    opt.selected = true;
                } else if (!oldCustomerTierId && index === 0) {
                    opt.selected = true;
                }

                selectTier.appendChild(opt);
            });

            if (!isSizeBased) {
                matchCustomerTierByWeight();
            } else {
                updateCustomerSummary();
            }
        }

        function matchCustomerTierByWeight() {
            const selectClothType = document.getElementById('customerClothType');
            if (!selectClothType) return;

            const selectedTypeId = parseInt(selectClothType.value);
            const clothType = customerClothTypes.find(t => t.id === selectedTypeId);
            if (!clothType || clothType.requires_size) {
                updateCustomerSummary();
                return;
            }

            const weightInput = document.getElementById('customerWeight');
            const weightVal = parseFloat(weightInput ? weightInput.value : 0);
            const selectTier = document.getElementById('customerPricingTier');
            const tiers = clothType.pricing_tiers || [];

            if (isNaN(weightVal) || weightVal <= 0 || tiers.length === 0) {
                updateCustomerSummary();
                return;
            }

            let matchedTier = tiers.find(t => {
                const min = t.min_weight !== null ? parseFloat(t.min_weight) : 0;
                const max = t.max_weight !== null ? parseFloat(t.max_weight) : Infinity;
                return weightVal >= min && weightVal <= max;
            });

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

            updateCustomerSummary();
        }

        function updateCustomerSummary() {
            const selectClothType = document.getElementById('customerClothType');
            const selectTier = document.getElementById('customerPricingTier');
            const weightInput = document.getElementById('customerWeight');
            const qtyInput = document.getElementById('customerQuantity');
            const priceEl = document.getElementById('customerTotalPrice');
            const detailsEl = document.getElementById('customerSummaryDetails');
            const tierDisplayEl = document.getElementById('summaryTierDisplay');
            const basePriceDisplayEl = document.getElementById('summaryBasePriceDisplay');
            const addonPriceDisplayEl = document.getElementById('summaryAddonPriceDisplay');

            let tierPrice = 0;
            let tierName = 'Standard';
            let isSizeBased = false;

            if (selectClothType) {
                const selectedOpt = selectClothType.selectedOptions[0];
                isSizeBased = selectedOpt && selectedOpt.getAttribute('data-requires-size') === '1';
            }

            if (selectTier && selectTier.selectedOptions.length > 0) {
                const opt = selectTier.selectedOptions[0];
                tierPrice = parseFloat(opt.dataset.price) || 0;
                tierName = opt.dataset.name || '';
            }

            const qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;
            const weightVal = parseFloat(weightInput ? weightInput.value : 0) || 0;

            let addonTotal = 0;
            document.querySelectorAll('.customer-addon-checkbox:checked').forEach(cb => {
                addonTotal += parseFloat(cb.getAttribute('data-price')) || 0;
            });

            const baseTotal = isSizeBased ? (tierPrice * qty) : tierPrice;
            const total = baseTotal + addonTotal;

            if (tierDisplayEl) tierDisplayEl.textContent = tierName;
            if (basePriceDisplayEl) basePriceDisplayEl.textContent = `PHP ${Math.round(baseTotal).toLocaleString('en-US')}`;
            if (addonPriceDisplayEl) addonPriceDisplayEl.textContent = `+PHP ${Math.round(addonTotal).toLocaleString('en-US')}`;

            if (priceEl) {
                priceEl.textContent = `PHP ${Math.round(total).toLocaleString('en-US')}`;
            }
            if (detailsEl) {
                if (isSizeBased) {
                    detailsEl.textContent = `${tierName} × ${qty}${addonTotal > 0 ? ' + Add-ons' : ''}`;
                } else {
                    detailsEl.textContent = `${weightVal > 0 ? Math.round(weightVal) + 'kg · ' : ''}${tierName} · ${qty} pcs`;
                }
            }
        }

        document.getElementById('customerClothType')?.addEventListener('change', populateCustomerTiers);
        
        const customerWeightEl = document.getElementById('customerWeight');
        customerWeightEl?.addEventListener('input', function() {
            const maxVal = parseFloat(this.max) || 8;
            let val = parseFloat(this.value);
            if (!isNaN(val) && val > maxVal) {
                this.value = maxVal;
            }
            matchCustomerTierByWeight();
        });

        document.getElementById('customerPricingTier')?.addEventListener('change', updateCustomerSummary);
        document.getElementById('customerQuantity')?.addEventListener('input', updateCustomerSummary);
        document.querySelectorAll('.customer-addon-checkbox').forEach(cb => cb.addEventListener('change', updateCustomerSummary));

        populateCustomerTiers();
    </script>
</x-customer-layout>
