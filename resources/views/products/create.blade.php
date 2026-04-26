@extends('layouts.app')
@section('title', 'Add Product')
@section('page-title', '🌾 Add Product')

@section('topbar-actions')
    <a href="{{ route('products.index') }}" class="btn btn-secondary">← Back</a>
@endsection

@section('content')
<div style="max-width:580px">
    <div class="card">
        <div class="card-header"><div class="card-title">New Product</div></div>
        <div class="card-body">
            <form method="POST" action="{{ route('products.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            value="{{ old('name') }}" placeholder="e.g. Premium Chicken Feed" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <input type="text" name="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}"
                            value="{{ old('type') }}" placeholder="e.g. Poultry" list="type-suggestions" required>
                        <datalist id="type-suggestions">
                            @foreach($types as $t)<option value="{{ $t }}">@endforeach
                            <option value="Poultry"><option value="Livestock"><option value="Pig"><option value="Horse"><option value="Goat">
                        </datalist>
                        @error('type')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Price (zł) *</label>
                        <input type="number" step="0.01" min="0" name="price" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                            value="{{ old('price') }}" placeholder="0.00" required>
                        @error('price')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stock Quantity *</label>
                        <input type="number" min="0" name="stock_quantity" class="form-control {{ $errors->has('stock_quantity') ? 'is-invalid' : '' }}"
                            value="{{ old('stock_quantity', 0) }}" required>
                        @error('stock_quantity')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">✅ Create Product</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
