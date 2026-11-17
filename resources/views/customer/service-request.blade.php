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
                <!-- Section 1: Laundry Details Card -->
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
                        <!-- Cloth Type Click Boxes (Same as Manager) -->
                        <div>
                            <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">
                                Type of Clothes <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid gap-3 sm:grid-cols-3">
                                @foreach($clothTypes as $type)
                                    <label class="relative flex flex-col justify-between rounded-2xl border-2 p-3.5 cursor-pointer transition duration-150 customer-cloth-type-tile border-slate-200 bg-slate-50/40 hover:bg-white hover:border-blue-400">
                                        <input type="radio" name="cloth_type_id" value="{{ $type->id }}" class="sr-only customer-cloth-radio" {{ (old('cloth_type_id', $clothTypes->first()->id ?? null) == $type->id) ? 'checked' : '' }} data-name="{{ $type->name }}" data-requires-size="{{ $type->requires_size ? '1' : '0' }}">
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
                        <div class="grid gap-4 sm:grid-cols-2">
                            <!-- Weight Input -->
                            <div id="customerWeightContainer">
                                <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Estimated Weight (kg) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-bold font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" type="number" name="estimated_weight" id="customerWeight" min="0" max="8" step="1" placeholder="0" value="{{ old('estimated_weight', '0') }}">
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
                        <div class="flex h-11 w-11 shrink-0 aspect-square items-center justify-center rounded-2xl bg-blue-100/80 text-blue-600 shadow-2xs">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
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

                        <!-- Quantity & Itemized Cloth Counter Widget -->
                        <div id="customerQuantityContainer" class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500" id="customerQuantityLabel">
                                    Total Items Quantity (pcs)
                                </label>
                                <button type="button" id="toggleItemBreakdownBtn" onclick="toggleItemBreakdown()" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 hover:text-blue-600 hover:border-blue-300 transition cursor-pointer shadow-2xs group">
                                    <svg id="toggleItemBreakdownChevron" class="h-3.5 w-3.5 text-slate-400 group-hover:text-blue-600 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <span id="toggleItemBreakdownText">Itemized Cloth Breakdown</span>
                                    <span id="itemBreakdownBadge" class="hidden rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-extrabold text-blue-700 font-mono">0 items</span>
                                </button>
                            </div>

                            <div class="relative">
                                <input class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-bold font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" type="number" name="quantity" id="customerQuantity" min="1" step="1" placeholder="e.g. 10" value="{{ old('quantity', 1) }}">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-mono font-bold text-slate-400">
                                    pcs
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 font-mono" id="customerQuantityHint">Enter total pieces, or open the checklist below to itemize each piece to protect against lost items.</p>

                            <!-- Collapsible Cloth Counter Checklist Box -->
                            <div id="itemBreakdownBox" class="hidden rounded-3xl border border-slate-200/90 bg-slate-50/70 p-5 transition-all shadow-xs space-y-4">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-200/80">
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100/80 text-blue-600 shadow-2xs">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-extrabold text-slate-900">Anti-Loss Clothing Breakdown</h4>
                                            <p class="text-[11px] text-slate-400 font-mono">Counts automatically sync with your total pieces.</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="clearItemBreakdown()" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-mono font-bold text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition cursor-pointer shadow-2xs">
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
                                                <button type="button" onclick="adjustPresetQty('{{ $key }}', -1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">
                                                    -
                                                </button>
                                                <input type="number" min="0" max="500" name="items[{{ $key }}]" id="preset_{{ $key }}" value="{{ old('items.' . $key, 0) }}" oninput="recalcBreakdown()" class="cloth-preset-input w-12 rounded-xl border border-slate-200 bg-white py-1.5 text-center font-mono text-xs font-bold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                                                <button type="button" onclick="adjustPresetQty('{{ $key }}', 1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Custom / Other Items Section -->
                                <div class="pt-3 border-t border-slate-200/80">
                                    <div class="flex items-center justify-between mb-2.5">
                                        <div>
                                            <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-500">Other Specific Clothes</p>
                                            <p class="text-[10px] text-slate-400 font-mono">Add custom garments (Barong, Curtains, etc.)</p>
                                        </div>
                                        <button type="button" onclick="addCustomClothRow()" class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100 transition cursor-pointer shadow-2xs">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                            </svg>
                                            <span>Add Other Item</span>
                                        </button>
                                    </div>

                                    <div id="customClothItemsContainer" class="space-y-2.5">
                                        <!-- Dynamically added custom rows -->
                                    </div>
                                </div>
                            </div>
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
                    <div class="mt-3 pt-3 border-t border-blue-100 flex items-start gap-2 text-[11px] text-slate-600 leading-relaxed">
                        <svg class="h-3.5 w-3.5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>
                            <strong class="font-bold text-slate-800">Note:</strong> Requests are routed to this branch. You can update your default branch anytime in your profile.
                        </p>
                    </div>
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

        function getActiveCustomerClothTypeRadio() {
            return document.querySelector('.customer-cloth-radio:checked') || document.querySelector('.customer-cloth-radio');
        }

        function updateCustomerClothTypeVisuals() {
            document.querySelectorAll('.customer-cloth-type-tile').forEach(tile => {
                const radio = tile.querySelector('.customer-cloth-radio');
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

        function populateCustomerTiers() {
            const activeRadio = getActiveCustomerClothTypeRadio();
            if (!activeRadio) return;

            const selectedTypeId = parseInt(activeRadio.value);
            const clothType = customerClothTypes.find(t => t.id === selectedTypeId);
            const selectTier = document.getElementById('customerPricingTier');
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
                weightInput.min = "0";
                weightInput.max = maxWeight.toString();
                weightInput.placeholder = `0-${maxWeight}`;
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
                    weightInput.min = "0";
                    if (weightInput.value === '' || isNaN(parseFloat(weightInput.value))) {
                        weightInput.value = '0';
                    }
                }
                if (quantityContainer) quantityContainer.classList.remove('hidden');
                tierLabel.innerHTML = 'Weight Tier <span class="text-rose-500">*</span>';
                tierHint.textContent = 'Auto-selected by entered weight or choose manually.';
                if (quantityLabel) quantityLabel.textContent = 'Items Quantity (pcs)';
                if (quantityHint) quantityHint.textContent = 'Estimated number of individual items';
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

            updateCustomerClothTypeVisuals();

            if (!isSizeBased) {
                matchCustomerTierByWeight();
            } else {
                updateCustomerSummary();
            }
        }

        function matchCustomerTierByWeight() {
            const activeRadio = getActiveCustomerClothTypeRadio();
            if (!activeRadio) return;

            const selectedTypeId = parseInt(activeRadio.value);
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

            let matchedTier = null;
            if (!isNaN(weightVal) && weightVal > 0) {
                matchedTier = tiers.find(t => {
                    const min = t.min_weight !== null ? parseFloat(t.min_weight) : 0;
                    const max = t.max_weight !== null ? parseFloat(t.max_weight) : Infinity;
                    return weightVal >= min && weightVal <= max;
                });

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

            updateCustomerSummary();
        }

        function updateCustomerSummary() {
            const activeRadio = getActiveCustomerClothTypeRadio();
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

            if (activeRadio) {
                isSizeBased = activeRadio.getAttribute('data-requires-size') === '1';
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
                    detailsEl.textContent = `${weightVal > 0 ? Math.round(weightVal) + 'kg · ' : '0kg · '}${tierName} · ${qty} pcs`;
                }
            }
        }

        function onCustomerPricingTierChange() {
            const selectTier = document.getElementById('customerPricingTier');
            const weightInput = document.getElementById('customerWeight');
            if (!selectTier || !weightInput) {
                updateCustomerSummary();
                return;
            }

            const activeRadio = getActiveCustomerClothTypeRadio();
            const selectedTypeId = parseInt(activeRadio ? activeRadio.value : 0);
            const clothType = customerClothTypes.find(t => t.id === selectedTypeId);
            const isSizeBased = clothType ? (clothType.requires_size || (clothType.pricing_tiers && clothType.pricing_tiers.some(t => t.size !== null))) : false;

            if (!isSizeBased && selectTier.selectedOptions.length > 0) {
                const selectedOpt = selectTier.selectedOptions[0];
                const minWeight = selectedOpt.dataset.minWeight;
                if (minWeight !== undefined && minWeight !== '' && !isNaN(parseFloat(minWeight))) {
                    weightInput.value = Math.round(parseFloat(minWeight));
                }
            }

            updateCustomerSummary();
        }

        document.querySelectorAll('.customer-cloth-radio').forEach(radio => {
            radio.addEventListener('change', populateCustomerTiers);
        });
        
        const customerWeightEl = document.getElementById('customerWeight');
        if (customerWeightEl) {
            ['input', 'change', 'keyup'].forEach(evt => {
                customerWeightEl.addEventListener(evt, function() {
                    const maxVal = parseFloat(this.max) || 8;
                    let val = parseFloat(this.value);
                    if (!isNaN(val) && val > maxVal) {
                        this.value = maxVal;
                    }
                    matchCustomerTierByWeight();
                });
            });
        }

        // Cloth Counter & Breakdown Logic
        let customClothIndex = 0;

        function toggleItemBreakdown() {
            const box = document.getElementById('itemBreakdownBox');
            const chevron = document.getElementById('toggleItemBreakdownChevron');
            const text = document.getElementById('toggleItemBreakdownText');
            if (!box) return;

            if (box.classList.contains('hidden')) {
                box.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
                if (text) text.textContent = 'Hide Breakdown';
            } else {
                box.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
                if (text) text.textContent = 'Itemized Cloth Breakdown';
            }
        }

        function adjustPresetQty(key, delta) {
            const input = document.getElementById('preset_' + key);
            if (!input) return;
            let current = parseInt(input.value) || 0;
            current = Math.max(0, current + delta);
            input.value = current;
            recalcBreakdown();
        }

        function recalcBreakdown() {
            let total = 0;
            // Presets
            document.querySelectorAll('.cloth-preset-input').forEach(inp => {
                const val = parseInt(inp.value) || 0;
                total += val;
            });

            // Custom Items
            document.querySelectorAll('.custom-cloth-qty').forEach(inp => {
                const val = parseInt(inp.value) || 0;
                total += val;
            });

            // Update badge
            const badge = document.getElementById('itemBreakdownBadge');
            if (badge) {
                if (total > 0) {
                    badge.classList.remove('hidden');
                    badge.textContent = `${total} pcs counted`;
                } else {
                    badge.classList.add('hidden');
                }
            }

            // Sync total quantity field
            const qtyInput = document.getElementById('customerQuantity');
            if (qtyInput && total > 0) {
                qtyInput.value = total;
                updateCustomerSummary();
            }
        }

        function clearItemBreakdown() {
            document.querySelectorAll('.cloth-preset-input').forEach(inp => {
                inp.value = 0;
            });
            const customContainer = document.getElementById('customClothItemsContainer');
            if (customContainer) customContainer.innerHTML = '';
            recalcBreakdown();
        }

        function addCustomClothRow(name = '', qty = 1) {
            const container = document.getElementById('customClothItemsContainer');
            if (!container) return;

            const idx = customClothIndex++;
            const row = document.createElement('div');
            row.id = `custom_row_${idx}`;
            row.className = 'flex items-center gap-2 rounded-2xl bg-white p-3 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition';
            row.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="custom_items[${idx}][name]" value="${name}" placeholder="Garment name (e.g. Barong, Curtains)" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="button" onclick="adjustCustomQty(${idx}, -1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">-</button>
                    <input type="number" min="1" max="500" name="custom_items[${idx}][qty]" id="custom_qty_${idx}" value="${qty}" oninput="recalcBreakdown()" class="custom-cloth-qty w-12 rounded-xl border border-slate-200 bg-white py-1.5 text-center font-mono text-xs font-bold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    <button type="button" onclick="adjustCustomQty(${idx}, 1)" class="flex h-8 w-8 items-center justify-center rounded-xl border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 active:scale-95 text-xs font-bold font-mono transition select-none cursor-pointer">+</button>
                </div>
                <button type="button" onclick="removeCustomClothRow(${idx})" class="flex h-8 w-8 items-center justify-center rounded-xl border border-transparent text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-100 transition cursor-pointer" title="Remove item">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(row);
            recalcBreakdown();
        }

        function adjustCustomQty(idx, delta) {
            const input = document.getElementById(`custom_qty_${idx}`);
            if (!input) return;
            let current = parseInt(input.value) || 1;
            current = Math.max(1, current + delta);
            input.value = current;
            recalcBreakdown();
        }

        function removeCustomClothRow(idx) {
            const row = document.getElementById(`custom_row_${idx}`);
            if (row) {
                row.remove();
                recalcBreakdown();
            }
        }

        document.getElementById('customerPricingTier')?.addEventListener('change', onCustomerPricingTierChange);
        document.getElementById('customerQuantity')?.addEventListener('input', updateCustomerSummary);
        document.querySelectorAll('.customer-addon-checkbox').forEach(cb => cb.addEventListener('change', updateCustomerSummary));

        populateCustomerTiers();
        recalcBreakdown();
    </script>
</x-customer-layout>
