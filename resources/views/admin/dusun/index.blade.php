@extends('admin.layouts.app')

@section('title', 'Daftar Dusun')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kependudukan /</span> Wilayah Dusun
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Dusun</h5>
                <a href="{{ route('admin.dusun.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Dusun
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Dusun</th>
                            <th>Kepala Dusun</th>
                            <th>Status Keaktifan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($dusunList as $dusun)
                            <tr>
                                <td><strong>{{ $dusun->nama }}</strong></td>
                                <td>{{ $dusun->kepala_dusun ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $dusun->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $dusun->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-menu-item dropdown-item" href="{{ route('admin.dusun.edit', $dusun->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.dusun.destroy', $dusun->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dusun ini? Semua data RT/RW di dalamnya juga harus kosong.');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bx bx-trash me-1 text-danger"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Tidak ada data dusun.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($dusunList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $dusunList->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
