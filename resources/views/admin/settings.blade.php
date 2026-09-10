<x-admin-layout>
    <x-slot:title>Settings - Laundry Spinzone</x-slot:title>
    <x-slot:header>Settings</x-slot:header>

    <div class="space-y-4 max-w-7xl mx-auto pb-6">
        <!-- Compact Page Header -->
        <div class="pb-2.5 border-b border-slate-200/60">
            <h1 class="text-xl font-extrabold tracking-tight text-slate-900">Service & Store Settings</h1>
            <p class="text-xs font-medium text-slate-500">Configure cycle durations, price lists, and add-on supplies.</p>
        </div>

        <!-- Notification Banner -->
        <x-notification />

        <!-- Main Settings Form with 2-Column Balanced Layout -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
            @csrf

            <!-- 2-Column Equal Grid (Left: Cycle Times + Addons stacked; Right: Price List matching full height) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-stretch">
                
                <!-- LEFT COLUMN: Cycle Times (Top) + Add-ons & Supplies (Below) -->
                <div class="space-y-4 flex flex-col justify-between">
                    
                    <!-- 1. Cycle Times by Load Weight -->
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-2xs">
                        <div class="flex items-center gap-2.5 pb-2.5 mb-2.5 border-b border-slate-100">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold font-mono">
                                1
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Cycle Times</h2>
                                <p class="text-[11px] text-slate-400">Operation mins by kg load</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                        <th class="pb-1.5 px-1 font-semibold">Weight</th>
                                        <th class="pb-1.5 px-1 text-center font-semibold">Wash</th>
                                        <th class="pb-1.5 px-1 text-center font-semibold">Dry</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($cycleTimes as $cycle)
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <td class="py-1 px-1 font-bold text-slate-800 whitespace-nowrap">
                                                <span class="inline-flex items-center justify-center h-6 w-11 rounded-md bg-indigo-50 text-indigo-700 font-mono text-[11px] font-bold border border-indigo-200/80">
                                                    {{ $cycle->kg }} kg
                                                </span>
                                            </td>
                                            <td class="py-1 px-1">
                                                <div class="relative mx-auto max-w-[85px]">
                                                    <input type="number" min="1" max="300" required name="cycle_times[{{ $cycle->id }}][washing_time_minutes]" value="{{ old('cycle_times.'.$cycle->id.'.washing_time_minutes', $cycle->washing_time_minutes) }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 pl-2 pr-6 py-0.5 text-xs font-bold text-slate-900 text-right focus:bg-white focus:border-blue-500 outline-none transition">
                                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-1.5 text-[10px] text-slate-400">m</span>
                                                </div>
                                            </td>
                                            <td class="py-1 px-1">
                                                <div class="relative mx-auto max-w-[85px]">
                                                    <input type="number" min="1" max="300" required name="cycle_times[{{ $cycle->id }}][drying_time_minutes]" value="{{ old('cycle_times.'.$cycle->id.'.drying_time_minutes', $cycle->drying_time_minutes) }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 pl-2 pr-6 py-0.5 text-xs font-bold text-slate-900 text-right focus:bg-white focus:border-amber-500 outline-none transition">
                                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-1.5 text-[10px] text-slate-400">m</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 3. Add-ons & Supplies (Placed Below Cycle Times) -->
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-2xs">
                        <div class="flex items-center gap-2.5 pb-2.5 mb-2.5 border-b border-slate-100">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-teal-100 text-teal-700 text-xs font-bold font-mono">
                                3
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Add-ons & Supplies</h2>
                                <p class="text-[11px] text-slate-400">Unit prices & active status</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            @foreach($addons as $addon)
                                <div class="rounded-xl border border-slate-100 bg-slate-50/60 px-3 py-2 flex items-center justify-between gap-3 hover:bg-white hover:border-teal-200 transition shadow-2xs">
                                    <span class="text-xs font-bold text-slate-800">{{ $addon->name }}</span>
                                    <div class="flex items-center gap-2.5 shrink-0">
                                        <div class="relative w-22">
                                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2 text-[11px] font-bold text-slate-400">₱</span>
                                            <input type="number" step="1" min="0" required name="addons[{{ $addon->id }}][price]" value="{{ old('addons.'.$addon->id.'.price', (int)$addon->price == $addon->price ? (int)$addon->price : $addon->price) }}" class="w-full rounded-lg border border-slate-200 bg-white pl-5 pr-2 py-1 text-xs font-bold text-slate-900 focus:border-teal-500 outline-none">
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer shrink-0" title="Toggle active">
                                            <input type="checkbox" name="addons[{{ $addon->id }}][is_active]" value="1" {{ $addon->is_active ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-7 h-4 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-teal-600"></div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Service Price List (Spaced out to match the total height of Left Column) -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-2xs flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center gap-2.5 pb-2.5 mb-3 border-b border-slate-100">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-700 text-xs font-bold font-mono">
                                2
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Service Price List</h2>
                                <p class="text-[11px] text-slate-400">Load categories & tier pricing</p>
                            </div>
                        </div>

                        <!-- Spacious Category Blocks -->
                        <div class="space-y-4 pt-1">
                            @foreach($clothTypes as $type)
                                <div class="rounded-xl border border-slate-200/70 bg-slate-50/50 p-3.5 space-y-2.5">
                                    <div class="flex items-center justify-between pb-1.5 border-b border-slate-200/60">
                                        <span class="text-xs font-black text-slate-900 uppercase tracking-wide">{{ $type->name }}</span>
                                        @if($type->requires_size)
                                            <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200">Per Piece</span>
                                        @else
                                            <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">By Weight Tier</span>
                                        @endif
                                    </div>

                                    <div class="space-y-2">
                                        @foreach($type->pricingTiers as $tier)
                                            <div class="rounded-lg border border-slate-200 bg-white px-3 py-2 flex items-center justify-between gap-3 shadow-2xs hover:border-blue-300 transition">
                                                <span class="text-xs font-bold text-slate-800">{{ $tier->name }}</span>
                                                <div class="relative w-26 shrink-0">
                                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2 text-xs font-bold text-slate-400">₱</span>
                                                    <input type="number" step="1" min="0" required name="tiers[{{ $tier->id }}][price]" value="{{ old('tiers.'.$tier->id.'.price', (int)$tier->price == $tier->price ? (int)$tier->price : $tier->price) }}" class="w-full rounded-md border border-slate-200 bg-slate-50/60 pl-6 pr-2 py-1 text-xs font-bold text-slate-900 text-right focus:bg-white focus:border-blue-500 outline-none transition">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Action Footer: Cancel next to Save Settings -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200/60">
                <a href="{{ route('admin.profile') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition shadow-2xs">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 text-xs shadow-sm shadow-blue-600/20 transition active:scale-[0.99] cursor-pointer">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Save Settings</span>
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
