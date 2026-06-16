{{-- resources/views/pemesanan/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Buat Pesanan')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('pemesanan.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-semibold">Buat Pesanan Baru</h4>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('pemesanan.store') }}" method="POST" id="formPemesanan">
    @csrf

    <div class="card mb-4">
        <div class="card-header fw-medium">Informasi Pesanan</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">No. Pesanan</label>
                    <input type="text" class="form-control" value="{{ $no_pemesanan }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Pesan <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pesan" class="form-control"
                        value="{{ old('tanggal_pesan', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Kirim</label>
                    <input type="date" name="tanggal_kirim" class="form-control"
                        value="{{ old('tanggal_kirim') }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Konsumen <span class="text-danger">*</span></label>
                    <select name="id_konsumen" class="form-select" required>
                        <option value="">-- Pilih Konsumen --</option>
                        @foreach($konsumens as $k)
                        <option value="{{ $k->id }}" @selected(old('id_konsumen') == $k->id)>
                            {{ $k->kode_konsumen }} — {{ $k->nama_konsumen }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2"
                        placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Barang --}}
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
                    <tbody id="tbodyDetail">
                        {{-- diisi JS --}}
                    </tbody>
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
        <a href="{{ route('pemesanan.index') }}" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i> Simpan Pesanan
        </button>
    </div>
    </form>
</div>

@push('scripts')
<script>
const barangs = @json($barangs);
const barangMap = Object.fromEntries(barangs.map(b => [b.id, b]));
let rowCount = 0;

function formatRupiah(n) {
    return 'Rp ' + Math.round(n).toLocaleString('id-ID');
}

function addRow() {
    const idx = rowCount++;
    const options = barangs.map(b =>
        `<option value="${b.id}" data-harga="${b.harga_jual}" data-satuan="${b.satuan}">${b.kode_barang} — ${b.nama_barang}</option>`
    ).join('');
    const row = `<tr id="row-${idx}">
        <td class="ps-3">
            <select name="details[${idx}][id_barang]" class="form-select form-select-sm select-barang" required
                data-idx="${idx}" style="min-width:150px">
                <option value="">Pilih...</option>${options}
            </select>
        </td>
        <td><span id="nama-${idx}" class="text-muted small">—</span></td>
        <td><span id="satuan-${idx}" class="text-muted small">—</span></td>
        <td>
            <input type="number" name="details[${idx}][harga_satuan]" id="harga-${idx}"
                class="form-control form-control-sm input-harga" step="1" min="0" value="0"
                data-idx="${idx}" style="width:120px">
        </td>
        <td>
            <input type="number" name="details[${idx}][jumlah_pesan]" id="qty-${idx}"
                class="form-control form-control-sm input-qty" min="1" value="1"
                data-idx="${idx}" style="width:75px">
        </td>
        <td class="text-end fw-medium" id="sub-${idx}">Rp 0</td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus"
                onclick="removeRow(${idx})"><i class="ti ti-trash"></i></button>
        </td>
    </tr>`;
    document.getElementById('tbodyDetail').insertAdjacentHTML('beforeend', row);
    document.querySelector(`.select-barang[data-idx="${idx}"]`).addEventListener('change', onBarangChange);
    document.querySelector(`.input-harga[data-idx="${idx}"]`).addEventListener('input', hitungSubtotal);
    document.querySelector(`.input-qty[data-idx="${idx}"]`).addEventListener('input', hitungSubtotal);
}

function onBarangChange(e) {
    const idx  = e.target.dataset.idx;
    const id   = e.target.value;
    const b    = barangMap[id];
    if (b) {
        document.getElementById(`nama-${idx}`).textContent   = b.nama_barang;
        document.getElementById(`satuan-${idx}`).textContent = b.satuan;
        document.getElementById(`harga-${idx}`).value        = b.harga_jual;
    }
    hitungSubtotal({ target: { dataset: { idx } } });
}

function hitungSubtotal(e) {
    const idx  = e.target.dataset.idx;
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

document.getElementById('btnTambahBarang').addEventListener('click', addRow);
addRow(); // mulai dengan satu baris
</script>
@endpush
@endsection
