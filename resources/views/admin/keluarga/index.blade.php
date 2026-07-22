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
                    <button type="button" class="btn btn-outline-success text-nowrap" data-bs-toggle="modal" data-bs-target="#reportKeluargaModal">
                        <i class="bx bx-spreadsheet me-1"></i> Report Excel
                    </button>
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
                                    <span class="badge bg-label-info">{{ $keluarga->penduduk->where('status', 'aktif')->count() }} Orang</span>
                                </td>
                                <td>
                                    <div style="display: grid; grid-template-columns: repeat(3, max-content); gap: 0.25rem;">

                                            <a href="{{ route('admin.keluarga.show', $keluarga->id) }}" class="btn btn-sm btn-icon" title="Detail KK & Anggota"><i class="bx bx-show  text-info"></i></a>
                                            <a href="{{ route('admin.keluarga.edit', $keluarga->id) }}" class="btn btn-sm btn-icon" title="Edit KK"><i class="bx bx-edit-alt  text-primary"></i></a>
                                            <form action="{{ route('admin.keluarga.destroy', $keluarga->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus KK ini? Semua data penduduk di dalamnya harus dipindahkan/dihapus terlebih dahulu.');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon" title="Hapus"><i class="bx bx-trash  text-danger"></i></button>
                                            </form>
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

<div class="modal fade" id="reportKeluargaModal" tabindex="-1" aria-labelledby="reportKeluargaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.keluarga.report') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportKeluargaModalLabel">Report Excel Keluarga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="report_keluarga_dusun">Dusun</label>
                            <select name="dusun_id" id="report_keluarga_dusun" class="form-select">
                                <option value="">Semua Dusun</option>
                                @foreach($dusunList as $dusun)
                                    <option value="{{ $dusun->id }}">{{ $dusun->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="report_keluarga_rt_rw">RT / RW</label>
                            <select name="rt_rw_id" id="report_keluarga_rt_rw" class="form-select">
                                <option value="">Semua RT / RW</option>
                                @foreach($rtRwList as $rtRw)
                                    <option value="{{ $rtRw->id }}">RT {{ $rtRw->no_rt }} / RW {{ $rtRw->no_rw }} - {{ $rtRw->dusun->nama ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="report_keluarga_tanggal_mulai">Tanggal Terdaftar Mulai</label>
                            <input type="date" name="tanggal_terdaftar_mulai" id="report_keluarga_tanggal_mulai" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="report_keluarga_tanggal_selesai">Tanggal Terdaftar Selesai</label>
                            <input type="date" name="tanggal_terdaftar_selesai" id="report_keluarga_tanggal_selesai" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="report_keluarga_status_kepala">Status Kepala Keluarga</label>
                            <select name="status_kepala_keluarga" id="report_keluarga_status_kepala" class="form-select">
                                <option value="">Semua</option>
                                <option value="ada">Sudah Ada Kepala Keluarga</option>
                                <option value="belum_ada">Belum Ada Kepala Keluarga</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-download me-1"></i> Download Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
