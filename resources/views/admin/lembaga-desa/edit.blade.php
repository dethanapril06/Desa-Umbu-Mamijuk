@extends('admin.layouts.app')

@section('title', 'Edit Lembaga Desa')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Profil Desa / Lembaga Desa /</span> Edit Data
    </h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Edit Lembaga Desa</h5>
                    <a href="{{ route('admin.lembaga-desa.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.lembaga-desa.update', $lembagaDesa->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nama_lembaga" class="form-label">Nama Lembaga Desa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_lembaga') is-invalid @enderror" id="nama_lembaga" name="nama_lembaga" value="{{ old('nama_lembaga', $lembagaDesa->nama_lembaga) }}" required>
                                    @error('nama_lembaga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="singkatan" class="form-label">Singkatan / Akronim</label>
                                    <input type="text" class="form-control @error('singkatan') is-invalid @enderror" id="singkatan" name="singkatan" value="{{ old('singkatan', $lembagaDesa->singkatan) }}">
                                    @error('singkatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ketua" class="form-label">Nama Ketua / Penanggung Jawab <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ketua') is-invalid @enderror" id="ketua" name="ketua" value="{{ old('ketua', $lembagaDesa->ketua) }}" required>
                                    @error('ketua')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_telepon" class="form-label">No. Telepon / Kontak Sekretariat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $lembagaDesa->no_telepon) }}" required>
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat_sekretariat" class="form-label">Alamat Sekretariat / Kantor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('alamat_sekretariat') is-invalid @enderror" id="alamat_sekretariat" name="alamat_sekretariat" value="{{ old('alamat_sekretariat', $lembagaDesa->alamat_sekretariat) }}" required>
                            @error('alamat_sekretariat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi & Tugas Lembaga <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $lembagaDesa->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo / Lambang Lembaga</label>

                            @if($lembagaDesa->logo)
                                <div class="mb-3 position-relative d-inline-block" style="max-width: 150px;">
                                    <img src="{{ asset('storage/' . $lembagaDesa->logo) }}" alt="{{ $lembagaDesa->nama_lembaga }}" class="img-thumbnail rounded" style="width: 100%; height: 120px; object-fit: cover;">
                                    
                                    {{-- Direct Logo Delete Button (Red Trash Icon) --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-icon bg-white shadow-sm border text-danger position-absolute top-0 end-0 m-1" 
                                            style="border-radius: 50%; width: 32px; height: 32px;"
                                            title="Hapus Logo" 
                                            onclick="confirmDeleteLogo()">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            @endif

                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengubah logo. Maksimal 10 MB.</div>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $lembagaDesa->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">Aktifkan Lembaga Desa (Tampilkan di Website Public)</label>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.lembaga-desa.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Standalone form for logo deletion placed OUTSIDE main form --}}
@if($lembagaDesa->logo)
    <form id="deleteLogoForm" action="{{ route('admin.lembaga-desa.delete-logo', $lembagaDesa->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endif

@endsection

@push('scripts')
<script>
function confirmDeleteLogo() {
    Swal.fire({
        title: 'Hapus Logo Lembaga Desa?',
        text: 'Logo yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus Logo!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteLogoForm').submit();
        }
    });
}
</script>
@endpush
