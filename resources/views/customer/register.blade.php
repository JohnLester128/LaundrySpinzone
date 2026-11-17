<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Register — Spinzone Laundry</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 flex items-center justify-center p-4 sm:p-6 antialiased selection:bg-blue-500 selection:text-white relative overflow-x-hidden">

    <!-- Ambient Background Blobs -->
    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-blue-100/60 blur-3xl"></div>
        <div class="absolute top-1/3 -right-32 h-96 w-96 rounded-full bg-indigo-100/50 blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/4 h-96 w-96 rounded-full bg-teal-100/40 blur-3xl"></div>
    </div>

    <main class="w-full max-w-md relative z-10">
        <!-- Main Card -->
        <div class="relative overflow-hidden rounded-3xl bg-white/95 p-7 sm:p-9 shadow-xl shadow-slate-900/5 border border-slate-200/80 backdrop-blur-md">
            
            <!-- Top Gradient Bar -->
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-teal-500"></div>

            <!-- Logo & Brand Header -->
            <div class="flex flex-col items-center text-center">
                <!-- Spinzone Brand Logo -->
                <div class="mb-3.5 flex h-16 w-16 items-center justify-center rounded-2xl overflow-hidden shadow-lg shadow-blue-500/15 bg-white border border-slate-200/80 p-1">
                    <img src="{{ asset('images/spinzone-logo.png') }}" alt="Spinzone Laundry" class="h-full w-full object-contain rounded-xl">
                </div>

                <!-- Brand Title -->
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    Create <span class="text-blue-600">Account</span>
                </h1>
                <p class="text-xs font-medium text-slate-400 mt-1">Join Spinzone Laundry to book and track services</p>
            </div>

            <!-- Notifications -->
            <div class="mt-5">
                <x-notification />
            </div>

            <!-- Register Form -->
            <form class="mt-5 space-y-4" action="{{ route('customer.register.store') }}" method="POST">
                @csrf
                
                <!-- Username Input -->
                <div>
                    <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                        Username <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="username" 
                            placeholder="e.g. maria_santos" 
                            value="{{ old('username') }}" 
                            required 
                            autofocus
                            class="w-full rounded-2xl border border-slate-200 bg-white pl-10 pr-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs"
                        />
                    </div>
                </div>

                <!-- Phone Number Input -->
                <div>
                    <label for="registerPhoneInput" class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                        Phone Number <span class="text-slate-400 font-normal">(Optional)</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <input 
                            type="tel" 
                            id="registerPhoneInput"
                            name="phone" 
                            autocomplete="tel"
                            placeholder="e.g. +63 912 345 6789" 
                            value="{{ old('phone') }}" 
                            class="w-full rounded-2xl border border-slate-200 bg-white pl-10 pr-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs"
                        />
                    </div>
                </div>

                <!-- Security Question Dropdown -->
                <div>
                    <label for="registerSecurityQuestion" class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                        Security Question <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <select 
                            id="registerSecurityQuestion"
                            name="security_question" 
                            required
                            class="w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-10 pr-10 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs cursor-pointer"
                        >
                            <option value="" disabled {{ old('security_question') ? '' : 'selected' }}>Select a security question...</option>
                            @foreach(($securityQuestions ?? \App\Models\Customer::SECURITY_QUESTIONS) as $question)
                                <option value="{{ $question }}" @selected(old('security_question') === $question)>
                                    {{ $question }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Security Answer Input -->
                <div>
                    <label for="registerSecurityAnswer" class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                        Security Answer <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="registerSecurityAnswer"
                            name="security_answer" 
                            autocomplete="off"
                            placeholder="Your answer (used if you forget your password)" 
                            value="{{ old('security_answer') }}" 
                            required
                            class="w-full rounded-2xl border border-slate-200 bg-white pl-10 pr-4 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs"
                        />
                    </div>
                </div>

                <!-- Password Input with Toggle -->
                <div>
                    <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="registerPasswordInput"
                            name="password" 
                            placeholder="At least 6 characters" 
                            required 
                            minlength="6"
                            class="w-full rounded-2xl border border-slate-200 bg-white pl-10 pr-11 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs"
                        />
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility('registerPasswordInput', 'regPasswordToggleIcon')" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition cursor-pointer"
                            title="Toggle password visibility"
                        >
                            <svg id="regPasswordToggleIcon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password Input with Toggle -->
                <div>
                    <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                        Confirm Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="registerPasswordConfirmInput"
                            name="password_confirmation" 
                            placeholder="Repeat your password" 
                            required 
                            minlength="6"
                            class="w-full rounded-2xl border border-slate-200 bg-white pl-10 pr-11 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs"
                        />
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility('registerPasswordConfirmInput', 'regPasswordConfirmToggleIcon')" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition cursor-pointer"
                            title="Toggle password visibility"
                        >
                            <svg id="regPasswordConfirmToggleIcon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="mt-2 w-full rounded-2xl bg-blue-600 hover:bg-blue-700 py-3.5 text-sm font-bold text-white shadow-md shadow-blue-600/20 transition duration-200 active:scale-[0.99] cursor-pointer flex items-center justify-center gap-2"
                >
                    <span>Create Account</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Login Footer Link -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">Already registered?</p>
                <a 
                    href="{{ route('customer.login') }}" 
                    class="mt-2 inline-flex items-center justify-center w-full rounded-2xl border border-slate-200 bg-slate-50/70 hover:bg-slate-100 py-3 text-xs font-bold text-slate-800 transition duration-200"
                >
                    Login to Existing Account
                </a>
            </div>

        </div>
    </main>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                }
            }
        }

        const passInput = document.getElementById('registerPasswordInput');
        const confirmInput = document.getElementById('registerPasswordConfirmInput');
        function validatePasswordMatch() {
            if (confirmInput.value && passInput.value !== confirmInput.value) {
                confirmInput.setCustomValidity("Passwords do not match");
            } else {
                confirmInput.setCustomValidity("");
            }
        }
        if (passInput && confirmInput) {
            passInput.addEventListener('input', validatePasswordMatch);
            confirmInput.addEventListener('input', validatePasswordMatch);
        }
    </script>
</body>
</html>
