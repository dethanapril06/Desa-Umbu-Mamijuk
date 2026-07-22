@extends('admin.layouts.app')

@section('title', 'Daftar Perangkat Desa')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Profil Desa /</span> Perangkat Desa
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Perangkat Desa</h5>
                <a href="{{ route('admin.perangkat-desa.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Perangkat Desa
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>NIP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($perangkatDesaList as $perangkat)
                            <tr>
                                <td>
                                    @if($perangkat->foto)
                                        <img src="{{ asset('storage/' . $perangkat->foto) }}" alt="{{ $perangkat->nama }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;" />
                                    @else
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded-circle bg-label-secondary">
                                                <i class="bx bx-user"></i>
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $perangkat->nama }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-label-primary">{{ $perangkat->jabatan }}</span>
                                </td>
                                <td>
                                    {{ $perangkat->nip ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge {{ $perangkat->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $perangkat->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: grid; grid-template-columns: repeat(3, max-content); gap: 0.25rem;">

                                            <a href="{{ route('admin.perangkat-desa.edit', $perangkat->id) }}" class="btn btn-sm btn-icon" title="Edit"><i class="bx bx-edit-alt  text-primary"></i></a>
                                            <form action="{{ route('admin.perangkat-desa.destroy', $perangkat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon" title="Hapus"><i class="bx bx-trash  text-danger"></i></button>
                                            </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Tidak ada data perangkat desa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($perangkatDesaList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $perangkatDesaList->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
