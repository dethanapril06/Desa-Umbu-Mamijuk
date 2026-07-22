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
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h5 class="mb-0">Catatan Mutasi Penduduk</h5>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#reportMutasiModal">
                        <i class="bx bx-spreadsheet me-1"></i> Report Excel
                    </button>
                    <a href="{{ route('admin.mutasi-penduduk.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Catat Mutasi
                    </a>
                </div>
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
                                    <div style="display: grid; grid-template-columns: repeat(3, max-content); gap: 0.25rem;">

                                            <a href="{{ route('admin.mutasi-penduduk.edit', $mutasi->id) }}" class="btn btn-sm btn-icon" title="Edit Catatan"><i class="bx bx-edit-alt  text-primary"></i></a>
                                            <form action="{{ route('admin.mutasi-penduduk.destroy', $mutasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan mutasi ini? Menghapus catatan mutasi akan mengembalikan status penduduk terkait menjadi AKTIF.');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon" title="Hapus"><i class="bx bx-trash  text-danger"></i></button>
                                            </form>
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

<div class="modal fade" id="reportMutasiModal" tabindex="-1" aria-labelledby="reportMutasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.mutasi-penduduk.report') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportMutasiModalLabel">Report Excel Mutasi Penduduk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="report_mutasi_search">Nama / NIK / No. Surat</label>
                            <input type="text" name="search" id="report_mutasi_search" class="form-control" placeholder="Ketik pencarian...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="report_mutasi_jenis">Jenis Mutasi</label>
                            <select name="jenis_mutasi" id="report_mutasi_jenis" class="form-select">
                                <option value="">Semua Jenis Mutasi</option>
                                <option value="mati">Meninggal</option>
                                <option value="pindah_masuk">Pindah Masuk</option>
                                <option value="pindah_keluar">Pindah Keluar</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="report_mutasi_dusun">Dusun</label>
                            <select name="dusun_id" id="report_mutasi_dusun" class="form-select">
                                <option value="">Semua Dusun</option>
                                @foreach($dusunList as $dusun)
                                    <option value="{{ $dusun->id }}">{{ $dusun->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="report_mutasi_rt_rw">RT / RW</label>
                            <select name="rt_rw_id" id="report_mutasi_rt_rw" class="form-select">
                                <option value="">Semua RT / RW</option>
                                @foreach($rtRwList as $rtRw)
                                    <option value="{{ $rtRw->id }}">RT {{ $rtRw->no_rt }} / RW {{ $rtRw->no_rw }} - {{ $rtRw->dusun->nama ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="report_mutasi_tanggal_mulai">Tanggal Mutasi Mulai</label>
                            <input type="date" name="tanggal_mutasi_mulai" id="report_mutasi_tanggal_mulai" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="report_mutasi_tanggal_selesai">Tanggal Mutasi Selesai</label>
                            <input type="date" name="tanggal_mutasi_selesai" id="report_mutasi_tanggal_selesai" class="form-control">
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
