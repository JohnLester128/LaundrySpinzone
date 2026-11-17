<x-admin-layout>
    @php
        $adminUser = auth('admin')->user();
        $isOwner = $adminUser?->isSuperAdmin() ?? false;
    @endphp

    <x-slot:title>{{ $isOwner ? 'Owner Profile' : 'Manager Profile' }} - Laundry Spinzone</x-slot:title>
    <x-slot:header>{{ $isOwner ? 'Owner Profile' : 'Manager Profile' }}</x-slot:header>

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">{{ $isOwner ? 'Owner Profile' : 'Manager Profile' }}</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">{{ $isOwner ? 'Manage your account settings and security credentials.' : 'View your account information and manage your password.' }}</p>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- 2-Column Responsive Layout (Identical to Customer Profile) -->
    <div class="grid gap-6 lg:grid-cols-3 items-start">
        <!-- Left Side: Profile Information Card (Spans 2 columns) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-blue-600"></div>

                <!-- Header & Action Button -->
                <div class="flex flex-wrap items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100/80 text-blue-600 shadow-2xs">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold tracking-tight text-slate-900">Profile Information</h2>
                            <p class="text-xs font-medium text-slate-400">Account login identity, contact phone number, and security.</p>
                        </div>
                    </div>

                    @if($isOwner)
                        <!-- Action Button: Edit Profile (Owner Only) -->
                        <button type="button" id="toggleEditBtn" onclick="toggleEditProfileMode()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl px-4 py-2.5 text-xs shadow-md shadow-blue-600/20 transition active:scale-[0.99] flex items-center gap-1.5 cursor-pointer">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span id="editBtnText">Edit Profile</span>
                        </button>
                    @endif
                </div>

                <!-- VIEW-ONLY PROFILE SECTION (Default) -->
                <div id="viewProfileSection" class="mt-6">
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Username</p>
                            <p class="text-base font-bold text-slate-900 mt-1">{{ $adminUser->username }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Phone Number</p>
                            <p class="text-base font-bold text-slate-900 mt-1">{{ $adminUser->phone ?: 'Not specified' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">System Role</p>
                            <div class="mt-1 flex items-center">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-bold {{ $adminUser->getRoleBadgeClasses() }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $isOwner ? 'bg-purple-500' : 'bg-blue-500' }}"></span>
                                    {{ $adminUser->getRoleBadgeLabel() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($isOwner)
                    <!-- EDITABLE PROFILE FORM (Owner Only) -->
                    <form id="editProfileForm" action="{{ route('admin.profile.update') }}" method="POST" class="mt-6 space-y-5 hidden">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Username
                                </label>
                                <input type="text" name="username" value="{{ old('username', $adminUser->username) }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                            </div>

                            <div>
                                <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Phone Number
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $adminUser->phone) }}" placeholder="e.g. +63 912 345 6789" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                            </div>
                        </div>

                        <!-- Form Action Controls -->
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button" onclick="toggleEditProfileMode(false)" class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl px-6 py-2.5 text-xs shadow-md shadow-blue-600/20 transition active:scale-[0.99] flex items-center gap-1.5 cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                @endif

                <!-- PASSWORD SUBSECTION (Clickable Accordion) -->
                <div id="securitySection" class="mt-6 pt-6 border-t border-slate-100">
                    <button type="button" onclick="togglePasswordForm()" class="w-full flex items-center justify-between group cursor-pointer text-left focus:outline-none p-2 -mx-2 rounded-2xl hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-teal-100 text-teal-600 shadow-2xs group-hover:bg-teal-200 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold tracking-tight text-slate-900 group-hover:text-teal-700 transition">Password & Security</h3>
                                <p class="text-xs text-slate-400">Click to change your account password.</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg id="passwordToggleIcon" class="h-4 w-4 text-slate-400 group-hover:text-slate-600 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <!-- Change Password Form (Hidden by default, toggled via Password button) -->
                    <form id="changePasswordForm" action="{{ route('admin.password.update') }}" method="POST" class="mt-4 rounded-2xl border border-teal-100 bg-teal-50/30 p-5 hidden transition-all">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Current Password
                                </label>
                                <input type="password" name="current_password" required placeholder="••••••••" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none transition">
                            </div>

                            <div>
                                <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    New Password
                                </label>
                                <input type="password" name="new_password" required placeholder="••••••••" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none transition">
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-3 pt-3 border-t border-teal-100/60">
                            <button type="button" onclick="togglePasswordForm(false)" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" class="border-2 border-teal-600 text-teal-700 hover:bg-teal-600 hover:text-white font-semibold rounded-2xl px-5 py-2 text-xs transition active:scale-[0.99] cursor-pointer">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side: Branch Card (Managers only) & Logout -->
        <div class="space-y-4">
            @if(!$isOwner)
                <!-- 1. Compact Branch Card for Branch Managers (View Only) -->
                <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-indigo-600"></div>

                    <!-- Header with Branch Title -->
                    <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100/80 text-indigo-600 shadow-2xs">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold tracking-tight text-slate-900">Assigned Branch</h2>
                                <p class="text-[11px] text-slate-400">Branch assigned by Store Owner</p>
                            </div>
                        </div>
                    </div>

                    <!-- VIEW-ONLY BRANCH SECTION -->
                    <div id="viewBranchSection" class="mt-4 space-y-2.5">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                            <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">Branch Name</p>
                            <p class="text-sm font-bold text-slate-900 mt-0.5">
                                {{ $branch->name ?? 'Central Branch' }}
                            </p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                            <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">Branch Location</p>
                            <p class="text-xs font-semibold text-slate-700 mt-0.5 leading-snug">
                                {{ $branch->address ?? 'Not specified' }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Owner Role Card (No branches) -->
                <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-purple-600"></div>
                    <div class="flex items-center gap-2.5 pb-3.5 border-b border-slate-100">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-purple-100/80 text-purple-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold tracking-tight text-slate-900">System Owner</h2>
                            <p class="text-[11px] text-slate-400">Full administrative access</p>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2.5">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                            <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">Account Access</p>
                            <p class="text-xs font-bold text-slate-900 mt-0.5">Global System Administrator</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 2. Logout Card -->
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full border border-rose-200/80 bg-rose-50/40 hover:bg-rose-100/60 text-rose-600 hover:text-rose-700 font-semibold rounded-2xl py-3.5 px-4 text-sm flex items-center justify-center gap-2 transition active:scale-[0.99] cursor-pointer shadow-2xs">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Toggle Scripts -->
    <script>
        function toggleEditProfileMode(forceState) {
            const viewSection = document.getElementById('viewProfileSection');
            const editForm = document.getElementById('editProfileForm');
            const editBtnText = document.getElementById('editBtnText');
            if (!editForm) return;
            
            const isEditing = forceState !== undefined ? forceState : editForm.classList.contains('hidden');

            if (isEditing) {
                viewSection.classList.add('hidden');
                editForm.classList.remove('hidden');
                if (editBtnText) editBtnText.textContent = 'Cancel';
            } else {
                editForm.classList.add('hidden');
                viewSection.classList.remove('hidden');
                if (editBtnText) editBtnText.textContent = 'Edit Profile';
            }
        }

        function togglePasswordForm(forceState) {
            const passForm = document.getElementById('changePasswordForm');
            const icon = document.getElementById('passwordToggleIcon');
            const isShowing = forceState !== undefined ? forceState : passForm.classList.contains('hidden');

            if (isShowing) {
                passForm.classList.remove('hidden');
                if (icon) icon.classList.add('rotate-180');
            } else {
                passForm.classList.add('hidden');
                if (icon) icon.classList.remove('rotate-180');
            }
        }

        // Auto-expand password form if there are password error messages
        @if(($errors ?? false) && ($errors->has('current_password') || $errors->has('new_password')))
            togglePasswordForm(true);
        @endif

        // Auto-expand edit profile form if there are profile error messages (Owner Only)
        @if($isOwner && ($errors ?? false) && ($errors->has('username') || $errors->has('phone')))
            toggleEditProfileMode(true);
        @endif
    </script>
</x-admin-layout>
