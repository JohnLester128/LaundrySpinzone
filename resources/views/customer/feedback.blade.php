<x-customer-layout>
    <x-slot:title>Feedback & Complaints — Spinzone Laundry</x-slot:title>

    <!-- Page Header -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Feedback &amp; Complaints</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">
                Share your experience, suggest improvements, or report an operational issue so our team can assist you.
            </p>
        </div>
        <div class="flex items-center gap-2 rounded-2xl border border-blue-100 bg-blue-50/70 px-4 py-2 text-xs font-bold text-blue-700 shadow-2xs">
            <span>Your Voice Helps Us Improve</span>
        </div>
    </div>

    <!-- Notification Banner -->
    <x-notification />

    <!-- Main Grid: Submission Form (Left) & History (Right) -->
    <div class="grid gap-8 lg:grid-cols-12">
        <!-- Left: Submit Feedback or Complaint Form (7 cols) -->
        <div class="lg:col-span-7">
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
                <!-- Header -->
                <div class="flex items-center gap-3 border-b border-slate-100 pb-5 mb-5">
                    <div id="formIconContainer" class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs transition-colors">
                        <svg id="formIconFeedback" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <svg id="formIconComplaint" class="h-5 w-5 hidden text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 id="formHeading" class="text-lg font-bold text-slate-900">Share Feedback</h2>
                        <p id="formSubheading" class="text-xs text-slate-500 mt-0.5">Please rate your experience and provide your thoughts</p>
                    </div>
                </div>

                <!-- Mode Switcher: Feedback vs Complaint (No star or triangle icons) -->
                <div class="mb-6 p-1 bg-slate-100/90 rounded-2xl flex items-center gap-1 border border-slate-200/60">
                    <button 
                        type="button" 
                        id="tabFeedbackBtn" 
                        onclick="switchNature('feedback')" 
                        class="flex-1 flex items-center justify-center py-2.5 px-3 rounded-xl text-xs font-bold transition-all shadow-xs bg-white text-blue-700 cursor-pointer"
                    >
                        <span>Feedback</span>
                    </button>
                    <button 
                        type="button" 
                        id="tabComplaintBtn" 
                        onclick="switchNature('complaint')" 
                        class="flex-1 flex items-center justify-center py-2.5 px-3 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 cursor-pointer"
                    >
                        <span>Complaint</span>
                    </button>
                </div>

                <form action="{{ route('customer.feedback.store') }}" method="POST" id="feedbackForm" class="space-y-6">
                    @csrf
                    <input type="hidden" name="nature" id="natureInput" value="{{ old('nature', 'feedback') }}">

                    <!-- ============================================== -->
                    <!-- FEEDBACK SPECIFIC: Category Radio Selection -->
                    <!-- ============================================== -->
                    <div id="feedbackCategorySection">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-2.5">
                            Category <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-2.5 sm:gap-3">
                            <label class="relative flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-slate-200/90 p-3.5 text-center transition hover:border-blue-300 has-checked:border-blue-600 has-checked:bg-blue-50/50 has-checked:shadow-2xs">
                                <input type="radio" name="type" value="service" class="sr-only feedback-type-radio" {{ old('type', 'service') === 'service' ? 'checked' : '' }} onchange="handleTypeChange('service')">
                                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100/70 text-blue-600 mb-1.5">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-900">Laundry Service</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Wash, dry, staff</span>
                            </label>

                            <label class="relative flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-slate-200/90 p-3.5 text-center transition hover:border-blue-300 has-checked:border-blue-600 has-checked:bg-blue-50/50 has-checked:shadow-2xs">
                                <input type="radio" name="type" value="app" class="sr-only feedback-type-radio" {{ old('type') === 'app' ? 'checked' : '' }} onchange="handleTypeChange('app')">
                                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-purple-100/70 text-purple-600 mb-1.5">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-900">App &amp; Website</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Features &amp; UI</span>
                            </label>

                            <label class="relative flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-slate-200/90 p-3.5 text-center transition hover:border-blue-300 has-checked:border-blue-600 has-checked:bg-blue-50/50 has-checked:shadow-2xs">
                                <input type="radio" name="type" value="general" class="sr-only feedback-type-radio" {{ old('type') === 'general' ? 'checked' : '' }} onchange="handleTypeChange('general')">
                                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-teal-100/70 text-teal-600 mb-1.5">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-900">General</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Other inquiries</span>
                            </label>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- COMPLAINT SPECIFIC: Category Radio Selection -->
                    <!-- ============================================== -->
                    <div id="complaintCategorySection" class="hidden">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-2.5">
                            Issue Category <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                            <label class="relative flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-slate-200/90 p-3 text-center transition hover:border-rose-300 has-checked:border-rose-500 has-checked:bg-rose-50/50 has-checked:shadow-2xs">
                                <input type="radio" name="complaint_type_radio" value="damaged_item" class="sr-only" checked onchange="handleComplaintTypeChange('damaged_item')">
                                <span class="text-xs font-bold text-slate-900">Damaged Clothes</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Torn, stained, color run</span>
                            </label>

                            <label class="relative flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-slate-200/90 p-3 text-center transition hover:border-rose-300 has-checked:border-rose-500 has-checked:bg-rose-50/50 has-checked:shadow-2xs">
                                <input type="radio" name="complaint_type_radio" value="missing_clothes" class="sr-only" onchange="handleComplaintTypeChange('missing_clothes')">
                                <span class="text-xs font-bold text-slate-900">Missing Items</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Lost or incomplete batch</span>
                            </label>

                            <label class="relative flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-slate-200/90 p-3 text-center transition hover:border-rose-300 has-checked:border-rose-500 has-checked:bg-rose-50/50 has-checked:shadow-2xs">
                                <input type="radio" name="complaint_type_radio" value="staff_delay" class="sr-only" onchange="handleComplaintTypeChange('staff_delay')">
                                <span class="text-xs font-bold text-slate-900">Staff Delay</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Delayed pickup or service</span>
                            </label>

                            <label class="relative flex cursor-pointer flex-col items-center justify-center rounded-2xl border border-slate-200/90 p-3 text-center transition hover:border-rose-300 has-checked:border-rose-500 has-checked:bg-rose-50/50 has-checked:shadow-2xs">
                                <input type="radio" name="complaint_type_radio" value="other" class="sr-only" onchange="handleComplaintTypeChange('other')">
                                <span class="text-xs font-bold text-slate-900">Other Issue</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Branch environment or general issue</span>
                            </label>
                        </div>

                        <!-- In-Store Visit Tip / Notice -->
                        <div class="mt-3.5 rounded-2xl border border-amber-200/90 bg-amber-50/70 p-3.5 text-amber-900 shadow-2xs">
                            <div class="flex items-start gap-3">
                                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 mt-0.5 shadow-2xs">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="text-xs">
                                    <p class="font-bold text-amber-950">In-Store Visit Recommended</p>
                                    <p class="text-amber-800/90 mt-0.5 leading-relaxed">
                                        If your issue requires physical inspection—such as damaged garments or missing items—please visit the shop directly with your order receipt so our staff can assist you immediately.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- SHARED: Linked Service Request (Optional) -->
                    <!-- ============================================== -->
                    <div id="serviceRequestWrapper">
                        <label for="service_request_id" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Order <span class="text-slate-400 font-normal lowercase">(optional)</span>
                        </label>
                        <select name="service_request_id" id="service_request_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                            <option value="">-- No specific order linked --</option>
                            @foreach($recentRequests as $req)
                                <option value="{{ $req->id }}" {{ (string)$selectedRequestId === (string)$req->id ? 'selected' : '' }}>
                                    #REQ-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }} · {{ $req->clothType->name ?? 'Laundry' }} ({{ $req->created_at->format('M d, Y') }}) — {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                </option>
                            @endforeach
                        </select>
                        <p id="orderHelperText" class="text-[11px] text-slate-400 mt-1">If this submission is about a recent laundry batch, select it to help staff trace the order.</p>
                    </div>

                    <!-- ============================================== -->
                    <!-- FEEDBACK SPECIFIC: Star Rating -->
                    <!-- ============================================== -->
                    <div id="starRatingSection">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Rating <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-1.5" id="starContainer">
                            @for($i = 1; $i <= 5; $i++)
                                <button 
                                    type="button" 
                                    class="star-btn p-1 text-slate-200 hover:scale-110 active:scale-95 transition cursor-pointer" 
                                    data-rating="{{ $i }}"
                                    onclick="setRating({{ $i }})"
                                    onmouseenter="hoverRating({{ $i }})"
                                    onmouseleave="resetHover()"
                                >
                                    <svg class="h-8 w-8 transition-colors duration-150 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 5) }}">
                        @error('rating')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ============================================== -->
                    <!-- FEEDBACK SPECIFIC: Quick Aspects Pills -->
                    <!-- ============================================== -->
                    <div id="aspectsSection">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Aspects <span class="text-slate-400 font-normal lowercase">(click to select)</span>
                        </label>
                        <div class="flex flex-wrap gap-2" id="aspectPills">
                            @php
                                $aspectOptions = [
                                    'Cleanliness & Freshness',
                                    'Fast Turnaround',
                                    'Neat Folding & Packaging',
                                    'Staff Politeness',
                                    'Easy Online Booking',
                                    'Order Tracking Accuracy',
                                    'Fair Pricing',
                                ];
                            @endphp
                            @foreach($aspectOptions as $aspect)
                                <label class="cursor-pointer inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50/70 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-100 has-checked:border-blue-500 has-checked:bg-blue-50 has-checked:text-blue-700 has-checked:font-semibold">
                                    <input type="checkbox" name="aspects[]" value="{{ $aspect }}" class="sr-only">
                                    <span>{{ $aspect }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- SHARED: Summary (1 Word) -->
                    <!-- ============================================== -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="subject" id="subjectLabel" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500">
                                Summary <span class="text-slate-400 font-normal lowercase">(optional)</span>
                            </label>
                            <span id="subjectCount" class="text-[11px] font-mono text-slate-400">0 / 60</span>
                        </div>
                        <input 
                            type="text" 
                            name="subject" 
                            id="subject" 
                            value="{{ old('subject') }}"
                            placeholder="e.g., Fresh scent and fast delivery" 
                            maxlength="60"
                            oninput="updateCharCount('subject', 'subjectCount', 60)"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition"
                        >
                        @error('subject')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ============================================== -->
                    <!-- SHARED: Details (1 Word) -->
                    <!-- ============================================== -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="comment" id="commentLabel" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-500">
                                Details <span class="text-slate-400 font-normal lowercase">(optional)</span>
                            </label>
                            <span id="commentCount" class="text-[11px] font-mono text-slate-400">0 / 300</span>
                        </div>
                        <textarea 
                            name="comment" 
                            id="comment" 
                            rows="4" 
                            maxlength="500"
                            placeholder="Please share what you enjoyed, or where we can do better..." 
                            oninput="updateCharCount('comment', 'commentCount', currentNature === 'complaint' ? 500 : 300)"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 p-4 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition resize-y"
                        >{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ============================================== -->
                    <!-- ANONYMOUS OPTION -->
                    <!-- ============================================== -->
                    <div class="rounded-2xl border border-slate-200/80 bg-slate-50/70 p-3.5 transition hover:bg-slate-100/60">
                        <label class="flex items-center justify-between cursor-pointer select-none">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 shadow-2xs">
                                    <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-slate-800">Submit Anonymously</span>
                                    <span class="block text-[11px] text-slate-400">Keep your identity and customer ID private from staff</span>
                                </div>
                            </div>
                            <input type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }} class="h-4 w-4 rounded-md border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" id="submitBtn" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-blue-500/20 hover:bg-blue-700 transition active:scale-[0.98] cursor-pointer">
                            <svg id="submitBtnFeedbackIcon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            <svg id="submitBtnComplaintIcon" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span id="submitBtnText">Submit Feedback</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Past Submissions History (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">My Submissions</h2>
                        <p class="text-xs text-slate-400">Previous feedback &amp; complaints</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600 font-mono">
                        {{ $feedbacks->count() }} Total
                    </span>
                </div>

                @if($feedbacks->count() > 0)
                    <div class="space-y-4">
                        @foreach($feedbacks as $fb)
                            <div class="feedback-item rounded-2xl border border-slate-100 bg-slate-50/60 p-4 transition hover:bg-slate-50">
                                <!-- Top Row: Nature + Status Badges -->
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    @if($fb->isComplaint())
                                        <!-- Complaint Badges -->
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wide bg-rose-50 text-rose-700 border border-rose-200">
                                                Complaint
                                            </span>
                                            
                                            <!-- Status Badge -->
                                            @php
                                                $statusClass = match($fb->status) {
                                                    'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                    'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'closed' => 'bg-slate-100 text-slate-600 border-slate-200',
                                                    default => 'bg-amber-50 text-amber-700 border-amber-200',
                                                };
                                                $statusDot = match($fb->status) {
                                                    'in_progress' => 'bg-blue-500 animate-pulse',
                                                    'resolved' => 'bg-emerald-500',
                                                    'closed' => 'bg-slate-400',
                                                    default => 'bg-amber-500 animate-pulse',
                                                };
                                            @endphp
                                            <span id="customerStatusBadge-{{ $fb->id }}" class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide border {{ $statusClass }}">
                                                <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                                <span class="status-label">{{ $fb->status_label }}</span>
                                            </span>

                                            @if($fb->isAnonymous())
                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide bg-slate-200 text-slate-700 font-mono">
                                                    Anonymous
                                                </span>
                                            @endif
                                        </div>

                                        @php
                                            $complaintLabels = [
                                                'damaged_item' => 'Damaged Clothes',
                                                'missing_clothes' => 'Missing Items',
                                                'machine_issue' => 'Machine Problem',
                                                'staff_delay' => 'Staff Delay',
                                                'other' => 'Other Issue',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold text-slate-600 bg-white border border-slate-200">
                                            {{ $complaintLabels[$fb->type] ?? ucfirst(str_replace('_', ' ', $fb->type)) }}
                                        </span>
                                    @else
                                        <!-- Feedback Stars + Category -->
                                        <div class="flex items-center gap-1.5">
                                            <div class="flex items-center text-amber-400">
                                                @for($s = 1; $s <= 5; $s++)
                                                    <svg class="h-4 w-4 {{ $s <= $fb->rating ? 'fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                                <span class="ml-1.5 text-xs font-extrabold text-slate-700 font-mono">{{ $fb->rating }}.0</span>
                                            </div>
                                            @if($fb->isAnonymous())
                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide bg-slate-200 text-slate-700 font-mono">
                                                    Anonymous
                                                </span>
                                            @endif
                                        </div>

                                        @php
                                            $typeColor = match($fb->type) {
                                                'app' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                'service' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                default => 'bg-teal-100 text-teal-700 border-teal-200'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wide border {{ $typeColor }}">
                                            {{ $fb->type }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Summary & Order info -->
                                @if($fb->subject)
                                    <h3 class="text-sm font-bold text-slate-900 mt-2 leading-snug break-words break-all">{{ $fb->subject }}</h3>
                                @endif

                                @if($fb->service_request_id)
                                    <a href="{{ route('customer.requests.show', $fb->service_request_id) }}" class="inline-flex items-center gap-1 text-[11px] font-mono font-bold text-blue-600 hover:underline mt-1">
                                        <span>Order #REQ-{{ str_pad($fb->service_request_id, 4, '0', STR_PAD_LEFT) }}</span>
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @endif

                                <!-- Details -->
                                @if($fb->comment)
                                    <p class="text-xs text-slate-600 mt-1.5 leading-relaxed break-words break-all">
                                        {{ $fb->comment }}
                                    </p>
                                @endif

                                <!-- Aspects Tags (Feedback only) -->
                                @if(!empty($fb->aspects) && is_array($fb->aspects))
                                    <div class="mt-2.5 flex flex-wrap gap-1">
                                        @foreach($fb->aspects as $aspect)
                                            <span class="rounded-lg bg-white border border-slate-200/80 px-2 py-0.5 text-[10px] font-medium text-slate-600">
                                                {{ $aspect }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Resolution Banner (if resolved or closed) -->
                                <div id="customerResolutionWrapper-{{ $fb->id }}">
                                    @if($fb->resolution_note)
                                        <div class="mt-3 rounded-xl border border-emerald-200 bg-emerald-50/70 p-3 text-xs text-emerald-900">
                                            <div class="flex items-center gap-1.5 font-bold text-[11px] uppercase tracking-wider text-emerald-700">
                                                <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>Resolution Note from Management</span>
                                            </div>
                                            <p class="mt-1 text-slate-700">{{ $fb->resolution_note }}</p>
                                            
                                            @if($fb->status === 'resolved')
                                                <div class="mt-2 pt-2 border-t border-emerald-200/70 flex items-center justify-between gap-2">
                                                    <span class="text-[11px] text-emerald-700">Satisfied with the resolution?</span>
                                                    <form action="{{ route('customer.feedback.resolve', $fb->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1 text-[11px] font-bold text-white shadow-2xs hover:bg-emerald-700 transition cursor-pointer">
                                                            Confirm &amp; Close Ticket
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Date footer & Thread Toggle Button -->
                                <div class="mt-3 pt-2.5 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-400 font-mono">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $fb->created_at->format('M d, Y · h:i A') }}</span>
                                        <span>•</span>
                                        <span>{{ $fb->branch->name ?? 'Central Branch' }}</span>
                                    </div>
                                    
                                    <button 
                                        type="button" 
                                        onclick="toggleCustomerThread({{ $fb->id }})" 
                                        class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-bold text-blue-600 hover:bg-blue-50 transition cursor-pointer"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <span id="threadCountText-{{ $fb->id }}">Discussion ({{ $fb->replies->count() }})</span>
                                        <svg id="threadChevron-{{ $fb->id }}" class="h-3 w-3 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Thread Accordion Container -->
                                <div id="customerThread-{{ $fb->id }}" class="hidden mt-3 pt-3 border-t border-dashed border-slate-200">
                                    <div id="customerThreadMessages-{{ $fb->id }}" data-count="{{ $fb->replies->count() }}" class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                                        @forelse($fb->replies as $reply)
                                            @if($reply->isAdmin())
                                                <!-- Staff Message (Left) -->
                                                <div class="flex items-start gap-2.5 max-w-[88%]">
                                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 font-bold text-[10px] shadow-2xs font-mono">
                                                        ST
                                                    </div>
                                                    <div class="min-w-0 rounded-2xl rounded-tl-sm bg-white border border-slate-200 p-3 shadow-2xs">
                                                        <div class="flex items-center gap-1.5 mb-1">
                                                            <span class="text-[11px] font-bold text-blue-900">{{ $reply->sender_display_name }}</span>
                                                            <span class="text-[9px] font-mono text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-slate-700 leading-relaxed break-words break-all">{{ $reply->message }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Customer Message (Right) -->
                                                <div class="flex items-start gap-2.5 max-w-[88%] ml-auto justify-end">
                                                    <div class="min-w-0 rounded-2xl rounded-tr-sm bg-blue-600 text-white p-3 shadow-2xs text-right">
                                                        <div class="flex items-center justify-end gap-1.5 mb-1 text-blue-100">
                                                            <span class="text-[11px] font-bold">You</span>
                                                            <span class="text-[9px] font-mono opacity-80">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-white leading-relaxed text-left break-words break-all">{{ $reply->message }}</p>
                                                    </div>
                                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-slate-200 text-slate-700 font-bold text-[10px] shadow-2xs font-mono">
                                                        ME
                                                    </div>
                                                </div>
                                            @endif
                                        @empty
                                            <p id="emptyThreadHint-{{ $fb->id }}" class="text-center py-3 text-xs text-slate-400 italic">No replies yet. Staff will respond here shortly.</p>
                                        @endforelse
                                    </div>

                                    <!-- Reply Box -->
                                    <form id="customerReplyForm-{{ $fb->id }}" onsubmit="submitCustomerReply(event, {{ $fb->id }})" action="{{ route('customer.feedback.reply', $fb->id) }}" method="POST" class="mt-3 pt-2 border-t border-slate-100">
                                        @csrf
                                        <div class="flex items-center gap-2">
                                            <input 
                                                type="text" 
                                                id="customerReplyInput-{{ $fb->id }}"
                                                name="message" 
                                                required 
                                                maxlength="1000"
                                                placeholder="{{ $fb->status === 'closed' ? 'Type to reopen & reply...' : 'Type a reply to management...' }}" 
                                                class="flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-100 outline-none transition"
                                            >
                                            <button 
                                                type="submit" 
                                                id="customerReplySubmitBtn-{{ $fb->id }}"
                                                class="rounded-xl bg-blue-600 px-3.5 py-2 text-xs font-bold text-white shadow-2xs hover:bg-blue-700 transition active:scale-95 cursor-pointer shrink-0 inline-flex items-center gap-1.5"
                                            >
                                                <span>Reply</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach

                        <!-- Compact Pagination Bar -->
                        <x-pagination id="customerFeedbackPagination" :pageSize="3" :pageSizes="[3, 5, 10]" :compact="true" />
                    </div>
                @else
                    <div class="py-10 text-center">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-700">No Submissions Yet</h4>
                        <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">
                            You haven't submitted any feedback or complaints yet. Your thoughts help us make LaundrySpinzone even better!
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Interactive Scripts -->
    <script>


        let currentRating = parseInt(document.getElementById('ratingInput').value) || 5;
        let currentNature = "{{ old('nature', 'feedback') }}";

        function switchNature(nature) {
            currentNature = nature;
            document.getElementById('natureInput').value = nature;

            const tabFeedback = document.getElementById('tabFeedbackBtn');
            const tabComplaint = document.getElementById('tabComplaintBtn');

            const feedbackCategory = document.getElementById('feedbackCategorySection');
            const complaintCategory = document.getElementById('complaintCategorySection');
            const starSection = document.getElementById('starRatingSection');
            const aspectsSection = document.getElementById('aspectsSection');

            const formIconContainer = document.getElementById('formIconContainer');
            const formIconFeedback = document.getElementById('formIconFeedback');
            const formIconComplaint = document.getElementById('formIconComplaint');
            const formHeading = document.getElementById('formHeading');
            const formSubheading = document.getElementById('formSubheading');

            const subject = document.getElementById('subject');
            const subjectLabel = document.getElementById('subjectLabel');
            const comment = document.getElementById('comment');
            const commentLabel = document.getElementById('commentLabel');
            const submitBtn = document.getElementById('submitBtn');
            const submitBtnText = document.getElementById('submitBtnText');
            const submitBtnFeedbackIcon = document.getElementById('submitBtnFeedbackIcon');
            const submitBtnComplaintIcon = document.getElementById('submitBtnComplaintIcon');
            const orderHelperText = document.getElementById('orderHelperText');

            if (nature === 'complaint') {
                // Tab active styles (clean, no icons)
                tabComplaint.className = 'flex-1 flex items-center justify-center py-2.5 px-3 rounded-xl text-xs font-bold transition-all shadow-xs bg-white text-rose-700 cursor-pointer';
                tabFeedback.className = 'flex-1 flex items-center justify-center py-2.5 px-3 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 cursor-pointer';

                // Toggle sections
                feedbackCategory.classList.add('hidden');
                complaintCategory.classList.remove('hidden');
                starSection.classList.add('hidden');
                aspectsSection.classList.add('hidden');

                // Header styling
                formIconContainer.className = 'flex h-11 w-11 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 shadow-2xs transition-colors';
                formIconFeedback.classList.add('hidden');
                formIconComplaint.classList.remove('hidden');
                formHeading.textContent = 'Report Complaint';
                formSubheading.textContent = 'Please tell us what went wrong so branch management can take action';

                // 1-word labels & placeholders
                subject.placeholder = 'e.g., Damaged blue dress in Order #REQ-0012';
                subjectLabel.innerHTML = 'Summary <span class="text-slate-400 font-normal lowercase">(optional)</span>';
                comment.placeholder = 'Please explain what happened in detail (e.g. what was damaged/missing, date/time)...';
                commentLabel.innerHTML = 'Details <span class="text-rose-500">*</span>';
                comment.required = true;
                orderHelperText.textContent = 'Selecting the affected order helps the manager immediately pull logs.';

                // Button styling
                submitBtn.className = 'w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-rose-600 px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-rose-500/20 hover:bg-rose-700 transition active:scale-[0.98] cursor-pointer';
                submitBtnFeedbackIcon.classList.add('hidden');
                submitBtnComplaintIcon.classList.remove('hidden');
                submitBtnText.textContent = 'Submit Complaint';

                // Sync first complaint radio value to type input if needed
                const checkedRadio = document.querySelector('input[name="complaint_type_radio"]:checked');
                if (checkedRadio) {
                    setComplaintTypeValue(checkedRadio.value);
                }
            } else {
                // Tab active styles (clean, no icons)
                tabFeedback.className = 'flex-1 flex items-center justify-center py-2.5 px-3 rounded-xl text-xs font-bold transition-all shadow-xs bg-white text-blue-700 cursor-pointer';
                tabComplaint.className = 'flex-1 flex items-center justify-center py-2.5 px-3 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 cursor-pointer';

                // Toggle sections
                feedbackCategory.classList.remove('hidden');
                complaintCategory.classList.add('hidden');
                starSection.classList.remove('hidden');
                aspectsSection.classList.remove('hidden');

                // Header styling
                formIconContainer.className = 'flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs transition-colors';
                formIconFeedback.classList.remove('hidden');
                formIconComplaint.classList.add('hidden');
                formHeading.textContent = 'Share Feedback';
                formSubheading.textContent = 'Please rate your experience and provide your thoughts';

                // 1-word labels & placeholders
                subject.placeholder = 'e.g., Fresh scent and fast delivery';
                subjectLabel.innerHTML = 'Summary <span class="text-slate-400 font-normal lowercase">(optional)</span>';
                comment.placeholder = 'Please share what you enjoyed, or where we can do better...';
                commentLabel.innerHTML = 'Details <span class="text-slate-400 font-normal lowercase">(optional)</span>';
                comment.required = false;
                orderHelperText.textContent = 'If this feedback is about a recent laundry batch, select it to help staff trace the order.';

                // Button styling
                submitBtn.className = 'w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-blue-500/20 hover:bg-blue-700 transition active:scale-[0.98] cursor-pointer';
                submitBtnFeedbackIcon.classList.remove('hidden');
                submitBtnComplaintIcon.classList.add('hidden');
                submitBtnText.textContent = 'Submit Feedback';

                // Reset feedback type radio
                const checkedFeedbackRadio = document.querySelector('input.feedback-type-radio:checked');
                if (checkedFeedbackRadio) {
                    handleTypeChange(checkedFeedbackRadio.value);
                }
            }
        }

        function setComplaintTypeValue(val) {
            let hiddenType = document.getElementById('activeComplaintType');
            if (!hiddenType) {
                hiddenType = document.createElement('input');
                hiddenType.type = 'hidden';
                hiddenType.id = 'activeComplaintType';
                hiddenType.name = 'type';
                document.getElementById('feedbackForm').appendChild(hiddenType);
            }
            hiddenType.value = val;
            document.querySelectorAll('.feedback-type-radio').forEach(r => r.disabled = true);
        }

        function handleComplaintTypeChange(val) {
            setComplaintTypeValue(val);
        }

        function handleTypeChange(type) {
            document.querySelectorAll('.feedback-type-radio').forEach(r => r.disabled = false);
            const hiddenType = document.getElementById('activeComplaintType');
            if (hiddenType) {
                hiddenType.remove();
            }

            const wrapper = document.getElementById('serviceRequestWrapper');
            if (wrapper) {
                if (type === 'app') {
                    wrapper.classList.add('opacity-50', 'pointer-events-none');
                } else {
                    wrapper.classList.remove('opacity-50', 'pointer-events-none');
                }
            }
        }

        function updateStarsVisual(rating) {
            const stars = document.querySelectorAll('.star-btn');
            stars.forEach((star, index) => {
                const starVal = index + 1;
                if (starVal <= rating) {
                    star.classList.remove('text-slate-200');
                    star.classList.add('text-amber-400');
                } else {
                    star.classList.remove('text-amber-400');
                    star.classList.add('text-slate-200');
                }
            });

        }

        function setRating(val) {
            currentRating = val;
            document.getElementById('ratingInput').value = val;
            updateStarsVisual(val);
        }

        function hoverRating(val) {
            updateStarsVisual(val);
        }

        function resetHover() {
            updateStarsVisual(currentRating);
        }

        function updateCharCount(inputId, countId, max) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(countId);
            if (input && counter) {
                const len = input.value.length;
                counter.textContent = `${len} / ${max}`;
                if (len >= max) {
                    counter.classList.add('text-amber-600', 'font-bold');
                    counter.classList.remove('text-slate-400');
                } else {
                    counter.classList.remove('text-amber-600', 'font-bold');
                    counter.classList.add('text-slate-400');
                }
            }
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        const activeCustomerPollThreads = new Set();
        let customerChatPollInterval = null;

        function getCustomerStatusBadgeHtml(status, label) {
            switch(status) {
                case 'in_progress':
                    return `<span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span><span class="status-label">${label || 'In Progress'}</span>`;
                case 'resolved':
                    return `<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span><span class="status-label">${label || 'Resolved'}</span>`;
                case 'closed':
                    return `<span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span><span class="status-label">${label || 'Closed'}</span>`;
                default:
                    return `<span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span><span class="status-label">${label || 'Open'}</span>`;
            }
        }

        function getCustomerStatusBadgeClass(status) {
            switch(status) {
                case 'in_progress':
                    return 'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide border bg-blue-50 text-blue-700 border-blue-200';
                case 'resolved':
                    return 'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide border bg-emerald-50 text-emerald-700 border-emerald-200';
                case 'closed':
                    return 'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide border bg-slate-100 text-slate-600 border-slate-200';
                default:
                    return 'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide border bg-amber-50 text-amber-700 border-amber-200';
            }
        }

        function toggleCustomerThread(id) {
            const threadEl = document.getElementById(`customerThread-${id}`);
            const chevronEl = document.getElementById(`threadChevron-${id}`);
            if (!threadEl) return;

            const isHidden = threadEl.classList.contains('hidden');
            if (isHidden) {
                threadEl.classList.remove('hidden');
                if (chevronEl) chevronEl.style.transform = 'rotate(180deg)';
                activeCustomerPollThreads.add(id);

                const messagesEl = document.getElementById(`customerThreadMessages-${id}`);
                if (messagesEl) {
                    messagesEl.scrollTop = messagesEl.scrollHeight;
                }

                pollSingleCustomerThread(id);

                if (!customerChatPollInterval) {
                    customerChatPollInterval = setInterval(pollActiveCustomerThreads, 2500);
                }
            } else {
                threadEl.classList.add('hidden');
                if (chevronEl) chevronEl.style.transform = 'rotate(0deg)';
                activeCustomerPollThreads.delete(id);

                if (activeCustomerPollThreads.size === 0 && customerChatPollInterval) {
                    clearInterval(customerChatPollInterval);
                    customerChatPollInterval = null;
                }
            }
        }

        async function pollActiveCustomerThreads() {
            if (document.hidden || activeCustomerPollThreads.size === 0) return;
            for (const id of activeCustomerPollThreads) {
                await pollSingleCustomerThread(id);
            }
        }

        async function pollSingleCustomerThread(id) {
            try {
                const res = await fetch(`/customer/feedback/${id}/replies`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!res.ok) return;
                const data = await res.json();

                // Update status badge
                const badgeEl = document.getElementById(`customerStatusBadge-${id}`);
                if (badgeEl && data.status) {
                    badgeEl.className = getCustomerStatusBadgeClass(data.status);
                    badgeEl.innerHTML = getCustomerStatusBadgeHtml(data.status, data.status_label);
                }

                // Update resolution note banner if updated
                const resolutionWrapper = document.getElementById(`customerResolutionWrapper-${id}`);
                if (resolutionWrapper) {
                    if (data.resolution_note) {
                        resolutionWrapper.innerHTML = `
                            <div class="mt-3 rounded-xl border border-emerald-200 bg-emerald-50/70 p-3 text-xs text-emerald-900 transition-all">
                                <div class="flex items-center gap-1.5 font-bold text-[11px] uppercase tracking-wider text-emerald-700">
                                    <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Resolution Note from Management</span>
                                </div>
                                <p class="mt-1 text-slate-700">${escapeHtml(data.resolution_note)}</p>
                                ${data.status === 'resolved' ? `
                                    <div class="mt-2 pt-2 border-t border-emerald-200/70 flex items-center justify-between gap-2">
                                        <span class="text-[11px] text-emerald-700">Satisfied with the resolution?</span>
                                        <form action="/customer/feedback/${id}/resolve" method="POST">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1 text-[11px] font-bold text-white shadow-2xs hover:bg-emerald-700 transition cursor-pointer">
                                                Confirm & Close Ticket
                                            </button>
                                        </form>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    }
                }

                // Update Discussion Count Pill
                const countTextEl = document.getElementById(`threadCountText-${id}`);
                const replies = data.replies || [];
                if (countTextEl) {
                    countTextEl.textContent = `Discussion (${replies.length})`;
                }

                // Check if replies count changed
                const messagesContainer = document.getElementById(`customerThreadMessages-${id}`);
                if (messagesContainer) {
                    const prevCount = parseInt(messagesContainer.getAttribute('data-count') || '0', 10);
                    if (prevCount !== replies.length) {
                        renderCustomerThreadMessages(id, replies);
                        messagesContainer.setAttribute('data-count', replies.length);
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }
                }
            } catch (err) {
                // Ignore transient network errors
            }
        }

        function renderCustomerThreadMessages(id, replies) {
            const container = document.getElementById(`customerThreadMessages-${id}`);
            if (!container) return;

            if (!replies || replies.length === 0) {
                container.innerHTML = `<p id="emptyThreadHint-${id}" class="text-center py-3 text-xs text-slate-400 italic">No replies yet. Staff will respond here shortly.</p>`;
                return;
            }

            container.innerHTML = '';
            replies.forEach(reply => {
                const isStaff = reply.sender_type === 'admin';
                const div = document.createElement('div');

                if (isStaff) {
                    div.className = 'flex items-start gap-2.5 max-w-[88%]';
                    div.innerHTML = `
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 font-bold text-[10px] shadow-2xs font-mono">
                            ST
                        </div>
                        <div class="min-w-0 rounded-2xl rounded-tl-sm bg-white border border-slate-200 p-3 shadow-2xs">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="text-[11px] font-bold text-blue-900">${escapeHtml(reply.sender_name)}</span>
                                <span class="text-[9px] font-mono text-slate-400">${escapeHtml(reply.time_diff || reply.created_at)}</span>
                            </div>
                            <p class="text-xs text-slate-700 leading-relaxed break-words break-all">${escapeHtml(reply.message)}</p>
                        </div>
                    `;
                } else {
                    div.className = 'flex items-start gap-2.5 max-w-[88%] ml-auto justify-end';
                    div.innerHTML = `
                        <div class="min-w-0 rounded-2xl rounded-tr-sm bg-blue-600 text-white p-3 shadow-2xs text-right">
                            <div class="flex items-center justify-end gap-1.5 mb-1 text-blue-100">
                                <span class="text-[11px] font-bold">You</span>
                                <span class="text-[9px] font-mono opacity-80">${escapeHtml(reply.time_diff || 'just now')}</span>
                            </div>
                            <p class="text-xs text-white leading-relaxed text-left break-words break-all">${escapeHtml(reply.message)}</p>
                        </div>
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-slate-200 text-slate-700 font-bold text-[10px] shadow-2xs font-mono">
                            ME
                        </div>
                    `;
                }
                container.appendChild(div);
            });
        }

        async function submitCustomerReply(e, id) {
            e.preventDefault();
            const input = document.getElementById(`customerReplyInput-${id}`);
            const btn = document.getElementById(`customerReplySubmitBtn-${id}`);
            if (!input) return;

            const message = input.value.trim();
            if (!message) return;

            btn.disabled = true;
            const originalBtnHtml = btn.innerHTML;
            btn.innerHTML = `
                <svg class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;

            try {
                const res = await fetch(`/customer/feedback/${id}/reply`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ message })
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to send message');

                input.value = '';

                // Remove empty state placeholder if present
                const emptyHint = document.getElementById(`emptyThreadHint-${id}`);
                if (emptyHint) emptyHint.remove();

                // Append new message bubble
                const container = document.getElementById(`customerThreadMessages-${id}`);
                if (container) {
                    const div = document.createElement('div');
                    div.className = 'flex items-start gap-2.5 max-w-[88%] ml-auto justify-end';
                    div.innerHTML = `
                        <div class="min-w-0 rounded-2xl rounded-tr-sm bg-blue-600 text-white p-3 shadow-2xs text-right">
                            <div class="flex items-center justify-end gap-1.5 mb-1 text-blue-100">
                                <span class="text-[11px] font-bold">You</span>
                                <span class="text-[9px] font-mono opacity-80">just now</span>
                            </div>
                            <p class="text-xs text-white leading-relaxed text-left break-words break-all">${escapeHtml(message)}</p>
                        </div>
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-slate-200 text-slate-700 font-bold text-[10px] shadow-2xs font-mono">
                            ME
                        </div>
                    `;
                    container.appendChild(div);
                    container.scrollTop = container.scrollHeight;

                    const curCount = parseInt(container.getAttribute('data-count') || '0', 10) + 1;
                    container.setAttribute('data-count', curCount);
                    const countTextEl = document.getElementById(`threadCountText-${id}`);
                    if (countTextEl) countTextEl.textContent = `Discussion (${curCount})`;
                }

                // If status auto-reopened to in_progress
                if (data.status) {
                    const badgeEl = document.getElementById(`customerStatusBadge-${id}`);
                    if (badgeEl) {
                        badgeEl.className = getCustomerStatusBadgeClass(data.status);
                        badgeEl.innerHTML = getCustomerStatusBadgeHtml(data.status, data.status_label);
                    }
                }

            } catch (err) {
                console.error(err);
                alert(err.message || 'Error sending reply. Please try again.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalBtnHtml;
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateStarsVisual(currentRating);
            updateCharCount('subject', 'subjectCount', 60);
            updateCharCount('comment', 'commentCount', currentNature === 'complaint' ? 500 : 300);

            if (currentNature === 'complaint') {
                switchNature('complaint');
            }

            // Initialize ClientPaginator
            if (document.getElementById('customerFeedbackPagination')) {
                new ClientPaginator({
                    containerId: 'customerFeedbackPagination',
                    pageSize: 3,
                    getItems: () => Array.from(document.querySelectorAll('.feedback-item')),
                    onPageChange: (visibleSlice) => {
                        const visibleSet = new Set(visibleSlice);
                        document.querySelectorAll('.feedback-item').forEach(card => {
                            card.style.display = visibleSet.has(card) ? '' : 'none';
                        });
                    }
                });
            }
        });
    </script>
</x-customer-layout>
