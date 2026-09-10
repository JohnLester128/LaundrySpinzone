<x-admin-layout>
    <x-slot:title>Admin Profile - Laundry Spinzone</x-slot:title>
    <x-slot:header>Admin Profile</x-slot:header>

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Admin Profile</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">Manage your account settings and administrative preferences.</p>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Main Profile Grid (2 Columns on lg screens: 2 cols left, 1 col right) -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Left Side: Unified Profile Information Card (Spans 2 columns) -->
        <div class="lg:col-span-2">
            <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-blue-600"></div>

                <!-- Top Card Header & Action Buttons -->
                <div class="flex flex-wrap items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100/80 text-blue-600 shadow-2xs">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold tracking-tight text-slate-900">Profile Information</h2>
                            <p class="text-xs font-medium text-slate-400">Account login identity, branch details, and security.</p>
                        </div>
                    </div>

                    <!-- Action Button: Edit Profile Only -->
                    <div class="flex items-center gap-2.5">
                        <button type="button" id="toggleEditBtn" onclick="toggleEditMode()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl px-4 py-2.5 text-xs shadow-md shadow-blue-600/20 transition active:scale-[0.99] flex items-center gap-1.5 cursor-pointer">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span id="editBtnText">Edit Profile</span>
                        </button>
                    </div>
                </div>

                <!-- VIEW-ONLY SECTION (Default View) -->
                <div id="viewProfileSection" class="mt-6 space-y-6">
                    <!-- User Details -->
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Username</p>
                            <p class="text-base font-bold text-slate-900 mt-1">{{ auth('admin')->user()->username }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Phone Number</p>
                            <p class="text-base font-bold text-slate-900 mt-1">{{ auth('admin')->user()->phone ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    <!-- Branch Details -->
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Branch Location</span>
                        </p>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                                <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Branch Name</p>
                                <p class="text-base font-bold text-slate-900 mt-1">{{ $branch->name ?? 'Central Branch' }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                                <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Branch Address</p>
                                <p class="text-base font-bold text-slate-900 mt-1">{{ $branch->address ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDITABLE PROFILE FORM (Hidden by default, shown on clicking Edit Profile) -->
                <form id="editProfileForm" action="{{ route('admin.profile.update') }}" method="POST" class="mt-6 space-y-5 hidden">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Username
                            </label>
                            <input type="text" name="username" value="{{ old('username', auth('admin')->user()->username) }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>

                        <div>
                            <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Phone Number
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', auth('admin')->user()->phone) }}" placeholder="e.g. +63 912 345 6789" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Branch Details</p>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Branch Name
                                </label>
                                <input type="text" name="branch_name" value="{{ old('branch_name', $branch->name ?? '') }}" required placeholder="e.g. Central Branch" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                            </div>

                            <div>
                                <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Branch Address
                                </label>
                                <input type="text" name="branch_address" value="{{ old('branch_address', $branch->address ?? '') }}" required placeholder="e.g. 123 Main St, Suite 400" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                            </div>
                        </div>
                    </div>

                    <!-- Form Action Controls -->
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" onclick="toggleEditMode(false)" class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
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
                                <h3 class="text-sm font-bold tracking-tight text-slate-900 group-hover:text-teal-700 transition">Password</h3>
                                <p class="text-xs text-slate-400">Click to update your administrator password credentials.</p>
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

        <!-- Right Side: Administration + Settings (Opens in new tab) + Logout (1 column) -->
        <div class="space-y-6">
            <!-- 1. Administration Card -->
            <div class="rounded-3xl border border-blue-100 bg-blue-50/50 p-6 shadow-sm">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/20">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold tracking-tight text-blue-950">Administration</h2>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('admin.customers') }}" class="group flex items-center justify-between rounded-2xl border border-white bg-white p-4 shadow-xs hover:shadow-md transition">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-800">Customers</span>
                        </div>
                        <span class="text-slate-400 transition group-hover:text-blue-600 group-hover:translate-x-0.5">→</span>
                    </a>

                    <a href="{{ route('admin.login-logs') }}" class="group flex items-center justify-between rounded-2xl border border-white bg-white p-4 shadow-xs hover:shadow-md transition">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-800">Login Logs</span>
                        </div>
                        <span class="text-slate-400 transition group-hover:text-purple-600 group-hover:translate-x-0.5">→</span>
                    </a>
                </div>
            </div>

            <!-- 2. Standalone SETTINGS Card -->
            <a href="{{ route('admin.settings') }}" class="group flex items-center justify-between rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm hover:shadow-md hover:border-indigo-300 transition block cursor-pointer">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 shadow-2xs group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight text-slate-900 group-hover:text-indigo-600 transition">Settings</h2>
                        <p class="text-xs text-slate-400">Pricing, durations & add-ons</p>
                    </div>
                </div>
                <span class="text-slate-400 transition group-hover:text-indigo-600 group-hover:translate-x-0.5">→</span>
            </a>

            <!-- 3. Standalone Logout Card -->
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
        function toggleEditMode(forceState) {
            const viewSection = document.getElementById('viewProfileSection');
            const editForm = document.getElementById('editProfileForm');
            const editBtnText = document.getElementById('editBtnText');
            
            const isEditing = forceState !== undefined ? forceState : editForm.classList.contains('hidden');

            if (isEditing) {
                viewSection.classList.add('hidden');
                editForm.classList.remove('hidden');
                editBtnText.textContent = 'Cancel Edit';
            } else {
                editForm.classList.add('hidden');
                viewSection.classList.remove('hidden');
                editBtnText.textContent = 'Edit Profile';
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
        @if($errors->has('current_password') || $errors->has('new_password'))
            togglePasswordForm(true);
        @endif

        // Auto-expand edit profile form if there are profile error messages
        @if($errors->has('username') || $errors->has('phone') || $errors->has('branch_name') || $errors->has('branch_address'))
            toggleEditMode(true);
        @endif
    </script>
</x-admin-layout>
