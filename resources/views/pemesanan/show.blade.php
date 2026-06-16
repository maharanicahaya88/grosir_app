{{-- resources/views/pemesanan/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Pesanan ' . $pemesanan->no_pemesanan)

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('pemesanan.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-arrow-left"></i>
            </a>
            <h4 class="mb-0 fw-semibold">Detail Pesanan</h4>
            @php
                $badge = match($pemesanan->status_pesan) {
                    'menunggu'   => 'warning',
                    'diproses'   => 'info',
                    'disetujui'  => 'success',
                    'dibatalkan' => 'danger',
                    default      => 'secondary',
                };
            @endphp
            <span class="badge text-bg-{{ $badge }} ms-1">{{ ucfirst($pemesanan->status_pesan) }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pemesanan.cetak-pdf', $pemesanan) }}" target="_blank"
               class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-printer me-1"></i> Cetak PDF
            </a>
            @if($pemesanan->status_pesan === 'menunggu')
            <a href="{{ route('pemesanan.edit', $pemesanan) }}" class="btn btn-outline-primary btn-sm">
                <i class="ti ti-edit me-1"></i> Edit
            </a>
            <form action="{{ route('pemesanan.approve', $pemesanan) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <button class="btn btn-success btn-sm"
                    onclick="return confirm('Setujui pesanan {{ $pemesanan->no_pemesanan }}?')">
                    <i class="ti ti-check me-1"></i> Setujui
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Info Pesanan --}}
    <div class="card mb-4">
        <div class="card-header fw-medium">Informasi Pesanan</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <p class="text-muted small mb-1">No. Pesanan</p>
                    <p class="fw-semibold text-primary mb-0">{{ $pemesanan->no_pemesanan }}</p>
                </div>
                <div class="col-md-4">
                    <p class="text-muted small mb-1">Tanggal Pesan</p>
                    <p class="mb-0">{{ $pemesanan->tanggal_pesan->format('d F Y') }}</p>
                </div>
                <div class="col-md-4">
                    <p class="text-muted small mb-1">Tanggal Kirim</p>
                    <p class="mb-0">{{ $pemesanan->tanggal_kirim ? $pemesanan->tanggal_kirim->format('d F Y') : '—' }}</p>
                </div>
                <div class="col-md-4">
                    <p class="text-muted small mb-1">Konsumen</p>
                    <p class="fw-medium mb-0">{{ $pemesanan->konsumen->nama_konsumen }}</p>
                </div>
                <div class="col-md-4">
                    <p class="text-muted small mb-1">Telepon</p>
                    <p class="mb-0">{{ $pemesanan->konsumen->telepon ?? '—' }}</p>
                </div>
                <div class="col-md-4">
                    <p class="text-muted small mb-1">Alamat</p>
                    <p class="mb-0">{{ $pemesanan->konsumen->alamat ?? '—' }}</p>
                </div>
                @if($pemesanan->keterangan)
                <div class="col-12">
                    <p class="text-muted small mb-1">Keterangan</p>
                    <p class="mb-0">{{ $pemesanan->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Detail Barang --}}
    <div class="card">
        <div class="card-header fw-medium">Detail Barang</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end pe-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pemesanan->details as $i => $d)
                        <tr>
                            <td class="ps-3 text-muted">{{ $i + 1 }}</td>
                            <td class="text-primary">{{ $d->barang->kode_barang }}</td>
                            <td>{{ $d->barang->nama_barang }}</td>
                            <td>{{ $d->barang->satuan }}</td>
                            <td class="text-end">{{ number_format($d->jumlah_pesan, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-end pe-3 fw-medium">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="6" class="text-end fw-semibold pe-3">Total Pesanan</td>
                            <td class="text-end pe-3 fw-bold fs-5">
                                Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
