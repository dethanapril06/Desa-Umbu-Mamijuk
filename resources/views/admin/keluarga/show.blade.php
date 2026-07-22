@extends('admin.layouts.app')

@section('title', 'Detail Kartu Keluarga')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kependudukan /</span> Detail Kartu Keluarga
        </h4>

        <div class="row g-4">
            <!-- Informasi Kartu Keluarga -->
            <div class="col-md-4">
                <div class="card mb-4 h-100">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white"><i class="bx bx-card me-1"></i> Data KK</h5>
                        <a href="{{ route('admin.keluarga.edit', $keluarga->id) }}" class="btn btn-sm btn-outline-light">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                    </div>
                    <div class="card-body pt-3">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-bold py-1 px-0" style="width: 40%;">No. KK:</td>
                                    <td class="py-1 px-0"><span class="badge bg-label-primary fs-6">{{ $keluarga->no_kk }}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold py-1 px-0">Kepala Keluarga:</td>
                                    <td class="py-1 px-0">
                                        @if($kepalaKeluarga)
                                            <a href="{{ route('admin.penduduk.show', $kepalaKeluarga->id) }}" class="fw-bold text-dark">
                                                {{ $kepalaKeluarga->nama_lengkap }}
                                            </a>
                                        @else
                                            <span class="text-danger"><i class="bx bx-error-circle me-1"></i>Belum ditentukan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold py-1 px-0">Wilayah:</td>
                                    <td class="py-1 px-0">
                                        RT {{ $keluarga->rtRw->no_rt }} / RW {{ $keluarga->rtRw->no_rw }} <br/>
                                        {{ $keluarga->rtRw->dusun->nama }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold py-1 px-0">Kode Pos:</td>
                                    <td class="py-1 px-0">{{ $keluarga->kode_pos ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold py-1 px-0">Tgl. Terdaftar:</td>
                                    <td class="py-1 px-0">{{ $keluarga->tanggal_terdaftar ? $keluarga->tanggal_terdaftar->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold py-1 px-0">Alamat Lengkap:</td>
                                    <td class="py-1 px-0 text-wrap">{{ $keluarga->alamat }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light p-3">
                        <a href="{{ route('admin.keluarga.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar KK
                        </a>
                    </div>
                </div>
            </div>

            <!-- Daftar Anggota Keluarga -->
            <div class="col-md-8">
                <div class="card mb-4 h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bx bx-group me-1"></i> Anggota Keluarga</h5>
                        <a href="{{ route('admin.penduduk.create', ['keluarga_id' => $keluarga->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus me-1"></i> Tambah Anggota
                        </a>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama Lengkap</th>
                                    <th>Hubungan</th>
                                    <th>L/P</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($keluarga->penduduk as $anggota)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.penduduk.show', $anggota->id) }}" class="fw-bold text-dark">
                                                {{ $anggota->nik }}
                                            </a>
                                        </td>
                                        <td>
                                            <strong>{{ $anggota->nama_lengkap }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge {{ $anggota->status_hubungan_keluarga === 'kepala_keluarga' ? 'bg-primary' : 'bg-label-secondary' }}">
                                                {{ str_replace('_', ' ', ucfirst($anggota->status_hubungan_keluarga)) }}
                                            </span>
                                        </td>
                                        <td>{{ $anggota->jenis_kelamin === 'laki-laki' ? 'L' : 'P' }}</td>
                                        <td>
                                            <span class="badge {{ $anggota->status === 'aktif' ? 'bg-success' : ($anggota->status === 'meninggal' ? 'bg-danger' : 'bg-warning') }}">
                                                {{ ucfirst($anggota->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div style="display: grid; grid-template-columns: repeat(3, max-content); gap: 0.25rem;">

                                                    <a href="{{ route('admin.penduduk.show', $anggota->id) }}" class="btn btn-sm btn-icon" title="Profil Detail"><i class="bx bx-show  text-info"></i></a>
                                                    <a href="{{ route('admin.penduduk.edit', $anggota->id) }}" class="btn btn-sm btn-icon" title="Edit Biodata"><i class="bx bx-edit-alt  text-primary"></i></a>
                                                    <form action="{{ route('admin.penduduk.destroy', $anggota->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data anggota keluarga ini?');" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-icon" title="Hapus"><i class="bx bx-trash  text-danger"></i></button>
                                                    </form>
                                    </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada anggota keluarga terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
