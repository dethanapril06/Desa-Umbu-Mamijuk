@extends('admin.layouts.app')

@section('title', 'Catat Mutasi Penduduk')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Mutasi /</span> Catat Mutasi Penduduk
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Catat Mutasi Penduduk</h5>
                <a href="{{ route('admin.mutasi-penduduk.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.mutasi-penduduk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="penduduk_id">Pilih Penduduk <span class="text-danger">*</span></label>
                        <select class="form-select" id="penduduk_id" name="penduduk_id" required>
                            <option value="">-- Pilih Penduduk Aktif --</option>
                            @foreach($pendudukList as $p)
                                <option value="{{ $p->id }}" {{ old('penduduk_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }} (NIK: {{ $p->nik }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Hanya penduduk dengan status AKTIF yang dapat dimutasi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="jenis_mutasi">Jenis Mutasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis_mutasi" name="jenis_mutasi" required onchange="toggleAlamatField(this.value)">
                            <option value="">-- Pilih Jenis Mutasi --</option>
                            <option value="lahir" {{ old('jenis_mutasi') == 'lahir' ? 'selected' : '' }}>Lahir</option>
                            <option value="mati" {{ old('jenis_mutasi') == 'mati' ? 'selected' : '' }}>Meninggal (Mati)</option>
                            <option value="pindah_masuk" {{ old('jenis_mutasi') == 'pindah_masuk' ? 'selected' : '' }}>Pindah Masuk</option>
                            <option value="pindah_keluar" {{ old('jenis_mutasi') == 'pindah_keluar' ? 'selected' : '' }}>Pindah Keluar</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tanggal_mutasi">Tanggal Mutasi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_mutasi" name="tanggal_mutasi" value="{{ old('tanggal_mutasi', date('Y-m-d')) }}" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_surat">No. Surat Keterangan / Pengantar</label>
                            <input type="text" class="form-control" id="no_surat" name="no_surat" value="{{ old('no_surat') }}" placeholder="Contoh: 470/12/VI/2026" />
                        </div>
                    </div>

                    <div class="mb-3" id="alamat_asal_wrapper" style="display: none;">
                        <label class="form-label" for="alamat_asal">Alamat Asal</label>
                        <textarea class="form-control" id="alamat_asal" name="alamat_asal" rows="3" placeholder="Tuliskan alamat daerah asal penduduk sebelum masuk ke desa ini">{{ old('alamat_asal') }}</textarea>
                    </div>

                    <div class="mb-3" id="alamat_tujuan_wrapper" style="display: none;">
                        <label class="form-label" for="alamat_tujuan">Alamat Tujuan Pindah</label>
                        <textarea class="form-control" id="alamat_tujuan" name="alamat_tujuan" rows="3" placeholder="Tuliskan alamat daerah tujuan pindah keluar">{{ old('alamat_tujuan') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="lampiran">Lampiran Dokumen Pendukung (Opsional)</label>
                        <input type="file" class="form-control" id="lampiran" name="lampiran" accept=".pdf,image/*" />
                        <div class="form-text">Format: pdf, jpeg, png, jpg. Maksimal 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="keterangan">Keterangan Tambahan / Alasan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Tuliskan keterangan tambahan atau alasan mutasi...">{{ old('keterangan') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Catatan Mutasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAlamatField(val) {
    var asal = document.getElementById('alamat_asal_wrapper');
    var tujuan = document.getElementById('alamat_tujuan_wrapper');
    
    asal.style.display = 'none';
    tujuan.style.display = 'none';

    if (val === 'pindah_masuk') {
        asal.style.display = 'block';
    } else if (val === 'pindah_keluar') {
        tujuan.style.display = 'block';
    }
}

// Trigger initial state
document.addEventListener('DOMContentLoaded', function() {
    toggleAlamatField(document.getElementById('jenis_mutasi').value);
});
</script>
@endsection
