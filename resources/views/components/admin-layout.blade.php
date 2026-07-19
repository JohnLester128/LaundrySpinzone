<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Admin Panel' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-shell">
    <aside class="admin-sidebar">
        <div class="flex h-20 items-center border-b border-slate-200 px-7"><span class="text-lg font-bold tracking-tight text-slate-900">LaundrySpinzone</span></div>
        <nav class="space-y-1 px-4 py-6">
            <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Administration</p>
            <a class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="admin-nav-link {{ request()->routeIs('admin.customers') ? 'active' : '' }}" href="{{ route('admin.customers') }}">Customers</a>
            <a class="admin-nav-link {{ request()->routeIs('admin.login-logs') ? 'active' : '' }}" href="{{ route('admin.login-logs') }}">Login logs</a>
            <a class="admin-nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}" href="{{ route('admin.profile') }}">Profile</a>
        </nav>
    </aside>
    <div class="admin-main">
        <header class="flex h-20 items-center justify-between border-b border-slate-200 bg-white px-5 sm:px-8">
            <div><p class="text-sm text-slate-500">Admin panel</p><p class="font-semibold text-slate-900">{{ auth('admin')->user()->username ?? 'Administrator' }}</p></div>
            <form action="{{ route('admin.logout') }}" method="POST">@csrf<button class="admin-button-secondary" type="submit">Sign out</button></form>
        </header>
        <main class="admin-content">{{ $slot }}</main>
    </div>

</body>
</html>
