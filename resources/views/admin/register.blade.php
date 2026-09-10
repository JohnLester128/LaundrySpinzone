<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Admin Account — Spinzone Laundry</title>
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
        <div class="absolute -bottom-32 left-1/4 h-96 w-96 rounded-full bg-purple-100/40 blur-3xl"></div>
    </div>

    <main class="w-full max-w-md relative z-10">
        <!-- Main Card -->
        <div class="relative overflow-hidden rounded-3xl bg-white/95 p-7 sm:p-9 shadow-xl shadow-slate-900/5 border border-slate-200/80 backdrop-blur-md">
            
            <!-- Top Gradient Bar -->
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>

            <!-- Logo & Brand Header -->
            <div class="flex flex-col items-center text-center">
                <!-- Admin Shield Icon Badge -->
                <div class="mb-3.5 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-tr from-slate-900 to-blue-900 text-white shadow-lg shadow-slate-900/20">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>

                <!-- Brand Title -->
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    Create <span class="text-blue-600">Admin</span>
                </h1>
                <p class="text-xs font-medium text-slate-400 mt-1">Register a new system administrator</p>

                <!-- Admin Badge -->
                <div class="mt-3.5 inline-flex items-center gap-1.5 rounded-full bg-slate-900 text-white px-3.5 py-1 text-[11px] font-bold font-mono tracking-wider">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                    <span>NEW STAFF ONBOARDING</span>
                </div>
            </div>

            <!-- Notifications -->
            <div class="mt-5">
                <x-notification />
            </div>

            <!-- Register Form -->
            <form class="mt-5 space-y-4" action="{{ route('admin.register.store') }}" method="POST">
                @csrf
                
                <!-- Username Input -->
                <div>
                    <label class="block font-mono text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                        Admin Username <span class="text-rose-500">*</span>
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
                            placeholder="e.g. admin_john" 
                            value="{{ old('username') }}" 
                            required 
                            autofocus
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
                            id="adminRegPasswordInput"
                            name="password" 
                            placeholder="At least 6 characters" 
                            required 
                            class="w-full rounded-2xl border border-slate-200 bg-white pl-10 pr-11 py-3 text-sm text-slate-900 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition shadow-2xs"
                        />
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility('adminRegPasswordInput', 'adminRegPassToggleIcon')" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition cursor-pointer"
                            title="Toggle password visibility"
                        >
                            <svg id="adminRegPassToggleIcon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="mt-2 w-full rounded-2xl bg-slate-900 hover:bg-slate-800 py-3.5 text-sm font-bold text-white shadow-md shadow-slate-900/20 transition duration-200 active:scale-[0.99] cursor-pointer flex items-center justify-center gap-2"
                >
                    <span>Register Admin</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Admin Login Link -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">Already registered as admin?</p>
                <a 
                    href="{{ route('admin.login') }}" 
                    class="mt-2 inline-flex items-center justify-center w-full rounded-2xl border border-slate-200 bg-slate-50/70 hover:bg-slate-100 py-3 text-xs font-bold text-slate-800 transition duration-200"
                >
                    Login to Admin Portal
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
    </script>
</body>
</html>
