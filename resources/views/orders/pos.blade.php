@extends('layouts.app')
@section('title', 'Point of Sale')
@section('page-title', '🛒 Point of Sale')

@section('topbar-actions')
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">📦 View Orders</a>
@endsection

@section('content')
<style>
.pos-shell { display: grid; grid-template-columns: 1fr 340px; gap: 16px; height: calc(100vh - 130px); }
.pos-left { overflow-y: auto; }
.pos-filter-bar { display: flex; gap: 8px; margin-bottom: 14px; position: sticky; top: 0; background: var(--bg); padding: 4px 0 10px; z-index: 10; }
.product-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.product-tile { background: var(--bg2); border: 1px solid var(--border); border-radius: 10px; padding: 13px; cursor: pointer; transition: all 0.15s; position: relative; }
.product-tile:hover:not(.disabled) { border-color: var(--accent); transform: translateY(-2px); box-shadow: 0 4px 16px rgba(74,222,128,0.1); }
.product-tile.disabled { opacity: 0.45; cursor: not-allowed; }
.product-tile.in-cart { border-color: rgba(74,222,128,0.4); background: rgba(74,222,128,0.04); }
.tile-type { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text3); margin-bottom: 5px; }
.tile-name { font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 5px; line-height: 1.3; }
.tile-price { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; color: var(--accent); }
.tile-stock { font-size: 11px; margin-top: 4px; }
.tile-stock.ok { color: var(--text3); }
.tile-stock.low { color: var(--accent3); }
.tile-stock.out { color: var(--accent4); }
.cart-panel { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius-lg); display: flex; flex-direction: column; overflow: hidden; position: sticky; top: 0; max-height: calc(100vh - 130px); }
.cart-head { padding: 14px 16px; border-bottom: 1px solid var(--border); }
.cart-title { font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700; color: var(--text); }
.cart-sub { font-size: 11px; color: var(--text3); margin-top: 2px; }
.cart-items-wrap { flex: 1; overflow-y: auto; }
.cart-empty-msg { text-align: center; padding: 36px 20px; color: var(--text3); font-size: 13px; }
.cart-item { display: flex; align-items: center; gap: 8px; padding: 9px 14px; border-bottom: 1px solid var(--border); }
.cart-item-info { flex: 1; min-width: 0; }
.cart-item-name { font-size: 12.5px; font-weight: 500; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cart-item-sub { font-size: 11px; color: var(--text3); }
.qty-ctrl { display: flex; align-items: center; gap: 4px; }
.qty-btn { width: 22px; height: 22px; border-radius: 5px; background: var(--surface); border: 1px solid var(--border2); color: var(--text); cursor: pointer; font-size: 13px; display: flex; align-items: center; justify-content: center; transition: all 0.12s; flex-shrink: 0; }
.qty-btn:hover { background: var(--surface2); }
.qty-val { font-size: 13px; font-weight: 600; min-width: 18px; text-align: center; color: var(--text); }
.cart-item-price { font-size: 13px; font-weight: 600; color: var(--accent); min-width: 60px; text-align: right; }
.cart-remove { background: none; border: none; color: var(--text3); cursor: pointer; font-size: 13px; padding: 2px; transition: color 0.12s; flex-shrink: 0; }
.cart-remove:hover { color: var(--accent4); }
.cart-footer { border-top: 1px solid var(--border); padding: 14px 16px; }
.cart-total-row { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 10px; }
.cart-total-lbl { font-size: 13px; color: var(--text2); }
.cart-total-val { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 700; color: var(--text); }
.cart-currency { font-size: 14px; font-weight: 400; color: var(--text2); }
</style>

<div class="pos-shell">
    <!-- LEFT: products -->
    <div class="pos-left">
        <div class="pos-filter-bar">
            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" id="pos-search" class="search-input" placeholder="Search products…" oninput="filterProducts()">
            </div>
            <select id="pos-type" class="form-control" style="width:auto;padding:7px 12px" onchange="filterProducts()">
                <option value="">All types</option>
                @foreach($products->pluck('type')->unique()->sort() as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="product-grid" id="product-grid">
            @foreach($products as $product)
            <div class="product-tile {{ $product->isOutOfStock() ? 'disabled' : '' }}"
                 data-id="{{ $product->id }}"
                 data-name="{{ $product->name }}"
                 data-type="{{ $product->type }}"
                 data-price="{{ $product->price }}"
                 data-stock="{{ $product->stock_quantity }}"
                 onclick="{{ $product->isOutOfStock() ? '' : 'addToCart(this)' }}">
                <div class="tile-type">{{ $product->type }}</div>
                <div class="tile-name">{{ $product->name }}</div>
                <div class="tile-price">zł {{ number_format($product->price, 2) }}</div>
                <div class="tile-stock {{ $product->isOutOfStock() ? 'out' : ($product->isLowStock() ? 'low' : 'ok') }}">
                    @if($product->isOutOfStock()) ❌ Out of stock
                    @elseif($product->isLowStock()) ⚠️ Only {{ $product->stock_quantity }} left
                    @else {{ $product->stock_quantity }} in stock
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- RIGHT: cart -->
    <div class="cart-panel">
        <div class="cart-head">
            <div class="cart-title">🛒 Cart</div>
            <div class="cart-sub" id="cart-sub">No items added yet</div>
        </div>
        <div class="cart-items-wrap" id="cart-items">
            <div class="cart-empty-msg" id="cart-empty">Tap a product to add it</div>
        </div>
        <div class="cart-footer">
            <div class="cart-total-row">
                <span class="cart-total-lbl">Total</span>
                <span class="cart-total-val"><span class="cart-currency">zł </span><span id="cart-total">0.00</span></span>
            </div>
            <button class="btn btn-primary" style="width:100%" id="checkout-btn" onclick="showCheckout()" disabled>Proceed to Checkout →</button>
            <button class="btn btn-outline" style="width:100%;margin-top:7px" onclick="clearCart()">Clear Cart</button>
        </div>
    </div>
</div>

<!-- Checkout modal -->
<div id="checkout-modal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:20px">
    <div style="background:var(--bg2);border:1px solid var(--border2);border-radius:18px;width:100%;max-width:520px;box-shadow:0 8px 48px rgba(0,0,0,0.6);animation:slideUp 0.2s ease">
        <div style="padding:20px 24px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
            <div style="font-family:'Syne',sans-serif;font-size:17px;font-weight:700;color:var(--text)">Complete Order</div>
            <button onclick="closeCheckout()" style="background:var(--surface);border:none;color:var(--text2);width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:15px;display:flex;align-items:center;justify-content:center">✕</button>
        </div>
        <div style="padding:22px 24px">
            <form id="order-form" method="POST" action="{{ route('orders.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Customer Name *</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="Farm or person name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="customer_phone" class="form-control" placeholder="+48 …">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Delivery Address</label>
                    <input type="text" name="customer_address" class="form-control" placeholder="Street, City">
                </div>
                <div style="background:var(--bg3);border-radius:8px;padding:14px;margin-top:4px" id="order-summary-box"></div>
                <div id="order-hidden-inputs"></div>
                <div style="display:flex;gap:10px;margin-top:18px">
                    <button type="button" onclick="closeCheckout()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="flex:1">✅ Place Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
@keyframes slideUp { from { opacity:0; transform:translateY(16px) scale(0.97); } to { opacity:1; transform:none; } }
</style>

<script>
let cart = {};

function addToCart(tile) {
    const id = tile.dataset.id;
    const name = tile.dataset.name;
    const type = tile.dataset.type;
    const price = parseFloat(tile.dataset.price);
    const stock = parseInt(tile.dataset.stock);
    if (!cart[id]) cart[id] = { id, name, type, price, stock, qty: 0 };
    if (cart[id].qty >= stock) { alert('Not enough stock available.'); return; }
    cart[id].qty++;
    tile.classList.add('in-cart');
    renderCart();
}

function changeQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) { delete cart[id]; updateTileClass(id, false); }
    renderCart();
}

function removeFromCart(id) {
    delete cart[id];
    updateTileClass(id, false);
    renderCart();
}

function updateTileClass(id, inCart) {
    const tile = document.querySelector(`.product-tile[data-id="${id}"]`);
    if (tile) tile.classList.toggle('in-cart', inCart);
}

function clearCart() {
    Object.keys(cart).forEach(id => updateTileClass(id, false));
    cart = {};
    renderCart();
}

function renderCart() {
    const wrap = document.getElementById('cart-items');
    const empty = document.getElementById('cart-empty');
    const sub = document.getElementById('cart-sub');
    const totalEl = document.getElementById('cart-total');
    const btn = document.getElementById('checkout-btn');
    const keys = Object.keys(cart).filter(k => cart[k].qty > 0);
    if (keys.length === 0) {
        wrap.innerHTML = '<div class="cart-empty-msg" id="cart-empty">Tap a product to add it</div>';
        sub.textContent = 'No items added yet';
        totalEl.textContent = '0.00';
        btn.disabled = true;
        return;
    }
    let total = 0, totalQty = 0;
    let html = '';
    keys.forEach(id => {
        const item = cart[id];
        const sub_ = item.price * item.qty;
        total += sub_;
        totalQty += item.qty;
        html += `<div class="cart-item">
            <div class="cart-item-info">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-sub">${item.type} · zł ${item.price.toFixed(2)} ea</div>
            </div>
            <div class="qty-ctrl">
                <button class="qty-btn" onclick="changeQty('${id}',-1)">−</button>
                <span class="qty-val">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty('${id}',1)">+</button>
            </div>
            <div class="cart-item-price">zł ${sub_.toFixed(2)}</div>
            <button class="cart-remove" onclick="removeFromCart('${id}')">✕</button>
        </div>`;
    });
    wrap.innerHTML = html;
    sub.textContent = `${totalQty} item(s)`;
    totalEl.textContent = total.toFixed(2);
    btn.disabled = false;
}

function showCheckout() {
    const keys = Object.keys(cart).filter(k => cart[k].qty > 0);
    if (!keys.length) return;
    let total = 0;
    let summaryHtml = `<div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text3);margin-bottom:10px">Order Summary</div>`;
    let hiddenHtml = '';
    keys.forEach(id => {
        const item = cart[id];
        const sub = item.price * item.qty;
        total += sub;
        summaryHtml += `<div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;margin-bottom:6px">
            <span style="color:var(--text)">${item.name} <span style="color:var(--text3)">×${item.qty}</span></span>
            <span style="font-weight:600;color:var(--accent)">zł ${sub.toFixed(2)}</span>
        </div>`;
        hiddenHtml += `<input type="hidden" name="products[${id}][quantity]" value="${item.qty}">`;
    });
    summaryHtml += `<div style="border-top:1px solid var(--border);margin-top:8px;padding-top:8px;display:flex;justify-content:space-between;align-items:center">
        <span style="font-weight:600;color:var(--text)">Total</span>
        <span style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:var(--accent)">zł ${total.toFixed(2)}</span>
    </div>`;
    document.getElementById('order-summary-box').innerHTML = summaryHtml;
    document.getElementById('order-hidden-inputs').innerHTML = hiddenHtml;
    document.getElementById('checkout-modal').style.display = 'flex';
}

function closeCheckout() {
    document.getElementById('checkout-modal').style.display = 'none';
}

function filterProducts() {
    const q = document.getElementById('pos-search').value.toLowerCase();
    const t = document.getElementById('pos-type').value;
    document.querySelectorAll('.product-tile').forEach(tile => {
        const matchQ = !q || tile.dataset.name.toLowerCase().includes(q) || tile.dataset.type.toLowerCase().includes(q);
        const matchT = !t || tile.dataset.type === t;
        tile.style.display = matchQ && matchT ? '' : 'none';
    });
}

document.getElementById('checkout-modal').addEventListener('click', function(e) {
    if (e.target === this) closeCheckout();
});
</script>
@endsection
