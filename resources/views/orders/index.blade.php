@extends('layouts.app')
@section('title', 'Orders')
@section('page-title', '📦 Orders')

@section('topbar-actions')
    <a href="{{ route('orders.create') }}" class="btn btn-primary">+ New Order</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            All Orders
            <span class="badge badge-gray" style="margin-left:8px">{{ $orders->count() }}</span>
        </div>
        <form method="GET" action="{{ route('orders.index') }}">
            <div style="display:flex;gap:8px">
                <div class="search-wrap">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="search" class="search-input" placeholder="Search customer or ID…" value="{{ request('search') }}">
                </div>
                <button class="btn btn-secondary btn-sm">Search</button>
                @if(request('search'))
                    <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm">Clear</a>
                @endif
            </div>
        </form>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Items</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><span class="badge badge-purple">#{{ $order->id }}</span></td>
                    <td style="font-weight:500">{{ $order->customer_name }}</td>
                    <td class="text-muted">{{ $order->customer_phone ?: '—' }}</td>
                    <td class="text-muted">{{ $order->items->count() }} item(s)</td>
                    <td class="text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                    <td style="font-weight:600;color:var(--accent)">zł {{ number_format($order->total_price, 2) }}</td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('orders.invoice', $order) }}" class="action-btn action-view">🧾 Invoice</a>
                            @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Delete order #{{ $order->id }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn action-del">🗑 Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon">📦</div>
                            <p>No orders yet. <a href="{{ route('orders.create') }}" style="color:var(--accent)">Create your first order</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
