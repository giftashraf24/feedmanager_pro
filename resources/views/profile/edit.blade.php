@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', '👤 My Profile')

@section('content')
@php
    $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0]))->take(2)->join('');
    $avatarClass = $user->isAdmin() ? 'avatar-admin' : 'avatar-staff';
@endphp

<div style="display:grid;grid-template-columns:260px 1fr;gap:16px">
    <!-- Profile card -->
    <div class="card" style="text-align:center;padding:28px">
        <div class="user-avatar {{ $avatarClass }}" style="width:72px;height:72px;font-size:22px;margin:0 auto 14px">{{ $initials }}</div>
        <div style="font-family:'Syne',sans-serif;font-size:17px;font-weight:700;color:var(--text);margin-bottom:4px">{{ $user->name }}</div>
        <div style="font-size:12px;color:var(--text3);margin-bottom:12px">{{ $user->email }}</div>
        <span class="badge {{ $user->isAdmin() ? 'badge-purple' : 'badge-cyan' }}">
            {{ $user->isAdmin() ? '⚡ Administrator' : '👤 Staff Member' }}
        </span>
        <div class="divider"></div>
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text3);margin-bottom:10px">Permissions</div>
        @php $perms = $user->isAdmin() ? ['Dashboard','POS','Orders','Products (Full)','Users','Profile'] : ['Dashboard','POS','Orders','Products (View)','Profile']; @endphp
        @foreach($perms as $perm)
        <div style="display:flex;align-items:center;gap:7px;text-align:left;font-size:12px;margin-bottom:6px;color:var(--text2)">
            <span style="color:var(--accent)">✓</span> {{ $perm }}
        </div>
        @endforeach
        <div class="divider"></div>
        <div style="font-size:11px;color:var(--text3)">Member since {{ $user->created_at->format('M Y') }}</div>
    </div>

    <!-- Forms -->
    <div>
        <!-- Profile info -->
        <div class="card" style="margin-bottom:14px">
            <div class="card-header"><div class="card-title">Update Profile</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
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
                    <button type="submit" class="btn btn-primary">💾 Save Profile</button>
                </form>
            </div>
        </div>

        <!-- Password -->
        <div class="card">
            <div class="card-header"><div class="card-title">Change Password</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Current Password *</label>
                        <input type="password" name="current_password" class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                            placeholder="Your current password" required autocomplete="current-password">
                        @error('current_password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">New Password *</label>
                            <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Min 8 chars, letters + numbers" required autocomplete="new-password">
                            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Repeat password" required autocomplete="new-password">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary">🔐 Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
