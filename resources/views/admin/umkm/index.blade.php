@extends('admin.layouts.app')

@section('title', 'Daftar UMKM')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Konten Website /</span> UMKM
        </h4>

        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="mb-0">Daftar Usaha Mikro, Kecil, & Menengah</h5>
                
                <div class="d-flex align-items-center gap-3">
                    <form action="{{ route('admin.umkm.index') }}" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari UMKM / Pemilik..." value="{{ $search }}" style="width: 220px;">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="bx bx-search"></i></button>
                        @if($search)
                            <a href="{{ route('admin.umkm.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bx bx-x"></i></a>
                        @endif
                    </form>
                    <a href="{{ route('admin.umkm.create') }}" class="btn btn-sm btn-primary">
                        <i class="bx bx-plus me-1"></i> Tambah UMKM
                    </a>
                </div>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Usaha</th>
                            <th>Pemilik</th>
                            <th>Kategori</th>
                            <th>Jam Operasional</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($umkmList as $umkm)
                            <tr>
                                <td>
                                    @if($umkm->foto)
                                        <img src="{{ asset('storage/' . $umkm->foto) }}" alt="Foto Usaha" class="rounded" style="width: 80px; height: 50px; object-fit: cover;" />
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 50px; font-size: 1.2rem;">
                                            <i class="bx bx-store"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $umkm->nama_usaha }}</strong>
                                </td>
                                <td>{{ $umkm->nama_pemilik }}</td>
                                <td>
                                    <span class="badge bg-label-info">{{ $umkm->kategori }}</span>
                                </td>
                                <td>{{ $umkm->jam_operasional ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $umkm->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $umkm->status === 'aktif' ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.umkm.edit', $umkm->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.umkm.destroy', $umkm->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data UMKM ini?');" style="display:inline;">
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
                                <td colspan="7" class="text-center py-4 text-muted">Tidak ada data UMKM ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($umkmList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $umkmList->appends(['search' => $search])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
