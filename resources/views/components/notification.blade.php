<div class="flash-alert-container">
    @if (session('success'))
        <div class="flash-alert mb-5 flex items-center justify-between gap-3 rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-xs font-bold text-emerald-800 shadow-2xs transition-all duration-400 ease-out">
            <div class="flex items-center gap-3">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white font-extrabold text-xs">✓</span>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="window.dismissFlashAlert(this.closest('.flash-alert'))" class="text-emerald-600 hover:text-emerald-900 transition p-1 rounded-lg hover:bg-emerald-100/70 cursor-pointer" title="Dismiss">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="flash-alert mb-5 flex items-center justify-between gap-3 rounded-2xl bg-rose-50 p-4 border border-rose-200 text-xs font-bold text-rose-800 shadow-2xs transition-all duration-400 ease-out">
            <div class="flex items-center gap-3">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-600 text-white font-extrabold text-xs">✕</span>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="window.dismissFlashAlert(this.closest('.flash-alert'))" class="text-rose-600 hover:text-rose-900 transition p-1 rounded-lg hover:bg-rose-100/70 cursor-pointer" title="Dismiss">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('warning'))
        <div class="flash-alert mb-5 flex items-center justify-between gap-3 rounded-2xl bg-amber-50 p-4 border border-amber-200 text-xs font-bold text-amber-800 shadow-2xs transition-all duration-400 ease-out">
            <div class="flex items-center gap-3">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-600 text-white font-extrabold text-xs">!</span>
                <span>{{ session('warning') }}</span>
            </div>
            <button type="button" onclick="window.dismissFlashAlert(this.closest('.flash-alert'))" class="text-amber-600 hover:text-amber-900 transition p-1 rounded-lg hover:bg-amber-100/70 cursor-pointer" title="Dismiss">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="flash-alert mb-5 rounded-2xl bg-rose-50 p-4 border border-rose-200 text-xs font-semibold text-rose-800 shadow-2xs transition-all duration-400 ease-out">
            <div class="flex items-center justify-between gap-2 font-bold mb-1.5">
                <div class="flex items-center gap-2">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-rose-600 text-white text-[10px]">✕</span>
                    <span>Please check the following errors:</span>
                </div>
                <button type="button" onclick="window.dismissFlashAlert(this.closest('.flash-alert'))" class="text-rose-600 hover:text-rose-900 transition p-1 rounded-lg hover:bg-rose-100/70 cursor-pointer" title="Dismiss">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <ul class="list-disc list-inside space-y-1 text-rose-700 pl-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<script>
    (function() {
        window.dismissFlashAlert = function(el) {
            if (!el || el.dataset.dismissing) return;
            el.dataset.dismissing = 'true';
            el.style.transition = 'all 0.4s ease-out';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => {
                el.style.maxHeight = '0';
                el.style.marginBottom = '0';
                el.style.paddingTop = '0';
                el.style.paddingBottom = '0';
                el.style.overflow = 'hidden';
                setTimeout(() => el.remove(), 400);
            }, 300);
        };

        function scheduleAlertDismissals() {
            document.querySelectorAll('.flash-alert:not([data-timer-set])').forEach(alert => {
                alert.dataset.timerSet = 'true';
                setTimeout(() => {
                    window.dismissFlashAlert(alert);
                }, 5000);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', scheduleAlertDismissals);
        } else {
            scheduleAlertDismissals();
        }
    })();
</script>