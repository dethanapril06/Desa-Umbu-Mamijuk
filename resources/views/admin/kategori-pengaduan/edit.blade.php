@extends('admin.layouts.app')

@section('title', 'Edit Kategori Pengaduan')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kategori Pengaduan /</span> Edit Kategori
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Kategori</h5>
                <a href="{{ route('admin.kategori-pengaduan.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.kategori-pengaduan.update', $kategoriPengaduan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $kategoriPengaduan->nama) }}" placeholder="Contoh: Infrastruktur, Layanan Sosial" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="icon">Icon Class (Boxicons - Opsional)</label>
                        <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', $kategoriPengaduan->icon) }}" placeholder="Contoh: bx-message-detail, bx-wrench" />
                        <div class="form-text">Gunakan class icon dari Boxicons (contoh: <code>bx-message-detail</code>, <code>bx-wrench</code>, <code>bx-shield</code>).</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Perbarui Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
