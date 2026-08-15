<x-customer-layout>
    <div class="mx-auto max-w-2xl">
        <div class="mb-7"><p class="text-sm font-semibold uppercase tracking-widest text-blue-600">Customer portal</p><h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Create service request</h1><p class="mt-2 text-slate-500">Tell us about your laundry and choose any services you need.</p></div>
        @if ($errors->any())<div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"><ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('customer.service-requests.store') }}" class="space-y-6">@csrf
            <section class="customer-card space-y-5"><h2 class="text-xl font-bold text-slate-900">Laundry Details</h2>
                <div><label class="customer-label">Type of Clothes <span class="text-red-500">*</span></label><select class="customer-input" name="cloth_type_id" required><option value="">Select cloth type</option>@foreach($clothTypes as $type)<option value="{{ $type->id }}" @selected(old('cloth_type_id') == $type->id)>{{ $type->name }}</option>@endforeach</select></div>
                <div><label class="customer-label">Total Laundry Weight (kg)</label><input class="customer-input" type="number" name="estimated_weight" min="0.1" step="0.01" placeholder="e.g., 5" value="{{ old('estimated_weight') }}"><p class="mt-2 text-xs text-slate-400">Staff can verify and update the final weight.</p></div>
                <div><label class="customer-label">Quantity</label><input class="customer-input" type="number" name="quantity" min="1" step="1" placeholder="Number of pieces" value="{{ old('quantity', 1) }}"></div>
                <div><label class="customer-label">Special Instructions</label><textarea class="customer-input min-h-32" name="notes" placeholder="e.g., Please handle with care">{{ old('notes') }}</textarea></div>
            </section>
            <section class="customer-card"><h2 class="text-xl font-bold text-slate-900">Add-Ons</h2><p class="mt-1 mb-4 text-sm text-slate-500">Select any extras you would like included.</p><div class="space-y-3">@foreach($addons as $addon)<label class="addon-card"><span><span class="block font-semibold text-slate-800">{{ $addon->name }}</span><span class="text-sm text-slate-500">+PHP {{ number_format($addon->price, 2) }}</span></span><input class="h-5 w-5 accent-blue-600" type="checkbox" name="addon_ids[]" value="{{ $addon->id }}" @checked(in_array($addon->id, old('addon_ids', [])))></label>@endforeach</div></section>
            <button class="customer-button w-full" type="submit">Submit Service Request</button>
        </form>
    </div>
</x-customer-layout>
