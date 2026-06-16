{{-- resources/views/pemesanan/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Pesanan ' . $pemesanan->no_pemesanan)

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('pemesanan.show', $pemesanan) }}" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-semibold">Edit Pesanan — {{ $pemesanan->no_pemesanan }}</h4>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('pemesanan.update', $pemesanan) }}" method="POST" id="formEdit">
    @csrf @method('PUT')

    <div class="card mb-4">
        <div class="card-header fw-medium">Informasi Pesanan</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">No. Pesanan</label>
                    <input type="text" class="form-control" value="{{ $pemesanan->no_pemesanan }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Pesan *</label>
                    <input type="date" name="tanggal_pesan" class="form-control"
                        value="{{ old('tanggal_pesan', $pemesanan->tanggal_pesan->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Kirim</label>
                    <input type="date" name="tanggal_kirim" class="form-control"
                        value="{{ old('tanggal_kirim', optional($pemesanan->tanggal_kirim)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Konsumen *</label>
                    <select name="id_konsumen" class="form-select" required>
                        <option value="">-- Pilih Konsumen --</option>
                        @foreach($konsumens as $k)
                        <option value="{{ $k->id }}"
                            {{ old('id_konsumen', $pemesanan->id_konsumen) == $k->id ? 'selected' : '' }}>
                            {{ $k->kode_konsumen }} -- {{ $k->nama_konsumen }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $pemesanan->keterangan) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center fw-medium">
            Detail Barang
            <button type="button" class="btn btn-sm btn-outline-primary" id="btnTambahBarang">
                <i class="ti ti-plus me-1"></i> Tambah Barang
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="tableDetail">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3" style="width:160px">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th style="width:80px">Satuan</th>
                            <th style="width:130px">Harga Satuan</th>
                            <th style="width:90px">Qty</th>
                            <th class="text-end" style="width:130px">Subtotal</th>
                            <th style="width:50px"></th>
                        </tr>
                    </thead>
                    <tbody id="tbodyDetail"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end fw-medium pe-3">Total Pesanan</td>
                            <td class="text-end fw-semibold fs-5" id="grandTotal">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('pemesanan.show', $pemesanan) }}" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
        </button>
    </div>
    </form>
</div>

@push('scripts')
<script>
const barangs    = @json($barangs);
const barangMap  = Object.fromEntries(barangs.map(b => [b.id, b]));
const existingDetails = @json($pemesanan->details->map(fn($d) => [
    'id_barang'    => $d->id_barang,
    'jumlah_pesan' => $d->jumlah_pesan,
    'harga_satuan' => $d->harga_satuan,
]));
let rowCount = 0;

function formatRupiah(n) {
    return 'Rp ' + Math.round(n).toLocaleString('id-ID');
}

function addRow(preset = null) {
    const idx = rowCount++;
    const opts = barangs.map(b =>
        `<option value="${b.id}" data-harga="${b.harga_jual}" data-satuan="${b.satuan}"
            ${preset && preset.id_barang == b.id ? 'selected' : ''}>
            ${b.kode_barang} -- ${b.nama_barang}
        </option>`
    ).join('');
    const namaPreset   = preset ? (barangMap[preset.id_barang]?.nama_barang  ?? '--') : '--';
    const satuanPreset = preset ? (barangMap[preset.id_barang]?.satuan       ?? '--') : '--';
    const hargaPreset  = preset ? preset.harga_satuan : 0;
    const qtyPreset    = preset ? preset.jumlah_pesan : 1;
    const subPreset    = preset ? formatRupiah(hargaPreset * qtyPreset) : 'Rp 0';

    const row = `<tr id="row-${idx}">
        <td class="ps-3">
            <select name="details[${idx}][id_barang]" class="form-select form-select-sm select-barang"
                data-idx="${idx}" required style="min-width:150px">
                <option value="">Pilih...</option>${opts}
            </select>
        </td>
        <td><span id="nama-${idx}" class="text-muted small">${namaPreset}</span></td>
        <td><span id="satuan-${idx}" class="text-muted small">${satuanPreset}</span></td>
        <td><input type="number" name="details[${idx}][harga_satuan]" id="harga-${idx}"
            class="form-control form-control-sm input-harga" step="1" min="0"
            value="${hargaPreset}" data-idx="${idx}" style="width:120px"></td>
        <td><input type="number" name="details[${idx}][jumlah_pesan]" id="qty-${idx}"
            class="form-control form-control-sm input-qty" min="1"
            value="${qtyPreset}" data-idx="${idx}" style="width:75px"></td>
        <td class="text-end fw-medium" id="sub-${idx}">${subPreset}</td>
        <td><button type="button" class="btn btn-sm btn-outline-danger"
            onclick="removeRow(${idx})"><i class="ti ti-trash"></i></button></td>
    </tr>`;

    document.getElementById('tbodyDetail').insertAdjacentHTML('beforeend', row);
    document.querySelector(`.select-barang[data-idx="${idx}"]`).addEventListener('change', onBarangChange);
    document.querySelector(`.input-harga[data-idx="${idx}"]`).addEventListener('input', (e) => hitungSubtotal(idx));
    document.querySelector(`.input-qty[data-idx="${idx}"]`).addEventListener('input',  (e) => hitungSubtotal(idx));
}

function onBarangChange(e) {
    const idx = e.target.dataset.idx;
    const id  = e.target.value;
    const b   = barangMap[id];
    if (b) {
        document.getElementById(`nama-${idx}`).textContent   = b.nama_barang;
        document.getElementById(`satuan-${idx}`).textContent = b.satuan;
        document.getElementById(`harga-${idx}`).value        = b.harga_jual;
    }
    hitungSubtotal(idx);
}

function hitungSubtotal(idx) {
    const harga = parseFloat(document.getElementById(`harga-${idx}`)?.value) || 0;
    const qty   = parseInt(document.getElementById(`qty-${idx}`)?.value)     || 0;
    document.getElementById(`sub-${idx}`).textContent = formatRupiah(harga * qty);
    hitungTotal();
}

function hitungTotal() {
    let total = 0;
    document.querySelectorAll('[id^="sub-"]').forEach(el => {
        total += parseInt(el.textContent.replace(/[^0-9]/g, '')) || 0;
    });
    document.getElementById('grandTotal').textContent = formatRupiah(total);
}

function removeRow(idx) {
    document.getElementById(`row-${idx}`)?.remove();
    hitungTotal();
}

document.getElementById('btnTambahBarang').addEventListener('click', () => addRow());

// Load existing details
existingDetails.forEach(d => addRow(d));
hitungTotal();
</script>
@endpush
@endsection
