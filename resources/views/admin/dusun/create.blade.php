@extends('admin.layouts.app')

@section('title', 'Tambah Dusun')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Dusun /</span> Tambah Dusun
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah Dusun</h5>
                <a href="{{ route('admin.dusun.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.dusun.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama Dusun <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Dusun Karanglo" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="kepala_dusun">Kepala Dusun (Kamituwo)</label>
                        <input type="text" class="form-control" id="kepala_dusun" name="kepala_dusun" value="{{ old('kepala_dusun') }}" placeholder="Nama kepala dusun" />
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif (Tampilkan di form kependudukan)</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Dusun
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

    const fields = document.querySelectorAll('#nama, #kepala_dusun');
    fields.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value) return;
            let val = this.value.trim().replace(/\s+/g, ' ');
            val = toCapitalEachWord(val);
            this.value = val;
        });
    });
});
</script>
@endpush
