@extends('admin.layouts.app')

@section('title', 'Tambah Kepala Desa')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kepala Desa /</span> Tambah Kepala Desa
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah Kepala Desa</h5>
                <a href="{{ route('admin.kepala-desa.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.kepala-desa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="gelar">Gelar / Pendidikan</label>
                        <input type="text" class="form-control" id="gelar" name="gelar" value="{{ old('gelar') }}" placeholder="Contoh: S.Sos, M.Si" />
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="periode_mulai">Tahun Periode Mulai <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="periode_mulai" name="periode_mulai" value="{{ old('periode_mulai') }}" placeholder="Contoh: 2020" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="periode_selesai">Tahun Periode Selesai <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="periode_selesai" name="periode_selesai" value="{{ old('periode_selesai') }}" placeholder="Contoh: 2026" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="foto">Foto Kepala Desa</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" />
                        <div class="form-text">Rekomendasi resolusi: 600x800 px (rasio 3:4 potret). Minimal: 250x300 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="sambutan">Sambutan</label>
                        <textarea class="form-control" id="sambutan" name="sambutan" rows="6" placeholder="Tuliskan kata sambutan kepala desa...">{{ old('sambutan') }}</textarea>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktifkan sebagai Kepala Desa saat ini</label>
                        <div class="form-text">Mengaktifkan kepala desa ini akan otomatis menonaktifkan kepala desa yang lain.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Data
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

    function toSentenceCase(str) {
        let lower = str.toLowerCase();
        return lower.replace(/(^|[.!?]\s+|\r?\n+)([a-z\p{L}])/gu, function(match, p1, p2) {
            return p1 + p2.toUpperCase();
        });
    }

    const singleFields = document.querySelectorAll('#nama, #gelar, #periode_mulai, #periode_selesai');
    singleFields.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value) return;
            let val = this.value.trim().replace(/\s+/g, ' ');
            if (this.id === 'nama') {
                val = toCapitalEachWord(val);
            }
            this.value = val;
        });
    });

    const sambutanInput = document.getElementById('sambutan');
    if (sambutanInput) {
        sambutanInput.addEventListener('blur', function() {
            if (!this.value) return;
            let lines = this.value.split(/\r?\n/);
            let cleanedLines = lines.map(line => line.trim().replace(/[^\S\r\n]+/g, ' '));
            let text = cleanedLines.join('\n').replace(/\n{3,}/g, '\n\n').trim();
            text = toSentenceCase(text);
            this.value = text;
        });
    }
});
</script>
@endpush
