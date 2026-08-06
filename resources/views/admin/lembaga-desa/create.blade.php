@extends('admin.layouts.app')

@section('title', 'Tambah Lembaga Desa')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Profil Desa / Lembaga Desa /</span> Tambah Data
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Tambah Lembaga Desa</h5>
                    <a href="{{ route('admin.lembaga-desa.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.lembaga-desa.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nama_lembaga" class="form-label">Nama Lembaga Desa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_lembaga') is-invalid @enderror" id="nama_lembaga" name="nama_lembaga" value="{{ old('nama_lembaga') }}" placeholder="Contoh: Badan Permusyawaratan Desa" required>
                                    @error('nama_lembaga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="singkatan" class="form-label">Singkatan / Akronim</label>
                                    <input type="text" class="form-control @error('singkatan') is-invalid @enderror" id="singkatan" name="singkatan" value="{{ old('singkatan') }}" placeholder="Contoh: BPD">
                                    @error('singkatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ketua" class="form-label">Nama Ketua / Penanggung Jawab <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ketua') is-invalid @enderror" id="ketua" name="ketua" value="{{ old('ketua') }}" placeholder="Contoh: Ahmad Subagyo, S.Pd" required>
                                    @error('ketua')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_telepon" class="form-label">No. Telepon / Kontak Sekretariat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 081234567890" required>
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat_sekretariat" class="form-label">Alamat Sekretariat / Kantor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('alamat_sekretariat') is-invalid @enderror" id="alamat_sekretariat" name="alamat_sekretariat" value="{{ old('alamat_sekretariat') }}" placeholder="Contoh: Jl. Raya Desa No. 12, RT 02 / RW 01" required>
                            @error('alamat_sekretariat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi & Tugas Lembaga <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan peran, fungsi, visi, dan aktivitas lembaga desa ini..." required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo / Lambang Lembaga</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                            <div class="form-text">Maksimal ukuran 10 MB. Format: JPG, PNG, WEBP.</div>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">Aktifkan Lembaga Desa (Tampilkan di Website Public)</label>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Simpan Data
                            </button>
                            <a href="{{ route('admin.lembaga-desa.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
