{{-- resources/views/laporan/pemesanan.blade.php --}}
@extends('layouts.app')
@section('title', 'Laporan Pemesanan')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-semibold">Laporan Pemesanan</h4>
        <a href="{{ route('laporan.pemesanan.pdf') . '?' . http_build_query(request()->all()) }}"
           target="_blank" class="btn btn-danger btn-sm">
            <i class="ti ti-file-type-pdf me-1"></i> Export PDF
        </a>
    </div>

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label form-label-sm">Konsumen</label>
                    <input type="text" name="konsumen" class="form-control form-control-sm"
                        placeholder="Nama konsumen..." value="{{ request('konsumen') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        @foreach(['menunggu','diproses','disetujui','dibatalkan'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-primary"><i class="ti ti-filter me-1"></i>Filter</button>
                    <a href="{{ route('laporan.pemesanan') }}" class="btn btn-sm btn-light ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Total Pesanan</div>
                    <div class="fs-4 fw-bold text-primary">{{ number_format($stats['total']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Total Nilai</div>
                    <div class="fs-5 fw-bold text-success">Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Disetujui</div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($stats['disetujui']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Menunggu / Diproses</div>
                    <div class="fs-4 fw-bold text-warning">
                        {{ number_format($stats['menunggu'] + $stats['diproses']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Tabel Pesanan --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header fw-semibold">Daftar Pesanan</div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No. Pesanan</th>
                                <th>Konsumen</th>
                                <th>Tgl Pesan</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanan as $p)
                            <tr>
                                <td class="ps-4 text-primary fw-medium">{{ $p->no_pemesanan }}</td>
                                <td>{{ $p->konsumen->nama_konsumen ?? '—' }}</td>
                                <td class="text-muted">{{ $p->tanggal_pesan?->format('d/m/Y') ?? '—' }}</td>
                                <td class="text-end fw-medium">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @php
                                        $badge = match($p->status_pesan) {
                                            'disetujui'  => 'success',
                                            'diproses'   => 'info',
                                            'dibatalkan' => 'danger',
                                            default      => 'warning',
                                        };
                                    @endphp
                                    <span class="badge text-bg-{{ $badge }}">{{ ucfirst($p->status_pesan) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="ti ti-clipboard-off d-block fs-3 mb-2"></i>
                                    Tidak ada data pesanan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pesanan->hasPages())
                <div class="card-footer">{{ $pesanan->links() }}</div>
                @endif
            </div>
        </div>

        {{-- Rekap Konsumen --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header fw-semibold">Top 10 Konsumen</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($rekapKonsumen as $rk)
                        <li class="list-group-item d-flex justify-content-between align-items-start py-3 px-3">
                            <div>
                                <div class="fw-medium">{{ $rk->nama_konsumen }}</div>
                                <small class="text-muted">{{ $rk->jumlah_pesanan }} pesanan</small>
                            </div>
                            <span class="text-end text-success fw-semibold small">
                                Rp {{ number_format($rk->total_nilai, 0, ',', '.') }}
                            </span>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted py-4">Belum ada data.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Status Breakdown --}}
            <div class="card mt-3">
                <div class="card-header fw-semibold">Rekap Status</div>
                <div class="card-body">
                    @foreach([
                        ['label' => 'Menunggu',   'key' => 'menunggu',   'color' => 'warning'],
                        ['label' => 'Diproses',   'key' => 'diproses',   'color' => 'info'],
                        ['label' => 'Disetujui',  'key' => 'disetujui',  'color' => 'success'],
                        ['label' => 'Dibatalkan', 'key' => 'dibatalkan', 'color' => 'danger'],
                    ] as $item)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">{{ $item['label'] }}</span>
                        <span class="badge text-bg-{{ $item['color'] }} fs-6 px-3">
                            {{ $stats[$item['key']] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection