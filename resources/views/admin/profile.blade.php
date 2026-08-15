<x-admin-layout>
    <div class="mb-8"><p class="text-sm font-medium text-slate-500">Account</p><h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">My profile</h1><p class="mt-2 text-slate-500">Update your administrator password.</p></div>

    <x-notification />

    <div class="grid max-w-3xl gap-6 lg:grid-cols-2">
    <form class="admin-card space-y-5 p-6 lg:col-span-2" action="{{ route('admin.profile.update') }}" method="POST">
        @csrf
        <h2 class="font-semibold text-slate-900">Profile Information</h2>
        <label class="block text-sm font-medium text-slate-700">Username<input class="admin-input" type="text" name="username" value="{{ old('username', auth('admin')->user()->username) }}" required></label>
        <label class="block text-sm font-medium text-slate-700">Branch Name<input class="admin-input" type="text" name="branch_name" value="{{ old('branch_name', $branch->name ?? auth('admin')->user()->username) }}" required></label>
        <label class="block text-sm font-medium text-slate-700">Branch Address<input class="admin-input" type="text" name="branch_address" value="{{ old('branch_address', $branch->address ?? '') }}" placeholder="Enter branch address" required></label>
        <button class="admin-button" type="submit">Save Profile</button>
    </form>
    <form class="admin-card space-y-5 p-6" action="{{ route('admin.password.update') }}" method="POST">
        @csrf

        <label class="block text-sm font-medium text-slate-700">Current password<input class="admin-input" type="password" name="current_password"></label>

        <label class="block text-sm font-medium text-slate-700">New password<input class="admin-input" type="password" name="new_password"></label>

        <button class="admin-button" type="submit">Update password</button>
    </form>
    <div class="admin-card space-y-3 p-6"><h2 class="font-semibold text-slate-900">Administration</h2><a class="admin-nav-link" href="{{ route('admin.customers') }}">Customers</a><a class="admin-nav-link" href="{{ route('admin.login-logs') }}">Login Logs</a><form class="pt-3" action="{{ route('admin.logout') }}" method="POST">@csrf<button class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700" type="submit">Sign Out</button></form></div></div>
</x-admin-layout>
