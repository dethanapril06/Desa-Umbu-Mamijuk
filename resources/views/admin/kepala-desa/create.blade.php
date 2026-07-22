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
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="gelar">Gelar / Pendidikan</label>
                        <input type="text" class="form-control" id="gelar" name="gelar" value="{{ old('gelar') }}" placeholder="Contoh: S.Sos, M.Si" />
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="periode_mulai">Tahun Periode Mulai <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="periode_mulai" name="periode_mulai" value="{{ old('periode_mulai') }}" placeholder="Contoh: 2020" inputmode="numeric" maxlength="4" autocomplete="off" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="periode_selesai">Tahun Periode Selesai <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="periode_selesai" name="periode_selesai" value="{{ old('periode_selesai') }}" placeholder="Contoh: 2026" inputmode="numeric" maxlength="4" autocomplete="off" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="foto">Foto Kepala Desa <span class="text-danger">*</span></label>
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

    // ── Capital Each Word ─────────────────────────────────────────────────────
    function toCapitalEachWord(str) {
        if (!str) return str;
        // Lowercase semua, lalu kapital huruf pertama setelah non-alphanumeric
        return str.toLowerCase().replace(
            /(^|[^a-zA-Z0-9\u00C0-\u024F\u1E00-\u1EFF]+)([a-zA-Z\u00C0-\u024F\u1E00-\u1EFF])/gu,
            function(match, p1, p2) { return p1 + p2.toUpperCase(); }
        );
    }

    function toSentenceCase(str) {
        let lower = str.toLowerCase();
        return lower.replace(/(^|[.!?]\s+|\r?\n+)([a-z\p{L}])/gu, function(match, p1, p2) {
            return p1 + p2.toUpperCase();
        });
    }

    // ── Field: nama & gelar → Capital Each Word (hanya saat blur) ────────────
    const capitalFields = document.querySelectorAll('#nama, #gelar');
    capitalFields.forEach(function(input) {
        input.addEventListener('blur', function() {
            if (!this.value) return;
            this.value = toCapitalEachWord(this.value.trim().replace(/\s+/g, ' '));
        });
    });

    // ── Field: tahun (periode_mulai & periode_selesai) → digit only, no space ─
    function sanitizeYear(input) {
        // Simpan posisi kursor
        let pos = input.selectionStart;
        let oldVal = input.value;
        // Strip semua non-digit, max 4 karakter
        let newVal = oldVal.replace(/[^0-9]/g, '').slice(0, 4);
        if (oldVal !== newVal) {
            // Hitung berapa karakter non-digit dihapus sebelum kursor
            let removed = 0;
            for (let i = 0; i < pos && i < oldVal.length; i++) {
                if (!/[0-9]/.test(oldVal[i])) removed++;
            }
            input.value = newVal;
            let newPos = Math.max(0, pos - removed);
            if (input.setSelectionRange) input.setSelectionRange(newPos, newPos);
        }
    }

    const yearFields = document.querySelectorAll('#periode_mulai, #periode_selesai');
    yearFields.forEach(function(input) {
        // 1. Blokir keydown: tolak semua kecuali digit & tombol kontrol
        input.addEventListener('keydown', function(e) {
            const ctrl = e.ctrlKey || e.metaKey;
            // Izinkan: Ctrl+A/C/V/X, tombol navigasi
            if (ctrl) return;
            const nav = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End','Enter'];
            if (nav.includes(e.key)) return;
            // Tolak semua selain digit
            if (!/^[0-9]$/.test(e.key)) {
                e.preventDefault();
            }
        });

        // 2. Intercept paste: ambil digit saja
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            let pasted = (e.clipboardData || window.clipboardData).getData('text');
            let digits = pasted.replace(/[^0-9]/g, '');
            // Sisipkan digit ke posisi kursor, max total 4
            let sel_start = this.selectionStart;
            let sel_end   = this.selectionEnd;
            let cur = this.value.replace(/[^0-9]/g, '');
            let merged = (cur.slice(0, sel_start) + digits + cur.slice(sel_end)).slice(0, 4);
            this.value = merged;
        });

        // 3. oninput fallback: strip semua non-digit (tangani IME, autocomplete, dll)
        input.addEventListener('input', function() {
            sanitizeYear(this);
        });

        // 4. blur: sanitasi terakhir
        input.addEventListener('blur', function() {
            sanitizeYear(this);
        });
    });

    // ── Field: sambutan → Sentence case ──────────────────────────────────────
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
