<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'LaundrySpinzone' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <nav class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex h-20 max-w-5xl items-center justify-between px-5 sm:px-8">
            <a href="{{ route('customer.dashboard') }}" class="text-lg font-bold tracking-tight text-slate-900">LaundrySpinzone</a>
            <div class="flex items-center gap-5 text-sm font-medium">
                <a class="text-slate-600 hover:text-slate-950" href="{{ route('customer.profile') }}">Profile</a>
                <form action="{{ route('customer.logout') }}" method="POST">@csrf<button class="rounded-lg border border-slate-300 px-3.5 py-2 text-slate-700 hover:bg-slate-50" type="submit">Sign out</button></form>
            </div>
        </div>
    </nav>
    <main class="mx-auto max-w-5xl px-5 py-10 sm:px-8">{{ $slot }}</main>

</body>
</html>
