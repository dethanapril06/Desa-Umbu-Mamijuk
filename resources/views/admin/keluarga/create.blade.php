@extends('admin.layouts.app')

@section('title', 'Tambah Kartu Keluarga')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Keluarga /</span> Tambah Kartu Keluarga
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah Kartu Keluarga (KK)</h5>
                <a href="{{ route('admin.keluarga.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.keluarga.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="no_kk">Nomor Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="no_kk" name="no_kk" value="{{ old('no_kk') }}" placeholder="16 digit nomor KK" maxlength="16" required />
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="dusun_id">Pilih Dusun <span class="text-danger">*</span></label>
                            <select class="form-select" id="dusun_id" name="dusun_id" required>
                                <option value="">-- Pilih Dusun --</option>
                                @foreach($dusunList as $d)
                                    <option value="{{ $d->id }}" {{ old('dusun_id') == $d->id ? 'selected' : '' }}>
                                        {{ $d->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="rt_rw_id">Pilih RT / RW <span class="text-danger">*</span></label>
                            <select class="form-select" id="rt_rw_id" name="rt_rw_id" required disabled>
                                <option value="">-- Pilih Dusun terlebih dahulu --</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="alamat">Alamat KK Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="4" placeholder="Tuliskan nama jalan, dukuh/dusun, gang, no rumah" required>{{ old('alamat') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="kode_pos">Kode Pos</label>
                            <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tanggal_terdaftar">Tanggal Terdaftar KK</label>
                            <input type="date" class="form-control" id="tanggal_terdaftar" name="tanggal_terdaftar" value="{{ old('tanggal_terdaftar', date('Y-m-d')) }}" />
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Kartu Keluarga
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

    const numberFields = document.querySelectorAll('#no_kk, #kode_pos');
    numberFields.forEach(input => {
        ['input', 'blur'].forEach(evt => {
            input.addEventListener(evt, function() {
                if (!this.value) return;
                this.value = this.value.replace(/\s+/g, '');
            });
        });
    });

    const alamatInput = document.getElementById('alamat');
    if (alamatInput) {
        alamatInput.addEventListener('blur', function() {
            if (!this.value) return;
            let lines = this.value.split(/\r?\n/);
            let cleanedLines = lines.map(line => line.trim().replace(/[^\S\r\n]+/g, ' '));
            let text = cleanedLines.join('\n').replace(/\n{3,}/g, '\n\n').trim();
            text = toCapitalEachWord(text);
            this.value = text;
        });
    }

    const rtRwData = @json($rtRwList->map(function($r) {
        return [
            'id' => $r->id,
            'dusun_id' => $r->dusun_id,
            'label' => 'RT ' . $r->no_rt . ' / RW ' . $r->no_rw
        ];
    }));
    const dusunSelect = document.getElementById('dusun_id');
    const rtRwSelect = document.getElementById('rt_rw_id');
    const oldRtRwId = "{{ old('rt_rw_id') }}";

    function filterRtRw() {
        const dusunId = dusunSelect.value;
        rtRwSelect.innerHTML = '';
        if (!dusunId) {
            rtRwSelect.innerHTML = '<option value="">-- Pilih Dusun terlebih dahulu --</option>';
            rtRwSelect.disabled = true;
            return;
        }

        const filtered = rtRwData.filter(item => String(item.dusun_id) === String(dusunId));
        if (filtered.length === 0) {
            rtRwSelect.innerHTML = '<option value="">-- Belum ada RT/RW di dusun ini --</option>';
            rtRwSelect.disabled = true;
            return;
        }

        rtRwSelect.disabled = false;
        rtRwSelect.innerHTML = '<option value="">-- Pilih RT / RW --</option>';
        filtered.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.label;
            if (String(item.id) === String(oldRtRwId)) {
                option.selected = true;
            }
            rtRwSelect.appendChild(option);
        });
    }

    if (dusunSelect && rtRwSelect) {
        dusunSelect.addEventListener('change', filterRtRw);
        filterRtRw();
    }
});
</script>
@endpush
