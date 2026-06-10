@extends('admin.layouts.app')

@section('title', 'Edit Album Galeri')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Album Galeri /</span> Edit Album
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Album</h5>
                <a href="{{ route('admin.album-galeri.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.album-galeri.update', $albumGaleri->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama Album <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $albumGaleri->nama) }}" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="cover">Gambar Cover Album</label>
                        <input type="file" class="form-control" id="cover" name="cover" accept="image/*" />
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah cover. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>

                        @if ($albumGaleri->cover)
                            <div class="mt-3">
                                <label class="d-block form-label">Cover Saat Ini:</label>
                                <img src="{{ asset('storage/' . $albumGaleri->cover) }}" alt="Cover Album" class="img-thumbnail" style="max-height: 150px; object-fit: cover;" />
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="deskripsi">Deskripsi Album (Opsional)</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $albumGaleri->deskripsi) }}</textarea>
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
