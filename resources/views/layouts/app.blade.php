<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GrosirApp') - GrosirApp</title>
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background: #f0f0ee; font-family: 'Nunito', sans-serif; margin: 0; }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 230px; height: 100vh;
            background: #1e1b4b;
            display: flex; flex-direction: column;
            z-index: 200;
            transition: transform .25s ease;
        }
        .sidebar-logo {
            padding: 20px 20px 18px;
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .logo-box {
            width: 34px; height: 34px;
            background: #4f46e5;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-box i { font-size: 18px; color: #fff; }
        .logo-text { font-size: 16px; font-weight: 700; color: #fff; }
        .sidebar-section {
            padding: 20px 16px 6px;
            font-size: 10px; font-weight: 700;
            color: rgba(255,255,255,0.3);
            letter-spacing: .1em; text-transform: uppercase;
        }
        .nav-item-side {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; margin: 2px 10px;
            font-size: 13px; color: rgba(255,255,255,0.6);
            border-radius: 10px; text-decoration: none;
            transition: all .15s;
        }
        .nav-item-side i { font-size: 18px; }
        .nav-item-side:hover {
            background: rgba(255,255,255,0.08);
            color: #fff; text-decoration: none;
        }
        .nav-item-side.active {
            background: #4f46e5;
            color: #fff; font-weight: 600;
        }
        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.08);
            padding: 10px;
        }
        .sidebar-footer .nav-item-side { color: rgba(255,255,255,0.5); }
        .sidebar-footer .nav-item-side:hover { color: #fff; background: rgba(255,255,255,0.08); }

        /* ── MAIN ── */
        .main-wrapper { margin-left: 230px; min-height: 100vh; }

        /* ── TOPBAR ── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e8e8e5;
            padding: 0 28px;
            height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 99;
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; }
        .hamburger {
            display: none;
            background: none; border: none;
            font-size: 22px; color: #555; cursor: pointer; padding: 4px;
        }
        .topbar-title { font-size: 15px; font-weight: 700; color: #1a1a1a; }
        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .topbar-badge {
            background: #f0f0ee; border-radius: 8px;
            padding: 6px 10px; font-size: 12px; color: #666;
            display: flex; align-items: center; gap: 6px;
        }
        .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: #eef2ff; color: #4f46e5;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700;
        }
        .user-name { font-size: 13px; color: #444; font-weight: 600; }

        /* ── CONTENT ── */
        .content-area { padding: 24px 28px; }

        /* ── STAT CARDS ── */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e8e8e5;
            padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            transition: border-color .2s;
        }
        .stat-card:hover { border-color: #c7c3f8; }
        .stat-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }
        .stat-info { flex: 1; }
        .stat-label { font-size: 12px; color: #888; margin-bottom: 2px; }
        .stat-value { font-size: 24px; font-weight: 700; line-height: 1.2; }

        /* ── FILTER CARD ── */
        .filter-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e8e8e5;
            padding: 16px 20px;
        }

        /* ── TABLE CARD ── */
        .table-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e8e8e5;
            overflow: hidden;
        }
        .table-card-header {
            padding: 14px 20px;
            border-bottom: 1px solid #f0f0ee;
            display: flex; align-items: center; justify-content: space-between;
        }
        .table-card-title { font-size: 14px; font-weight: 700; color: #1a1a1a; }
        .table-count { font-size: 12px; color: #888; background: #f5f5f3; padding: 3px 10px; border-radius: 20px; }
        .table thead th {
            background: #fafaf8;
            font-size: 11px; font-weight: 700; color: #999;
            border-bottom: 1px solid #e8e8e5;
            padding: 11px 16px; white-space: nowrap;
            text-transform: uppercase; letter-spacing: .05em;
        }
        .table tbody td {
            padding: 13px 16px; font-size: 13.5px;
            border-bottom: 1px solid #f5f5f3;
            vertical-align: middle;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover { background: #fafaf8; }

        /* ── BADGES ── */
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
        .badge-menunggu  { background: #fef9c3; color: #854d0e; }
        .badge-menunggu::before  { background: #d97706; }
        .badge-diproses  { background: #dbeafe; color: #1e40af; }
        .badge-diproses::before  { background: #3b82f6; }
        .badge-disetujui { background: #dcfce7; color: #166534; }
        .badge-disetujui::before { background: #16a34a; }
        .badge-dibatalkan{ background: #fee2e2; color: #991b1b; }
        .badge-dibatalkan::before{ background: #dc2626; }

        /* ── FORMS ── */
        .form-control, .form-select {
            border: 1px solid #e0e0dd; border-radius: 8px;
            font-size: 13px; color: #1a1a1a;
            font-family: 'Nunito', sans-serif;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }

        /* ── BUTTONS ── */
        .btn { font-family: 'Nunito', sans-serif; border-radius: 8px; font-size: 13px; font-weight: 600; }
        .btn-primary { background: #4f46e5; border-color: #4f46e5; }
        .btn-primary:hover { background: #4338ca; border-color: #4338ca; }
        .btn-success { background: #16a34a; border-color: #16a34a; }
        .btn-success:hover { background: #15803d; border-color: #15803d; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-outline-secondary { border-color: #e0e0dd; color: #666; }
        .btn-outline-secondary:hover { background: #f5f5f3; border-color: #ccc; color: #333; }
        .btn-outline-primary { border-color: #c7c3f8; color: #4f46e5; }
        .btn-outline-primary:hover { background: #eef2ff; border-color: #4f46e5; color: #4f46e5; }
        .btn-outline-success { border-color: #bbf7d0; color: #16a34a; }
        .btn-outline-success:hover { background: #dcfce7; }
        .btn-outline-danger { border-color: #fecaca; color: #dc2626; }
        .btn-outline-danger:hover { background: #fee2e2; }

        /* ── PAGINATION ── */
        .pagination .page-link {
            border-radius: 8px !important; margin: 0 2px;
            font-size: 13px; color: #555;
            border: 1px solid #e0e0dd;
            font-family: 'Nunito', sans-serif;
        }
        .pagination .page-item.active .page-link { background: #4f46e5; border-color: #4f46e5; }

        /* ── ALERT ── */
        .alert { border-radius: 12px; font-size: 13px; border: none; }
        .alert-success { background: #dcfce7; color: #166534; }

        /* ── OVERLAY ── */
        .sidebar-overlay {
            display: none; position: fixed;
            inset: 0; background: rgba(0,0,0,0.4);
            z-index: 199;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .main-wrapper { margin-left: 0; }
            .hamburger { display: flex; }
            .content-area { padding: 16px; }
            .topbar { padding: 0 16px; }
            .user-name { display: none; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-box"><i class="ti ti-shopping-cart"></i></div>
        <span class="logo-text">GrosirApp</span>
    </div>
    <div class="pt-2">
        <div class="sidebar-section">Menu Utama</div>
        <a href="{{ route('pemesanan.index') }}" class="nav-item-side {{ request()->routeIs('pemesanan.*') ? 'active' : '' }}">
            <i class="ti ti-clipboard-list"></i> Pemesanan
        </a>
        <a href="{{ route('konsumen.index') }}" class="nav-item-side {{ request()->routeIs('konsumen.*') ? 'active' : '' }}">
            <i class="ti ti-users"></i> Konsumen
        </a>
        <a href="{{ route('supplier.index') }}" class="nav-item-side {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
            <i class="ti ti-truck"></i> Supplier
        </a>
        <a href="{{ route('barang.index') }}" class="nav-item-side {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <i class="ti ti-package"></i> Barang
        </a>
        <div class="sidebar-section">Analitik</div>
        <a href="{{ route('laporan.pemesanan') }}" class="nav-item-side {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="ti ti-chart-bar"></i> Laporan
        </a>
        <a href="{{ route('pembayaran.index') }}" class="nav-item-side {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
            <i class="ti ti-cash"></i> Pembayaran
        </a>
    </div>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item-side w-100 border-0 bg-transparent text-start">
                <i class="ti ti-logout"></i> Logout
            </button>
        </form>
    </div>
</div>

<div class="main-wrapper">
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()">
                <i class="ti ti-menu-2"></i>
            </button>
            <div class="topbar-title">@yield('title', 'GrosirApp')</div>
        </div>
        <div class="topbar-right">
            <div class="topbar-badge d-none d-md-flex">
                <i class="ti ti-calendar" style="font-size:14px"></i>
                {{ now()->translatedFormat('d M Y') }}
            </div>
            <div class="avatar">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</div>
            <span class="user-name">{{ Auth::user()->name ?? '' }}</span>
        </div>
    </div>
    <div class="content-area">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('open');
}
</script>
@stack('scripts')
</body>
</html>