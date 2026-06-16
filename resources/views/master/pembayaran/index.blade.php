@extends('layouts.app')
@section('title', 'Pembayaran')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-1" style="font-size:20px;font-weight:700;color:#1a1a1a">Pembayaran</h4>
        <p class="mb-0" style="font-size:13px;color:#888">Kelola semua transaksi pembayaran</p>
    </div>
    <a href="{{ route('pembayaran.create') }}" class="btn btn-primary btn-sm">
        <i class="ti ti-plus me-1"></i>Tambah Pembayaran
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="ti ti-circle-check me-1"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eef2ff">
                <i class="ti ti-receipt" style="color:#4f46e5"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value" style="color:#4f46e5">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7">
                <i class="ti ti-circle-check" style="color:#16a34a"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Lunas</div>
                <div class="stat-value" style="color:#16a34a">{{ $stats['lunas'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2">
                <i class="ti ti-clock" style="color:#dc2626"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Belum Lunas</div>
                <div class="stat-value" style="color:#dc2626">{{ $stats['belum_lunas'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7">
                <i class="ti ti-coin" style="color:#16a34a"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Diterima</div>
                <div class="stat-value" style="color:#16a34a;font-size:16px">
                    Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="filter-card mb-4">
    <form method="GET" action="{{ route('pembayaran.index') }}">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <div style="position:relative">
                    <i class="ti ti-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#aaa;font-size:15px"></i>
                    <input type="text" name="search" class="form-control form-control-sm"
                        style="padding-left:32px"
                        placeholder="Cari no. pembayaran / pesanan..."
                        value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="lunas" @selected(request('status')=='lunas')>Lunas</option>
                    <option value="belum_lunas" @selected(request('status')=='belum_lunas')>Belum Lunas</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-6 col-md-2">
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-6 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="ti ti-filter me-1"></i>Filter
                </button>
                <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-refresh me-1"></i>Reset
                </a>
            </div>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title">Daftar Pembayaran</span>
        <span class="table-count">{{ $pembayaran->total() }} data</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="padding-left:20px">No. Pembayaran</th>
                    <th>No. Pesanan</th>
                    <th>Konsumen</th>
                    <th>Tgl Bayar</th>
                    <th>Metode</th>
                    <th class="text-end">Jumlah Bayar</th>
                    <th class="text-end">Sisa</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="padding-right:20px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayaran as $p)
                <tr>
                    <td style="padding-left:20px;font-weight:700;color:#4f46e5">{{ $p->no_pembayaran }}</td>
                    <td>
                        <a href="{{ route('pemesanan.show', $p->pemesanan) }}"
                           class="text-decoration-none" style="color:#4f46e5;font-weight:600">
                            {{ $p->pemesanan->no_pemesanan }}
                        </a>
                    </td>
                    <td style="font-weight:600">{{ $p->pemesanan->konsumen->nama_konsumen }}</td>
                    <td style="color:#888;font-size:13px">{{ $p->tanggal_bayar->format('d M Y') }}</td>
                    <td>
                        <span