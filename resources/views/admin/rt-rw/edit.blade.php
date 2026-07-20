@extends('admin.layouts.app')

@section('title', 'Edit RT / RW')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">RT / RW /</span> Edit RT / RW
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit RT / RW</h5>
                <a href="{{ route('admin.rt-rw.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.rt-rw.update', $rtRw->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="dusun_id">Pilih Dusun <span class="text-danger">*</span></label>
                        <select class="form-select" id="dusun_id" name="dusun_id" required>
                            @foreach($dusunList as $d)
                                <option value="{{ $d->id }}" {{ old('dusun_id', $rtRw->dusun_id) == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_rt">No. RT <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_rt" name="no_rt" value="{{ old('no_rt', $rtRw->no_rt) }}" inputmode="numeric" autocomplete="off" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_rw">No. RW <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_rw" name="no_rw" value="{{ old('no_rw', $rtRw->no_rw) }}" inputmode="numeric" autocomplete="off" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="ketua_rt">Nama Ketua RT (Opsional)</label>
                        <input type="text" class="form-control" id="ketua_rt" name="ketua_rt" value="{{ old('ketua_rt', $rtRw->ketua_rt) }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="ketua_rw">Nama Ketua RW (Opsional)</label>
                        <input type="text" class="form-control" id="ketua_rw" name="ketua_rw" value="{{ old('ketua_rw', $rtRw->ketua_rw) }}" />
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

    // ── Field: no_rt & no_rw → digit only, no space ────────────────────────
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

    const digitFields = document.querySelectorAll('#no_rt, #no_rw');
    digitFields.forEach(function(input) {
        if (!input) return;
        input.addEventListener('keydown', function(e) {
            const ctrl = e.ctrlKey || e.metaKey;
            if (ctrl) return;
            const nav = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End','Enter'];
            if (nav.includes(e.key)) return;
            if (!/^[0-9]$/.test(e.key)) e.preventDefault();
        });

        input.addEventListener('paste', function(e) {
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

        input.addEventListener('input', function() { sanitizeDigits(this); });
        input.addEventListener('blur',  function() { sanitizeDigits(this); });
    });

    const nameFields = document.querySelectorAll('#ketua_rt, #ketua_rw');
    nameFields.forEach(input => {
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
