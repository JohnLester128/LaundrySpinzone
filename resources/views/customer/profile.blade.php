<x-customer-layout>
    <x-slot:title>Profile — Spinzone Laundry</x-slot:title>

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Profile</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">Manage your account information, default laundry branch, and security credentials.</p>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    @php
        $customerUser = auth('customer')->user();
        $currentBranch = $customerUser->branch;
    @endphp

    <!-- 2-Column Responsive Layout -->
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

                    <!-- Action Button: Edit Phone -->
                    <button type="button" id="toggleEditBtn" onclick="toggleEditProfileMode()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl px-4 py-2.5 text-xs shadow-md shadow-blue-600/20 transition active:scale-[0.99] flex items-center gap-1.5 cursor-pointer">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span id="editBtnText">Edit Phone</span>
                    </button>
                </div>

                <!-- VIEW-ONLY PROFILE SECTION (Default) -->
                <div id="viewProfileSection" class="mt-6">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Username</p>
                            <p class="text-base font-bold text-slate-900 mt-1">{{ $customerUser->username }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Phone Number</p>
                            <p class="text-base font-bold text-slate-900 mt-1">{{ $customerUser->phone ?: 'Not specified' }}</p>
                        </div>
                    </div>
                </div>

                <!-- EDITABLE PROFILE FORM (Phone Number only editable) -->
                <form id="editProfileForm" action="{{ route('customer.profile.update') }}" method="POST" class="mt-6 space-y-5 hidden">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <!-- Fixed Username Display -->
                        <div class="rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4">
                            <div class="flex items-center justify-between">
                                <p class="font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-400">Username</p>
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-200/70 px-2 py-0.5 rounded-full">Permanent</span>
                            </div>
                            <p class="text-base font-bold text-slate-700 mt-1">{{ $customerUser->username }}</p>
                        </div>

                        <!-- Editable Phone Number -->
                        <div>
                            <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Phone Number
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $customerUser->phone) }}" placeholder="e.g. +63 912 345 6789" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
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
                            <span>Save Phone</span>
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
                                <h3 class="text-sm font-bold tracking-tight text-slate-900 group-hover:text-teal-700 transition">Password & Security</h3>
                                <p class="text-xs text-slate-400">Click to change your account password credentials.</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg id="passwordToggleIcon" class="h-4 w-4 text-slate-400 group-hover:text-slate-600 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <!-- Change Password Form (Hidden by default) -->
                    <form id="changePasswordForm" action="{{ route('customer.password.update') }}" method="POST" class="mt-4 rounded-2xl border border-teal-100 bg-teal-50/30 p-5 hidden transition-all">
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

                <!-- SECURITY QUESTION SUBSECTION (Clickable Accordion) -->
                <div id="securityQuestionSection" class="mt-4 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleSecurityQuestionForm()" class="w-full flex items-center justify-between group cursor-pointer text-left focus:outline-none p-2 -mx-2 rounded-2xl hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 shadow-2xs group-hover:bg-indigo-200 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-bold tracking-tight text-slate-900 group-hover:text-indigo-700 transition">Account Recovery Question</h3>
                                    @if($customerUser->security_question)
                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">Configured</span>
                                    @else
                                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-700">Not Set</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400">
                                    {{ $customerUser->security_question ?: 'Set a security question so you can reset your password if you forget it.' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg id="securityQuestionToggleIcon" class="h-4 w-4 text-slate-400 group-hover:text-slate-600 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <!-- Update Security Question Form (Hidden by default) -->
                    <form id="securityQuestionForm" action="{{ route('customer.security-question.update') }}" method="POST" class="mt-4 rounded-2xl border border-indigo-100 bg-indigo-50/30 p-5 hidden transition-all space-y-4">
                        @csrf
                        <div>
                            <label for="profileSecurityQuestion" class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                Security Question
                            </label>
                            <select id="profileSecurityQuestion" name="security_question" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 font-semibold focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition cursor-pointer">
                                <option value="" disabled {{ old('security_question', $customerUser->security_question) ? '' : 'selected' }}>Select a security question...</option>
                                @foreach(($securityQuestions ?? \App\Models\Customer::SECURITY_QUESTIONS) as $question)
                                    <option value="{{ $question }}" @selected(old('security_question', $customerUser->security_question) === $question)>
                                        {{ $question }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="profileSecurityAnswer" class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    New Security Answer
                                </label>
                                <input type="text" id="profileSecurityAnswer" name="security_answer" required autocomplete="off" placeholder="Enter your answer" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                            </div>

                            <div>
                                <label for="currentPasswordForSecurity" class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Current Password (To Confirm)
                                </label>
                                <input type="password" id="currentPasswordForSecurity" name="current_password_for_security" required placeholder="••••••••" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-indigo-100/60">
                            <button type="button" onclick="toggleSecurityQuestionForm(false)" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" class="border-2 border-indigo-600 text-indigo-700 hover:bg-indigo-600 hover:text-white font-semibold rounded-2xl px-5 py-2 text-xs transition active:scale-[0.99] cursor-pointer">
                                Save Recovery Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side: Compact Branch Card & Closely Positioned Logout (1 column) -->
        <div class="space-y-4">
            <!-- 1. Compact Branch Card (Non-clickable / Note) -->
            <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-blue-600"></div>

                <!-- Header with Branch Title and Assigned Badge -->
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100/80 text-blue-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold tracking-tight text-slate-900">Branch</h2>
                            <p class="text-[11px] font-medium text-slate-400">Your default laundry branch</p>
                        </div>
                    </div>

                    <!-- Toggle Edit Branch Button -->
                    <button type="button" onclick="toggleEditBranchMode()" id="toggleBranchBtn" class="flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition active:scale-[0.98] cursor-pointer">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span id="branchBtnText">Edit</span>
                    </button>
                </div>

                <!-- VIEW-ONLY BRANCH SECTION -->
                <div id="viewBranchSection" class="mt-4 space-y-2.5">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">Branch Name</p>
                        <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $currentBranch->name ?? 'No Branch Selected' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <p class="font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">Branch Address</p>
                        <p class="text-xs font-semibold text-slate-700 mt-0.5 leading-snug">{{ $currentBranch->address ?? 'Please select a branch' }}</p>
                    </div>
                </div>

                <!-- COMPACT EDITABLE BRANCH FORM (Hidden by default) -->
                <form id="editBranchForm" action="{{ route('customer.branch.update') }}" method="POST" class="mt-4 space-y-3 hidden">
                    @csrf
                    <div>
                        <label class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1">
                            Select Branch <span class="text-rose-500">*</span>
                        </label>
                        <select name="branch_id" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" @selected($customerUser->branch_id == $branch->id)>
                                    {{ $branch->name }} — {{ $branch->address }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Form Action Controls -->
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" onclick="toggleEditBranchMode(false)" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl px-4 py-1.5 text-xs shadow-xs transition active:scale-[0.99] cursor-pointer">
                            Save
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. Feedback & Complaints Card -->
            <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-blue-600"></div>

                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100/80 text-blue-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold tracking-tight text-slate-900 leading-tight">Feedback &amp; Complaints</h2>
                            <p class="text-[11px] font-medium text-slate-400">Share reviews or report issues</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 space-y-3">
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Share your laundry experience, suggest improvements, or report an operational issue directly to management.
                    </p>

                    @php
                        $userSubmissionsCount = \App\Models\Feedback::where('customer_id', $customerUser->id)->count();
                    @endphp

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3 flex items-center justify-between">
                        <span class="text-[11px] font-mono font-semibold uppercase tracking-wider text-slate-400">My Submissions</span>
                        <span class="text-xs font-bold text-slate-800 font-mono">
                            {{ $userSubmissionsCount }} {{ Str::plural('Entry', $userSubmissionsCount) }}
                        </span>
                    </div>

                    <a href="{{ route('customer.feedback') }}" class="w-full flex items-center justify-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 text-xs shadow-md shadow-blue-600/20 transition active:scale-[0.99] cursor-pointer">
                        <span>Open Feedback &amp; Complaints</span>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- 3. Logout Card (Directly below Feedback & Complaints) -->
            <form action="{{ route('customer.logout') }}" method="POST">
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
            
            const isEditing = forceState !== undefined ? forceState : editForm.classList.contains('hidden');

            if (isEditing) {
                viewSection.classList.add('hidden');
                editForm.classList.remove('hidden');
                editBtnText.textContent = 'Cancel';
            } else {
                editForm.classList.add('hidden');
                viewSection.classList.remove('hidden');
                editBtnText.textContent = 'Edit Phone';
            }
        }

        function toggleEditBranchMode(forceState) {
            const viewSection = document.getElementById('viewBranchSection');
            const editForm = document.getElementById('editBranchForm');
            const branchBtnText = document.getElementById('branchBtnText');
            
            const isEditing = forceState !== undefined ? forceState : editForm.classList.contains('hidden');

            if (isEditing) {
                viewSection.classList.add('hidden');
                editForm.classList.remove('hidden');
                branchBtnText.textContent = 'Cancel';
            } else {
                editForm.classList.add('hidden');
                viewSection.classList.remove('hidden');
                branchBtnText.textContent = 'Edit';
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

        function toggleSecurityQuestionForm(forceState) {
            const secForm = document.getElementById('securityQuestionForm');
            const icon = document.getElementById('securityQuestionToggleIcon');
            const isShowing = forceState !== undefined ? forceState : secForm.classList.contains('hidden');

            if (isShowing) {
                secForm.classList.remove('hidden');
                if (icon) icon.classList.add('rotate-180');
            } else {
                secForm.classList.add('hidden');
                if (icon) icon.classList.remove('rotate-180');
            }
        }

        @if($errors->has('phone'))
            toggleEditProfileMode(true);
        @endif

        @if($errors->has('branch_id'))
            toggleEditBranchMode(true);
        @endif

        @if($errors->has('current_password') || $errors->has('new_password'))
            togglePasswordForm(true);
        @endif

        @if($errors->has('security_question') || $errors->has('security_answer') || $errors->has('current_password_for_security'))
            toggleSecurityQuestionForm(true);
        @endif
    </script>
</x-customer-layout>
