@extends('layouts.app')
@section('title', 'Add User')
@section('page-title', '👥 Add User')

@section('topbar-actions')
    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back</a>
@endsection

@section('content')
<div style="max-width:580px">
    <div class="card">
        <div class="card-header"><div class="card-title">New Team Member</div></div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            value="{{ old('name') }}" placeholder="John Doe" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            value="{{ old('email') }}" placeholder="john@example.com" required>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Min 8 chars, letters + numbers" required autocomplete="new-password">
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required autocomplete="new-password">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}">
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>👤 Staff — Can create orders, view products</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>⚡ Administrator — Full access including user management</option>
                    </select>
                    @error('role')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div style="display:flex;gap:10px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">✅ Create User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
