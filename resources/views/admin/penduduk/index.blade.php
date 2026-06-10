@extends('admin.layouts.app')

@section('title', 'Daftar Penduduk')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kependudukan /</span> Data Penduduk
        </h4>

        <!-- Filter & Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.penduduk.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="search">Cari Nama / NIK</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Ketik nama atau NIK..." value="{{ $search }}" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="status">Status Penduduk</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">-- Semua Status --</option>
                            <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="pindah" {{ $status == 'pindah' ? 'selected' : '' }}>Pindah</option>
                            <option value="meninggal" {{ $status == 'meninggal' ? 'selected' : '' }}>Meninggal</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                            <option value="">-- Semua Gender --</option>
                            <option value="laki-laki" {{ $jk == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="perempuan" {{ $jk == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter-alt me-1"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Biodata Penduduk</h5>
                <a href="{{ route('admin.penduduk.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Penduduk
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>No. KK</th>
                            <th>L/P</th>
                            <th>RT / RW / Dusun</th>
                            <th>Pekerjaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($pendudukList as $penduduk)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.penduduk.show', $penduduk->id) }}" class="fw-bold text-primary">
                                        {{ $penduduk->nik }}
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $penduduk->nama_lengkap }}</strong>
                                </td>
                                <td>
                                    @if($penduduk->keluarga)
                                        <a href="{{ route('admin.keluarga.show', $penduduk->keluarga_id) }}" class="text-secondary">
                                            {{ $penduduk->keluarga->no_kk }}
                                        </a>
                                    @else
                                        <span class="text-danger">Belum ada KK</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $penduduk->jenis_kelamin === 'laki-laki' ? 'L' : 'P' }}
                                </td>
                                <td>
                                    @if($penduduk->keluarga && $penduduk->keluarga->rtRw)
                                        RT {{ $penduduk->keluarga->rtRw->no_rt }} / RW {{ $penduduk->keluarga->rtRw->no_rw }} <br/>
                                        <small class="text-muted">{{ $penduduk->keluarga->rtRw->dusun->nama }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    {{ $penduduk->pekerjaan ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge {{ $penduduk->status === 'aktif' ? 'bg-success' : ($penduduk->status === 'meninggal' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($penduduk->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.penduduk.show', $penduduk->id) }}">
                                                <i class="bx bx-show me-1 text-info"></i> Profil Detail
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.penduduk.edit', $penduduk->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit Biodata
                                            </a>
                                            <form action="{{ route('admin.penduduk.destroy', $penduduk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penduduk ini?');" style="display:inline;">
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
                                <td colspan="8" class="text-center py-4">Tidak ada data penduduk yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pendudukList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $pendudukList->appends(['search' => $search, 'status' => $status, 'jenis_kelamin' => $jk])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
