@extends('admin.layouts.app')

@section('title', 'Tambah Slider')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Slider /</span> Tambah Slider
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah Slider Banner</h5>
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

                <form action="{{ route('admin.slider.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="gambar">Gambar Banner <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" />
                        <div class="form-text"><strong>Wajib orientasi mendatar (Landscape).</strong> Rekomendasi resolusi: 1920x800 px. Minimal 800x350 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="judul">Judul Banner (Opsional)</label>
                        <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Teks judul utama di atas banner" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="deskripsi">Deskripsi Singkat (Opsional)</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Teks sub-judul atau penjelasan pendek di atas banner">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="link">Link Tujuan / URL (Opsional)</label>
                        <input type="url" class="form-control" id="link" name="link" value="{{ old('link') }}" placeholder="Contoh: https://example.com/berita/detail" />
                        <div class="form-text">Tautan ketika banner di-klik. Harus diawali dengan http:// atau https://</div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif (Tampilkan di website)</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Slider
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const judul = document.getElementById('judul');
    if (judul) {
        judul.addEventListener('blur', function() {
            this.value = this.value.replace(/\s+/g, ' ').trim().replace(/\w\S*/g, function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        });
    }

    const spaceFields = ['deskripsi', 'link'];
    spaceFields.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('blur', function() {
                this.value = this.value.replace(/\s+/g, ' ').trim();
            });
        }
    });
});
</script>
@endpush
@endsection
