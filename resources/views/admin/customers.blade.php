<x-admin-layout>
    <div class="mb-8"><p class="text-sm font-medium text-slate-500">Administration</p><h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">Customers</h1><p class="mt-2 text-slate-500">Manage customer accounts and passwords.</p></div>
    <x-notification />

    <div class="admin-card overflow-hidden"><table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Joined</th>
            <th>Reset Password</th>
        </tr>
        @foreach ($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->username }}</td>
                <td>{{ $customer->created_at }}</td>
                <td>
                    <form action="{{ route('admin.customers.reset', $customer->id) }}" method="POST">
                        @csrf
                        <input class="admin-input mt-0 inline-block w-44" type="password" name="password" placeholder="New password" required value="{{ old('password') }}">
                        <button class="admin-button ml-2" type="submit">Reset</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table></div>
</x-admin-layout>
