@extends('admin.layouts.app')

@section('title', 'Daftar Mutasi Penduduk')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kependudukan /</span> Mutasi Penduduk
        </h4>

        <!-- Filter & Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.mutasi-penduduk.index') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label" for="search">Cari Nama Penduduk / NIK / No. Surat</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Ketik pencarian..." value="{{ $search }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="jenis_mutasi">Jenis Mutasi</label>
                        <select name="jenis_mutasi" id="jenis_mutasi" class="form-select">
                            <option value="">-- Semua Jenis Mutasi --</option>
                            <option value="mati" {{ $jenis == 'mati' ? 'selected' : '' }}>Meninggal (Mati)</option>
                            <option value="pindah_masuk" {{ $jenis == 'pindah_masuk' ? 'selected' : '' }}>Pindah Masuk</option>
                            <option value="pindah_keluar" {{ $jenis == 'pindah_keluar' ? 'selected' : '' }}>Pindah Keluar</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter-alt me-1"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Catatan Mutasi Penduduk</h5>
                <a href="{{ route('admin.mutasi-penduduk.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Catat Mutasi
                </a>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Penduduk</th>
                            <th>NIK</th>
                            <th>Jenis Mutasi</th>
                            <th>Tanggal Mutasi</th>
                            <th>No. Surat</th>
                            <th>Lampiran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($mutasiList as $mutasi)
                            <tr>
                                <td>
                                    @if($mutasi->penduduk)
                                        <a href="{{ route('admin.penduduk.show', $mutasi->penduduk_id) }}" class="fw-bold text-dark">
                                            {{ $mutasi->penduduk->nama_lengkap }}
                                        </a>
                                    @else
                                        <span class="text-danger">Data Penduduk Terhapus</span>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $mutasi->penduduk ? $mutasi->penduduk->nik : '-' }}</code>
                                </td>
                                <td>
                                    @php
                                        $badges = [
                                            'lahir' => 'bg-label-success',
                                            'mati' => 'bg-label-danger',
                                            'pindah_masuk' => 'bg-label-info',
                                            'pindah_keluar' => 'bg-label-warning',
                                        ];
                                        $label = [
                                            'lahir' => 'Lahir',
                                            'mati' => 'Meninggal',
                                            'pindah_masuk' => 'Pindah Masuk',
                                            'pindah_keluar' => 'Pindah Keluar',
                                        ];
                                    @endphp
                                    <span class="badge {{ $badges[$mutasi->jenis_mutasi] ?? 'bg-label-secondary' }}">
                                        {{ $label[$mutasi->jenis_mutasi] ?? $mutasi->jenis_mutasi }}
                                    </span>
                                </td>
                                <td>
                                    {{ $mutasi->tanggal_mutasi ? $mutasi->tanggal_mutasi->format('d M Y') : '-' }}
                                </td>
                                <td>
                                    {{ $mutasi->no_surat ?? '-' }}
                                </td>
                                <td>
                                    @if($mutasi->lampiran)
                                        <a href="{{ asset('storage/' . $mutasi->lampiran) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                            <i class="bx bx-download me-1"></i> Lihat Dokumen
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.mutasi-penduduk.edit', $mutasi->id) }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit Catatan
                                            </a>
                                            <form action="{{ route('admin.mutasi-penduduk.destroy', $mutasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan mutasi ini? Menghapus catatan mutasi akan mengembalikan status penduduk terkait menjadi AKTIF.');" style="display:inline;">
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
                                <td colspan="7" class="text-center py-4">Tidak ada catatan mutasi kependudukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($mutasiList->hasPages())
                <div class="card-footer bg-light p-3">
                    {{ $mutasiList->appends(['search' => $search, 'jenis_mutasi' => $jenis])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
