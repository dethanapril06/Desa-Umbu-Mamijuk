@extends('admin.layouts.app')

@section('title', 'Unggah Foto Galeri')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Foto Galeri /</span> Unggah Foto Baru
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Unggah Foto</h5>
                <a href="{{ route('admin.galeri.index', ['album_galeri_id' => $selectedAlbumId]) }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="album_galeri_id">Pilih Album Galeri <span class="text-danger">*</span></label>
                        <select class="form-select" id="album_galeri_id" name="album_galeri_id">
                            <option value="">-- Pilih Album --</option>
                            @foreach($albums as $alb)
                                <option value="{{ $alb->id }}" {{ (old('album_galeri_id') == $alb->id || $selectedAlbumId == $alb->id) ? 'selected' : '' }}>{{ $alb->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="gambar">Pilih Foto <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" />
                        <div class="form-text"><strong>Wajib orientasi mendatar (Landscape).</strong> Rekomendasi: 1200x800 px. Minimal: 400x250 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="caption">Keterangan Foto / Caption</label>
                        <input type="text" class="form-control" id="caption" name="caption" value="{{ old('caption') }}" placeholder="Tuliskan keterangan pendek mengenai foto..." />
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-upload me-1"></i> Unggah Foto
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
