@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', '📊 Dashboard')

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card green">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">zł {{ number_format($totalSales, 2) }}</div>
        <div class="stat-sub">All-time orders</div>
    </div>
    <div class="stat-card cyan">
        <div class="stat-icon">📦</div>
        <div class="stat-label">Total Orders</div>
        <div class="stat-value">{{ $totalOrders }}</div>
        <div class="stat-sub">Transactions placed</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon">🌾</div>
        <div class="stat-label">Products</div>
        <div class="stat-value">{{ $totalProducts }}</div>
        <div class="stat-sub">{{ $outOfStock }} out of stock</div>
    </div>
    <div class="stat-card rose">
        <div class="stat-icon">⚠️</div>
        <div class="stat-label">Low Stock</div>
        <div class="stat-value">{{ $lowStock->count() }}</div>
        <div class="stat-sub">Need restocking</div>
    </div>
</div>

<!-- Charts row -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:12px;margin-bottom:20px">
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Sales — Last 7 Days</div>
                <div class="text-sm text-muted" style="margin-top:2px">Revenue & order volume</div>
            </div>
            <div style="display:flex;gap:12px;font-size:11px">
                <span style="display:flex;align-items:center;gap:5px;color:var(--text2)"><span style="width:10px;height:10px;border-radius:50%;background:var(--accent);display:inline-block"></span>Revenue</span>
                <span style="display:flex;align-items:center;gap:5px;color:var(--text2)"><span style="width:10px;height:10px;border-radius:50%;background:var(--accent2);display:inline-block"></span>Orders</span>
            </div>
        </div>
        <div class="card-body">
            <canvas id="salesChart" style="max-height:210px"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">By Category</div>
        </div>
        <div class="card-body">
            <canvas id="catChart" style="max-height:210px"></canvas>
        </div>
    </div>
</div>

<!-- Bottom row -->
<div style="display:grid;grid-template-columns:1fr 300px;gap:12px">
    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Recent Orders</div>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Total</th></tr></thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td><span class="badge badge-purple">#{{ $order->id }}</span></td>
                        <td style="font-weight:500">{{ $order->customer_name }}</td>
                        <td class="text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                        <td style="font-weight:600;color:var(--accent)">zł {{ number_format($order->total_price, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--text3);padding:28px">No orders yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Low Stock -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">⚠️ Low Stock Alerts</div>
        </div>
        <div class="card-body" style="padding-top:12px">
            @forelse($lowStock as $product)
            <div style="display:flex;align-items:center;justify-content:space-between;background:var(--bg3);border-radius:8px;padding:9px 12px;margin-bottom:8px">
                <div>
                    <div style="font-size:13px;font-weight:500;color:var(--text)">{{ $product->name }}</div>
                    <div class="text-sm text-muted">{{ $product->type }}</div>
                </div>
                <span class="badge badge-amber">{{ $product->stock_quantity }} left</span>
            </div>
            @empty
            <div style="text-align:center;padding:24px 0;color:var(--text3);font-size:13px">✅ All products well-stocked</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
Chart.defaults.color = '#64748b';
Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';

// Sales chart data from server
const salesData = @json($salesData);
const last7 = [];
for (let i = 6; i >= 0; i--) {
    const d = new Date(); d.setDate(d.getDate() - i);
    const key = d.toISOString().split('T')[0];
    const found = salesData.find(s => s.date === key);
    last7.push({ date: d.toLocaleDateString('en', {month:'short',day:'numeric'}), total: found ? parseFloat(found.total) : 0, count: found ? parseInt(found.count) : 0 });
}

new Chart(document.getElementById('salesChart'), {
    type: 'bar',
    data: {
        labels: last7.map(d => d.date),
        datasets: [
            { label: 'Revenue', data: last7.map(d => d.total), backgroundColor: 'rgba(74,222,128,0.2)', borderColor: '#4ade80', borderWidth: 2, borderRadius: 6, yAxisID: 'y' },
            { label: 'Orders', data: last7.map(d => d.count), type: 'line', borderColor: '#22d3ee', borderWidth: 2, pointBackgroundColor: '#22d3ee', pointRadius: 4, tension: 0.4, yAxisID: 'y1' }
        ]
    },
    options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false } }, scales: { y: { grid: { color: 'rgba(255,255,255,0.04)' } }, y1: { display: false, position: 'right' } } }
});

// Category doughnut
const catData = @json($categoryData);
new Chart(document.getElementById('catChart'), {
    type: 'doughnut',
    data: {
        labels: catData.map(c => c.type),
        datasets: [{ data: catData.map(c => c.count), backgroundColor: ['rgba(74,222,128,0.75)','rgba(34,211,238,0.75)','rgba(245,158,11,0.75)','rgba(167,139,250,0.75)','rgba(244,63,94,0.75)'], borderWidth: 0, hoverOffset: 6 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { padding: 12, font: { size: 11 } } } } }
});
</script>
@endsection
