@extends('layouts.app')
@section('title', 'Tambah Pembayaran')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="ti ti-arrow-left"></i>
    </a>
    <div>
        <h4 class="mb-0" style="font-size:20px;font-weight:700;color:#1a1a1a">Tambah Pembayaran</h4>
        <p class="mb-0" style="font-size:13px;color:#888">Catat transaksi pembayaran pesanan</p>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-7">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('pembayaran.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px">Pilih Pesanan</label>
                    <select name="id_pemesanan" id="id_pemesanan" class="form-select @error('id_pemesanan') is-invalid @enderror" onchange="this.form.submit()" required>
                        <option value="">-- Pilih No. Pesanan --</option>
                        @foreach($pemesanan as $p)
                        <option value="{{ $p->id }}" {{ (request('id_pemesanan') == $p->id || (isset($selectedPemesanan) && $selectedPemesanan->id == $p->id)) ? 'selected' : '' }}>
                            {{ $p->no_pemesanan }} - {{ $p->konsumen->nama_konsumen }}
                            (Sisa: Rp {{ number_format($p->sisaTagihan(), 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                    @error('id_pemesanan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @if(isset($selectedPemesanan))
                <div class="mb-3 p-3" style="background:#f8f8f6;border-radius:10px;font-size:13px">
                    <div class="row g-2">
                        <div class="col-6">
                            <div style="color:#888;font-size:11px">Total Tagihan</div>
                            <div style="font-weight:700;color:#1a1a1a">Rp {{ number_format($selectedPemesanan->total_harga, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-6">
                            <div style="color:#888;font-size:11px">Sudah Dibayar</div>
                            <div style="font-weight:700;color:#16a34a">Rp {{ number_format($selectedPemesanan->totalDibayar(), 0, ',', '.') }}</div>
                        </div>
                        <div class="col-6">
                            <div style="color:#888;font-size:11px">Sisa Tagihan</div>
                            <div style="font-weight:700;color:#dc2626;font-size:16px">Rp {{ number_format($selectedPemesanan->sisaTagihan(), 0, ',', '.') }}</div>
                        </div>
                        <div class="col-6">
                            <div style="color:#888;font-size:11px">Konsumen</div>
                            <div style="font-weight:600">{{ $selectedPemesanan->konsumen->nama_konsumen }}</div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                    @error('tanggal_bayar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px">Jumlah Bayar</label>
                    <div class="input-group">
                        <span class="input-group-text" style="font-size:13px;border-color:#e0e0dd">Rp</span>
                        <input type="number" name="jumlah_bayar" class="form-control @error('jumlah_bayar') is-invalid @enderror" value="{{ old('jumlah_bayar', isset($selectedPemesanan) ? $selectedPemesanan->sisaTagihan() : '') }}" placeholder="0" min="1" required>
                        @error('jumlah_bayar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px">Metode Pembayaran</label>
                    <select name="metode_bayar" class="form-select @error('metode_bayar') is-invalid @enderror" required>
                        <option value="tunai" @selected(old('metode_bayar')=='tunai')>Tunai</option>
                        <option value="transfer" @selected(old('metode_bayar')=='transfer')>Transfer Bank</option>
                        <option value="cek" @selected(old('metode_bayar')=='cek')>Cek</option>
                        <option value="giro" @selected(old('metode_bayar')=='giro')>Giro</option>
                    </select>
                    @error('metode_bayar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px">Bukti Pembayaran <span style="color:#aaa;font-weight:400">(opsional)</span></label>
                    <input type="file" name="bukti_bayar" class="form-control" accept="image/*">
                    <div style="font-size:11px;color:#aaa;margin-top:4px">Format: JPG, PNG. Maks 2MB</div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:13px">Keterangan <span style="color:#aaa;font-weight:400">(opsional)</span></label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan Pembayaran</button>
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-5">
        <div class="table-card p-4">
            <div style="font-size:14px;font-weight:700;color:#1a1a1a;margin-bottom:14px">
                <i class="ti ti-info-circle me-1" style="color:#4f46e5"></i>Panduan Pembayaran
            </div>
            <div style="font-size:13px;color:#666;line-height:1.8">
                <div class="d-flex gap-2 mb-2"><i class="ti ti-point-filled" style="color:#4f46e5;margin-top:4px"></i>Pilih pesanan yang sudah disetujui</div>
                <div class="d-flex gap-2 mb-2"><i class="ti ti-point-filled" style="color:#4f46e5;margin-top:4px"></i>Masukkan jumlah yang dibayarkan</div>
                <div class="d-flex gap-2 mb-2"><i class="ti ti-point-filled" style="color:#4f46e5;margin-top:4px"></i>Pembayaran bisa dilakukan bertahap</div>
                <div class="d-flex gap-2 mb-2"><i class="ti ti-point-filled" style="color:#4f46e5;margin-top:4px"></i>Status otomatis jadi Lunas jika sisa = 0</div>
                <div class="d-flex gap-2"><i class="ti ti-point-filled" style="color:#4f46e5;margin-top:4px"></i>Upload bukti transfer jika ada</div>
            </div>
        </div>
    </div>
</div>
@endsection
