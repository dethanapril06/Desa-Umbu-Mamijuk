@extends('admin.layouts.app')

@section('title', 'Lembaga Desa')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Profil Desa /</span> Lembaga Desa
        </h4>
        <a href="{{ route('admin.lembaga-desa.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Tambah Lembaga Desa
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header border-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <h5 class="card-title mb-0">Daftar Lembaga Kemasyarakatan Desa</h5>
            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('admin.lembaga-desa.index') }}" method="GET" class="d-flex align-items-center">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari lembaga / ketua..." value="{{ $search }}">
                        @if($search)
                            <a href="{{ route('admin.lembaga-desa.index') }}" class="btn btn-outline-secondary" title="Reset Search">
                                <i class="bx bx-x"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 70px;">Logo</th>
                        <th>Nama Lembaga</th>
                        <th>Singkatan</th>
                        <th>Ketua</th>
                        <th>No. Telepon</th>
                        <th>Sekretariat</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($lembagaList as $lembaga)
                        <tr>
                            <td>
                                @if($lembaga->logo)
                                    <img src="{{ asset('storage/' . $lembaga->logo) }}" alt="{{ $lembaga->nama_lembaga }}" class="rounded" style="width: 45px; height: 45px; object-fit: cover;">
                                @else
                                    <div class="bg-label-secondary rounded d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bx bx-sitemap fs-4"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $lembaga->nama_lembaga }}</strong>
                            </td>
                            <td>
                                @if($lembaga->singkatan)
                                    <span class="badge bg-label-info">{{ $lembaga->singkatan }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $lembaga->ketua ?? '-' }}</td>
                            <td>{{ $lembaga->no_telepon ?? '-' }}</td>
                            <td>{{ Str::limit($lembaga->alamat_sekretariat, 30) ?? '-' }}</td>
                            <td>
                                <form action="{{ route('admin.lembaga-desa.toggle-status', $lembaga->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm border-0 p-0" title="Klik untuk mengubah status">
                                        @if($lembaga->is_active)
                                            <span class="badge bg-success"><i class="bx bx-check-circle me-1"></i> Aktif</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="bx bx-minus-circle me-1"></i> Non-Aktif</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.lembaga-desa.edit', $lembaga->id) }}" class="btn btn-sm btn-icon btn-outline-warning" title="Edit">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <form action="{{ route('admin.lembaga-desa.destroy', $lembaga->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete" title="Hapus">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bx bx-sitemap fs-1 mb-2 d-block text-secondary"></i>
                                Belum ada data Lembaga Desa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($lembagaList->hasPages())
            <div class="card-footer">
                {{ $lembagaList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        Swal.fire({
            title: 'Hapus Lembaga Desa?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
