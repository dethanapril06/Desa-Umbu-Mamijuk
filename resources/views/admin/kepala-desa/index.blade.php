@extends('admin.layouts.app')

@section('title', 'Daftar Kepala Desa')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Profil Desa /</span> Kepala Desa
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Kepala Desa</h5>
                <a href="{{ route('admin.kepala-desa.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Kepala Desa
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama & Gelar</th>
                            <th>Periode</th>
                            <th>Status Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($kepalaDesaList as $kades)
                            <tr>
                                <td>
                                    @if($kades->foto)
                                        <img src="{{ asset('storage/' . $kades->foto) }}" alt="{{ $kades->nama }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;" />
                                    @else
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded-circle bg-label-secondary">
                                                <i class="bx bx-user"></i>
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $kades->nama }}</strong>
                                    @if($kades->gelar)
                                        <br/><small class="text-muted">{{ $kades->gelar }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $kades->periode_mulai }} – {{ $kades->periode_selesai }}
                                </td>
                                <td>
                                    <form action="{{ route('admin.kepala-desa.toggle-status', $kades->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-xs {{ $kades->is_active ? 'btn-success' : 'btn-secondary' }}">
                                            {{ $kades->is_active ? 'Aktif' : 'Non-Aktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-menu-item dropdown-item" href="{{ route('admin.kepala-desa.edit', $kades->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.kepala-desa.destroy', $kades->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" style="display:inline;">
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
                                <td colspan="5" class="text-center py-4">Tidak ada data kepala desa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kepalaDesaList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $kepalaDesaList->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
