<x-admin-layout>
    <div class="mb-8"><p class="text-sm font-medium text-slate-500">Account</p><h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">My profile</h1><p class="mt-2 text-slate-500">Update your administrator password.</p></div>

    <x-notification />

    <form class="admin-card max-w-xl space-y-5 p-6" action="{{ route('admin.password.update') }}" method="POST">
        @csrf

        <label class="block text-sm font-medium text-slate-700">Current password<input class="admin-input" type="password" name="current_password"></label>

        <label class="block text-sm font-medium text-slate-700">New password<input class="admin-input" type="password" name="new_password"></label>

        <button class="admin-button" type="submit">Update password</button>
    </form>
</x-admin-layout>
