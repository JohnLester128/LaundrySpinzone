<x-admin-layout>
    <x-slot:title>Branch Management - Laundry Spinzone</x-slot:title>
    <x-slot:header>Branch Management</x-slot:header>

    <div class="space-y-6 max-w-7xl mx-auto pb-10">
        <!-- Notification Banner -->
        <x-notification />

        <!-- Header Controls Row -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-3 border-b border-slate-200/70">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">Branch &amp; Manager Management</h1>
                <p class="text-xs text-slate-500 mt-1">Register new branch locations, assign branch managers with login credentials, and manage operations.</p>
            </div>
            <div>
                <button type="button" onclick="openAddBranchModal()" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-4 sm:px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-blue-600/25 hover:bg-blue-700 transition cursor-pointer active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden sm:inline">Add Branch &amp; Manager</span>
                    <span class="sm:hidden">Add Branch</span>
                </button>
            </div>
        </div>

        <!-- Navigation Tabs: Settings Hub -->
        <x-settings-tabs />

        <!-- Metrics Overview Row (Square on Mobile, Standard on Desktop) -->
        <div class="grid grid-cols-4 gap-2 sm:gap-4 lg:grid-cols-4">
            <!-- Total Branches -->
            <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-2 sm:p-4 lg:p-5 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between aspect-square md:aspect-auto">
                <div class="absolute top-0 inset-x-0 h-1 bg-indigo-600"></div>
                <!-- Header: Label + Icon -->
                <div class="flex items-center justify-between gap-1">
                    <p class="font-mono text-[9px] sm:text-[11px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">
                        <span class="sm:hidden">Branches</span>
                        <span class="hidden sm:inline">Total Branches</span>
                    </p>
                    <div class="flex h-5 w-5 sm:h-8 sm:w-8 lg:h-10 lg:w-10 items-center justify-center rounded-md sm:rounded-xl bg-indigo-600 text-white shadow-sm shadow-indigo-500/20 shrink-0">
                        <svg class="h-2.5 w-2.5 sm:h-4 sm:w-4 lg:h-5 lg:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <!-- Value -->
                <div class="my-auto sm:my-0 sm:mt-2 min-w-0">
                    <p class="text-sm min-[360px]:text-base sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-slate-900 truncate">
                        {{ $branches->count() }}
                    </p>
                </div>
                <!-- Mobile Status Indicator -->
                <div class="flex md:hidden items-center text-[8px] sm:text-[10px] font-bold text-indigo-600 truncate">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-indigo-500 mr-1 shrink-0"></span>
                    <span class="truncate">Stores</span>
                </div>
                <!-- Desktop Subtitle -->
                <div class="hidden md:flex mt-3 items-center text-xs font-medium text-slate-500">
                    <span class="inline-block h-2 w-2 rounded-full bg-indigo-500 mr-1.5"></span>
                    <span>Registered locations</span>
                </div>
            </div>

            <!-- Active Stores -->
            <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-2 sm:p-4 lg:p-5 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between aspect-square md:aspect-auto">
                <div class="absolute top-0 inset-x-0 h-1 bg-emerald-600"></div>
                <!-- Header: Label + Icon -->
                <div class="flex items-center justify-between gap-1">
                    <p class="font-mono text-[9px] sm:text-[11px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">
                        <span class="sm:hidden">Active</span>
                        <span class="hidden sm:inline">Active Stores</span>
                    </p>
                    <div class="flex h-5 w-5 sm:h-8 sm:w-8 lg:h-10 lg:w-10 items-center justify-center rounded-md sm:rounded-xl bg-emerald-600 text-white shadow-sm shadow-emerald-500/20 shrink-0">
                        <svg class="h-2.5 w-2.5 sm:h-4 sm:w-4 lg:h-5 lg:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <!-- Value -->
                <div class="my-auto sm:my-0 sm:mt-2 min-w-0">
                    <p class="text-sm min-[360px]:text-base sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-slate-900 truncate">
                        {{ $branches->where('is_active', true)->count() }}
                    </p>
                </div>
                <!-- Mobile Status Indicator -->
                <div class="flex md:hidden items-center text-[8px] sm:text-[10px] font-bold text-emerald-600 truncate">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500 mr-1 shrink-0"></span>
                    <span class="truncate">Online</span>
                </div>
                <!-- Desktop Subtitle -->
                <div class="hidden md:flex mt-3 items-center text-xs font-medium text-slate-500">
                    <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 mr-1.5"></span>
                    <span>Operational branches</span>
                </div>
            </div>

            <!-- Branch Managers -->
            <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-2 sm:p-4 lg:p-5 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between aspect-square md:aspect-auto">
                <div class="absolute top-0 inset-x-0 h-1 bg-blue-600"></div>
                <!-- Header: Label + Icon -->
                <div class="flex items-center justify-between gap-1">
                    <p class="font-mono text-[9px] sm:text-[11px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">
                        <span class="sm:hidden">Staff</span>
                        <span class="hidden sm:inline">Branch Managers</span>
                    </p>
                    <div class="flex h-5 w-5 sm:h-8 sm:w-8 lg:h-10 lg:w-10 items-center justify-center rounded-md sm:rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/20 shrink-0">
                        <svg class="h-2.5 w-2.5 sm:h-4 sm:w-4 lg:h-5 lg:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <!-- Value -->
                <div class="my-auto sm:my-0 sm:mt-2 min-w-0">
                    <p class="text-sm min-[360px]:text-base sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-slate-900 truncate">
                        {{ $branches->sum(fn($b) => $b->managers->count()) }}
                    </p>
                </div>
                <!-- Mobile Status Indicator -->
                <div class="flex md:hidden items-center text-[8px] sm:text-[10px] font-bold text-blue-600 truncate">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-500 mr-1 shrink-0"></span>
                    <span class="truncate">Assigned</span>
                </div>
                <!-- Desktop Subtitle -->
                <div class="hidden md:flex mt-3 items-center text-xs font-medium text-slate-500">
                    <span class="inline-block h-2 w-2 rounded-full bg-blue-500 mr-1.5"></span>
                    <span>Manager accounts</span>
                </div>
            </div>

            <!-- Total Machines -->
            <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-2 sm:p-4 lg:p-5 shadow-sm transition-all duration-200 hover:shadow-md flex flex-col justify-between aspect-square md:aspect-auto">
                <div class="absolute top-0 inset-x-0 h-1 bg-amber-500"></div>
                <!-- Header: Label + Icon -->
                <div class="flex items-center justify-between gap-1">
                    <p class="font-mono text-[9px] sm:text-[11px] lg:text-xs font-semibold uppercase tracking-wider text-slate-500 truncate">
                        <span class="sm:hidden">Units</span>
                        <span class="hidden sm:inline">Total Machines</span>
                    </p>
                    <div class="flex h-5 w-5 sm:h-8 sm:w-8 lg:h-10 lg:w-10 items-center justify-center rounded-md sm:rounded-xl bg-amber-500 text-white shadow-sm shadow-amber-500/20 shrink-0">
                        <svg class="h-2.5 w-2.5 sm:h-4 sm:w-4 lg:h-5 lg:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                </div>
                <!-- Value -->
                <div class="my-auto sm:my-0 sm:mt-2 min-w-0">
                    <p class="text-sm min-[360px]:text-base sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-slate-900 truncate">
                        {{ $branches->sum(fn($b) => $b->machines->count()) }}
                    </p>
                </div>
                <!-- Mobile Status Indicator -->
                <div class="flex md:hidden items-center text-[8px] sm:text-[10px] font-bold text-amber-600 truncate">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-amber-500 mr-1 shrink-0"></span>
                    <span class="truncate">Hardware</span>
                </div>
                <!-- Desktop Subtitle -->
                <div class="hidden md:flex mt-3 items-center text-xs font-medium text-slate-500">
                    <span class="inline-block h-2 w-2 rounded-full bg-amber-500 mr-1.5"></span>
                    <span>Branch laundry equipment</span>
                </div>
            </div>
        </div>

        <!-- Branch List Card & Table -->
        <div class="rounded-3xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
            <!-- Table Header -->
            <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/40">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Branch List</h2>
                    <p class="text-xs text-slate-500">All registered branches and their designated manager login accounts.</p>
                </div>
                <div class="text-xs font-semibold text-slate-500 font-mono">
                    Showing {{ $branches->count() }} locations
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200/80 bg-slate-50/70 text-[11px] font-mono font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5">Branch Name &amp; Address</th>
                            <th class="py-3.5 px-4">Assigned Manager</th>
                            <th class="py-3.5 px-4 text-center">Equipment</th>
                            <th class="py-3.5 px-4 text-center">Active Orders</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($branches as $branch)
                            <tr class="hover:bg-slate-50/70 transition">
                                <!-- Branch Name & Address -->
                                <td class="py-4 px-5">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-500 to-blue-600 text-white shadow-sm shadow-blue-500/20 font-bold text-sm">
                                            {{ strtoupper(substr($branch->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900 text-sm">{{ $branch->name }}</span>
                                                <span class="inline-flex items-center rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-500">ID: #{{ $branch->id }}</span>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-0.5 max-w-sm flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>{{ $branch->address ?: 'No address specified' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Assigned Managers -->
                                <td class="py-4 px-4">
                                    @php
                                        $managers = $branch->managers;
                                    @endphp
                                    @if($managers->count() > 0)
                                        <div class="space-y-1.5">
                                            @foreach($managers as $mgr)
                                                <div class="inline-flex items-center gap-2 rounded-xl border border-blue-100 bg-blue-50/70 px-2.5 py-1 text-xs text-blue-900 shadow-2xs">
                                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white text-[10px] font-bold">
                                                        {{ strtoupper(substr($mgr->username, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <span class="font-bold">{{ $mgr->username }}</span>
                                                        @if($mgr->phone)
                                                            <span class="text-[10px] text-slate-400 block -mt-0.5">{{ $mgr->phone }}</span>
                                                        @endif
                                                    </div>
                                                    <button type="button" onclick="openResetPasswordModal({{ $mgr->id }}, '{{ $mgr->username }}')" class="ml-1 text-[10px] font-bold text-blue-600 hover:text-blue-800 hover:underline cursor-pointer" title="Reset manager password">
                                                        Reset PW
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 border border-amber-200">
                                                No manager
                                            </span>
                                            <button type="button" onclick="openAddManagerModal({{ $branch->id }}, '{{ addslashes($branch->name) }}')" class="text-xs font-bold text-blue-600 hover:underline cursor-pointer">
                                                + Assign
                                            </button>
                                        </div>
                                    @endif
                                </td>

                                <!-- Equipment Units -->
                                <td class="py-4 px-4 text-center">
                                    @php
                                        $washersCount = $branch->machines->where('type', 'washer')->count();
                                        $dryersCount = $branch->machines->where('type', 'dryer')->count();
                                    @endphp
                                    <div class="inline-flex items-center gap-2 rounded-xl bg-slate-100/80 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                        <span title="Washers">🌀 {{ $washersCount }}W</span>
                                        <span class="text-slate-300">|</span>
                                        <span title="Dryers">💨 {{ $dryersCount }}D</span>
                                    </div>
                                </td>

                                <!-- Active Orders -->
                                <td class="py-4 px-4 text-center">
                                    @php
                                        $activeOrders = $branch->serviceRequests->whereIn('status', ['pending', 'received', 'washing', 'drying', 'ready_for_pickup', 'ready'])->count();
                                    @endphp
                                    <span class="inline-flex items-center justify-center rounded-full px-2.5 py-0.5 text-xs font-bold {{ $activeOrders > 0 ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $activeOrders }} active
                                    </span>
                                </td>

                                <!-- Status (Toggle) -->
                                <td class="py-4 px-4 text-center">
                                    <form action="{{ route('admin.branches.toggle-status', $branch) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold transition cursor-pointer {{ $branch->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}" title="Click to {{ $branch->is_active ? 'Deactivate' : 'Activate' }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $branch->is_active ? 'bg-emerald-600' : 'bg-slate-400' }}"></span>
                                            <span>{{ $branch->is_active ? 'Active' : 'Inactive' }}</span>
                                        </button>
                                    </form>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Add Another Manager -->
                                        <button type="button" onclick="openAddManagerModal({{ $branch->id }}, '{{ addslashes($branch->name) }}')" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition shadow-2xs cursor-pointer" title="Add another manager to branch">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                            </svg>
                                        </button>

                                        <!-- Edit Branch Details -->
                                        <button type="button" onclick="openEditBranchModal({{ $branch->id }}, '{{ addslashes($branch->name) }}', '{{ addslashes($branch->address ?? '') }}', {{ $branch->wash_buffer_minutes ?? 5 }})" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition shadow-2xs cursor-pointer" title="Edit branch name and address">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <!-- Delete Branch -->
                                        @if($branches->count() > 1)
                                            <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete branch \'{{ addslashes($branch->name) }}\'? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-600 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition shadow-2xs cursor-pointer" title="Delete branch">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                    No branches registered yet. Click "Add Branch &amp; Manager" above to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 1: ADD BRANCH & REGISTER MANAGER (The requested core feature)       -->
    <!-- ========================================================================= -->
    <div id="addBranchModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs transition-opacity">
        <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 sm:p-7 shadow-2xl border border-slate-200">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 font-bold">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Add New Branch</h2>
                        <p class="text-xs text-slate-400">Creates branch location &amp; registers manager account</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddBranchModal()" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.branches.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf

                <!-- Section A: Branch Details -->
                <div class="rounded-2xl border border-indigo-100 bg-indigo-50/30 p-4 space-y-3">
                    <span class="block font-mono text-[10px] font-bold uppercase tracking-wider text-indigo-700">
                        1. Branch Location Information
                    </span>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Branch Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="branch_name" required placeholder="e.g. Panay Branch, Baybay Branch" value="{{ old('branch_name') }}" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Branch Address <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="branch_address" required placeholder="e.g. Poblacion Ilaya, Panay, Capiz" value="{{ old('branch_address') }}" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>
                </div>

                <!-- Section B: Manager Register Details -->
                <div class="rounded-2xl border border-blue-100 bg-blue-50/30 p-4 space-y-3">
                    <span class="block font-mono text-[10px] font-bold uppercase tracking-wider text-blue-700">
                        2. Branch Manager Registration (Login Credentials)
                    </span>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Manager Username <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="manager_username" required placeholder="e.g. panay_manager" value="{{ old('manager_username') }}" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Contact Phone
                            </label>
                            <input type="text" name="manager_phone" placeholder="e.g. +63 912 345 6789" value="{{ old('manager_phone') }}" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="manager_password" required minlength="6" placeholder="Min 6 characters" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Confirm Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="manager_password_confirmation" required minlength="6" placeholder="Repeat password" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeAddBranchModal()" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2 text-xs shadow-md shadow-blue-600/25 transition active:scale-95 cursor-pointer flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Register Branch &amp; Manager</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: EDIT BRANCH DETAILS                                              -->
    <!-- ========================================================================= -->
    <div id="editBranchModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs transition-opacity">
        <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Edit Branch Details</h3>
                <button type="button" onclick="closeEditBranchModal()" class="rounded-xl p-1 text-slate-400 hover:bg-slate-100 transition cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="editBranchForm" method="POST" class="mt-4 space-y-3.5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Branch Name</label>
                    <input type="text" id="editBranchName" name="name" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Branch Address</label>
                    <input type="text" id="editBranchAddress" name="address" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Wash Buffer (mins)</label>
                    <input type="number" id="editBranchWashBuffer" name="wash_buffer_minutes" min="0" max="120" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 outline-none">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeEditBranchModal()" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2 text-xs shadow-xs transition active:scale-95 cursor-pointer">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 3: ADD MANAGER TO EXISTING BRANCH                                   -->
    <!-- ========================================================================= -->
    <div id="addManagerModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs transition-opacity">
        <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Add Manager Account</h3>
                    <p class="text-xs text-slate-400" id="addManagerBranchTitle">Branch</p>
                </div>
                <button type="button" onclick="closeAddManagerModal()" class="rounded-xl p-1 text-slate-400 hover:bg-slate-100 transition cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="addManagerForm" method="POST" class="mt-4 space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Manager Username</label>
                    <input type="text" name="username" required placeholder="e.g. manager_username" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number (Optional)</label>
                    <input type="text" name="phone" placeholder="e.g. +63 912 345 6789" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required minlength="6" placeholder="Min 6 chars" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required minlength="6" placeholder="Repeat" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 outline-none">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeAddManagerModal()" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2 text-xs shadow-xs transition active:scale-95 cursor-pointer">
                        Add Manager
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 4: RESET MANAGER PASSWORD                                           -->
    <!-- ========================================================================= -->
    <div id="resetPasswordModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs transition-opacity">
        <div class="relative w-full max-w-sm rounded-3xl bg-white p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Reset Password</h3>
                    <p class="text-xs text-slate-400" id="resetPasswordUsername">Manager</p>
                </div>
                <button type="button" onclick="closeResetPasswordModal()" class="rounded-xl p-1 text-slate-400 hover:bg-slate-100 transition cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="resetPasswordForm" method="POST" class="mt-4 space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">New Password</label>
                    <input type="password" name="password" required minlength="6" placeholder="Min 6 chars" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required minlength="6" placeholder="Repeat password" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-blue-500 outline-none">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeResetPasswordModal()" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2 text-xs shadow-xs transition active:scale-95 cursor-pointer">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modals -->
    <script>
        function openAddBranchModal() {
            const modal = document.getElementById('addBranchModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeAddBranchModal() {
            const modal = document.getElementById('addBranchModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openEditBranchModal(id, name, address, washBuffer) {
            const modal = document.getElementById('editBranchModal');
            document.getElementById('editBranchForm').action = `/admin/branches/${id}`;
            document.getElementById('editBranchName').value = name;
            document.getElementById('editBranchAddress').value = address;
            document.getElementById('editBranchWashBuffer').value = washBuffer;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditBranchModal() {
            const modal = document.getElementById('editBranchModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openAddManagerModal(branchId, branchName) {
            const modal = document.getElementById('addManagerModal');
            document.getElementById('addManagerForm').action = `/admin/branches/${branchId}/manager`;
            document.getElementById('addManagerBranchTitle').innerText = branchName;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeAddManagerModal() {
            const modal = document.getElementById('addManagerModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openResetPasswordModal(managerId, username) {
            const modal = document.getElementById('resetPasswordModal');
            document.getElementById('resetPasswordForm').action = `/admin/branches/managers/${managerId}/reset-password`;
            document.getElementById('resetPasswordUsername').innerText = `@${username}`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeResetPasswordModal() {
            const modal = document.getElementById('resetPasswordModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-admin-layout>
