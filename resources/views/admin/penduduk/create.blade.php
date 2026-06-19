@extends('admin.layouts.app')

@section('title', 'Tambah Penduduk')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Penduduk /</span> Tambah Penduduk
        </h4>

        <form action="{{ route('admin.penduduk.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Card 1: Identitas Utama -->
                <div class="col-md-6">
                    <div class="card h-100 mb-4">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0 text-white"><i class="bx bx-user me-1"></i> Identitas Utama</h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="mb-3">
                                <label class="form-label" for="keluarga_id">Hubungkan dengan Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                                <select class="form-select" id="keluarga_id" name="keluarga_id" required>
                                    <option value="">-- Pilih Kartu Keluarga --</option>
                                    @foreach($keluargaList as $kk)
                                        @php
                                            $kepala = $kk->penduduk->where('status_hubungan_keluarga', 'kepala_keluarga')->first();
                                            $labelKepala = $kepala ? ' (Kepala: ' . $kepala->nama_lengkap . ')' : ' (Belum ada Kepala)';
                                        @endphp
                                        <option value="{{ $kk->id }}" {{ (old('keluarga_id') == $kk->id || $selectedKeluargaId == $kk->id) ? 'selected' : '' }}>
                                            {{ $kk->no_kk }} - {{ Str::limit($kk->alamat, 20) }}{{ $labelKepala }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="nik">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik') }}" maxlength="16" placeholder="16 digit NIK" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required />
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label d-block">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="laki-laki" {{ old('jenis_kelamin', 'laki-laki') == 'laki-laki' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="jk_l">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="jk_p">Perempuan</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="no_telepon">No. Telepon / HP</label>
                                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 08xxxxxxx" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="status_hubungan_keluarga">Hubungan Keluarga <span class="text-danger">*</span></label>
                                <select class="form-select" id="status_hubungan_keluarga" name="status_hubungan_keluarga" required>
                                    <option value="">-- Hubungan Keluarga --</option>
                                    <option value="kepala_keluarga" {{ old('status_hubungan_keluarga') == 'kepala_keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                    <option value="istri" {{ old('status_hubungan_keluarga') == 'istri' ? 'selected' : '' }}>Istri</option>
                                    <option value="anak" {{ old('status_hubungan_keluarga') == 'anak' ? 'selected' : '' }}>Anak</option>
                                    <option value="menantu" {{ old('status_hubungan_keluarga') == 'menantu' ? 'selected' : '' }}>Menantu</option>
                                    <option value="cucu" {{ old('status_hubungan_keluarga') == 'cucu' ? 'selected' : '' }}>Cucu</option>
                                    <option value="orang_tua" {{ old('status_hubungan_keluarga') == 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                                    <option value="mertua" {{ old('status_hubungan_keluarga') == 'mertua' ? 'selected' : '' }}>Mertua</option>
                                    <option value="famili_lain" {{ old('status_hubungan_keluarga') == 'famili_lain' ? 'selected' : '' }}>Famili Lain</option>
                                    <option value="lainnya" {{ old('status_hubungan_keluarga') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Informasi Sosial & Kewarganegaraan -->
                <div class="col-md-6">
                    <div class="card h-100 mb-4">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0 text-white"><i class="bx bx-info-circle me-1"></i> Data Sosial & Fisik</h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="agama">Agama</label>
                                    <select class="form-select" id="agama" name="agama">
                                        <option value="">-- Pilih Agama --</option>
                                        <option value="islam" {{ old('agama') == 'islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="kristen" {{ old('agama') == 'kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="katolik" {{ old('agama') == 'katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="hindu" {{ old('agama') == 'hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="buddha" {{ old('agama') == 'buddha' ? 'selected' : '' }}>Buddha</option>
                                        <option value="konghucu" {{ old('agama') == 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                                        <option value="lainnya" {{ old('agama') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                    <select class="form-select" id="pendidikan_terakhir" name="pendidikan_terakhir">
                                        <option value="">-- Pilih Pendidikan --</option>
                                        <option value="tidak_sekolah" {{ old('pendidikan_terakhir') == 'tidak_sekolah' ? 'selected' : '' }}>Tidak / Belum Sekolah</option>
                                        <option value="sd" {{ old('pendidikan_terakhir') == 'sd' ? 'selected' : '' }}>SD / Sederajat</option>
                                        <option value="smp" {{ old('pendidikan_terakhir') == 'smp' ? 'selected' : '' }}>SMP / Sederajat</option>
                                        <option value="sma" {{ old('pendidikan_terakhir') == 'sma' ? 'selected' : '' }}>SMA / Sederajat</option>
                                        <option value="d1" {{ old('pendidikan_terakhir') == 'd1' ? 'selected' : '' }}>D1</option>
                                        <option value="d2" {{ old('pendidikan_terakhir') == 'd2' ? 'selected' : '' }}>D2</option>
                                        <option value="d3" {{ old('pendidikan_terakhir') == 'd3' ? 'selected' : '' }}>D3</option>
                                        <option value="s1" {{ old('pendidikan_terakhir') == 's1' ? 'selected' : '' }}>S1 / Diploma IV</option>
                                        <option value="s2" {{ old('pendidikan_terakhir') == 's2' ? 'selected' : '' }}>S2</option>
                                        <option value="s3" {{ old('pendidikan_terakhir') == 's3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                </div>
                            </div>

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
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="pekerjaan">Pekerjaan</label>
                                    <select class="form-select" id="pekerjaan" name="pekerjaan">
                                        <option value="">-- Pilih Pekerjaan --</option>
                                        @foreach($pekerjaanList as $job)
                                            <option value="{{ $job }}" {{ old('pekerjaan') == $job ? 'selected' : '' }}>{{ $job }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="status_perkawinan">Status Perkawinan</label>
                                    <select class="form-select" id="status_perkawinan" name="status_perkawinan">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="belum_kawin" {{ old('status_perkawinan') == 'belum_kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                        <option value="kawin" {{ old('status_perkawinan') == 'kawin' ? 'selected' : '' }}>Kawin</option>
                                        <option value="cerai_hidup" {{ old('status_perkawinan') == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                        <option value="cerai_mati" {{ old('status_perkawinan') == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="kewarganegaraan">Kewarganegaraan <span class="text-danger">*</span></label>
                                    <select class="form-select" id="kewarganegaraan" name="kewarganegaraan" required>
                                        <option value="WNI" {{ old('kewarganegaraan', 'WNI') == 'WNI' ? 'selected' : '' }}>WNI</option>
                                        <option value="WNA" {{ old('kewarganegaraan') == 'WNA' ? 'selected' : '' }}>WNA</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="golongan_darah">Golongan Darah</label>
                                    <select class="form-select" id="golongan_darah" name="golongan_darah">
                                        <option value="">-- Pilih Golongan Darah --</option>
                                        <option value="A" {{ old('golongan_darah') == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('golongan_darah') == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="AB" {{ old('golongan_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                                        <option value="O" {{ old('golongan_darah') == 'O' ? 'selected' : '' }}>O</option>
                                        <option value="-" {{ old('golongan_darah') == '-' ? 'selected' : '' }}>Tidak Tahu / -</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="no_paspor">No. Paspor</label>
                                    <input type="text" class="form-control" id="no_paspor" name="no_paspor" value="{{ old('no_paspor') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="no_kitas_kitap">No. KITAS / KITAP</label>
                                    <input type="text" class="form-control" id="no_kitas_kitap" name="no_kitas_kitap" value="{{ old('no_kitas_kitap') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Hubungan Keluarga, Orang Tua, Fisik, Status & Keterangan -->
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0 text-white"><i class="bx bx-certification me-1"></i> Data Orang Tua, Disabilitas, & Status Keaktifan</h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="nama_ayah">Nama Lengkap Ayah</label>
                                    <input type="text" class="form-control" id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah') }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="nama_ibu">Nama Lengkap Ibu</label>
                                    <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu') }}" />
                                </div>
                            </div>

                            <div class="row border-top pt-3 mt-3">
                                <div class="col-md-4 mb-3 d-flex flex-column justify-content-center">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_asuransi_kesehatan" id="is_asuransi_kesehatan" value="1" {{ old('is_asuransi_kesehatan') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_asuransi_kesehatan">Memiliki Asuransi Kesehatan (BPJS/Lainnya)</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_disabilitas" id="is_disabilitas" value="1" {{ old('is_disabilitas') ? 'checked' : '' }} onchange="toggleDisabilitas(this)">
                                        <label class="form-check-label fw-bold" for="is_disabilitas">Penyandang Disabilitas</label>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3" id="jenis_disabilitas_wrapper" style="{{ old('is_disabilitas') ? '' : 'display: none;' }}">
                                    <label class="form-label" for="jenis_disabilitas">Jenis Disabilitas</label>
                                    <input type="text" class="form-control" id="jenis_disabilitas" name="jenis_disabilitas" value="{{ old('jenis_disabilitas') }}" placeholder="Contoh: Tuna Netra, Tuna Rungu" />
                                </div>
                            </div>

                            <div class="row border-top pt-3 mt-3">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="status">Status Penduduk <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="pindah" {{ old('status') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                                        <option value="meninggal" {{ old('status') == 'meninggal' ? 'selected' : '' }}>Meninggal</option>
                                    </select>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label" for="keterangan">Keterangan Tambahan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Catatan opsional mengenai warga...">{{ old('keterangan') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light d-flex justify-content-end gap-2 p-3">
                            <a href="{{ $selectedKeluargaId ? route('admin.keluarga.show', $selectedKeluargaId) : route('admin.penduduk.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Simpan Data Penduduk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDisabilitas(checkbox) {
    var wrapper = document.getElementById('jenis_disabilitas_wrapper');
    if (checkbox.checked) {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';
        document.getElementById('jenis_disabilitas').value = '';
    }
}
</script>
@endsection
