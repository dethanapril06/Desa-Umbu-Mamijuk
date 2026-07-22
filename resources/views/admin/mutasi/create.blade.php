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

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="filter_dusun_id">Dusun <span class="text-danger">*</span></label>
                            <select class="form-select" id="filter_dusun_id" name="filter_dusun_id">
                                <option value="">-- Pilih Dusun --</option>
                                @foreach($dusunList as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="filter_rt_rw_id">RT / RW <span class="text-danger">*</span></label>
                            <select class="form-select" id="filter_rt_rw_id" name="filter_rt_rw_id" disabled>
                                <option value="">-- Pilih Dusun terlebih dahulu --</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3" id="penduduk_id_wrapper">
                            <label class="form-label" for="penduduk_id">Pilih Penduduk <span class="text-danger">*</span></label>
                            <select class="form-select" id="penduduk_id" name="penduduk_id" disabled>
                                <option value="">-- Pilih RT/RW terlebih dahulu --</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="jenis_mutasi">Jenis Mutasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis_mutasi" name="jenis_mutasi" onchange="toggleAlamatField(this.value)">
                            <option value="">-- Pilih Jenis Mutasi --</option>
                            <option value="mati" {{ old('jenis_mutasi') == 'mati' ? 'selected' : '' }}>Meninggal (Mati)</option>
                            <option value="pindah_masuk" {{ old('jenis_mutasi') == 'pindah_masuk' ? 'selected' : '' }}>Pindah Masuk</option>
                            <option value="pindah_keluar" {{ old('jenis_mutasi') == 'pindah_keluar' ? 'selected' : '' }}>Pindah Keluar</option>
                        </select>
                    </div>



                    <!-- Data Penduduk Masuk Baru (Khusus Pindah Masuk) -->
                    <div id="data_masuk_wrapper" style="display: none;">
                        <hr class="my-4">
                        <h5 class="mb-3 text-primary"><i class="bx bx-user-plus me-1"></i> Data Penduduk Pindah Masuk</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_keluarga_id">Hubungkan ke Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_keluarga_id" name="masuk_keluarga_id" disabled>
                                    <option value="">-- Pilih Dusun/RT-RW terlebih dahulu --</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_nik">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="masuk_nik" name="masuk_nik" value="{{ old('masuk_nik') }}" placeholder="16 digit NIK" maxlength="16" inputmode="numeric" autocomplete="off" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="masuk_nama_lengkap" name="masuk_nama_lengkap" value="{{ old('masuk_nama_lengkap') }}" placeholder="Nama Lengkap Penduduk" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_no_telepon">No. Telepon / HP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="masuk_no_telepon" name="masuk_no_telepon" value="{{ old('masuk_no_telepon') }}" placeholder="Contoh: 08xxxxxxx" inputmode="numeric" autocomplete="off" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="masuk_tempat_lahir" name="masuk_tempat_lahir" value="{{ old('masuk_tempat_lahir') }}" placeholder="Tempat Lahir" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="masuk_tanggal_lahir" name="masuk_tanggal_lahir" value="{{ old('masuk_tanggal_lahir') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_jenis_kelamin" name="masuk_jenis_kelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="laki-laki" {{ old('masuk_jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="perempuan" {{ old('masuk_jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_status_hubungan_keluarga">Hubungan Keluarga <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_status_hubungan_keluarga" name="masuk_status_hubungan_keluarga">
                                    <option value="">-- Hubungan Keluarga --</option>
                                    <option value="kepala_keluarga" {{ old('masuk_status_hubungan_keluarga') == 'kepala_keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                    <option value="istri" {{ old('masuk_status_hubungan_keluarga') == 'istri' ? 'selected' : '' }}>Istri</option>
                                    <option value="anak" {{ old('masuk_status_hubungan_keluarga') == 'anak' ? 'selected' : '' }}>Anak</option>
                                    <option value="menantu" {{ old('masuk_status_hubungan_keluarga') == 'menantu' ? 'selected' : '' }}>Menantu</option>
                                    <option value="cucu" {{ old('masuk_status_hubungan_keluarga') == 'cucu' ? 'selected' : '' }}>Cucu</option>
                                    <option value="orang_tua" {{ old('masuk_status_hubungan_keluarga') == 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                                    <option value="mertua" {{ old('masuk_status_hubungan_keluarga') == 'mertua' ? 'selected' : '' }}>Mertua</option>
                                    <option value="famili_lain" {{ old('masuk_status_hubungan_keluarga') == 'famili_lain' ? 'selected' : '' }}>Famili Lain</option>
                                    <option value="lainnya" {{ old('masuk_status_hubungan_keluarga') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_agama">Agama <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_agama" name="masuk_agama">
                                    <option value="islam" {{ old('masuk_agama') == 'islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="kristen" {{ old('masuk_agama') == 'kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="katolik" {{ old('masuk_agama') == 'katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="hindu" {{ old('masuk_agama') == 'hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="buddha" {{ old('masuk_agama') == 'buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="konghucu" {{ old('masuk_agama') == 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    <option value="lainnya" {{ old('masuk_agama') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_pendidikan_terakhir">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_pendidikan_terakhir" name="masuk_pendidikan_terakhir">
                                    <option value="tidak_sekolah" {{ old('masuk_pendidikan_terakhir') == 'tidak_sekolah' ? 'selected' : '' }}>Tidak / Belum Sekolah</option>
                                    <option value="sd" {{ old('masuk_pendidikan_terakhir') == 'sd' ? 'selected' : '' }}>SD / Sederajat</option>
                                    <option value="smp" {{ old('masuk_pendidikan_terakhir') == 'smp' ? 'selected' : '' }}>SMP / Sederajat</option>
                                    <option value="sma" {{ old('masuk_pendidikan_terakhir') == 'sma' ? 'selected' : '' }}>SMA / Sederajat</option>
                                    <option value="d1" {{ old('masuk_pendidikan_terakhir') == 'd1' ? 'selected' : '' }}>D1</option>
                                    <option value="d2" {{ old('masuk_pendidikan_terakhir') == 'd2' ? 'selected' : '' }}>D2</option>
                                    <option value="d3" {{ old('masuk_pendidikan_terakhir') == 'd3' ? 'selected' : '' }}>D3</option>
                                    <option value="s1" {{ old('masuk_pendidikan_terakhir') == 's1' ? 'selected' : '' }}>S1 / Diploma IV</option>
                                    <option value="s2" {{ old('masuk_pendidikan_terakhir') == 's2' ? 'selected' : '' }}>S2</option>
                                    <option value="s3" {{ old('masuk_pendidikan_terakhir') == 's3' ? 'selected' : '' }}>S3</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_pekerjaan">Pekerjaan <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_pekerjaan" name="masuk_pekerjaan">
                                    @foreach($pekerjaanList as $job)
                                        <option value="{{ $job }}" {{ old('masuk_pekerjaan', 'Belum / Tidak Bekerja') == $job ? 'selected' : '' }}>{{ $job }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_status_perkawinan">Status Perkawinan <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_status_perkawinan" name="masuk_status_perkawinan">
                                    <option value="belum_kawin" {{ old('masuk_status_perkawinan') == 'belum_kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                    <option value="kawin" {{ old('masuk_status_perkawinan') == 'kawin' ? 'selected' : '' }}>Kawin</option>
                                    <option value="cerai_hidup" {{ old('masuk_status_perkawinan') == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                    <option value="cerai_mati" {{ old('masuk_status_perkawinan') == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_kewarganegaraan">Kewarganegaraan <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_kewarganegaraan" name="masuk_kewarganegaraan">
                                    <option value="WNI" {{ old('masuk_kewarganegaraan', 'WNI') == 'WNI' ? 'selected' : '' }}>WNI</option>
                                    <option value="WNA" {{ old('masuk_kewarganegaraan') == 'WNA' ? 'selected' : '' }}>WNA</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_golongan_darah">Golongan Darah <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_golongan_darah" name="masuk_golongan_darah">
                                    <option value="">-- Pilih Golongan Darah --</option>
                                    <option value="A" {{ old('masuk_golongan_darah') == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('masuk_golongan_darah') == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('masuk_golongan_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('masuk_golongan_darah') == 'O' ? 'selected' : '' }}>O</option>
                                    <option value="-" {{ old('masuk_golongan_darah') == '-' ? 'selected' : '' }}>Tidak Tahu / -</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_no_paspor">No. Paspor</label>
                                <input type="text" class="form-control" id="masuk_no_paspor" name="masuk_no_paspor" value="{{ old('masuk_no_paspor') }}" placeholder="No. Paspor (jika ada)" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_no_kitas_kitap">No. KITAS / KITAP</label>
                                <input type="text" class="form-control" id="masuk_no_kitas_kitap" name="masuk_no_kitas_kitap" value="{{ old('masuk_no_kitas_kitap') }}" placeholder="No. KITAS/KITAP (jika ada)" inputmode="numeric" autocomplete="off" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_nama_ayah">Nama Lengkap Ayah <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="masuk_nama_ayah" name="masuk_nama_ayah" value="{{ old('masuk_nama_ayah') }}" placeholder="Nama Ayah" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_nama_ibu">Nama Lengkap Ibu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="masuk_nama_ibu" name="masuk_nama_ibu" value="{{ old('masuk_nama_ibu') }}" placeholder="Nama Ibu" />
                            </div>
                        </div>

                        <div class="row border-top pt-3 mt-3">
                            <div class="col-md-4 mb-3 d-flex flex-column justify-content-center">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="masuk_is_asuransi_kesehatan" id="masuk_is_asuransi_kesehatan" value="1" {{ old('masuk_is_asuransi_kesehatan') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="masuk_is_asuransi_kesehatan">Memiliki Asuransi Kesehatan</label>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="masuk_is_disabilitas" id="masuk_is_disabilitas" value="1" {{ old('masuk_is_disabilitas') ? 'checked' : '' }} onchange="toggleMasukDisabilitas(this)">
                                    <label class="form-check-label fw-bold" for="masuk_is_disabilitas">Penyandang Disabilitas</label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3" id="masuk_jenis_disabilitas_wrapper" style="{{ old('masuk_is_disabilitas') ? '' : 'display: none;' }}">
                                <label class="form-label" for="masuk_jenis_disabilitas">Jenis Disabilitas</label>
                                <input type="text" class="form-control" id="masuk_jenis_disabilitas" name="masuk_jenis_disabilitas" value="{{ old('masuk_jenis_disabilitas') }}" placeholder="Tuna Netra, dll" />
                            </div>
                        </div>
                        <hr class="my-4">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tanggal_mutasi">Tanggal Mutasi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_mutasi" name="tanggal_mutasi" value="{{ old('tanggal_mutasi', date('Y-m-d')) }}" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_surat">No. Surat Keterangan / Pengantar <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_surat" name="no_surat" value="{{ old('no_surat') }}" placeholder="Contoh: 470/12/VI/2026" />
                        </div>
                    </div>

                    <div class="mb-3" id="alamat_asal_wrapper" style="display: none;">
                        <label class="form-label" for="alamat_asal">Alamat Asal <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat_asal" name="alamat_asal" rows="3" placeholder="Tuliskan alamat daerah asal penduduk sebelum masuk ke desa ini">{{ old('alamat_asal') }}</textarea>
                    </div>

                    <div class="mb-3" id="alamat_tujuan_wrapper" style="display: none;">
                        <label class="form-label" for="alamat_tujuan">Alamat Tujuan Pindah <span class="text-danger">*</span></label>
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
function toggleMasukDisabilitas(checkbox) {
    var wrapper = document.getElementById('masuk_jenis_disabilitas_wrapper');
    if (checkbox.checked) {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';
        document.getElementById('masuk_jenis_disabilitas').value = '';
    }
}

function toggleAlamatField(val) {
    var asal = document.getElementById('alamat_asal_wrapper');
    var tujuan = document.getElementById('alamat_tujuan_wrapper');
    var pendudukWrapper = document.getElementById('penduduk_id_wrapper');
    var masukWrapper = document.getElementById('data_masuk_wrapper');
    
    // Reset display
    asal.style.display = 'none';
    tujuan.style.display = 'none';
    masukWrapper.style.display = 'none';
    pendudukWrapper.style.display = 'block';

    if (val === 'pindah_masuk') {
        asal.style.display = 'block';
        masukWrapper.style.display = 'block';
        pendudukWrapper.style.display = 'none';
    } else if (val === 'pindah_keluar') {
        tujuan.style.display = 'block';
    }

    var selectPenduduk = document.getElementById('penduduk_id');

    // Inbound migration fields
    var masukKeluarga = document.getElementById('masuk_keluarga_id');
    var masukNik = document.getElementById('masuk_nik');
    var masukNama = document.getElementById('masuk_nama_lengkap');
    var masukTglLahir = document.getElementById('masuk_tanggal_lahir');
    var masukJk = document.getElementById('masuk_jenis_kelamin');
    var masukHubungan = document.getElementById('masuk_status_hubungan_keluarga');
    var masukKewarganegaraan = document.getElementById('masuk_kewarganegaraan');
    var masukTelp = document.getElementById('masuk_no_telepon');
    var masukTempatLahir = document.getElementById('masuk_tempat_lahir');
    var masukAgama = document.getElementById('masuk_agama');
    var masukPendidikan = document.getElementById('masuk_pendidikan_terakhir');
    var masukPekerjaan = document.getElementById('masuk_pekerjaan');
    var masukStatusKawin = document.getElementById('masuk_status_perkawinan');
    var masukGolDarah = document.getElementById('masuk_golongan_darah');
    var masukPaspor = document.getElementById('masuk_no_paspor');
    var masukKitas = document.getElementById('masuk_no_kitas_kitap');
    var masukAyah = document.getElementById('masuk_nama_ayah');
    var masukIbu = document.getElementById('masuk_nama_ibu');
    var masukIsAsuransi = document.getElementById('masuk_is_asuransi_kesehatan');
    var masukIsDisabilitas = document.getElementById('masuk_is_disabilitas');
    var masukJenisDisabilitas = document.getElementById('masuk_jenis_disabilitas');

    if (val === 'pindah_masuk') {
        // Disable resident select
        selectPenduduk.disabled = true;
        selectPenduduk.required = false;

        // Enable & require masuk fields
        masukKeluarga.disabled = false;
        masukKeluarga.required = true;
        masukNik.disabled = false;
        masukNik.required = true;
        masukNama.disabled = false;
        masukNama.required = true;
        masukTglLahir.disabled = false;
        masukTglLahir.required = true;
        masukJk.disabled = false;
        masukJk.required = true;
        masukHubungan.disabled = false;
        masukHubungan.required = true;
        masukKewarganegaraan.disabled = false;
        masukKewarganegaraan.required = true;
        masukTelp.disabled = false;
        masukTempatLahir.disabled = false;
        masukAgama.disabled = false;
        masukPendidikan.disabled = false;
        masukPekerjaan.disabled = false;
        masukStatusKawin.disabled = false;
        masukGolDarah.disabled = false;
        masukPaspor.disabled = false;
        masukKitas.disabled = false;
        masukAyah.disabled = false;
        masukIbu.disabled = false;
        masukIsAsuransi.disabled = false;
        masukIsDisabilitas.disabled = false;
        masukJenisDisabilitas.disabled = false;
    } else {
        // Disable masuk fields
        masukKeluarga.disabled = true;
        masukKeluarga.required = false;
        masukNik.disabled = true;
        masukNik.required = false;
        masukNama.disabled = true;
        masukNama.required = false;
        masukTglLahir.disabled = true;
        masukTglLahir.required = false;
        masukJk.disabled = true;
        masukJk.required = false;
        masukHubungan.disabled = true;
        masukHubungan.required = false;
        masukKewarganegaraan.disabled = true;
        masukKewarganegaraan.required = false;
        masukTelp.disabled = true;
        masukTempatLahir.disabled = true;
        masukAgama.disabled = true;
        masukPendidikan.disabled = true;
        masukPekerjaan.disabled = true;
        masukStatusKawin.disabled = true;
        masukGolDarah.disabled = true;
        masukPaspor.disabled = true;
        masukKitas.disabled = true;
        masukAyah.disabled = true;
        masukIbu.disabled = true;
        masukIsAsuransi.disabled = true;
        masukIsDisabilitas.disabled = true;
        masukJenisDisabilitas.disabled = true;

        // Enable resident select
        selectPenduduk.required = true;
    }

    const filterD = document.getElementById('filter_dusun_id');
    const filterR = document.getElementById('filter_rt_rw_id');
    if (filterD && typeof updateTargetOptions === 'function') {
        updateTargetOptions(filterD.value, filterR.value);
    }
}

// Trigger initial state
@php
    $rtRwArray = $rtRwList->map(function($r) {
        return [
            'id' => $r->id,
            'dusun_id' => $r->dusun_id,
            'label' => 'RT ' . $r->no_rt . ' / RW ' . $r->no_rw
        ];
    })->values();

    $pendudukArray = $pendudukList->map(function($p) {
        return [
            'id' => $p->id,
            'nik' => $p->nik,
            'nama_lengkap' => $p->nama_lengkap,
            'dusun_id' => $p->keluarga && $p->keluarga->rtRw ? $p->keluarga->rtRw->dusun_id : null,
            'rt_rw_id' => $p->keluarga ? $p->keluarga->rt_rw_id : null,
            'label' => $p->nama_lengkap . ' (NIK: ' . $p->nik . ')'
        ];
    })->values();

    $keluargaArray = $keluargaList->map(function($k) {
        $namaKepala = $k->kepalaKeluarga ? strtoupper($k->kepalaKeluarga->nama_lengkap) : 'Belum ada Kepala Keluarga';
        return [
            'id' => $k->id,
            'no_kk' => $k->no_kk,
            'dusun_id' => $k->rtRw ? $k->rtRw->dusun_id : null,
            'rt_rw_id' => $k->rt_rw_id,
            'label' => $k->no_kk . ' - Kepala Keluarga: ' . $namaKepala
        ];
    })->values();
@endphp

const rtRwData = @json($rtRwArray);
const pendudukData = @json($pendudukArray);
const keluargaData = @json($keluargaArray);

const filterDusun = document.getElementById('filter_dusun_id');
const filterRtRw = document.getElementById('filter_rt_rw_id');
const pendudukSelect = document.getElementById('penduduk_id');
const keluargaSelect = document.getElementById('masuk_keluarga_id');

let initialPendudukId = "{{ old('penduduk_id', '') }}";
let initialKeluargaId = "{{ old('masuk_keluarga_id', '') }}";
let initialRtRwId = "";
let initialDusunId = "";

const jMutasi = document.getElementById('jenis_mutasi');
if (initialPendudukId && jMutasi && jMutasi.value !== 'pindah_masuk') {
    const foundP = pendudukData.find(p => String(p.id) === String(initialPendudukId));
    if (foundP) {
        initialRtRwId = foundP.rt_rw_id;
        initialDusunId = foundP.dusun_id;
    }
} else if (initialKeluargaId && jMutasi && jMutasi.value === 'pindah_masuk') {
    const foundK = keluargaData.find(k => String(k.id) === String(initialKeluargaId));
    if (foundK) {
        initialRtRwId = foundK.rt_rw_id;
        initialDusunId = foundK.dusun_id;
    }
}

function updateRtRwOptions(dusunId, selectedRtRwId = '') {
    filterRtRw.innerHTML = '';
    if (!dusunId) {
        filterRtRw.innerHTML = '<option value="">-- Pilih Dusun terlebih dahulu --</option>';
        filterRtRw.disabled = true;
        return;
    }

    const filtered = rtRwData.filter(item => String(item.dusun_id) === String(dusunId));
    if (filtered.length === 0) {
        filterRtRw.innerHTML = '<option value="">-- Belum ada RT/RW di dusun ini --</option>';
        filterRtRw.disabled = true;
        return;
    }

    filterRtRw.disabled = false;
    filterRtRw.innerHTML = '<option value="">-- Semua RT / RW --</option>';
    filtered.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = item.label;
        if (String(item.id) === String(selectedRtRwId)) {
            opt.selected = true;
        }
        filterRtRw.appendChild(opt);
    });
}

function updateTargetOptions(dusunId, rtRwId) {
    const isMasuk = document.getElementById('jenis_mutasi').value === 'pindah_masuk';
    const currentPendudukId = pendudukSelect ? (pendudukSelect.value || initialPendudukId) : initialPendudukId;
    const currentKeluargaId = keluargaSelect ? (keluargaSelect.value || initialKeluargaId) : initialKeluargaId;

    if (pendudukSelect) {
        pendudukSelect.innerHTML = '';
        if (!dusunId && !rtRwId) {
            pendudukSelect.innerHTML = '<option value="">-- Pilih Dusun terlebih dahulu --</option>';
            pendudukSelect.disabled = true;
        } else {
            let filtered = [];
            if (rtRwId) {
                filtered = pendudukData.filter(item => String(item.rt_rw_id) === String(rtRwId));
            } else if (dusunId) {
                filtered = pendudukData.filter(item => String(item.dusun_id) === String(dusunId));
            }

            if (filtered.length === 0) {
                pendudukSelect.innerHTML = '<option value="">-- Tidak ada penduduk aktif di wilayah ini --</option>';
                pendudukSelect.disabled = true;
            } else {
                pendudukSelect.disabled = isMasuk;
                pendudukSelect.innerHTML = '<option value="">-- Pilih Penduduk Aktif --</option>';
                filtered.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.label;
                    if (String(item.id) === String(currentPendudukId)) {
                        opt.selected = true;
                    }
                    pendudukSelect.appendChild(opt);
                });
            }
        }
    }

    if (keluargaSelect) {
        keluargaSelect.innerHTML = '';
        if (!dusunId && !rtRwId) {
            keluargaSelect.innerHTML = '<option value="">-- Pilih Dusun terlebih dahulu --</option>';
            keluargaSelect.disabled = true;
        } else {
            let filtered = [];
            if (rtRwId) {
                filtered = keluargaData.filter(item => String(item.rt_rw_id) === String(rtRwId));
            } else if (dusunId) {
                filtered = keluargaData.filter(item => String(item.dusun_id) === String(dusunId));
            }

            if (filtered.length === 0) {
                keluargaSelect.innerHTML = '<option value="">-- Tidak ada KK di wilayah ini --</option>';
                keluargaSelect.disabled = true;
            } else {
                keluargaSelect.disabled = !isMasuk;
                keluargaSelect.innerHTML = '<option value="">-- Pilih Kartu Keluarga --</option>';
                filtered.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.label;
                    if (String(item.id) === String(currentKeluargaId)) {
                        opt.selected = true;
                    }
                    keluargaSelect.appendChild(opt);
                });
            }
        }
    }
    initialPendudukId = '';
    initialKeluargaId = '';
}

if (filterDusun && filterRtRw) {
    filterDusun.addEventListener('change', function() {
        updateRtRwOptions(this.value, '');
        updateTargetOptions(this.value, '');
    });

    filterRtRw.addEventListener('change', function() {
        updateTargetOptions(filterDusun.value, this.value);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    function toCapitalEachWord(str) {
        return str.toLowerCase().replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    }

    // ── Field: masuk_nik & masuk_no_telepon → digit only, no space ─────────
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

    const digitFields = document.querySelectorAll('#masuk_nik, #masuk_no_telepon, #masuk_no_kitas_kitap');
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

    const pasporFields = document.querySelectorAll('#masuk_no_paspor');
    pasporFields.forEach(input => {
        ['input', 'blur'].forEach(evt => {
            input.addEventListener(evt, function() {
                if (!this.value) return;
                this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
            });
        });
    });

    const titleFields = document.querySelectorAll('#masuk_nama_lengkap, #masuk_tempat_lahir, #masuk_nama_ayah, #masuk_nama_ibu, #masuk_jenis_disabilitas');
    titleFields.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value) return;
            let cleaned = this.value.replace(/\s+/g, ' ').trim();
            this.value = toCapitalEachWord(cleaned);
        });
    });

    const textFields = document.querySelectorAll('#keterangan, #alamat_tujuan, #alamat_asal');
    textFields.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value) return;
            let lines = this.value.split(/\r?\n/);
            let cleanedLines = lines.map(line => line.trim().replace(/[^\S\r\n]+/g, ' '));
            this.value = cleanedLines.join('\n').replace(/\n{3,}/g, '\n\n').trim();
        });
    });

    const upperFields = document.querySelectorAll('#no_surat');
    upperFields.forEach(input => {
        ['input', 'blur'].forEach(evt => {
            input.addEventListener(evt, function() {
                if (!this.value) return;
                let cleaned = this.value.replace(/\s+/g, ' ');
                if (evt === 'blur') cleaned = cleaned.trim();
                this.value = cleaned.toUpperCase();
            });
        });
    });

    if (initialDusunId) {
        filterDusun.value = initialDusunId;
    }
    updateRtRwOptions(filterDusun.value, initialRtRwId);
    toggleAlamatField(document.getElementById('jenis_mutasi').value);
});
</script>
@endsection
