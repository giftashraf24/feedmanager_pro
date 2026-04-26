@extends('layouts.app')
@section('title', '403 — Access Denied')
@section('page-title', '🔒 Access Denied')

@section('content')
<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:400px;text-align:center;gap:12px">
    <div style="font-size:56px;opacity:0.3">🔒</div>
    <div style="font-family:'Syne',sans-serif;font-size:22px;font-weight:700;color:var(--text2)">Access Restricted</div>
    <div style="color:var(--text3);font-size:13px;max-width:340px">{{ $exception->getMessage() ?: 'You do not have permission to view this page.' }}</div>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary" style="margin-top:8px">← Back to Dashboard</a>
</div>
@endsection
