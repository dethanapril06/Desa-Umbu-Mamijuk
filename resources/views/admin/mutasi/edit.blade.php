@extends('admin.layouts.app')

@section('title', 'Edit Catatan Mutasi')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Mutasi /</span> Edit Catatan Mutasi
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Edit Catatan Mutasi Penduduk</h5>
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

                <form action="{{ route('admin.mutasi-penduduk.update', $mutasiPenduduk->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="jenis_mutasi">Jenis Mutasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis_mutasi" name="jenis_mutasi" required onchange="toggleAlamatField(this.value)">
                            <option value="lahir" {{ old('jenis_mutasi', $mutasiPenduduk->jenis_mutasi) == 'lahir' ? 'selected' : '' }}>Lahir</option>
                            <option value="mati" {{ old('jenis_mutasi', $mutasiPenduduk->jenis_mutasi) == 'mati' ? 'selected' : '' }}>Meninggal (Mati)</option>
                            <option value="pindah_masuk" {{ old('jenis_mutasi', $mutasiPenduduk->jenis_mutasi) == 'pindah_masuk' ? 'selected' : '' }}>Pindah Masuk</option>
                            <option value="pindah_keluar" {{ old('jenis_mutasi', $mutasiPenduduk->jenis_mutasi) == 'pindah_keluar' ? 'selected' : '' }}>Pindah Keluar</option>
                        </select>
                    </div>

                    <div class="mb-3" id="penduduk_id_wrapper">
                        <label class="form-label" for="penduduk_id">Pilih Penduduk <span class="text-danger">*</span></label>
                        <select class="form-select" id="penduduk_id" name="penduduk_id" required>
                            @foreach($pendudukList as $p)
                                <option value="{{ $p->id }}" {{ old('penduduk_id', $mutasiPenduduk->penduduk_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }} (NIK: {{ $p->nik }}) - status: [{{ $p->status }}]
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Data Bayi Baru (Khusus Lahir) -->
                    <div id="data_bayi_wrapper" style="display: none;">
                        <hr class="my-4">
                        <h5 class="mb-3 text-primary"><i class="bx bx-baby me-1"></i> Data Bayi</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_nama_lengkap">Nama Lengkap Bayi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="baby_nama_lengkap" name="baby_nama_lengkap" value="{{ old('baby_nama_lengkap', optional($mutasiPenduduk->penduduk)->nama_lengkap) }}" placeholder="Masukkan nama lengkap bayi" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_nik">NIK Bayi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="baby_nik" name="baby_nik" value="{{ old('baby_nik', optional($mutasiPenduduk->penduduk)->nik) }}" placeholder="16 digit NIK bayi" maxlength="16" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_tempat_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control" id="baby_tempat_lahir" name="baby_tempat_lahir" value="{{ old('baby_tempat_lahir', optional($mutasiPenduduk->penduduk)->tempat_lahir) }}" placeholder="Contoh: Sumba Tengah" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="baby_tanggal_lahir" name="baby_tanggal_lahir" value="{{ old('baby_tanggal_lahir', optional($mutasiPenduduk->penduduk)->tanggal_lahir ? $mutasiPenduduk->penduduk->tanggal_lahir->format('Y-m-d') : '') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select" id="baby_jenis_kelamin" name="baby_jenis_kelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="laki-laki" {{ old('baby_jenis_kelamin', optional($mutasiPenduduk->penduduk)->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="perempuan" {{ old('baby_jenis_kelamin', optional($mutasiPenduduk->penduduk)->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                                            {{ old('baby_keluarga_id', optional($mutasiPenduduk->penduduk)->keluarga_id) == $k->id ? 'selected' : '' }}>
                                            KK: {{ $k->no_kk }} - Kepala: {{ $k->kepalaKeluarga ? $k->kepalaKeluarga->nama_lengkap : '–' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_nama_ayah">Nama Ayah</label>
                                <input type="text" class="form-control" id="baby_nama_ayah" name="baby_nama_ayah" value="{{ old('baby_nama_ayah', optional($mutasiPenduduk->penduduk)->nama_ayah) }}" placeholder="Nama Lengkap Ayah" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="baby_nama_ibu">Nama Ibu</label>
                                <input type="text" class="form-control" id="baby_nama_ibu" name="baby_nama_ibu" value="{{ old('baby_nama_ibu', optional($mutasiPenduduk->penduduk)->nama_ibu) }}" placeholder="Nama Lengkap Ibu" />
                            </div>
                        </div>
                        <hr class="my-4">
                    </div>

                    <!-- Data Penduduk Masuk (Khusus Pindah Masuk) -->
                    @php
                        $pekerjaanList = [
                            'Belum / Tidak Bekerja',
                            'Mengurus Rumah Tangga',
                            'Pelajar / Mahasiswa',
                            'PNS / ASN',
                            'TNI / POLRI',
                            'Karyawan Swasta / BUMN / BUMD',
                            'Petani / Pekebun / Peternak',
                            'Nelayan',
                            'Wiraswasta / Pedagang',
                            'Buruh Harian Lepas',
                            'Tenaga Pendidik (Guru / Dosen)',
                            'Tenaga Medis (Dokter / Perawat / Bidan)',
                            'Sopir / Pengemudi',
                            'Pensiunan',
                            'Lainnya'
                        ];
                    @endphp
                    <div id="data_masuk_wrapper" style="display: none;">
                        <hr class="my-4">
                        <h5 class="mb-3 text-primary"><i class="bx bx-user-plus me-1"></i> Data Penduduk Pindah Masuk</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_keluarga_id">Hubungkan ke Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_keluarga_id" name="masuk_keluarga_id">
                                    <option value="">-- Pilih Kartu Keluarga --</option>
                                    @foreach($keluargaList as $k)
                                        <option value="{{ $k->id }}" 
                                            {{ old('masuk_keluarga_id', optional($mutasiPenduduk->penduduk)->keluarga_id) == $k->id ? 'selected' : '' }}>
                                            KK: {{ $k->no_kk }} - Kepala: {{ $k->kepalaKeluarga ? $k->kepalaKeluarga->nama_lengkap : '–' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_nik">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="masuk_nik" name="masuk_nik" 
                                    value="{{ old('masuk_nik', optional($mutasiPenduduk->penduduk)->nik) }}" placeholder="16 digit NIK" maxlength="16" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="masuk_nama_lengkap" name="masuk_nama_lengkap" 
                                    value="{{ old('masuk_nama_lengkap', optional($mutasiPenduduk->penduduk)->nama_lengkap) }}" placeholder="Nama Lengkap Penduduk" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_no_telepon">No. Telepon / HP</label>
                                <input type="text" class="form-control" id="masuk_no_telepon" name="masuk_no_telepon" 
                                    value="{{ old('masuk_no_telepon', optional($mutasiPenduduk->penduduk)->no_telepon) }}" placeholder="Contoh: 08xxxxxxx" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_tempat_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control" id="masuk_tempat_lahir" name="masuk_tempat_lahir" 
                                    value="{{ old('masuk_tempat_lahir', optional($mutasiPenduduk->penduduk)->tempat_lahir) }}" placeholder="Tempat Lahir" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="masuk_tanggal_lahir" name="masuk_tanggal_lahir" 
                                    value="{{ old('masuk_tanggal_lahir', optional($mutasiPenduduk->penduduk)->tanggal_lahir ? $mutasiPenduduk->penduduk->tanggal_lahir->format('Y-m-d') : '') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_jenis_kelamin" name="masuk_jenis_kelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="laki-laki" {{ old('masuk_jenis_kelamin', optional($mutasiPenduduk->penduduk)->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="perempuan" {{ old('masuk_jenis_kelamin', optional($mutasiPenduduk->penduduk)->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_status_hubungan_keluarga">Hubungan Keluarga <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_status_hubungan_keluarga" name="masuk_status_hubungan_keluarga">
                                    <option value="">-- Hubungan Keluarga --</option>
                                    <option value="kepala_keluarga" {{ old('masuk_status_hubungan_keluarga', optional($mutasiPenduduk->penduduk)->status_hubungan_keluarga) == 'kepala_keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                    <option value="istri" {{ old('masuk_status_hubungan_keluarga', optional($mutasiPenduduk->penduduk)->status_hubungan_keluarga) == 'istri' ? 'selected' : '' }}>Istri</option>
                                    <option value="anak" {{ old('masuk_status_hubungan_keluarga', optional($mutasiPenduduk->penduduk)->status_hubungan_keluarga) == 'anak' ? 'selected' : '' }}>Anak</option>
                                    <option value="menantu" {{ old('masuk_status_hubungan_keluarga', optional($mutasiPenduduk->penduduk)->status_hubungan_keluarga) == 'menantu' ? 'selected' : '' }}>Menantu</option>
                                    <option value="cucu" {{ old('masuk_status_hubungan_keluarga', optional($mutasiPenduduk->penduduk)->status_hubungan_keluarga) == 'cucu' ? 'selected' : '' }}>Cucu</option>
                                    <option value="orang_tua" {{ old('masuk_status_hubungan_keluarga', optional($mutasiPenduduk->penduduk)->status_hubungan_keluarga) == 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                                    <option value="mertua" {{ old('masuk_status_hubungan_keluarga', optional($mutasiPenduduk->penduduk)->status_hubungan_keluarga) == 'mertua' ? 'selected' : '' }}>Mertua</option>
                                    <option value="famili_lain" {{ old('masuk_status_hubungan_keluarga', optional($mutasiPenduduk->penduduk)->status_hubungan_keluarga) == 'famili_lain' ? 'selected' : '' }}>Famili Lain</option>
                                    <option value="lainnya" {{ old('masuk_status_hubungan_keluarga', optional($mutasiPenduduk->penduduk)->status_hubungan_keluarga) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_agama">Agama</label>
                                <select class="form-select" id="masuk_agama" name="masuk_agama">
                                    <option value="islam" {{ old('masuk_agama', optional($mutasiPenduduk->penduduk)->agama) == 'islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="kristen" {{ old('masuk_agama', optional($mutasiPenduduk->penduduk)->agama) == 'kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="katolik" {{ old('masuk_agama', optional($mutasiPenduduk->penduduk)->agama) == 'katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="hindu" {{ old('masuk_agama', optional($mutasiPenduduk->penduduk)->agama) == 'hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="buddha" {{ old('masuk_agama', optional($mutasiPenduduk->penduduk)->agama) == 'buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="konghucu" {{ old('masuk_agama', optional($mutasiPenduduk->penduduk)->agama) == 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    <option value="lainnya" {{ old('masuk_agama', optional($mutasiPenduduk->penduduk)->agama) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_pendidikan_terakhir">Pendidikan Terakhir</label>
                                <select class="form-select" id="masuk_pendidikan_terakhir" name="masuk_pendidikan_terakhir">
                                    <option value="tidak_sekolah" {{ old('masuk_pendidikan_terakhir', optional($mutasiPenduduk->penduduk)->pendidikan_terakhir) == 'tidak_sekolah' ? 'selected' : '' }}>Tidak / Belum Sekolah</option>
                                    <option value="sd" {{ old('masuk_pendidikan_terakhir', optional($mutasiPenduduk->penduduk)->pendidikan_terakhir) == 'sd' ? 'selected' : '' }}>SD / Sederajat</option>
                                    <option value="smp" {{ old('masuk_pendidikan_terakhir', optional($mutasiPenduduk->penduduk)->pendidikan_terakhir) == 'smp' ? 'selected' : '' }}>SMP / Sederajat</option>
                                    <option value="sma" {{ old('masuk_pendidikan_terakhir', optional($mutasiPenduduk->penduduk)->pendidikan_terakhir) == 'sma' ? 'selected' : '' }}>SMA / Sederajat</option>
                                    <option value="d1" {{ old('masuk_pendidikan_terakhir', optional($mutasiPenduduk->penduduk)->pendidikan_terakhir) == 'd1' ? 'selected' : '' }}>D1</option>
                                    <option value="d2" {{ old('masuk_pendidikan_terakhir', optional($mutasiPenduduk->penduduk)->pendidikan_terakhir) == 'd2' ? 'selected' : '' }}>D2</option>
                                    <option value="d3" {{ old('masuk_pendidikan_terakhir', optional($mutasiPenduduk->penduduk)->pendidikan_terakhir) == 'd3' ? 'selected' : '' }}>D3</option>
                                    <option value="s1" {{ old('masuk_pendidikan_terakhir', optional($mutasiPenduduk->penduduk)->pendidikan_terakhir) == 's1' ? 'selected' : '' }}>S1 / Diploma IV</option>
                                    <option value="s2" {{ old('masuk_pendidikan_terakhir', optional($mutasiPenduduk->penduduk)->pendidikan_terakhir) == 's2' ? 'selected' : '' }}>S2</option>
                                    <option value="s3" {{ old('masuk_pendidikan_terakhir', optional($mutasiPenduduk->penduduk)->pendidikan_terakhir) == 's3' ? 'selected' : '' }}>S3</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_pekerjaan">Pekerjaan</label>
                                <select class="form-select" id="masuk_pekerjaan" name="masuk_pekerjaan">
                                    @foreach($pekerjaanList as $job)
                                        <option value="{{ $job }}" {{ old('masuk_pekerjaan', optional($mutasiPenduduk->penduduk)->pekerjaan) == $job ? 'selected' : '' }}>{{ $job }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_status_perkawinan">Status Perkawinan</label>
                                <select class="form-select" id="masuk_status_perkawinan" name="masuk_status_perkawinan">
                                    <option value="belum_kawin" {{ old('masuk_status_perkawinan', optional($mutasiPenduduk->penduduk)->status_perkawinan) == 'belum_kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                    <option value="kawin" {{ old('masuk_status_perkawinan', optional($mutasiPenduduk->penduduk)->status_perkawinan) == 'kawin' ? 'selected' : '' }}>Kawin</option>
                                    <option value="cerai_hidup" {{ old('masuk_status_perkawinan', optional($mutasiPenduduk->penduduk)->status_perkawinan) == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                    <option value="cerai_mati" {{ old('masuk_status_perkawinan', optional($mutasiPenduduk->penduduk)->status_perkawinan) == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_kewarganegaraan">Kewarganegaraan <span class="text-danger">*</span></label>
                                <select class="form-select" id="masuk_kewarganegaraan" name="masuk_kewarganegaraan">
                                    <option value="WNI" {{ old('masuk_kewarganegaraan', optional($mutasiPenduduk->penduduk)->kewarganegaraan) == 'WNI' ? 'selected' : '' }}>WNI</option>
                                    <option value="WNA" {{ old('masuk_kewarganegaraan', optional($mutasiPenduduk->penduduk)->kewarganegaraan) == 'WNA' ? 'selected' : '' }}>WNA</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_golongan_darah">Golongan Darah</label>
                                <select class="form-select" id="masuk_golongan_darah" name="masuk_golongan_darah">
                                    <option value="">-- Pilih Golongan Darah --</option>
                                    <option value="A" {{ old('masuk_golongan_darah', optional($mutasiPenduduk->penduduk)->golongan_darah) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('masuk_golongan_darah', optional($mutasiPenduduk->penduduk)->golongan_darah) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('masuk_golongan_darah', optional($mutasiPenduduk->penduduk)->golongan_darah) == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('masuk_golongan_darah', optional($mutasiPenduduk->penduduk)->golongan_darah) == 'O' ? 'selected' : '' }}>O</option>
                                    <option value="-" {{ old('masuk_golongan_darah', optional($mutasiPenduduk->penduduk)->golongan_darah) == '-' ? 'selected' : '' }}>Tidak Tahu / -</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_no_paspor">No. Paspor</label>
                                <input type="text" class="form-control" id="masuk_no_paspor" name="masuk_no_paspor" 
                                    value="{{ old('masuk_no_paspor', optional($mutasiPenduduk->penduduk)->no_paspor) }}" placeholder="No. Paspor (jika ada)" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_no_kitas_kitap">No. KITAS / KITAP</label>
                                <input type="text" class="form-control" id="masuk_no_kitas_kitap" name="masuk_no_kitas_kitap" 
                                    value="{{ old('masuk_no_kitas_kitap', optional($mutasiPenduduk->penduduk)->no_kitas_kitap) }}" placeholder="No. KITAS/KITAP (jika ada)" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_nama_ayah">Nama Lengkap Ayah</label>
                                <input type="text" class="form-control" id="masuk_nama_ayah" name="masuk_nama_ayah" 
                                    value="{{ old('masuk_nama_ayah', optional($mutasiPenduduk->penduduk)->nama_ayah) }}" placeholder="Nama Ayah" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="masuk_nama_ibu">Nama Lengkap Ibu</label>
                                <input type="text" class="form-control" id="masuk_nama_ibu" name="masuk_nama_ibu" 
                                    value="{{ old('masuk_nama_ibu', optional($mutasiPenduduk->penduduk)->nama_ibu) }}" placeholder="Nama Ibu" />
                            </div>
                        </div>

                        <div class="row border-top pt-3 mt-3">
                            <div class="col-md-4 mb-3 d-flex flex-column justify-content-center">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="masuk_is_asuransi_kesehatan" id="masuk_is_asuransi_kesehatan" value="1" 
                                        {{ old('masuk_is_asuransi_kesehatan', optional($mutasiPenduduk->penduduk)->is_asuransi_kesehatan) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="masuk_is_asuransi_kesehatan">Memiliki Asuransi Kesehatan</label>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="masuk_is_disabilitas" id="masuk_is_disabilitas" value="1" 
                                        {{ old('masuk_is_disabilitas', optional($mutasiPenduduk->penduduk)->is_disabilitas) ? 'checked' : '' }} onchange="toggleMasukDisabilitas(this)">
                                    <label class="form-check-label fw-bold" for="masuk_is_disabilitas">Penyandang Disabilitas</label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3" id="masuk_jenis_disabilitas_wrapper" 
                                style="{{ old('masuk_is_disabilitas', optional($mutasiPenduduk->penduduk)->is_disabilitas) ? '' : 'display: none;' }}">
                                <label class="form-label" for="masuk_jenis_disabilitas">Jenis Disabilitas</label>
                                <input type="text" class="form-control" id="masuk_jenis_disabilitas" name="masuk_jenis_disabilitas" 
                                    value="{{ old('masuk_jenis_disabilitas', optional($mutasiPenduduk->penduduk)->jenis_disabilitas) }}" placeholder="Tuna Netra, dll" />
                            </div>
                        </div>
                        <hr class="my-4">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tanggal_mutasi">Tanggal Mutasi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_mutasi" name="tanggal_mutasi" value="{{ old('tanggal_mutasi', $mutasiPenduduk->tanggal_mutasi ? $mutasiPenduduk->tanggal_mutasi->format('Y-m-d') : '') }}" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_surat">No. Surat Keterangan / Pengantar</label>
                            <input type="text" class="form-control" id="no_surat" name="no_surat" value="{{ old('no_surat', $mutasiPenduduk->no_surat) }}" />
                        </div>
                    </div>

                    <div class="mb-3" id="alamat_asal_wrapper" style="display: none;">
                        <label class="form-label" for="alamat_asal">Alamat Asal</label>
                        <textarea class="form-control" id="alamat_asal" name="alamat_asal" rows="3">{{ old('alamat_asal', $mutasiPenduduk->alamat_asal) }}</textarea>
                    </div>

                    <div class="mb-3" id="alamat_tujuan_wrapper" style="display: none;">
                        <label class="form-label" for="alamat_tujuan">Alamat Tujuan Pindah</label>
                        <textarea class="form-control" id="alamat_tujuan" name="alamat_tujuan" rows="3">{{ old('alamat_tujuan', $mutasiPenduduk->alamat_tujuan) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="lampiran">Lampiran Dokumen Pendukung</label>
                        <input type="file" class="form-control" id="lampiran" name="lampiran" accept=".pdf,image/*" />
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah lampiran. Format: pdf, jpeg, png, jpg. Maksimal 2MB.</div>

                        @if ($mutasiPenduduk->lampiran)
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $mutasiPenduduk->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="bx bx-download me-1"></i> Unduh Lampiran Saat Ini
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="keterangan">Keterangan Tambahan / Alasan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $mutasiPenduduk->keterangan) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Perubahan
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
    var babyWrapper = document.getElementById('data_bayi_wrapper');
    var masukWrapper = document.getElementById('data_masuk_wrapper');
    
    // Reset display
    asal.style.display = 'none';
    tujuan.style.display = 'none';
    babyWrapper.style.display = 'none';
    masukWrapper.style.display = 'none';
    pendudukWrapper.style.display = 'block';

    if (val === 'pindah_masuk') {
        asal.style.display = 'block';
        masukWrapper.style.display = 'block';
        pendudukWrapper.style.display = 'none';
    } else if (val === 'pindah_keluar') {
        tujuan.style.display = 'block';
    } else if (val === 'lahir') {
        babyWrapper.style.display = 'block';
        pendudukWrapper.style.display = 'none';
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

    if (val === 'lahir') {
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
    } else if (val === 'pindah_masuk') {
        // Disable baby fields
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
        // Disable baby fields
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
