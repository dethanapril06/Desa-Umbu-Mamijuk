@extends('admin.layouts.app')

@section('title', 'Edit Kepala Desa')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kepala Desa /</span> Edit Kepala Desa
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Kepala Desa</h5>
                <a href="{{ route('admin.kepala-desa.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>
            
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.kepala-desa.update', $kepalaDesa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $kepalaDesa->nama) }}" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="gelar">Gelar / Pendidikan</label>
                        <input type="text" class="form-control" id="gelar" name="gelar" value="{{ old('gelar', $kepalaDesa->gelar) }}" placeholder="Contoh: S.Sos, M.Si" />
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="periode_mulai">Tahun Periode Mulai <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="periode_mulai" name="periode_mulai" value="{{ old('periode_mulai', $kepalaDesa->periode_mulai) }}" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="periode_selesai">Tahun Periode Selesai <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="periode_selesai" name="periode_selesai" value="{{ old('periode_selesai', $kepalaDesa->periode_selesai) }}" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="foto">Foto Kepala Desa</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" />
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto. Rekomendasi resolusi: 600x800 px (rasio 3:4 potret). Minimal: 250x300 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>

                        @if ($kepalaDesa->foto)
                            <div class="mt-3">
                                <label class="d-block form-label">Foto Saat Ini:</label>
                                <img src="{{ asset('storage/' . $kepalaDesa->foto) }}" alt="Foto Kepala Desa" class="img-thumbnail" style="max-height: 150px; object-fit: cover;" />
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="sambutan">Kata Sambutan</label>
                        <textarea class="form-control" id="sambutan" name="sambutan" rows="6" placeholder="Tuliskan kata sambutan kepala desa...">{{ old('sambutan', $kepalaDesa->sambutan) }}</textarea>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $kepalaDesa->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktifkan sebagai Kepala Desa saat ini</label>
                        <div class="form-text">Mengaktifkan kepala desa ini akan otomatis menonaktifkan kepala desa yang lain.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
