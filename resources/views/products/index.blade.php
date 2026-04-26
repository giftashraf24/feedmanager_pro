@extends('layouts.app')
@section('title', 'Products')
@section('page-title', '🌾 Products')

@section('topbar-actions')
    @if(auth()->user()->isAdmin())
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
    @endif
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            Inventory
            <span class="badge badge-gray" style="margin-left:8px">{{ $products->count() }}</span>
        </div>
        <div style="display:flex;gap:8px;align-items:center">
            <form method="GET" action="{{ route('products.index') }}" style="display:flex;gap:8px">
                <div class="search-wrap">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="search" class="search-input" placeholder="Search products…" value="{{ request('search') }}">
                </div>
                <select name="type" class="form-control" style="width:auto;padding:7px 12px" onchange="this.form.submit()">
                    <option value="">All types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                @if(request('search') || request('type'))
                    <a href="{{ route('products.index') }}" class="btn btn-outline btn-sm">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    @if(auth()->user()->isAdmin())<th>Actions</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="text-muted font-mono">{{ $product->id }}</td>
                    <td style="font-weight:500">{{ $product->name }}</td>
                    <td><span class="badge badge-cyan">{{ $product->type }}</span></td>
                    <td style="font-weight:600">zł {{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>
                        @if($product->isOutOfStock())
                            <span class="badge badge-rose">❌ Out of stock</span>
                        @elseif($product->isLowStock())
                            <span class="badge badge-amber">⚠️ Low stock</span>
                        @else
                            <span class="badge badge-green">✓ In stock</span>
                        @endif
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <div class="action-group">
                            <a href="{{ route('products.edit', $product) }}" class="action-btn action-edit">✏️ Edit</a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn action-del">🗑 Delete</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}">
                        <div class="empty-state">
                            <div class="empty-state-icon">🌾</div>
                            <p>No products found. @if(auth()->user()->isAdmin())<a href="{{ route('products.create') }}" style="color:var(--accent)">Add your first product</a>@endif</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
