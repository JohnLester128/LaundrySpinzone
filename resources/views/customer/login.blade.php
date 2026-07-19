<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Customer Login</title>@vite(['resources/css/app.css', 'resources/js/app.js'])</head><body><div class="flex min-h-screen items-center justify-center bg-slate-50 px-5"><div class="admin-card w-full max-w-md p-8"><h1 class="text-2xl font-bold text-slate-900">Welcome back</h1><p class="mt-2 text-sm text-slate-500">Sign in to your LaundrySpinzone account.</p>

<x-notification />

<form action="{{ route('customer.login.store') }}" method="POST">
    @csrf

    <label class="mt-6 block text-sm font-medium text-slate-700">Username<input class="admin-input" type="text" name="username" value="{{ old('username') }}"></label>

    <label class="mt-4 block text-sm font-medium text-slate-700">Password<input class="admin-input" type="password" name="password"></label>

    <button class="admin-button mt-6 w-full" type="submit">Sign in</button>
</form>
</div></div></body></html>
