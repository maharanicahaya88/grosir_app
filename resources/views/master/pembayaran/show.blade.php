@extends('layouts.app')
@section('title', 'Detail Pembayaran')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="ti ti-arrow-left"></i>
    </a>
    <div>
        <h4 class="mb-0" style="font-size:20px;font-weight:700;color:#1a1a1a">{{ $pembayaran->no_pembayaran }}</h4>
        <p class="mb-0" style="font-size:13px;color:#888">Detail transaksi pembayaran</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="table-card p-4 mb-4">
            <div style="font-size:14px;font-weight:700;margin-bottom:16px;color:#1a1a1a">Info Pembayaran</div>
            <table style="width:100%;font-size:13px">
                <tr>
                    <td style="color:#888;padding:6px 0;width:140px">No. Pembayaran</td>
                    <td style="font-weight:700;color:#4f46e5">{{ $pembayaran->no_pembayaran }}</td>
                </tr>
                <tr>
                    <td style="color:#888;padding:6px 0">No. Pesanan</td>
                    <td><a href="{{ route('pemesanan.show', $pembayaran->pemesanan) }}" style="color:#4f46e5;font-weight:600;text-decoration:none">{{ $pembayaran->pemesanan->no_pemesanan }}</a></td>
                </tr>
                <tr>
                    <td style="color:#888;padding:6px 0">Konsumen</td>
                    <td style="font-weight:600">{{ $pembayaran->pemesanan->konsumen->nama_konsumen }}</td>
                </tr>
                <tr>
                    <td style="color:#888;padding:6px 0">Tanggal Bayar</td>
                    <td>{{ $pembayaran->tanggal_bayar->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td style="color:#888;padding:6px 0">Metode</td>
                    <td><span style="background:#f0f0ee;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;text-transform:capitalize">{{ $pembayaran->metode_bayar }}</span></td>
                </tr>
                <tr>
                    <td style="color:#888;padding:6px 0">Status</td>
                    <td>
                        @if($pembayaran->status_bayar === 'lunas')
                            <span class="status-badge badge-disetujui">Lunas</span>
                        @else
                            <span class="status-badge badge-menunggu">Belum Lunas</span>
                        @endif
                    </td>
                </tr>
                @if($pembayaran->keterangan)
                <tr>
                    <td style="color:#888;padding:6px 0">Keterangan</td>
                    <td>{{ $pembayaran->keterangan }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="table-card p-4">
            <div style="font-size:14px;font-weight:700;margin-bottom:16px;color:#1a1a1a">Rincian Tagihan</div>
            <div class="row g-3">
                <div class="col-4 text-center p-3" style="background:#f8f8f6;border-radius:10px">
                    <div style="font-size:11px;color:#888;margin-bottom:4px">Total Tagihan</div>
                    <div style="font-size:15px;font-weight:700;color:#1a1a1a">Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</div>
                </div>
                <div class="col-4 text-center p-3" style="background:#dcfce7;border-radius:10px">
                    <div style="font-size:11px;color:#166534;margin-bottom:4px">Dibayar</div>
                    <div style="font-size:15px;font-weight:700;color:#16a34a">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</div>
                </div>
                <div class="col-4 text-center p-3" style="background:{{ $pembayaran->sisa_tagihan > 0 ? '#fee2e2' : '#dcfce7' }};border-radius:10px">
                    <div style="font-size:11px;color:{{ $pembayaran->sisa_tagihan > 0 ? '#991b1b' : '#166534' }};margin-bottom:4px">Sisa</div>
                    <div style="font-size:15px;font-weight:700;color:{{ $pembayaran->sisa_tagihan > 0 ? '#dc2626' : '#16a34a' }}">Rp {{ number_format($pembayaran->sisa_tagihan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        @if($pembayaran->bukti_bayar)
        <div class="table-card p-4 mb-4">
            <div style="font-size:14px;font-weight:700;margin-bottom:12px;color:#1a1a1a">Bukti Pembayaran</div>
            <img src="{{ Storage::url($pembayaran->bukti_bayar) }}"
                 alt="Bukti Bayar" class="img-fluid" style="border-radius:10px;border:1px solid #e8e8e5">
        </div>
        @endif

        <div class="table-card p-4">
            <div style="font-size:14px;font-weight:700;margin-bottom:14px;color:#1a1a1a">Aksi</div>
            <div class="d-flex flex-column gap-2">
                @if($pembayaran->sisa_tagihan > 0)
                <a href="{{ route('pembayaran.create', ['id_pemesanan' => $pembayaran->id_pemesanan]) }}"
                   class="btn btn-primary btn-sm">
                    <i class="ti ti-plus me-1"></i>Bayar Sisa Tagihan
                </a>
                @endif
                <form action="{{ route('pembayaran.destroy', $pembayaran) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm w-100"
                        onclick="return confirm('Hapus data pembayaran ini?')">
                        <i class="ti ti-trash me-1"></i>Hapus Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection