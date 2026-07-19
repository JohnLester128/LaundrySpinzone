<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>

    <nav style="background:#333; padding:10px;">
        <a href="{{ route('admin.dashboard') }}" style="color:white; margin-right:15px;">Dashboard</a>
        <a href="{{ route('admin.login-logs') }}" style="color:white; margin-right:15px;">Login Logs</a>
        <a href="{{ route('admin.customers') }}" style="color:white; margin-right:15px;">Customers</a>
        <a href="{{ route('admin.profile') }}" style="color:white; margin-right:15px;">Profile</a>

        
    </nav>

    <main style="padding:20px;">
        {{ $slot }}
    </main>

</body>
</html>