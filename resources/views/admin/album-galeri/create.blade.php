@extends('admin.layouts.app')

@section('title', 'Buat Album Galeri')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Album Galeri /</span> Buat Album Baru
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Buat Album</h5>
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

                <form action="{{ route('admin.album-galeri.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama Album <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: HUT RI Ke-78, Pembangunan Embung" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="cover">Gambar Cover Album <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="cover" name="cover" accept="image/*" />
                        <div class="form-text">Resolusi yang disarankan: 800x600 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="deskripsi">Deskripsi Album <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Tuliskan deskripsi singkat mengenai isi album ini...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Album
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
