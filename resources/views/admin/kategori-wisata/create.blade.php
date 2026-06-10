@extends('admin.layouts.app')

@section('title', 'Tambah Kategori Wisata')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kategori Wisata /</span> Tambah Kategori
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah Kategori</h5>
                <a href="{{ route('admin.kategori-wisata.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.kategori-wisata.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Air Terjun, Wisata Budaya" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="icon">Icon Class (Boxicons - Opsional)</label>
                        <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon') }}" placeholder="Contoh: bx-landscape, bx-water" />
                        <div class="form-text">Gunakan class icon dari Boxicons (contoh: <code>bx-landscape</code>, <code>bx-water</code>, <code>bx-camera-movie</code>).</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
