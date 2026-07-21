@extends('admin.layouts.app')

@section('title', 'Tambah Berita')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Berita /</span> Tambah Berita Baru
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah Berita</h5>
                <a href="{{ route('admin.berita.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <!-- Judul Berita -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label" for="judul">Judul Berita <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Tuliskan judul berita yang menarik..." />
                            </div>
                        </div>
                        
                        <!-- Kategori Berita -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="kategori_berita_id">Kategori Berita <span class="text-danger">*</span></label>
                                <select class="form-select" id="kategori_berita_id" name="kategori_berita_id">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('kategori_berita_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Gambar Cover Berita -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="gambar">Gambar Cover / Banner</label>
                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" />
                                <div class="form-text"><strong>Wajib orientasi mendatar (Landscape, rasio 16:9).</strong> Rekomendasi: 1200x675 px. Minimal: 400x250 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>
                            </div>
                        </div>

                        <!-- Caption Gambar -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="caption_gambar">Keterangan / Caption Gambar</label>
                                <input type="text" class="form-control" id="caption_gambar" name="caption_gambar" value="{{ old('caption_gambar') }}" placeholder="Contoh: Dok. Pemdes Umbu Mamijuk" />
                            </div>
                        </div>

                        <!-- Excerpt / Ringkasan Pendek -->
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="excerpt">Ringkasan Singkat (Excerpt)</label>
                                <textarea class="form-control" id="excerpt" name="excerpt" rows="2" placeholder="Tuliskan rangkuman pendek berita untuk halaman depan (opsional)...">{{ old('excerpt') }}</textarea>
                                <div class="form-text">Maksimal 500 karakter. Jika kosong, ringkasan akan diambil dari potongan konten berita.</div>
                            </div>
                        </div>

                        <!-- Konten Berita (WYSIWYG Editor) -->
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="editor">Isi Berita Lengkap <span class="text-danger">*</span></label>
                                <textarea id="editor" name="konten">{{ old('konten') }}</textarea>
                            </div>
                        </div>

                        <!-- Tag Berita -->
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label d-block">Tag Berita</label>
                                <div class="row pt-1">
                                    @forelse($tags as $tag)
                                        <div class="col-md-3 col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}" {{ (is_array(old('tags')) && in_array($tag->id, old('tags'))) ? 'checked' : '' }}>
                                                <label class="form-check-label text-truncate" for="tag_{{ $tag->id }}">{{ $tag->nama }}</label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <span class="text-muted small">Belum ada tag yang dibuat. Silakan tambahkan tag terlebih dahulu di menu Tag.</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Status Publikasi -->
                        <div class="col-12 border-top pt-3">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" {{ old('is_published', 1) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="is_published">Langsung Publikasikan Berita</label>
                                <div class="form-text">Jika tidak dicentang, berita akan disimpan sebagai Draft dan tidak muncul di halaman publik.</div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        <i class="bx bx-paper-plane me-1"></i> Simpan Berita
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.ck-editor__editable_inline {
    min-height: 350px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>
@endpush
@endsection
