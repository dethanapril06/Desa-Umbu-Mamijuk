@extends('admin.layouts.app')

@section('title', 'Detail Profil Penduduk')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kependudukan /</span> Profil Penduduk
        </h4>

        <div class="row g-4">
            <!-- Sidebar: NIK, Nama & Kartu Keluarga -->
            <div class="col-lg-4 col-md-5">
                <div class="card mb-4 h-100">
                    <div class="card-body text-center pt-5">
                        <div class="avatar avatar-xl mx-auto mb-3">
                            <span class="avatar-initial rounded-circle bg-label-primary fs-1">
                                <i class="bx bx-user"></i>
                            </span>
                        </div>
                        <h5 class="mb-1 fw-bold">{{ $penduduk->nama_lengkap }}</h5>
                        <p class="text-muted mb-3">NIK: <code>{{ $penduduk->nik }}</code></p>
                        
                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <span class="badge {{ $penduduk->status === 'aktif' ? 'bg-success' : ($penduduk->status === 'meninggal' ? 'bg-danger' : 'bg-warning') }}">
                                Status: {{ ucfirst($penduduk->status) }}
                            </span>
                            <span class="badge bg-label-secondary">
                                {{ str_replace('_', ' ', ucfirst($penduduk->status_hubungan_keluarga)) }}
                            </span>
                        </div>

                        <div class="border-top pt-4 text-start">
                            <h6 class="fw-bold"><i class="bx bx-home-heart me-1 text-primary"></i> Hubungan Keluarga</h6>
                            <table class="table table-borderless table-sm small">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold ps-0" style="width: 40%;">No. KK:</td>
                                        <td>
                                            @if($penduduk->keluarga)
                                                <a href="{{ route('admin.keluarga.show', $penduduk->keluarga_id) }}" class="fw-bold text-primary">
                                                    {{ $penduduk->keluarga->no_kk }}
                                                </a>
                                            @else
                                                <span class="text-danger">Belum terdaftar</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold ps-0">Alamat KK:</td>
                                        <td class="text-wrap">{{ $penduduk->keluarga ? $penduduk->keluarga->alamat : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold ps-0">RT / RW:</td>
                                        <td>
                                            @if($penduduk->keluarga && $penduduk->keluarga->rtRw)
                                                RT {{ $penduduk->keluarga->rtRw->no_rt }} / RW {{ $penduduk->keluarga->rtRw->no_rw }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold ps-0">Dusun:</td>
                                        <td>{{ $penduduk->keluarga && $penduduk->keluarga->rtRw && $penduduk->keluarga->rtRw->dusun ? $penduduk->keluarga->rtRw->dusun->nama : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-3 d-flex gap-2">
                        <a href="{{ route('admin.penduduk.edit', $penduduk->id) }}" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bx bx-edit me-1"></i> Edit Profil
                        </a>
                        <a href="{{ $penduduk->keluarga_id ? route('admin.keluarga.show', $penduduk->keluarga_id) : route('admin.penduduk.index') }}" class="btn btn-outline-secondary btn-sm flex-grow-1">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Area: Data Biodata Lengkap -->
            <div class="col-lg-8 col-md-7">
                <div class="card mb-4 h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bx bx-file me-1"></i> Biodata Lengkap Penduduk</h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Tempat, Tanggal Lahir</span>
                                <strong class="fs-6">{{ $penduduk->tempat_lahir ?? '-' }}, {{ $penduduk->tanggal_lahir ? $penduduk->tanggal_lahir->format('d F Y') : '-' }}</strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Jenis Kelamin</span>
                                <strong class="fs-6">{{ ucfirst($penduduk->jenis_kelamin) }}</strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Agama</span>
                                <strong class="fs-6">{{ ucfirst($penduduk->agama ?? '-') }}</strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Golongan Darah</span>
                                <strong class="fs-6"><span class="badge bg-danger">{{ $penduduk->golongan_darah ?? '-' }}</span></strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Pendidikan Terakhir</span>
                                <strong class="fs-6">{{ str_replace('_', ' ', ucfirst($penduduk->pendidikan_terakhir ?? '-')) }}</strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Pekerjaan</span>
                                <strong class="fs-6">{{ $penduduk->pekerjaan ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Status Perkawinan</span>
                                <strong class="fs-6">{{ str_replace('_', ' ', ucfirst($penduduk->status_perkawinan ?? '-')) }}</strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">No. Telepon / HP</span>
                                <strong class="fs-6">{{ $penduduk->no_telepon ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Kewarganegaraan</span>
                                <strong class="fs-6">{{ $penduduk->kewarganegaraan }}</strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">No. Paspor / KITAS</span>
                                <strong class="fs-6">
                                    Paspor: {{ $penduduk->no_paspor ?? '-' }} <br/>
                                    KITAS/KITAP: {{ $penduduk->no_kitas_kitap ?? '-' }}
                                </strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Nama Ayah</span>
                                <strong class="fs-6">{{ $penduduk->nama_ayah ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Nama Ibu</span>
                                <strong class="fs-6">{{ $penduduk->nama_ibu ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Asuransi Kesehatan</span>
                                <strong class="fs-6">
                                    @if($penduduk->is_asuransi_kesehatan)
                                        <span class="text-success"><i class="bx bx-check-circle me-1"></i>Memiliki Asuransi</span>
                                    @else
                                        <span class="text-muted"><i class="bx bx-x-circle me-1"></i>Tidak Memiliki</span>
                                    @endif
                                </strong>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="d-block text-muted small">Kondisi Disabilitas</span>
                                <strong class="fs-6">
                                    @if($penduduk->is_disabilitas)
                                        <span class="text-warning"><i class="bx bx-error-circle me-1"></i>Penyandang Disabilitas ({{ $penduduk->jenis_disabilitas }})</span>
                                    @else
                                        <span class="text-success"><i class="bx bx-check-circle me-1"></i>Bukan Penyandang Disabilitas</span>
                                    @endif
                                </strong>
                            </div>
                            <div class="col-12 pb-2">
                                <span class="d-block text-muted small">Keterangan Lainnya</span>
                                <p class="mb-0 text-wrap">{{ $penduduk->keterangan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
