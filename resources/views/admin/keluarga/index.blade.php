@extends('admin.layouts.app')

@section('title', 'Daftar Kartu Keluarga')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kependudukan /</span> Kartu Keluarga
        </h4>

        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h5 class="mb-0">Daftar Kartu Keluarga</h5>
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.keluarga.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari KK atau Kepala..." value="{{ $search }}" />
                        <button type="submit" class="btn btn-outline-primary">Cari</button>
                    </form>
                    <a href="{{ route('admin.keluarga.create') }}" class="btn btn-primary text-nowrap">
                        <i class="bx bx-plus me-1"></i> Tambah KK
                    </a>
                </div>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. KK</th>
                            <th>Kepala Keluarga</th>
                            <th>Alamat</th>
                            <th>RT / RW / Dusun</th>
                            <th>Anggota</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($keluargaList as $keluarga)
                            @php
                                $kepala = $keluarga->penduduk->where('status_hubungan_keluarga', 'kepala_keluarga')->first();
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('admin.keluarga.show', $keluarga->id) }}" class="fw-bold text-primary">
                                        {{ $keluarga->no_kk }}
                                    </a>
                                </td>
                                <td>
                                    @if($kepala)
                                        <strong>{{ $kepala->nama_lengkap }}</strong>
                                    @else
                                        <span class="text-warning"><i class="bx bx-error-circle me-1"></i>Belum di-set</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($keluarga->alamat, 30) }}</td>
                                <td>
                                    RT {{ $keluarga->rtRw->no_rt }} / RW {{ $keluarga->rtRw->no_rw }} - {{ $keluarga->rtRw->dusun->nama }}
                                </td>
                                <td>
                                    <span class="badge bg-label-info">{{ $keluarga->penduduk->count() }} Orang</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.keluarga.show', $keluarga->id) }}">
                                                <i class="bx bx-show me-1 text-info"></i> Detail KK & Anggota
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.keluarga.edit', $keluarga->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit KK
                                            </a>
                                            <form action="{{ route('admin.keluarga.destroy', $keluarga->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus KK ini? Semua data penduduk di dalamnya harus dipindahkan/dihapus terlebih dahulu.');" style="display:inline;">
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
                                <td colspan="6" class="text-center py-4">Tidak ada data Kartu Keluarga.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($keluargaList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $keluargaList->appends(['search' => $search])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
