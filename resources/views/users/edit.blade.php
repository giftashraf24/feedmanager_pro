@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', '✏️ Edit User')

@section('topbar-actions')
    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back</a>
@endsection

@section('content')
<div style="max-width:580px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">{{ $user->name }}</div>
            @if($user->id === auth()->id())
                <span class="badge badge-green">You</span>
            @endif
        </div>
        <div class="card-body">
            @if($user->id === auth()->id())
            <div class="alert alert-warning" style="margin-bottom:16px">⚠️ You are editing your own account. Changing your role may affect your current session.</div>
            @endif

            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf @method('PUT')
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-control">
                        <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>👤 Staff</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>⚡ Administrator</option>
                    </select>
                </div>

                <div class="divider"></div>
                <div style="font-size:13px;font-weight:600;color:var(--text2);margin-bottom:12px">Change Password <span class="text-muted" style="font-weight:400">(leave blank to keep current)</span></div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Min 8 chars, letters + numbers" autocomplete="new-password">
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" autocomplete="new-password">
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
