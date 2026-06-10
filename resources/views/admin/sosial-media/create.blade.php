@extends('admin.layouts.app')

@section('title', 'Tambah Sosial Media')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Sosial Media /</span> Tambah Sosial Media
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah Sosial Media</h5>
                <a href="{{ route('admin.sosial-media.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.sosial-media.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="platform">Nama Platform <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="platform" name="platform" value="{{ old('platform') }}" placeholder="Contoh: Facebook, Instagram, YouTube" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="url">Link / URL Profil <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="url" name="url" value="{{ old('url') }}" placeholder="Contoh: https://facebook.com/username" required />
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="icon">Icon Class (Boxicons) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon') }}" placeholder="Contoh: bxl-facebook, bxl-instagram, bxl-youtube" required />
                            <div class="form-text">Gunakan class icon dari Boxicons (contoh: <code>bxl-facebook</code>, <code>bxl-instagram</code>, <code>bxl-youtube</code>, <code>bxl-twitter</code>, <code>bxl-tiktok</code>).</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="urutan">Urutan Tampil <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="urutan" name="urutan" value="{{ old('urutan', 1) }}" min="1" required />
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif (Tampilkan di website)</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Sosial Media
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
