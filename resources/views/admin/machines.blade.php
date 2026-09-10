<x-admin-layout>
    <x-slot:title>Manage Machines - Laundry Spinzone</x-slot:title>
    <x-slot:header>Machines Management</x-slot:header>

    <div class="space-y-6">
        <!-- Notification Component -->
        <x-notification />

        <!-- Header Controls Row: Title/Breadcrumb + Add Machine Button -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Branch Machines</h1>
                <p class="text-xs text-slate-500 mt-0.5">Click any machine unit to view details, update specifications, set maintenance, or delete.</p>
            </div>
            <div>
                <button type="button" onclick="openAddMachineModal()" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-2.5 text-xs font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 transition cursor-pointer active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Machine</span>
                </button>
            </div>
        </div>

        <!-- Metrics Overview Row -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Machines -->
            <div class="flex items-center gap-4 rounded-3xl border border-slate-200/80 bg-white p-5 shadow-xs">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Total Units</p>
                    <p class="text-2xl font-bold tracking-tight text-slate-900 mt-0.5">{{ $machines->count() }}</p>
                </div>
            </div>

            <!-- Washers Count -->
            <div class="flex items-center gap-4 rounded-3xl border border-slate-200/80 bg-white p-5 shadow-xs">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Washers</p>
                    <p class="text-2xl font-bold tracking-tight text-slate-900 mt-0.5">{{ $machines->where('type', 'washer')->count() }}</p>
                </div>
            </div>

            <!-- Dryers Count -->
            <div class="flex items-center gap-4 rounded-3xl border border-slate-200/80 bg-white p-5 shadow-xs">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                    </svg>
                </div>
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Dryers</p>
                    <p class="text-2xl font-bold tracking-tight text-slate-900 mt-0.5">{{ $machines->where('type', 'dryer')->count() }}</p>
                </div>
            </div>

            <!-- Available Status -->
            <div class="flex items-center gap-4 rounded-3xl border border-slate-200/80 bg-white p-5 shadow-xs">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">Available</p>
                    <p class="text-2xl font-bold tracking-tight text-slate-900 mt-0.5">{{ $machines->where('status', 'available')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Main Card Container -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <!-- Top Controls: Search Input + Filter Tabs -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-100 mb-6">
                <!-- Search Bar -->
                <div class="relative w-full sm:w-80">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="machineSearch" onkeyup="filterMachines()" placeholder="Search machine name, brand, model..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-10 pr-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                </div>

                <!-- Filter Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" onclick="setMachineFilter('all', this)" class="machine-filter-btn rounded-xl px-4 py-2 text-xs font-semibold transition bg-blue-600 text-white shadow-sm cursor-pointer">
                        All ({{ $machines->count() }})
                    </button>
                    <button type="button" onclick="setMachineFilter('washer', this)" class="machine-filter-btn rounded-xl px-4 py-2 text-xs font-semibold transition bg-slate-100/80 text-slate-600 hover:bg-slate-200/60 cursor-pointer">
                        Washers ({{ $machines->where('type', 'washer')->count() }})
                    </button>
                    <button type="button" onclick="setMachineFilter('dryer', this)" class="machine-filter-btn rounded-xl px-4 py-2 text-xs font-semibold transition bg-slate-100/80 text-slate-600 hover:bg-slate-200/60 cursor-pointer">
                        Dryers ({{ $machines->where('type', 'dryer')->count() }})
                    </button>
                    <button type="button" onclick="setMachineFilter('available', this)" class="machine-filter-btn rounded-xl px-4 py-2 text-xs font-semibold transition bg-slate-100/80 text-slate-600 hover:bg-slate-200/60 cursor-pointer">
                        Available ({{ $machines->where('status', 'available')->count() }})
                    </button>
                    <button type="button" onclick="setMachineFilter('in_use', this)" class="machine-filter-btn rounded-xl px-4 py-2 text-xs font-semibold transition bg-slate-100/80 text-slate-600 hover:bg-slate-200/60 cursor-pointer">
                        In Use ({{ $machines->where('status', 'in_use')->count() }})
                    </button>
                    <button type="button" onclick="setMachineFilter('maintenance', this)" class="machine-filter-btn rounded-xl px-4 py-2 text-xs font-semibold transition bg-slate-100/80 text-slate-600 hover:bg-slate-200/60 cursor-pointer">
                        Maintenance ({{ $machines->where('status', 'maintenance')->count() }})
                    </button>
                </div>
            </div>

            <!-- Machines Grid -->
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3" id="machinesGrid">
                @forelse($machines as $machine)
                    @php
                        $statusRaw = strtolower($machine->status);
                        $isAvailable = $statusRaw === 'available';
                        $isInUse = $statusRaw === 'in_use';
                        $isMaintenance = !$isAvailable && !$isInUse;
                        $isWasher = strtolower($machine->type) === 'washer';
                        $activeReq = $machine->active_service_request;

                        $cardBorderClass = $isAvailable 
                            ? 'border-emerald-200/80 bg-emerald-50/40 hover:bg-emerald-50/70 hover:border-emerald-300' 
                            : ($isInUse 
                                ? 'border-blue-200/80 bg-blue-50/40 hover:bg-blue-50/70 hover:border-blue-300' 
                                : 'border-amber-200/80 bg-amber-50/40 hover:bg-amber-50/70 hover:border-amber-300');

                        $badgeClass = $isAvailable 
                            ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' 
                            : ($isInUse 
                                ? 'bg-blue-100 text-blue-700 border border-blue-200' 
                                : 'bg-amber-100 text-amber-800 border border-amber-200');

                        $statusLabel = $isAvailable ? 'AVAILABLE' : ($isInUse ? 'IN_USE' : 'MAINTENANCE');
                    @endphp
                    <article class="machine-card group relative overflow-hidden rounded-2xl border-2 {{ $cardBorderClass }} p-5 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 cursor-pointer"
                             onclick="openMachineModal({{ $machine->id }}, '{{ addslashes($machine->name) }}', '{{ $machine->type }}', '{{ addslashes($machine->brand ?? '') }}', '{{ addslashes($machine->model ?? '') }}', '{{ $isAvailable ? 'available' : ($isInUse ? 'in_use' : 'maintenance') }}', '{{ addslashes($machine->branch->name ?? 'Main Branch') }}', '{{ $machine->created_at ? $machine->created_at->format('M d, Y h:i A') : 'N/A' }}', {{ json_encode($activeReq ? ['id' => $activeReq->id, 'customer' => $activeReq->customer->username ?? 'Walk-in', 'status' => $activeReq->status, 'url' => route('admin.service-requests.show', $activeReq)] : null) }})"
                             data-name="{{ strtolower($machine->name) }}"
                             data-brand="{{ strtolower($machine->brand ?? '') }}"
                             data-model="{{ strtolower($machine->model ?? '') }}"
                             data-branch="{{ strtolower($machine->branch->name ?? '') }}"
                             data-type="{{ strtolower($machine->type) }}"
                             data-status="{{ $isAvailable ? 'available' : ($isInUse ? 'in_use' : 'maintenance') }}"
                             data-request="{{ strtolower(($activeReq->customer->username ?? '') . ' ' . ($activeReq ? '#req-' . str_pad($activeReq->id, 4, '0', STR_PAD_LEFT) : '')) }}">
                        
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $isWasher ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-amber-500 text-white shadow-md shadow-amber-500/20' }}">
                                    @if($isWasher)
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    @else
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-mono text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        {{ $machine->type }}
                                    </span>
                                    <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-blue-600 transition">{{ $machine->name }}</h3>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider shadow-2xs {{ $badgeClass }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $isAvailable ? 'bg-emerald-500 animate-pulse' : ($isInUse ? 'bg-blue-500 animate-pulse' : 'bg-amber-500') }}"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <!-- Middle Row: Brand & Model (Left) + Active Request Badge (Right below status badge) -->
                        <div class="mt-3 flex items-center justify-between gap-2 min-h-[30px]">
                            <!-- Brand & Model Tags -->
                            <div class="flex flex-wrap items-center gap-1.5">
                                @if($machine->brand)
                                    <span class="inline-flex items-center rounded-lg bg-white/80 border border-slate-200 px-2.5 py-0.5 text-xs font-semibold text-slate-700">
                                        🏷️ {{ $machine->brand }}
                                    </span>
                                @endif
                                @if($machine->model)
                                    <span class="inline-flex items-center rounded-lg bg-white/80 border border-slate-200 px-2.5 py-0.5 text-xs font-mono font-medium text-slate-600">
                                        Mod: {{ $machine->model }}
                                    </span>
                                @endif
                            </div>

                            <!-- Active Service Request Info (Right Side) -->
                            @if($activeReq)
                                <a href="{{ route('admin.service-requests.show', $activeReq) }}" onclick="event.stopPropagation();" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-2.5 py-1 text-xs font-mono font-bold shadow-xs hover:shadow-md transition shrink-0 ml-auto z-10" title="Click to view request details">
                                    <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                                    <span>#REQ-{{ str_pad($activeReq->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </a>
                            @endif
                        </div>
                        
                        <div class="mt-4 pt-3 border-t border-slate-200/50 flex items-center justify-between text-xs font-medium text-slate-500">
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ $machine->branch->name ?? 'Main Branch' }}
                            </span>
                            <span class="font-mono text-[10px] text-slate-400 flex items-center gap-1">
                                <span>ID: #{{ str_pad($machine->id, 3, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-blue-500 font-bold opacity-0 group-hover:opacity-100 transition">⚙ Manage →</span>
                            </span>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border-2 border-dashed border-slate-200 p-12 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-slate-900">No Machines Added Yet</h3>
                        <p class="mt-1 text-sm text-slate-500">Get started by clicking the "Add Machine" button above.</p>
                        <button type="button" onclick="openAddMachineModal()" class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-2.5 text-xs font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 transition cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add Machine Now</span>
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Machine Details, Edit & Delete Modal Overlay -->
    <div id="machineDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden transition-opacity duration-200 overflow-y-auto">
        <!-- Modal Card -->
        <div class="relative w-full max-w-lg rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-2xl space-y-6 animate-in fade-in zoom-in-95 duration-150 my-8 max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-start justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div id="modalMachineIcon" class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-500/20">
                        <!-- Icon swapped via JS -->
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 id="modalMachineTitle" class="text-xl font-extrabold tracking-tight text-slate-900">Machine Details</h2>
                            <span id="modalMachineStatusBadge" class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider"></span>
                        </div>
                        <p id="modalMachineSubtitle" class="text-xs text-slate-500 mt-0.5">Main Branch · ID: #001</p>
                    </div>
                </div>
                <button type="button" onclick="closeMachineModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Active Service Request Callout (If currently in use) -->
            <div id="modalActiveReqContainer" class="hidden rounded-2xl bg-blue-50 p-4 border border-blue-200">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="h-3 w-3 rounded-full bg-blue-600 animate-ping"></div>
                        <div>
                            <p class="text-xs font-mono font-bold uppercase tracking-wider text-blue-600">Currently In Cycle</p>
                            <p id="modalActiveReqText" class="text-sm font-extrabold text-blue-950 truncate mt-0.5">Customer: Walk-in</p>
                        </div>
                    </div>
                    <a id="modalActiveReqLink" href="#" class="inline-flex items-center gap-1 rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 text-xs font-bold shadow-sm transition shrink-0">
                        <span>View Order</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Quick Maintenance / Availability Toggle Box -->
            <div class="rounded-2xl bg-slate-50 p-4 border border-slate-200/80 flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-bold text-slate-900">Unit Operational Status</p>
                    <p class="text-xs text-slate-500 mt-0.5">Quickly toggle machine between Available and Maintenance.</p>
                </div>
                <form id="toggleMachineStatusForm" method="POST" action="">
                    @csrf
                    <button type="submit" id="modalToggleStatusBtn" class="inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold shadow-xs transition cursor-pointer active:scale-95">
                        <!-- Button content populated by JS -->
                    </button>
                </form>
            </div>

            <!-- Edit Machine Form -->
            <form id="editMachineForm" method="POST" action="" class="space-y-4 pt-2 border-t border-slate-100">
                @csrf
                @method('PUT')

                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-mono">Edit Machine Information</h3>

                <!-- Name Field -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Machine Name</label>
                    <input type="text" name="name" id="editMachineName" required class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-bold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                </div>

                <!-- Grid for Type & Status -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Type Field -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Type</label>
                        <select name="type" id="editMachineType" required class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer">
                            <option value="washer">Washer 🧺</option>
                            <option value="dryer">Dryer 💨</option>
                        </select>
                    </div>

                    <!-- Status Select -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Status</label>
                        <select name="status" id="editMachineStatus" required class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer">
                            <option value="available">Available ✅</option>
                            <option value="maintenance">Maintenance 🛠️</option>
                            <option value="in_use">In Use 🔄</option>
                        </select>
                    </div>
                </div>

                <!-- Grid for Brand & Model -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Brand Field -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Brand <span class="text-[10px] text-slate-400 font-normal">(Opt)</span></label>
                        <input type="text" name="brand" id="editMachineBrand" list="brandSuggestions" placeholder="e.g. Whirlpool" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>

                    <!-- Model Field -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Model <span class="text-[10px] text-slate-400 font-normal">(Opt)</span></label>
                        <input type="text" name="model" id="editMachineModel" placeholder="e.g. 9/11" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>
                </div>

                <!-- Save Changes Submit Button -->
                <div class="pt-2 flex justify-end">
                    <button type="submit" class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 transition cursor-pointer active:scale-98">
                        Save Changes
                    </button>
                </div>
            </form>

            <!-- Danger Zone: Delete Machine Form -->
            <div class="pt-4 border-t border-rose-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-rose-700">Delete Machine Unit</p>
                    <p class="text-[11px] text-slate-400">Permanently remove this unit from inventory.</p>
                </div>
                <form id="deleteMachineForm" method="POST" action="" onsubmit="return confirm('Are you sure you want to permanently delete this machine? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2 text-xs font-bold text-rose-700 hover:bg-rose-600 hover:text-white transition cursor-pointer active:scale-95 shadow-2xs">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Delete Machine</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Machine Modal Overlay -->
    <div id="addMachineModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 hidden transition-opacity duration-200">
        <!-- Modal Card -->
        <div class="relative w-full max-w-md rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-2xl space-y-6 animate-in fade-in zoom-in-95 duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-slate-900">Add New Machine</h2>
                        <p class="text-xs text-slate-500">Register a washer or dryer to your branch inventory.</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddMachineModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('admin.machines.store') }}" class="space-y-4">
                @csrf

                <!-- Type Field -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Machine Type</label>
                    <select name="type" id="machineType" onchange="updateAutoMachineName()" required class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer">
                        <option value="washer">Washer 🧺</option>
                        <option value="dryer">Dryer 💨</option>
                    </select>
                </div>

                <!-- Brand Field (Optional) -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Machine Brand <span class="text-[10px] text-slate-400 font-normal">(Optional)</span></label>
                    <input type="text" name="brand" id="machineBrand" list="brandSuggestions" placeholder="e.g. Speed Queen, LG, Whirlpool" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    <datalist id="brandSuggestions">
                        <option value="Speed Queen">
                        <option value="LG">
                        <option value="Whirlpool">
                        <option value="Electrolux">
                        <option value="Maytag">
                        <option value="Samsung">
                        <option value="Huebsch">
                    </datalist>
                </div>

                <!-- Model Field (Optional) -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-1.5">Machine Model <span class="text-[10px] text-slate-400 font-normal">(Optional)</span></label>
                    <input type="text" name="model" id="machineModel" placeholder="e.g. AFN28R or Commercial Heavy 15kg" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                </div>

                <!-- Generated Machine Name Field (Readonly / Auto-filled preview) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500">Machine Name (Auto-generated)</label>
                        <span class="text-[10px] font-semibold text-blue-600">Auto-filled</span>
                    </div>
                    <input type="text" name="name" id="machineName" readonly placeholder="Auto-generated name" class="w-full rounded-2xl border border-slate-200 bg-slate-100/70 px-4 py-2.5 text-sm font-bold text-slate-700 outline-none transition cursor-not-allowed">
                </div>

                <!-- Modal Footer -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeAddMachineModal()" class="rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-2xl bg-blue-600 px-5 py-2.5 text-xs font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 transition cursor-pointer">
                        Create Machine
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Client-side Scripts -->
    <script>
        let currentFilter = 'all';

        // Auto-generation logic for Add Machine
        function updateAutoMachineName() {
            const typeSelect = document.getElementById('machineType');
            const nameInput = document.getElementById('machineName');
            if (!typeSelect || !nameInput) return;

            const type = typeSelect.value === 'washer' ? 'Washer' : 'Dryer';
            const washerCount = {{ $machines->where('type', 'washer')->count() }};
            const dryerCount = {{ $machines->where('type', 'dryer')->count() }};
            const count = (typeSelect.value === 'washer' ? washerCount : dryerCount) + 1;
            const numStr = String(count).padStart(2, '0');

            nameInput.value = type + ' #' + numStr;
        }

        // Add Machine Modal controls
        function openAddMachineModal() {
            const modal = document.getElementById('addMachineModal');
            modal.classList.remove('hidden');
            updateAutoMachineName();
        }

        function closeAddMachineModal() {
            const modal = document.getElementById('addMachineModal');
            modal.classList.add('hidden');
        }

        // Machine Details, Edit & Delete Modal controls
        function openMachineModal(id, name, type, brand, model, status, branch, createdAt, activeReq) {
            const modal = document.getElementById('machineDetailsModal');
            if (!modal) return;

            // Update Header & Icons
            document.getElementById('modalMachineTitle').textContent = name;
            document.getElementById('modalMachineSubtitle').textContent = `${branch} · ID: #${String(id).padStart(3, '0')}`;

            const iconContainer = document.getElementById('modalMachineIcon');
            const isWasher = type.toLowerCase() === 'washer';
            if (isWasher) {
                iconContainer.className = 'flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-500/20';
                iconContainer.innerHTML = '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>';
            } else {
                iconContainer.className = 'flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-md shadow-amber-500/20';
                iconContainer.innerHTML = '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /></svg>';
            }

            // Status Badge
            const statusBadge = document.getElementById('modalMachineStatusBadge');
            const statusNorm = status.toLowerCase();
            const isAvail = statusNorm === 'available';
            const isInUse = statusNorm === 'in_use';

            if (isAvail) {
                statusBadge.className = 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200';
                statusBadge.textContent = 'AVAILABLE';
            } else if (isInUse) {
                statusBadge.className = 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200';
                statusBadge.textContent = 'IN_USE';
            } else {
                statusBadge.className = 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200';
                statusBadge.textContent = 'MAINTENANCE';
            }

            // Active Request Callout
            const reqContainer = document.getElementById('modalActiveReqContainer');
            if (activeReq) {
                reqContainer.classList.remove('hidden');
                document.getElementById('modalActiveReqText').textContent = `Order #REQ-${String(activeReq.id).padStart(4, '0')} · Customer: ${activeReq.customer}`;
                document.getElementById('modalActiveReqLink').href = activeReq.url;
            } else {
                reqContainer.classList.add('hidden');
            }

            // Quick Toggle Status Button (Available <-> Maintenance)
            const toggleBtn = document.getElementById('modalToggleStatusBtn');
            const toggleForm = document.getElementById('toggleMachineStatusForm');
            toggleForm.action = `/admin/machines/${id}/toggle-status`;

            if (isAvail) {
                toggleBtn.className = 'inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold shadow-xs transition cursor-pointer bg-amber-100 text-amber-900 hover:bg-amber-200 border border-amber-300';
                toggleBtn.innerHTML = '<span>🛠️ Mark as Maintenance</span>';
            } else {
                toggleBtn.className = 'inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold shadow-xs transition cursor-pointer bg-emerald-100 text-emerald-800 hover:bg-emerald-200 border border-emerald-300';
                toggleBtn.innerHTML = '<span>✅ Mark as Available</span>';
            }

            // Populate Edit Form
            const editForm = document.getElementById('editMachineForm');
            editForm.action = `/admin/machines/${id}`;
            document.getElementById('editMachineName').value = name;
            document.getElementById('editMachineType').value = type.toLowerCase();
            document.getElementById('editMachineBrand').value = brand;
            document.getElementById('editMachineModel').value = model;

            // Map status in select
            const statusSelect = document.getElementById('editMachineStatus');
            if (isAvail) {
                statusSelect.value = 'available';
            } else if (isInUse) {
                statusSelect.value = 'in_use';
            } else {
                statusSelect.value = 'maintenance';
            }

            // Populate Delete Form
            const deleteForm = document.getElementById('deleteMachineForm');
            deleteForm.action = `/admin/machines/${id}`;

            modal.classList.remove('hidden');
        }

        function closeMachineModal() {
            const modal = document.getElementById('machineDetailsModal');
            if (modal) modal.classList.add('hidden');
        }

        // Close modals on backdrop click & ESC key
        document.getElementById('addMachineModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeAddMachineModal();
        });

        document.getElementById('machineDetailsModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeMachineModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddMachineModal();
                closeMachineModal();
            }
        });

        // Client-side Search & Filter
        function filterMachines() {
            const query = document.getElementById('machineSearch').value.toLowerCase().trim();
            const cards = document.querySelectorAll('#machinesGrid .machine-card');

            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                const brand = card.getAttribute('data-brand') || '';
                const model = card.getAttribute('data-model') || '';
                const branch = card.getAttribute('data-branch') || '';
                const type = card.getAttribute('data-type') || '';
                const status = card.getAttribute('data-status') || '';
                const request = card.getAttribute('data-request') || '';

                const matchesSearch = name.includes(query) || brand.includes(query) || model.includes(query) || branch.includes(query) || request.includes(query);
                let matchesFilter = true;

                if (currentFilter === 'washer') matchesFilter = (type === 'washer');
                else if (currentFilter === 'dryer') matchesFilter = (type === 'dryer');
                else if (currentFilter === 'available') matchesFilter = (status === 'available');
                else if (currentFilter === 'in_use') matchesFilter = (status === 'in_use');
                else if (currentFilter === 'maintenance') matchesFilter = (status === 'maintenance');

                if (matchesSearch && matchesFilter) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }

        function setMachineFilter(filter, btn) {
            currentFilter = filter;
            document.querySelectorAll('.machine-filter-btn').forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
                b.classList.add('bg-slate-100/80', 'text-slate-600', 'hover:bg-slate-200/60');
            });

            btn.classList.remove('bg-slate-100/80', 'text-slate-600', 'hover:bg-slate-200/60');
            btn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');

            filterMachines();
        }

        @if(isset($errors) && $errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                openAddMachineModal();
            });
        @endif
    </script>
</x-admin-layout>
