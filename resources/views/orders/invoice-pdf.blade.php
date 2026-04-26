<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 13px; color: #1e293b; background: #fff; padding: 40px; }
.header { display: flex; justify-content: space-between; border-bottom: 2px solid #4ade80; padding-bottom: 20px; margin-bottom: 24px; }
.brand { font-size: 24px; font-weight: 700; color: #0f172a; }
.brand span { color: #16a34a; }
.tagline { font-size: 11px; color: #64748b; margin-top: 4px; }
.invoice-meta { text-align: right; }
.invoice-num { font-size: 20px; font-weight: 700; color: #475569; }
.invoice-date { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.section-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: #94a3b8; margin-bottom: 6px; }
.customer-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; margin-bottom: 24px; }
.customer-name { font-weight: 600; font-size: 14px; color: #0f172a; }
.customer-detail { font-size: 12px; color: #64748b; margin-top: 3px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
thead { background: #f1f5f9; }
th { padding: 8px 12px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; }
td { padding: 12px 12px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
.totals { border-top: 2px solid #e2e8f0; padding-top: 16px; text-align: right; }
.grand { font-size: 18px; font-weight: 700; color: #0f172a; }
.grand-amount { color: #16a34a; }
.footer { margin-top: 40px; padding-top: 16px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 11px; color: #94a3b8; }
</style>
</head>
<body>
<div class="header">
    <div>
        <div class="brand">Feed<span>Manager</span></div>
        <div class="tagline">Premium Feed Solutions</div>
    </div>
    <div class="invoice-meta">
        <div class="invoice-num">Invoice #{{ $order->id }}</div>
        <div class="invoice-date">{{ $order->created_at->format('F d, Y') }}</div>
    </div>
</div>

<div class="section-label">Bill To</div>
<div class="customer-box">
    <div class="customer-name">{{ $order->customer_name }}</div>
    @if($order->customer_phone)
    <div class="customer-detail">Phone: {{ $order->customer_phone }}</div>
    @endif
    @if($order->customer_address)
    <div class="customer-detail">Address: {{ $order->customer_address }}</div>
    @endif
</div>

<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Type</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th style="text-align:right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td><strong>{{ $item->product?->name ?? 'Deleted product' }}</strong></td>
            <td>{{ $item->product?->type ?? '—' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>zł {{ number_format($item->price, 2) }}</td>
            <td style="text-align:right;font-weight:600;">zł {{ number_format($item->subtotal(), 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="totals">
    <div class="grand">Total: <span class="grand-amount">zł {{ number_format($order->total_price, 2) }}</span></div>
</div>

<div class="footer">
    Thank you for your business! &middot; Payment due within 30 days.<br>
    Processed by {{ $order->user?->name ?? 'System' }} &middot; FeedManager Pro
</div>
</body>
</html>
