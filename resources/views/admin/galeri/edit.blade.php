@extends('admin.layouts.app')

@section('title', 'Edit Foto Galeri')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Foto Galeri /</span> Edit Foto
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Foto</h5>
                <a href="{{ route('admin.galeri.index', ['album_galeri_id' => $galeri->album_galeri_id]) }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="album_galeri_id">Pilih Album Galeri <span class="text-danger">*</span></label>
                        <select class="form-select" id="album_galeri_id" name="album_galeri_id">
                            @foreach($albums as $alb)
                                <option value="{{ $alb->id }}" {{ old('album_galeri_id', $galeri->album_galeri_id) == $alb->id ? 'selected' : '' }}>{{ $alb->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="gambar">Ganti Foto</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" />
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto. <strong>Wajib orientasi mendatar (Landscape).</strong> Rekomendasi: 1200x800 px. Minimal: 400x250 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>

                        @if ($galeri->gambar)
                            <div class="mt-3">
                                <label class="d-block form-label">Foto Saat Ini:</label>
                                <img src="{{ asset('storage/' . $galeri->gambar) }}" alt="Foto" class="img-thumbnail" style="max-height: 150px; object-fit: cover;" />
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="caption">Keterangan Foto / Caption <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="caption" name="caption" value="{{ old('caption', $galeri->caption) }}" />
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
