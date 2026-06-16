{{-- resources/views/master/barang/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Master Barang')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-semibold">Master Barang</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="ti ti-plus me-1"></i> Tambah Barang
        </button>
    </div>

    @foreach(['success','error'] as $type)
    @if(session($type))
    <div class="alert alert-{{ $type === 'success' ? 'success' : 'danger' }} alert-dismissible fade show">
        {{ session($type) }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @endforeach

    <div class="card">
        <div class="card-body pb-2">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari kode / nama barang..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-secondary"><i class="ti ti-search me-1"></i>Cari</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-sm btn-light ms-1">Reset</a>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-end">Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $b)
                    <tr>
                        <td class="ps-4 text-primary fw-medium">{{ $b->kode_barang }}</td>
                        <td>{{ $b->nama_barang }}</td>
                        <td>{{ $b->satuan }}</td>
                        <td class="text-end text-muted">Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                        <td class="text-end fw-medium">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <span class="{{ $b->stokMenipis() ? 'text-danger fw-semibold' : '' }}">
                                {{ number_format($b->stok, 0, ',', '.') }}
                            </span>
                            @if($b->stokMenipis())
                            <i class="ti ti-alert-triangle text-warning ms-1" title="Stok menipis!"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge text-bg-{{ $b->status === 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($b->status) }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex gap-1 justify-content-center">
                                <button class="btn btn-sm btn-outline-primary btn-edit"
                                    data-id="{{ $b->id }}"
                                    data-nama="{{ $b->nama_barang }}"
                                    data-satuan="{{ $b->satuan }}"
                                    data-harga-beli="{{ $b->harga_beli }}"
                                    data-harga-jual="{{ $b->harga_jual }}"
                                    data-stok="{{ $b->stok }}"
                                    data-stok-min="{{ $b->stok_minimum }}"
                                    data-keterangan="{{ $b->keterangan }}"
                                    data-status="{{ $b->status }}"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <form action="{{ route('barang.destroy', $b) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus barang {{ $b->nama_barang }}?')">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">
                        <i class="ti ti-package-off d-block fs-3 mb-2"></i>Belum ada data barang.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($barangs->hasPages())
        <div class="card-footer">{{ $barangs->links() }}</div>
        @endif
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('barang.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Tambah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control" required maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                        <select name="satuan" class="form-select" required>
                            <option value="Pcs">Pcs</option>
                            <option value="Kg">Kg</option>
                            <option value="Dus">Dus</option>
                            <option value="Karton">Karton</option>
                            <option value="Sak">Sak</option>
                            <option value="Liter">Liter</option>
                            <option value="Lusin">Lusin</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                        <input type="number" name="harga_beli" class="form-control" min="0" step="100" required value="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                        <input type="number" name="harga_jual" class="form-control" min="0" step="100" required value="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control" min="0" required value="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stok Minimum</label>
                        <input type="number" name="stok_minimum" class="form-control" min="0" value="5">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="(Opsional)"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formEdit" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" id="e_nama" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Satuan</label>
                        <select name="satuan" id="e_satuan" class="form-select">
                            <option value="Pcs">Pcs</option>
                            <option value="Kg">Kg</option>
                            <option value="Dus">Dus</option>
                            <option value="Karton">Karton</option>
                            <option value="Sak">Sak</option>
                            <option value="Liter">Liter</option>
                            <option value="Lusin">Lusin</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" name="harga_beli" id="e_harga_beli" class="form-control" min="0" step="100">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga Jual</label>
                        <input type="number" name="harga_jual" id="e_harga_jual" class="form-control" min="0" step="100">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" id="e_stok" class="form-control" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stok Minimum</label>
                        <input type="number" name="stok_minimum" id="e_stok_min" class="form-control" min="0">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="e_ket" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" id="e_status" class="form-select">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        document.getElementById('formEdit').action = `/barang/${id}`;
        document.getElementById('e_nama').value       = this.dataset.nama;
        document.getElementById('e_satuan').value     = this.dataset.satuan;
        document.getElementById('e_harga_beli').value = this.dataset.hargaBeli;
        document.getElementById('e_harga_jual').value = this.dataset.hargaJual;
        document.getElementById('e_stok').value       = this.dataset.stok;
        document.getElementById('e_stok_min').value   = this.dataset.stokMin;
        document.getElementById('e_ket').value        = this.dataset.keterangan;
        document.getElementById('e_status').value     = this.dataset.status;
    });
});
</script>
@endpush
@endsection
