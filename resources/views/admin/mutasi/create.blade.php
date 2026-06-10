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
                        <label class="form-label" for="jenis_mutasi">Jenis Mutasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis_mutasi" name="jenis_mutasi" required onchange="toggleAlamatField(this.value)">
                            <option value="">-- Pilih Jenis Mutasi --</option>
                            <option value="lahir" {{ old('jenis_mutasi') == 'lahir' ? 'selected' : '' }}>Lahir</option>
                            <option value="mati" {{ old('jenis_mutasi') == 'mati' ? 'selected' : '' }}>Meninggal (Mati)</option>
                            <option value="pindah_masuk" {{ old('jenis_mutasi') == 'pindah_masuk' ? 'selected' : '' }}>Pindah Masuk</option>
                            <option value="pindah_keluar" {{ old('jenis_mutasi') == 'pindah_keluar' ? 'selected' : '' }}>Pindah Keluar</option>
                        </select>
                    </div>

                    <div class="mb-3" id="penduduk_id_wrapper">
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

                    <!-- Data Bayi Baru (Khusus Lahir) -->
                    <div id="data_bayi_wrapper" style="display: none;">
                        <hr class="my-4">
                        <h5 class="mb-3 text-primary"><i class="bx bx-baby me-1"></i> Data Bayi Baru</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_nama_lengkap">Nama Lengkap Bayi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="baby_nama_lengkap" name="baby_nama_lengkap" value="{{ old('baby_nama_lengkap') }}" placeholder="Masukkan nama lengkap bayi" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_nik">NIK Bayi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="baby_nik" name="baby_nik" value="{{ old('baby_nik') }}" placeholder="16 digit NIK bayi" maxlength="16" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_tempat_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control" id="baby_tempat_lahir" name="baby_tempat_lahir" value="{{ old('baby_tempat_lahir') }}" placeholder="Contoh: Sumba Tengah" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="baby_tanggal_lahir" name="baby_tanggal_lahir" value="{{ old('baby_tanggal_lahir', date('Y-m-d')) }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select" id="baby_jenis_kelamin" name="baby_jenis_kelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="laki-laki" {{ old('baby_jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="perempuan" {{ old('baby_jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_keluarga_id">Hubungkan ke Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                                <select class="form-select" id="baby_keluarga_id" name="baby_keluarga_id">
                                    <option value="">-- Pilih Kartu Keluarga --</option>
                                    @foreach($keluargaList as $k)
                                        <option value="{{ $k->id }}" 
                                            data-ayah="{{ $k->nama_ayah }}"
                                            data-ibu="{{ $k->nama_ibu }}"
                                            {{ old('baby_keluarga_id') == $k->id ? 'selected' : '' }}>
                                            KK: {{ $k->no_kk }} - Kepala: {{ $k->kepalaKeluarga ? $k->kepalaKeluarga->nama_lengkap : '–' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_nama_ayah">Nama Ayah</label>
                                <input type="text" class="form-control" id="baby_nama_ayah" name="baby_nama_ayah" value="{{ old('baby_nama_ayah') }}" placeholder="Nama Lengkap Ayah" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_nama_ibu">Nama Ibu</label>
                                <input type="text" class="form-control" id="baby_nama_ibu" name="baby_nama_ibu" value="{{ old('baby_nama_ibu') }}" placeholder="Nama Lengkap Ibu" />
                            </div>
                        </div>
                        <hr class="my-4">
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
    var pendudukWrapper = document.getElementById('penduduk_id_wrapper');
    var babyWrapper = document.getElementById('data_bayi_wrapper');
    
    // Reset display
    asal.style.display = 'none';
    tujuan.style.display = 'none';

    if (val === 'pindah_masuk') {
        asal.style.display = 'block';
    } else if (val === 'pindah_keluar') {
        tujuan.style.display = 'block';
    }

    var selectPenduduk = document.getElementById('penduduk_id');
    var babyNama = document.getElementById('baby_nama_lengkap');
    var babyNik = document.getElementById('baby_nik');
    var babyTglLahir = document.getElementById('baby_tanggal_lahir');
    var babyJk = document.getElementById('baby_jenis_kelamin');
    var babyKeluarga = document.getElementById('baby_keluarga_id');
    var babyTempatLahir = document.getElementById('baby_tempat_lahir');
    var babyAyah = document.getElementById('baby_nama_ayah');
    var babyIbu = document.getElementById('baby_nama_ibu');

    if (val === 'lahir') {
        babyWrapper.style.display = 'block';
        pendudukWrapper.style.display = 'none';

        // Enable & require baby fields
        babyNama.disabled = false;
        babyNama.required = true;
        babyNik.disabled = false;
        babyNik.required = true;
        babyTglLahir.disabled = false;
        babyTglLahir.required = true;
        babyJk.disabled = false;
        babyJk.required = true;
        babyKeluarga.disabled = false;
        babyKeluarga.required = true;
        babyTempatLahir.disabled = false;
        babyAyah.disabled = false;
        babyIbu.disabled = false;

        // Disable resident select
        selectPenduduk.disabled = true;
        selectPenduduk.required = false;
    } else {
        babyWrapper.style.display = 'none';
        pendudukWrapper.style.display = 'block';

        // Disable & unrequire baby fields
        babyNama.disabled = true;
        babyNama.required = false;
        babyNik.disabled = true;
        babyNik.required = false;
        babyTglLahir.disabled = true;
        babyTglLahir.required = false;
        babyJk.disabled = true;
        babyJk.required = false;
        babyKeluarga.disabled = true;
        babyKeluarga.required = false;
        babyTempatLahir.disabled = true;
        babyAyah.disabled = true;
        babyIbu.disabled = true;

        // Enable resident select
        selectPenduduk.disabled = false;
        selectPenduduk.required = true;
    }
}

// Trigger initial state
document.addEventListener('DOMContentLoaded', function() {
    toggleAlamatField(document.getElementById('jenis_mutasi').value);
    
    // Auto fill father and mother name on KK selection
    var babyKeluargaSelect = document.getElementById('baby_keluarga_id');
    if (babyKeluargaSelect) {
        babyKeluargaSelect.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var ayah = selectedOption.getAttribute('data-ayah') || '';
            var ibu = selectedOption.getAttribute('data-ibu') || '';
            
            document.getElementById('baby_nama_ayah').value = ayah;
            document.getElementById('baby_nama_ibu').value = ibu;
        });
    }
});
</script>
@endsection
