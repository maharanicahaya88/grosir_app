@extends('layouts.app')
@section('title', 'Pemesanan')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-1" style="font-size:20px;font-weight:700;color:#1a1a1a">Pemesanan</h4>
        <p class="mb-0" style="font-size:13px;color:#888">Kelola semua transaksi pemesanan</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('pemesanan.export-excel', request()->query()) }}" class="btn btn-success btn-sm">
            <i class="ti ti-file-spreadsheet me-1"></i>Excel
        </a>
        <a href="{{ route('pemesanan.create') }}" class="btn btn-primary btn-sm">
            <i class="ti ti-plus me-1"></i>Buat Pesanan
        </a>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="ti ti-circle-check me-1"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eef2ff">
                <i class="ti ti-clipboard-list" style="color:#4f46e5"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Pesanan</div>
                <div class="stat-value" style="color:#4f46e5">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef9c3">
                <i class="ti ti-clock" style="color:#d97706"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Menunggu</div>
                <div class="stat-value" style="color:#d97706">{{ $stats['menunggu'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7">
                <i class="ti ti-circle-check" style="color:#16a34a"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Disetujui</div>
                <div class="stat-value" style="color:#16a34a">{{ $stats['disetujui'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2">
                <i class="ti ti-circle-x" style="color:#dc2626"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Dibatalkan</div>
                <div class="stat-value" style="color:#dc2626">{{ $stats['dibatalkan'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="filter-card mb-4">
    <form method="GET" action="{{ route('pemesanan.index') }}">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <div style="position:relative">
                    <i class="ti ti-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#aaa;font-size:15px"></i>
                    <input type="text" name="search" class="form-control form-control-sm"
                        style="padding-left:32px"
                        placeholder="Cari no. pesanan / konsumen..."
                        value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu"   @selected(request('status')=='menunggu')>Menunggu</option>
                    <option value="diproses"   @selected(request('status')=='diproses')>Diproses</option>
                    <option value="disetujui"  @selected(request('status')=='disetujui')>Disetujui</option>
                    <option value="dibatalkan" @selected(request('status')=='dibatalkan')>Dibatalkan</option>
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
                <a href="{{ route('pemesanan.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-refresh me-1"></i>Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title">Daftar Pesanan</span>
        <span class="table-count">{{ $pesanan->total() }} data</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="padding-left:20px">No. Pesanan</th>
                    <th>Tanggal</th>
                    <th>Konsumen</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="padding-right:20px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $p)
                <tr>
                    <td style="padding-left:20px">
                        <a href="{{ route('pemesanan.show', $p) }}"
                           class="fw-bold text-decoration-none" style="color:#4f46e5">
                            {{ $p->no_pemesanan }}
                        </a>
                    </td>
                    <td style="color:#888;font-size:13px">
                        {{ $p->tanggal_pesan->format('d M Y') }}
                    </td>
                    <td style="font-weight:600;color:#1a1a1a">{{ $p->konsumen->nama_konsumen }}</td>
                    <td class="text-end fw-bold" style="color:#1a1a1a">
                        Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        <span class="status-badge badge-{{ $p->status_pesan }}">
                            {{ ucfirst($p->status_pesan) }}
                        </span>
                    </td>
                    <td class="text-center" style="padding-right:20px">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('pemesanan.show', $p) }}"
                               class="btn btn-sm btn-outline-secondary" title="Detail">
                                <i class="ti ti-eye"></i>
                            </a>
                            @if($p->status_pesan === 'menunggu')
                            <a href="{{ route('pemesanan.edit', $p) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('pemesanan.approve', $p) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-success" title="Setujui"
                                    onclick="return confirm('Setujui pesanan {{ $p->no_pemesanan }}?')">
                                    <i class="ti ti-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('pemesanan.destroy', $p) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Batalkan"
                                    onclick="return confirm('Batalkan pesanan {{ $p->no_pemesanan }}?')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('pemesanan.cetak-pdf', $p) }}"
                               class="btn btn-sm btn-outline-secondary" target="_blank" title="Cetak PDF">
                                <i class="ti ti-printer"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5" style="color:#ccc">
                        <i class="ti ti-clipboard-x" style="font-size:40px;display:block;margin-bottom:10px"></i>
                        <span style="font-size:14px;color:#aaa">Belum ada data pesanan.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pesanan->hasPages())
    <div class="px-4 py-3" style="border-top:1px solid #f0f0ee">
        {{ $pesanan->links() }}
    </div>
    @endif
</div>

@endsection