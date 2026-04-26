@extends('layouts.app')
@section('title', 'Users')
@section('page-title', '👥 User Management')

@section('topbar-actions')
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Add User</a>
@endsection

@section('content')
<div style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:8px;padding:10px 16px;font-size:13px;color:var(--accent3);margin-bottom:18px;display:flex;align-items:center;gap:8px">
    🔐 Administrator access only — manage team roles and permissions here
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            Team Members
            <span class="badge badge-gray" style="margin-left:8px">{{ $users->count() }}</span>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:9px">
                            @php $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0]))->take(2)->join(''); @endphp
                            <div class="user-avatar {{ $user->isAdmin() ? 'avatar-admin' : 'avatar-staff' }}" style="width:28px;height:28px;font-size:10px">{{ $initials }}</div>
                            <span style="font-weight:500">{{ $user->name }}</span>
                            @if($user->id === auth()->id())
                                <span class="badge badge-green" style="font-size:10px">You</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        @if($user->isAdmin())
                            <span class="badge badge-purple">⚡ Admin</span>
                        @else
                            <span class="badge badge-cyan">👤 Staff</span>
                        @endif
                    </td>
                    <td class="text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('users.edit', $user) }}" class="action-btn action-edit">✏️ Edit</a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete user {{ addslashes($user->name) }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn action-del">🗑 Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
