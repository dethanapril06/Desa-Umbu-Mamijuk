@extends('admin.layouts.app')

@section('title', 'Tambah Penginapan / Homestay Baru')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
<div class="row">
    <div class="col-12 col-md-10 offset-md-1">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Konten Website / Penginapan /</span> Tambah Penginapan
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah Penginapan / Homestay</h5>
                <a href="{{ route('admin.penginapan.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.penginapan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="nama_penginapan">Nama Penginapan / Homestay <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_penginapan" name="nama_penginapan" value="{{ old('nama_penginapan') }}" placeholder="Contoh: Homestay Asri Ibu Adri" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="jenis">Jenis Penginapan <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis" name="jenis">
                                <option value="Homestay" {{ old('jenis') == 'Homestay' ? 'selected' : '' }}>Homestay</option>
                                <option value="Villa" {{ old('jenis') == 'Villa' ? 'selected' : '' }}>Villa</option>
                                <option value="Hotel" {{ old('jenis') == 'Hotel' ? 'selected' : '' }}>Hotel</option>
                                <option value="Guesthouse" {{ old('jenis') == 'Guesthouse' ? 'selected' : '' }}>Guesthouse</option>
                                <option value="Pondok Wisata" {{ old('jenis') == 'Pondok Wisata' ? 'selected' : '' }}>Pondok Wisata</option>
                                <option value="Camping Ground" {{ old('jenis') == 'Camping Ground' ? 'selected' : '' }}>Camping Ground</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="kisaran_harga">Kisaran Harga / Malam <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kisaran_harga" name="kisaran_harga" value="{{ old('kisaran_harga') }}" placeholder="Contoh: Rp 150.000 - Rp 300.000" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_telepon">No. WhatsApp / Reservasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 08123456789" inputmode="numeric" autocomplete="off" />
                            <div class="form-text">Gunakan format angka tanpa spasi/simbol untuk tombol chat WhatsApp.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="jarak">Jarak / Keterangan Lokasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jarak" name="jarak" value="{{ old('jarak') }}" placeholder="Contoh: 200 meter dari Bukit Kami / Pusat Desa" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="fasilitas_singkat">Fasilitas Singkat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fasilitas_singkat" name="fasilitas_singkat" value="{{ old('fasilitas_singkat') }}" placeholder="Contoh: AC, Wi-Fi, Sarapan Pagi, Kamar Mandi Dalam" />
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="wisata_ids">Terletak di Dekat Destinasi Wisata Apa Saja? (Bisa pilih lebih dari satu) <span class="text-danger">*</span></label>
                        <select class="form-select select2-multiple" id="wisata_ids" name="wisata_ids[]" multiple="multiple" data-placeholder="Cari & pilih destinasi wisata terkait...">
                            @foreach($allWisata as $w)
                                <option value="{{ $w->id }}" {{ (is_array(old('wisata_ids')) && in_array($w->id, old('wisata_ids'))) ? 'selected' : '' }}>
                                    {{ $w->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Pilih wisata yang berjarak dekat atau relevan dengan penginapan ini.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="foto">Foto Penginapan <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewImage(this)" />
                        <div class="form-text"><strong>Wajib orientasi mendatar (Landscape).</strong> Rekomendasi: 1200x800 px. Minimal: 400x250 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>
                        <div class="mt-3">
                            <img id="image-preview" src="#" alt="Preview Foto" class="img-fluid rounded" style="max-height: 250px; display: none;" />
                        </div>
                    </div>

                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">Tampilkan Penginapan di Website Publik</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Data Penginapan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-multiple').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: $('#wisata_ids').data('placeholder') || 'Pilih destinasi wisata...'
    });

    // Normalisasi input real-time saat blur
    function toTitleCase(str) {
        return str.replace(
            /\w\S*/g,
            function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            }
        );
    }

    function cleanSpaces(str) {
        return str.replace(/\s+/g, ' ').trim();
    }

    function formatRupiahRange(str) {
        if (!str) return '';
        str = cleanSpaces(str.replace(/\s*-\s*/g, ' - '));
        const parts = str.split(' - ');
        const res = parts.map(part => {
            part = part.trim();
            const match = part.match(/^(?:[A-Za-z\.\s]*?)([\d\.,]+)(.*)$/);
            if (match) {
                let numStr = match[1].replace(/[^\d]/g, '');
                let suffix = match[2].trim();
                if (numStr && !isNaN(numStr) && parseInt(numStr) > 0) {
                    let formatted = 'Rp ' + parseInt(numStr).toLocaleString('id-ID');
                    return suffix ? formatted + ' ' + suffix : formatted;
                }
            }
            return part;
        });
        return res.join(' - ');
    }

    $('#nama_penginapan').on('blur', function() {
        let val = $(this).val();
        if (val) {
            val = cleanSpaces(val);
            $(this).val(toTitleCase(val));
        }
    });

    $('#kisaran_harga').on('blur', function() {
        let val = $(this).val();
        if (val) {
            $(this).val(formatRupiahRange(val));
        }
    });

    $('#jarak, #fasilitas_singkat').on('blur', function() {
        let val = $(this).val();
        if (val) {
            $(this).val(cleanSpaces(val));
        }
    });

    // ── Field: no_telepon → digit only, no space ───────────────────────────
    function sanitizeDigits(input) {
        let pos = input.selectionStart;
        let oldVal = input.value;
        let newVal = oldVal.replace(/[^0-9]/g, '');
        if (oldVal !== newVal) {
            let removed = 0;
            for (let i = 0; i < pos && i < oldVal.length; i++) {
                if (!/[0-9]/.test(oldVal[i])) removed++;
            }
            input.value = newVal;
            let newPos = Math.max(0, pos - removed);
            if (input.setSelectionRange) input.setSelectionRange(newPos, newPos);
        }
    }

    const telpField = document.getElementById('no_telepon');
    if (telpField) {
        telpField.addEventListener('keydown', function(e) {
            const ctrl = e.ctrlKey || e.metaKey;
            if (ctrl) return;
            const nav = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End','Enter'];
            if (nav.includes(e.key)) return;
            if (!/^[0-9]$/.test(e.key)) e.preventDefault();
        });

        telpField.addEventListener('paste', function(e) {
            e.preventDefault();
            let pasted = (e.clipboardData || window.clipboardData).getData('text');
            let digits = pasted.replace(/[^0-9]/g, '');
            let maxLen = this.getAttribute('maxlength') ? parseInt(this.getAttribute('maxlength')) : null;
            let sel_start = this.selectionStart;
            let sel_end   = this.selectionEnd;
            let cur = this.value.replace(/[^0-9]/g, '');
            let merged = (cur.slice(0, sel_start) + digits + cur.slice(sel_end));
            if (maxLen && merged.length > maxLen) merged = merged.slice(0, maxLen);
            this.value = merged;
        });

        telpField.addEventListener('input', function() { sanitizeDigits(this); });
        telpField.addEventListener('blur',  function() { sanitizeDigits(this); });
    }
});

function previewImage(input) {
    var preview = document.getElementById('image-preview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
}
</script>
@endpush
