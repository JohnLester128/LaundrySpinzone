<x-admin-layout>
    <div class="mb-8"><p class="text-sm font-medium text-slate-500">Security</p><h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">Login logs</h1><p class="mt-2 text-slate-500">Review recent login activity.</p></div>

    <div class="admin-card overflow-x-auto"><table class="admin-table">
        <tr>
            <th>Username</th>
            <th>Type</th>
            <th>IP Address</th>
            <th>Login At</th>
            <th>Logout At</th>
            <th>Status</th>
        </tr>
        @foreach ($logs as $log)
            <tr>
                <td>{{ $log->username }}</td>
                <td>{{ $log->user_type }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>{{ $log->login_at }}</td>
                <td>
                    @if ($log->status === 'failed')
                        —
                    @elseif ($log->logout_at)
                        {{ $log->logout_at }}
                    @else
                        Still logged in
                    @endif
                </td>
                <td>{{ $log->status }}</td>
            </tr>
        @endforeach
    </table></div>
</x-admin-layout>
