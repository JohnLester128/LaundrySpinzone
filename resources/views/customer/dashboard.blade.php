<x-customer-layout>
    
<div class="mb-8"><p class="text-sm font-medium text-slate-500">Customer portal</p><h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">Welcome, {{ Auth::guard('customer')->user()->username }}</h1><p class="mt-2 text-slate-500">Manage your account from one place.</p></div>
<div class="admin-card max-w-xl p-6"><h2 class="text-lg font-semibold text-slate-900">Your account</h2><p class="mt-2 text-sm leading-6 text-slate-500">Keep your account details secure and your password up to date.</p><a href="{{ route('customer.profile') }}" class="admin-button mt-5 inline-block">Manage profile</a></div>

</x-customer-layout>
