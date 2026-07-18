@extends('admin.layouts.app')

@section('title', 'Edit Perangkat Desa')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Perangkat Desa /</span> Edit Perangkat Desa
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Perangkat Desa</h5>
                <a href="{{ route('admin.perangkat-desa.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.perangkat-desa.update', $perangkatDesa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $perangkatDesa->nama) }}" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="jabatan">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ old('jabatan', $perangkatDesa->jabatan) }}" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="nip">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip', $perangkatDesa->nip) }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="foto">Foto Perangkat Desa</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" />
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto. Rekomendasi resolusi: 600x800 px (rasio 3:4 potret). Minimal: 250x300 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>

                        @if ($perangkatDesa->foto)
                            <div class="mt-3">
                                <label class="d-block form-label">Foto Saat Ini:</label>
                                <img src="{{ asset('storage/' . $perangkatDesa->foto) }}" alt="Foto Perangkat" class="img-thumbnail" style="max-height: 150px; object-fit: cover;" />
                            </div>
                        @endif
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $perangkatDesa->is_active) ? 'checked' : '' }}>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function toCapitalEachWord(str) {
        return str.toLowerCase().replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    }

    const fields = document.querySelectorAll('#nama, #jabatan, #nip');
    fields.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value) return;
            let val = this.value.trim().replace(/\s+/g, ' ');
            if (this.id === 'nama' || this.id === 'jabatan') {
                val = toCapitalEachWord(val);
            }
            this.value = val;
        });
    });
});
</script>
@endpush
