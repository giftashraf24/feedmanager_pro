<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — FeedManager Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,400&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
    /* ─── RESET & ROOT ─── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
        --bg:        #0b0e14;
        --bg2:       #111520;
        --bg3:       #161b27;
        --surface:   #1c2232;
        --surface2:  #222a3e;
        --border:    rgba(255,255,255,0.07);
        --border2:   rgba(255,255,255,0.13);
        --accent:    #4ade80;
        --accent2:   #22d3ee;
        --accent3:   #f59e0b;
        --accent4:   #f43f5e;
        --accent5:   #a78bfa;
        --text:      #e2e8f0;
        --text2:     #94a3b8;
        --text3:     #64748b;
        --radius:    10px;
        --radius-lg: 16px;
        --shadow:    0 4px 24px rgba(0,0,0,0.4);
        --font-head: 'Syne', sans-serif;
        --font-body: 'DM Sans', sans-serif;
    }
    html, body { height: 100%; background: var(--bg); color: var(--text); font-family: var(--font-body); font-size: 14px; line-height: 1.6; }
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: var(--bg2); }
    ::-webkit-scrollbar-thumb { background: var(--surface2); border-radius: 3px; }

    /* ─── SHELL ─── */
    .app-shell { display: flex; height: 100vh; overflow: hidden; }

    /* ─── SIDEBAR ─── */
    .sidebar { width: 260px; min-width: 260px; background: var(--bg2); border-right: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; }
    .sidebar-logo { padding: 22px 18px 18px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); }
    .logo-icon { width: 34px; height: 34px; background: linear-gradient(135deg, var(--accent), var(--accent2)); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .logo-text { font-family: var(--font-head); font-size: 16px; font-weight: 800; color: var(--text); letter-spacing: -0.3px; }
    .logo-text span { color: var(--accent); }
    .sidebar-nav { flex: 1; overflow-y: auto; padding: 10px 8px; }
    .nav-section-title { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text3); padding: 10px 10px 5px; }
    .nav-item { display: flex; align-items: center; gap: 9px; padding: 8px 11px; border-radius: 8px; color: var(--text2); font-size: 13.5px; font-weight: 400; text-decoration: none; margin-bottom: 1px; position: relative; transition: all 0.15s; }
    .nav-item:hover { background: var(--surface); color: var(--text); }
    .nav-item.active { background: rgba(74,222,128,0.1); color: var(--accent); font-weight: 500; }
    .nav-item.active::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 3px; height: 60%; background: var(--accent); border-radius: 0 2px 2px 0; }
    .nav-icon { font-size: 15px; width: 20px; text-align: center; flex-shrink: 0; }
    .nav-badge { margin-left: auto; background: var(--accent4); color: #fff; border-radius: 20px; font-size: 10px; font-weight: 700; padding: 1px 7px; min-width: 18px; text-align: center; }
    .nav-badge.amber { background: rgba(245,158,11,0.15); color: var(--accent3); }
    .sidebar-footer { padding: 14px; border-top: 1px solid var(--border); }
    .sidebar-user { display: flex; align-items: center; gap: 9px; padding: 9px 10px; border-radius: 9px; background: var(--bg3); }
    .user-avatar { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: var(--font-head); font-size: 11px; font-weight: 700; flex-shrink: 0; }
    .avatar-admin { background: linear-gradient(135deg, #a78bfa, #7c3aed); }
    .avatar-staff { background: linear-gradient(135deg, #22d3ee, #0891b2); }
    .user-info { flex: 1; min-width: 0; }
    .user-name { font-size: 12.5px; font-weight: 500; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .user-role-label { font-size: 10px; color: var(--text3); }

    /* ─── MAIN ─── */
    .main-content { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
    .topbar { padding: 14px 26px; border-bottom: 1px solid var(--border); display: flex; align-items: center; background: var(--bg); flex-shrink: 0; gap: 16px; }
    .topbar-title { font-family: var(--font-head); font-size: 19px; font-weight: 700; color: var(--text); letter-spacing: -0.3px; }
    .topbar-actions { margin-left: auto; display: flex; gap: 8px; align-items: center; }
    .page-body { flex: 1; overflow-y: auto; padding: 22px 26px; }

    /* ─── BUTTONS ─── */
    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 15px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.15s; border: none; font-family: var(--font-body); text-decoration: none; white-space: nowrap; }
    .btn-primary { background: var(--accent); color: #0b0e14; font-weight: 600; }
    .btn-primary:hover { background: #22c55e; }
    .btn-secondary { background: var(--surface); color: var(--text); border: 1px solid var(--border2); }
    .btn-secondary:hover { background: var(--surface2); }
    .btn-danger { background: rgba(244,63,94,0.12); color: var(--accent4); border: 1px solid rgba(244,63,94,0.2); }
    .btn-danger:hover { background: rgba(244,63,94,0.22); }
    .btn-sm { padding: 5px 11px; font-size: 12px; }
    .btn-outline { background: transparent; color: var(--text2); border: 1px solid var(--border2); }
    .btn-outline:hover { background: var(--surface); color: var(--text); }

    /* ─── CARDS ─── */
    .card { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius-lg); }
    .card-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .card-title { font-family: var(--font-head); font-size: 14px; font-weight: 600; color: var(--text); }
    .card-body { padding: 20px; }

    /* ─── STAT CARDS ─── */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px; }
    .stat-card { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 18px 20px; position: relative; overflow: hidden; }
    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; }
    .stat-card.green::before { background: linear-gradient(90deg, var(--accent), transparent); }
    .stat-card.cyan::before { background: linear-gradient(90deg, var(--accent2), transparent); }
    .stat-card.amber::before { background: linear-gradient(90deg, var(--accent3), transparent); }
    .stat-card.rose::before { background: linear-gradient(90deg, var(--accent4), transparent); }
    .stat-icon { position: absolute; right: 16px; top: 16px; font-size: 22px; opacity: 0.25; }
    .stat-label { font-size: 11px; font-weight: 500; color: var(--text3); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 7px; }
    .stat-value { font-family: var(--font-head); font-size: 26px; font-weight: 700; color: var(--text); line-height: 1; }
    .stat-sub { font-size: 11px; color: var(--text3); margin-top: 5px; }

    /* ─── TABLES ─── */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--bg3); }
    th { padding: 9px 18px; text-align: left; font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text3); white-space: nowrap; }
    td { padding: 12px 18px; border-top: 1px solid var(--border); font-size: 13.5px; vertical-align: middle; }
    tr:hover td { background: rgba(255,255,255,0.015); }

    /* ─── BADGES ─── */
    .badge { display: inline-flex; align-items: center; gap: 3px; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .badge-green  { background: rgba(74,222,128,0.1);  color: var(--accent); }
    .badge-cyan   { background: rgba(34,211,238,0.1);  color: var(--accent2); }
    .badge-amber  { background: rgba(245,158,11,0.1);  color: var(--accent3); }
    .badge-rose   { background: rgba(244,63,94,0.1);   color: var(--accent4); }
    .badge-purple { background: rgba(167,139,250,0.1); color: var(--accent5); }
    .badge-gray   { background: rgba(148,163,184,0.1); color: var(--text2); }

    /* ─── FORMS ─── */
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text3); margin-bottom: 6px; }
    .form-control { width: 100%; background: var(--bg3); border: 1px solid var(--border2); border-radius: 8px; padding: 9px 12px; color: var(--text); font-family: var(--font-body); font-size: 13.5px; outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
    .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(74,222,128,0.1); }
    .form-control::placeholder { color: var(--text3); }
    .form-control.is-invalid { border-color: var(--accent4); }
    .invalid-feedback { font-size: 12px; color: var(--accent4); margin-top: 4px; display: block; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-hint { font-size: 11px; color: var(--text3); margin-top: 4px; }

    /* ─── ALERTS ─── */
    .alert { padding: 11px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 8px; }
    .alert-success { background: rgba(74,222,128,0.08);  border: 1px solid rgba(74,222,128,0.2);  color: #86efac; }
    .alert-error   { background: rgba(244,63,94,0.08);   border: 1px solid rgba(244,63,94,0.2);   color: #fda4af; }
    .alert-warning { background: rgba(245,158,11,0.08);  border: 1px solid rgba(245,158,11,0.2);  color: #fcd34d; }
    .alert-info    { background: rgba(34,211,238,0.08);  border: 1px solid rgba(34,211,238,0.2);  color: #67e8f9; }

    /* ─── ACTION BUTTONS ─── */
    .action-group { display: flex; gap: 5px; }
    .action-btn { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 500; cursor: pointer; border: none; font-family: var(--font-body); text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: all 0.15s; }
    .action-edit   { background: rgba(34,211,238,0.1);  color: var(--accent2); }
    .action-edit:hover { background: rgba(34,211,238,0.2); }
    .action-del    { background: rgba(244,63,94,0.1);   color: var(--accent4); }
    .action-del:hover  { background: rgba(244,63,94,0.2); }
    .action-view   { background: rgba(167,139,250,0.1); color: var(--accent5); }
    .action-view:hover { background: rgba(167,139,250,0.2); }
    .action-print  { background: rgba(245,158,11,0.1);  color: var(--accent3); }
    .action-print:hover { background: rgba(245,158,11,0.2); }

    /* ─── SEARCH ─── */
    .search-wrap { position: relative; }
    .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text3); font-size: 13px; pointer-events: none; }
    .search-input { background: var(--bg3); border: 1px solid var(--border); border-radius: 8px; padding: 7px 12px 7px 32px; color: var(--text); font-size: 13px; font-family: var(--font-body); outline: none; width: 220px; transition: border-color 0.2s; }
    .search-input:focus { border-color: var(--accent); }

    /* ─── MISC ─── */
    .divider { height: 1px; background: var(--border); margin: 16px 0; }
    .text-muted { color: var(--text3); }
    .text-sm { font-size: 12px; }
    .font-mono { font-family: monospace; }
    .empty-state { text-align: center; padding: 48px 20px; color: var(--text3); }
    .empty-state-icon { font-size: 40px; opacity: 0.4; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; }
    .access-denied { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 300px; text-align: center; gap: 10px; }
    .access-denied-icon { font-size: 48px; opacity: 0.3; }
    .section-title { font-family: var(--font-head); font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid var(--border); }

    @media print {
        .sidebar, .topbar, .no-print { display: none !important; }
        .page-body { padding: 0 !important; overflow: visible !important; }
        .main-content { overflow: visible !important; }
        .app-shell { display: block !important; }
        body { background: #fff !important; color: #000 !important; }
    }
    </style>
</head>
<body>
<div class="app-shell">
    <!-- ── Sidebar ── -->
    <nav class="sidebar no-print">
        <div class="sidebar-logo">
            <div class="logo-icon">🌾</div>
            <div class="logo-text">Feed<span>Manager</span></div>
        </div>
        <div class="sidebar-nav">
            <div class="nav-section-title">Overview</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>

            <div class="nav-section-title" style="margin-top:8px">Operations</div>
            <a href="{{ route('orders.create') }}" class="nav-item {{ request()->routeIs('orders.create') ? 'active' : '' }}">
                <span class="nav-icon">🛒</span> Point of Sale
            </a>
            <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                <span class="nav-icon">📦</span> Orders
            </a>
            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') && !request()->routeIs('products.create') ? 'active' : '' }}">
                <span class="nav-icon">🌾</span> Products
                @php $lowCount = \App\Models\Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<', 5)->count(); @endphp
                @if($lowCount > 0)
                    <span class="nav-badge amber">{{ $lowCount }}</span>
                @endif
            </a>

            @if(auth()->user()->isAdmin())
            <div class="nav-section-title" style="margin-top:8px">Administration</div>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> User Management
            </a>
            @endif

            <div class="nav-section-title" style="margin-top:8px">Account</div>
            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span class="nav-icon">👤</span> My Profile
            </a>
        </div>
        <div class="sidebar-footer">
            <div class="sidebar-user">
                @php
                    $initials = collect(explode(' ', auth()->user()->name))->map(fn($w) => strtoupper($w[0]))->take(2)->join('');
                    $avatarClass = auth()->user()->isAdmin() ? 'avatar-admin' : 'avatar-staff';
                @endphp
                <div class="user-avatar {{ $avatarClass }}">{{ $initials }}</div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role-label">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" title="Sign out" style="background:none;border:none;color:var(--text3);cursor:pointer;font-size:14px;padding:4px;border-radius:5px;transition:color 0.15s" onmouseover="this.style.color='#f43f5e'" onmouseout="this.style.color='var(--text3)'">⏻</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- ── Main Content ── -->
    <div class="main-content">
        <div class="topbar no-print">
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            <div class="topbar-actions">@yield('topbar-actions')</div>
        </div>
        <div class="page-body">
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">❌ {{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
@yield('scripts')
</body>
</html>
