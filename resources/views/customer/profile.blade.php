<x-customer-layout>
<div class="mb-8"><p class="text-sm font-medium text-slate-500">Account</p><h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">My profile</h1><p class="mt-2 text-slate-500">{{ auth('customer')->user()->username }}</p></div>

<x-notification />

<section class="customer-card mb-6 max-w-xl"><h2 class="text-xl font-bold text-slate-900">Selected Branch</h2><p class="mt-1 text-sm text-slate-500">This branch will be used automatically for new service requests.</p><form class="mt-4" action="{{ route('customer.branch.update') }}" method="POST">@csrf<select class="customer-input" name="branch_id" required>@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(auth('customer')->user()->branch_id == $branch->id)>{{ $branch->name }} — {{ $branch->address }}</option>@endforeach</select><button class="customer-button mt-4" type="submit">Save Branch</button></form></section>

<form class="admin-card max-w-xl space-y-5 p-6" action="{{ route('customer.password.update') }}" method="POST">
    @csrf

    <label class="block text-sm font-medium text-slate-700">Current password<input class="admin-input" type="password" name="current_password"></label>

    <label class="block text-sm font-medium text-slate-700">New password<input class="admin-input" type="password" name="new_password"></label>

    <button class="admin-button" type="submit">Update password</button>
</form>
<form class="mt-6 max-w-xl" action="{{ route('customer.logout') }}" method="POST">@csrf<button class="w-full rounded-2xl bg-red-600 px-5 py-3.5 font-semibold text-white transition hover:bg-red-700" type="submit">Sign Out</button></form>
</x-customer-layout>
