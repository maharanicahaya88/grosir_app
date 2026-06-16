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
            <div class="stat-icon" style="background:#eef2ff"><i class="ti ti-receipt" style="color:#4f46e5"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value" style="color:#4f46e5">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7"><i class="ti ti-circle-check" style="color:#16a34a"></i></div>
            <div class="stat-info">
                <div class="stat-label">Lunas</div>
                <div class="stat-value" style="color:#16a34a">{{ $stats['lunas'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2"><i class="ti ti-clock" style="color:#dc2626"></i></div>
            <div class="stat-info">
                <div class="stat-label">Belum Lunas</div>
                <div class="stat-value" style="color:#dc2626">{{ $stats['belum_lunas'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7"><i class="ti ti-coin" style="color:#16a34a"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Diterima</div>
                <div class="stat-value" style="color:#16a34a;font-size:16px">Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>
<div class="filter-card mb-4">
    <form method="GET" action="{{ route('pembayaran.index') }}">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari no. pembayaran / pesanan..." value="{{ request('search') }}">
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
                <button type="submit" class="btn btn-sm btn-primary"><i class="ti ti-filter me-1"></i>Filter</button>
                <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-refresh me-1"></i>Reset</a>
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
                    <td><a href="{{ route('pemesanan.show', $p->pemesanan) }}" class="text-decoration-none" style="color:#4f46e5;font-weight:600">{{ $p->pemesanan->no_pemesanan }}</a></td>
                    <td style="font-weight:600">{{ $p->pemesanan->konsumen->nama_konsumen }}</td>
                    <td style="color:#888;font-size:13px">{{ $p->tanggal_bayar->format('d M Y') }}</td>
                    <td><span style="background:#f0f0ee;color:#555;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;text-transform:capitalize">{{ $p->metode_bayar }}</span></td>
                    <td class="text-end fw-bold" style="color:#16a34a">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="text-end" style="color:{{ $p->sisa_tagihan > 0 ? '#dc2626' : '#16a34a' }};font-weight:600">Rp {{ number_format($p->sisa_tagihan, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($p->status_bayar === 'lunas')
                            <span class="status-badge badge-disetujui">Lunas</span>
                        @else
                            <span class="status-badge badge-menunggu">Belum Lunas</span>
                        @endif
                    </td>
                    <td class="text-center" style="padding-right:20px">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('pembayaran.show', $p) }}" class="btn btn-sm btn-outline-secondary" title="Detail"><i class="ti ti-eye"></i></a>
                            <form action="{{ route('pembayaran.destroy', $p) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data pembayaran ini?')"><i class="ti ti-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5" style="color:#ccc">
                        <i class="ti ti-receipt-off" style="font-size:40px;display:block;margin-bottom:10px"></i>
                        <span style="font-size:14px;color:#aaa">Belum ada data pembayaran.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pembayaran->hasPages())
    <div class="px-4 py-3" style="border-top:1px solid #f0f0ee">{{ $pembayaran->links() }}</div>
    @endif
</div>
@endsection
