@extends('admin.layouts.app')

@section('title', 'Edit Slider')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Slider /</span> Edit Slider
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Slider Banner</h5>
                <a href="{{ route('admin.slider.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="gambar">Gambar Banner</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" />
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar. Rekomendasi resolusi: 1920x600 px. Format: jpeg, png, jpg. Maksimal 2MB.</div>

                        @if ($slider->gambar)
                            <div class="mt-3">
                                <label class="d-block form-label">Gambar Saat Ini:</label>
                                <img src="{{ asset('storage/' . $slider->gambar) }}" alt="Slider" class="img-thumbnail" style="max-height: 150px; object-fit: cover;" />
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="judul">Judul Banner (Opsional)</label>
                        <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $slider->judul) }}" placeholder="Teks judul utama di atas banner" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="deskripsi">Deskripsi Singkat (Opsional)</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Teks sub-judul atau penjelasan pendek di atas banner">{{ old('deskripsi', $slider->deskripsi) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="link">Link Tujuan / URL (Opsional)</label>
                            <input type="url" class="form-control" id="link" name="link" value="{{ old('link', $slider->link) }}" placeholder="Contoh: https://example.com/berita/detail" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="urutan">Urutan Tampil <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="urutan" name="urutan" value="{{ old('urutan', $slider->urutan) }}" min="1" required />
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif (Tampilkan di website)</label>
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
