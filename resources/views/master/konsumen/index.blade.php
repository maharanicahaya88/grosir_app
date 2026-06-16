{{-- resources/views/master/konsumen/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Master Konsumen')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-semibold">Master Konsumen</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="ti ti-plus me-1"></i> Tambah Konsumen
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
                        placeholder="Cari nama / kode konsumen..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-secondary"><i class="ti ti-search me-1"></i>Cari</button>
                    <a href="{{ route('konsumen.index') }}" class="btn btn-sm btn-light ms-1">Reset</a>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Konsumen</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($konsumens as $k)
                    <tr>
                        <td class="ps-4 text-primary fw-medium">{{ $k->kode_konsumen }}</td>
                        <td>{{ $k->nama_konsumen }}</td>
                        <td class="text-muted">{{ $k->telepon ?? '—' }}</td>
                        <td class="text-muted" style="max-width:220px">
                            <span class="text-truncate d-block">{{ $k->alamat ?? '—' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge text-bg-{{ $k->status === 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($k->status) }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex gap-1 justify-content-center">
                                <button class="btn btn-sm btn-outline-primary btn-edit"
                                    data-id="{{ $k->id }}"
                                    data-nama="{{ $k->nama_konsumen }}"
                                    data-alamat="{{ $k->alamat }}"
                                    data-telepon="{{ $k->telepon }}"
                                    data-email="{{ $k->email }}"
                                    data-status="{{ $k->status }}"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <form action="{{ route('konsumen.destroy', $k) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus konsumen {{ $k->nama_konsumen }}?')">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">
                        <i class="ti ti-users-off d-block fs-3 mb-2"></i>Belum ada data konsumen.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($konsumens->hasPages())
        <div class="card-footer">{{ $konsumens->links() }}</div>
        @endif
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('konsumen.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Tambah Konsumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Konsumen <span class="text-danger">*</span></label>
                    <input type="text" name="nama_konsumen" class="form-control" required maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"></textarea>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" class="form-control" maxlength="20">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" maxlength="100">
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
    <div class="modal-dialog">
        <form id="formEdit" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Edit Konsumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Konsumen <span class="text-danger">*</span></label>
                    <input type="text" name="nama_konsumen" id="edit_nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" id="edit_alamat" class="form-control" rows="2"></textarea>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" id="edit_telepon" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_status" class="form-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
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
        document.getElementById('formEdit').action = `/konsumen/${id}`;
        document.getElementById('edit_nama').value    = this.dataset.nama;
        document.getElementById('edit_alamat').value  = this.dataset.alamat;
        document.getElementById('edit_telepon').value = this.dataset.telepon;
        document.getElementById('edit_email').value   = this.dataset.email;
        document.getElementById('edit_status').value  = this.dataset.status;
    });
});
</script>
@endpush
@endsection
