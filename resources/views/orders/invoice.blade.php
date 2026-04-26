@extends('layouts.app')
@section('title', 'Invoice #' . $order->id)
@section('page-title', '🧾 Invoice #' . $order->id)

@section('topbar-actions')
    <a href="{{ route('orders.index') }}" class="btn btn-secondary no-print">← Orders</a>
    <button onclick="window.print()" class="btn btn-primary no-print">🖨️ Print</button>
@endsection

@section('content')
<style>
.invoice-wrap { max-width: 720px; margin: 0 auto; background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 40px; }
.invoice-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 36px; padding-bottom: 28px; border-bottom: 2px solid var(--border); }
.inv-brand { font-family: 'Syne', sans-serif; font-size: 24px; font-weight: 800; }
.inv-brand span { color: var(--accent); }
.inv-tagline { font-size: 12px; color: var(--text3); margin-top: 4px; }
.inv-meta { text-align: right; }
.inv-num { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 700; color: var(--text2); }
.inv-date { font-size: 12px; color: var(--text3); margin-top: 4px; }
.inv-section-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: var(--text3); margin-bottom: 8px; }
.inv-customer { background: var(--bg3); border-radius: 8px; padding: 14px 16px; margin-bottom: 28px; }
.inv-customer-name { font-weight: 600; color: var(--text); font-size: 14px; }
.inv-customer-detail { font-size: 12px; color: var(--text2); margin-top: 3px; }
.inv-table { width: 100%; border-collapse: collapse; margin-bottom: 28px; }
.inv-table th { padding: 8px 12px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text3); border-bottom: 1px solid var(--border2); }
.inv-table td { padding: 13px 12px; border-bottom: 1px solid var(--border); font-size: 13.5px; }
.inv-table tr:last-child td { border-bottom: none; }
.inv-totals { border-top: 1px solid var(--border); padding-top: 18px; display: flex; flex-direction: column; align-items: flex-end; gap: 5px; }
.inv-total-row { display: flex; gap: 56px; font-size: 13px; color: var(--text2); }
.inv-total-row.grand { font-family: 'Syne', sans-serif; font-size: 19px; font-weight: 700; color: var(--text); margin-top: 8px; }
.inv-total-row.grand span:last-child { color: var(--accent); }
.inv-footer { margin-top: 32px; padding-top: 20px; border-top: 1px solid var(--border); text-align: center; font-size: 12px; color: var(--text3); }
@media print {
    body { background: #fff !important; }
    .invoice-wrap { background: #fff !important; border: none !important; color: #000 !important; padding: 0 !important; max-width: 100% !important; }
    .inv-brand, .inv-num { color: #000 !important; }
    .inv-brand span { color: #16a34a !important; }
    .inv-customer { background: #f8f8f8 !important; }
    .inv-total-row.grand span:last-child { color: #16a34a !important; }
}
</style>

<div class="invoice-wrap">
    <div class="invoice-top">
        <div>
            <div class="inv-brand">Feed<span>Manager</span></div>
            <div class="inv-tagline">Premium Feed Solutions</div>
        </div>
        <div class="inv-meta">
            <div class="inv-num">Invoice #{{ $order->id }}</div>
            <div class="inv-date">{{ $order->created_at->format('F d, Y') }}</div>
        </div>
    </div>

    <div class="inv-section-label">Bill To</div>
    <div class="inv-customer">
        <div class="inv-customer-name">{{ $order->customer_name }}</div>
        @if($order->customer_phone)
        <div class="inv-customer-detail">📞 {{ $order->customer_phone }}</div>
        @endif
        @if($order->customer_address)
        <div class="inv-customer-detail">📍 {{ $order->customer_address }}</div>
        @endif
    </div>

    <table class="inv-table">
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
                <td style="font-weight:500">{{ $item->product?->name ?? 'Deleted product' }}</td>
                <td><span class="badge badge-cyan">{{ $item->product?->type ?? '—' }}</span></td>
                <td>{{ $item->quantity }}</td>
                <td>zł {{ number_format($item->price, 2) }}</td>
                <td style="text-align:right;font-weight:600">zł {{ number_format($item->subtotal(), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="inv-totals">
        <div class="inv-total-row grand">
            <span>Total</span>
            <span>zł {{ number_format($order->total_price, 2) }}</span>
        </div>
    </div>

    <div class="inv-footer">
        Thank you for your business! · Payment due within 30 days.<br>
        Processed by {{ $order->user?->name ?? 'System' }} · FeedManager Pro
    </div>
</div>
@endsection
