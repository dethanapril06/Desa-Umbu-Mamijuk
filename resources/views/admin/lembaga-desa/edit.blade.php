@extends('admin.layouts.app')

@section('title', 'Edit Lembaga Desa')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Lembaga Desa /</span> Edit Lembaga Desa
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Lembaga Desa</h5>
                <a href="{{ route('admin.lembaga-desa.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.lembaga-desa.update', $lembagaDesa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $lembagaDesa->nama) }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="jabatan">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ old('jabatan', $lembagaDesa->jabatan) }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="nip">NIP (Nomor Induk Pegawai / Anggota)</label>
                        <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip', $lembagaDesa->nip) }}" inputmode="numeric" autocomplete="off" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="foto">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" />
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto. Rekomendasi resolusi: 600x800 px (rasio 3:4 potret). Minimal: 250x300 px. Format: jpeg, png, jpg, webp. Maksimal 10MB.</div>

                        @if ($lembagaDesa->foto)
                            <div class="mt-3">
                                <label class="d-block form-label">Foto Saat Ini:</label>
                                <div class="position-relative d-inline-block rounded border p-1 bg-light">
                                    <img src="{{ asset('storage/' . $lembagaDesa->foto) }}" alt="Foto Lembaga" class="img-thumbnail border-0" style="max-height: 160px; object-fit: cover;" />
                                    
                                    <button type="button" class="btn btn-icon position-absolute top-0 end-0 m-1 bg-white border text-danger rounded-circle shadow-sm" title="Hapus Foto" onclick="confirmDeleteFoto()" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10;">
                                        <i class="bx bx-trash fs-5"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $lembagaDesa->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif (Tampilkan di website)</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Perubahan
                    </button>
                </form>

                @if ($lembagaDesa->foto)
                    <form id="deleteFotoForm" action="{{ route('admin.lembaga-desa.delete-foto', $lembagaDesa->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
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
        return str.toLowerCase().replace(
            /(^|[^a-zA-Z0-9\u00C0-\u024F\u1E00-\u1EFF]+)([a-zA-Z\u00C0-\u024F\u1E00-\u1EFF])/gu,
            function(match, p1, p2) { return p1 + p2.toUpperCase(); }
        );
    }

    // ── Field: nama & jabatan → Capital Each Word (hanya saat blur) ─────────
    const capitalFields = document.querySelectorAll('#nama, #jabatan');
    capitalFields.forEach(function(input) {
        input.addEventListener('blur', function() {
            if (!this.value) return;
            this.value = toCapitalEachWord(this.value.trim().replace(/\s+/g, ' '));
        });
    });

    // ── Field: nip → digit only, no space ─────────────────────────────────
    function sanitizeNip(input) {
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

    const nipField = document.getElementById('nip');
    if (nipField) {
        nipField.addEventListener('keydown', function(e) {
            const ctrl = e.ctrlKey || e.metaKey;
            if (ctrl) return;
            const nav = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End','Enter'];
            if (nav.includes(e.key)) return;
            if (!/^[0-9]$/.test(e.key)) e.preventDefault();
        });

        nipField.addEventListener('paste', function(e) {
            e.preventDefault();
            let pasted = (e.clipboardData || window.clipboardData).getData('text');
            let digits = pasted.replace(/[^0-9]/g, '');
            let sel_start = this.selectionStart;
            let sel_end   = this.selectionEnd;
            let cur = this.value.replace(/[^0-9]/g, '');
            this.value = (cur.slice(0, sel_start) + digits + cur.slice(sel_end));
        });

        nipField.addEventListener('input', function() { sanitizeNip(this); });
        nipField.addEventListener('blur',  function() { sanitizeNip(this); });
    }
});

function confirmDeleteFoto() {
    Swal.fire({
        title: 'Hapus Foto Lembaga Desa?',
        text: 'Foto yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus Foto!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteFotoForm').submit();
        }
    });
}
</script>
@endpush
