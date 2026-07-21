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

            <!-- Card Wilayah & Keluarga (Full 12 Col) -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-white"><i class="bx bx-home-alt me-1"></i> Pilihan Wilayah & Kartu Keluarga</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="dusun_id">Dusun <span class="text-danger">*</span></label>
                            <select class="form-select" id="dusun_id" name="dusun_id" required>
                                <option value="">-- Pilih Dusun --</option>
                                @foreach($dusunList as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="rt_rw_id">RT / RW <span class="text-danger">*</span></label>
                            <select class="form-select" id="rt_rw_id" name="rt_rw_id" required disabled>
                                <option value="">-- Pilih Dusun terlebih dahulu --</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="keluarga_id">Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                            <select class="form-select" id="keluarga_id" name="keluarga_id" required disabled>
                                <option value="">-- Pilih RT/RW terlebih dahulu --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Card 1: Identitas Utama -->
                <div class="col-md-6">
                    <div class="card h-100 mb-4">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0 text-white"><i class="bx bx-user me-1"></i> Identitas Utama</h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="mb-3">
                                <label class="form-label" for="nik">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik') }}" maxlength="16" placeholder="16 digit NIK" inputmode="numeric" autocomplete="off" required />
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
                                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 08xxxxxxx" inputmode="numeric" autocomplete="off" />
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
                                    <input type="text" class="form-control" id="no_kitas_kitap" name="no_kitas_kitap" value="{{ old('no_kitas_kitap') }}" inputmode="numeric" autocomplete="off" />
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

@endsection

@push('scripts')
@php
    $rtRwArray = $rtRwList->map(function($r) {
        return [
            'id' => $r->id,
            'dusun_id' => $r->dusun_id,
            'label' => 'RT ' . $r->no_rt . ' / RW ' . $r->no_rw
        ];
    })->values();

    $keluargaArray = $keluargaList->map(function($kk) {
        $kepala = $kk->penduduk->where('status_hubungan_keluarga', 'kepala_keluarga')->first();
        $namaKepala = $kepala ? strtoupper($kepala->nama_lengkap) : 'Belum ada Kepala Keluarga';
        return [
            'id' => $kk->id,
            'rt_rw_id' => $kk->rt_rw_id,
            'dusun_id' => $kk->rtRw ? $kk->rtRw->dusun_id : null,
            'label' => $kk->no_kk . ' - Kepala Keluarga: ' . $namaKepala
        ];
    })->values();
@endphp
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

document.addEventListener('DOMContentLoaded', function() {
    function toCapitalEachWord(str) {
        return str.toLowerCase().replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    }

    // ── Field: nik & no_telepon → digit only, no space ─────────────────────
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

    const digitFields = document.querySelectorAll('#nik, #no_telepon, #no_kitas_kitap');
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

    const pasporFields = document.querySelectorAll('#no_paspor');
    pasporFields.forEach(input => {
        ['input', 'blur'].forEach(evt => {
            input.addEventListener(evt, function() {
                if (!this.value) return;
                this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
            });
        });
    });

    const titleFields = document.querySelectorAll('#nama_lengkap, #tempat_lahir, #nama_ayah, #nama_ibu, #jenis_disabilitas');
    titleFields.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value) return;
            let cleaned = this.value.replace(/\s+/g, ' ').trim();
            this.value = toCapitalEachWord(cleaned);
        });
    });

    const ketInput = document.getElementById('keterangan');
    if (ketInput) {
        ketInput.addEventListener('blur', function() {
            if (!this.value) return;
            let lines = this.value.split(/\r?\n/);
            let cleanedLines = lines.map(line => line.trim().replace(/[^\S\r\n]+/g, ' '));
            this.value = cleanedLines.join('\n').replace(/\n{3,}/g, '\n\n').trim();
        });
    }

    const rtRwData = @json($rtRwArray);
    const keluargaData = @json($keluargaArray);

    const dusunSelect = document.getElementById('dusun_id');
    const rtRwSelect = document.getElementById('rt_rw_id');
    const keluargaSelect = document.getElementById('keluarga_id');

    let targetKeluargaId = "{{ old('keluarga_id', $selectedKeluargaId ?? '') }}";
    let targetRtRwId = "{{ old('rt_rw_id', '') }}";
    let targetDusunId = "{{ old('dusun_id', '') }}";

    if (targetKeluargaId && !targetRtRwId) {
        const found = keluargaData.find(k => String(k.id) === String(targetKeluargaId));
        if (found) {
            targetRtRwId = found.rt_rw_id;
            targetDusunId = found.dusun_id;
        }
    }

    function filterRtRw(selectedDusunId, selectedRtRwId = '') {
        rtRwSelect.innerHTML = '';
        if (!selectedDusunId) {
            rtRwSelect.innerHTML = '<option value="">-- Pilih Dusun terlebih dahulu --</option>';
            rtRwSelect.disabled = true;
            return;
        }

        const filtered = rtRwData.filter(item => String(item.dusun_id) === String(selectedDusunId));
        if (filtered.length === 0) {
            rtRwSelect.innerHTML = '<option value="">-- Belum ada RT/RW di dusun ini --</option>';
            rtRwSelect.disabled = true;
            return;
        }

        rtRwSelect.disabled = false;
        rtRwSelect.innerHTML = '<option value="">-- Pilih RT / RW --</option>';
        filtered.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.label;
            if (String(item.id) === String(selectedRtRwId)) {
                opt.selected = true;
            }
            rtRwSelect.appendChild(opt);
        });
    }

    function filterKeluarga(selectedDusunId, selectedRtRwId, selectedKkId = '') {
        keluargaSelect.innerHTML = '';
        if (!selectedDusunId && !selectedRtRwId) {
            keluargaSelect.innerHTML = '<option value="">-- Pilih RT/RW terlebih dahulu --</option>';
            keluargaSelect.disabled = true;
            return;
        }

        let filtered = [];
        if (selectedRtRwId) {
            filtered = keluargaData.filter(item => String(item.rt_rw_id) === String(selectedRtRwId));
        } else if (selectedDusunId) {
            filtered = keluargaData.filter(item => String(item.dusun_id) === String(selectedDusunId));
        }

        if (filtered.length === 0) {
            keluargaSelect.innerHTML = '<option value="">-- Belum ada KK di wilayah ini --</option>';
            keluargaSelect.disabled = true;
            return;
        }

        keluargaSelect.disabled = false;
        keluargaSelect.innerHTML = '<option value="">-- Pilih Kartu Keluarga --</option>';
        filtered.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.label;
            if (String(item.id) === String(selectedKkId)) {
                opt.selected = true;
            }
            keluargaSelect.appendChild(opt);
        });
    }

    if (dusunSelect && rtRwSelect && keluargaSelect) {
        dusunSelect.addEventListener('change', function() {
            filterRtRw(this.value, '');
            filterKeluarga(this.value, '', '');
        });

        rtRwSelect.addEventListener('change', function() {
            filterKeluarga(dusunSelect.value, this.value, '');
        });

        if (targetDusunId) {
            dusunSelect.value = targetDusunId;
        }
        filterRtRw(dusunSelect.value, targetRtRwId);
        filterKeluarga(dusunSelect.value, targetRtRwId, targetKeluargaId);
    }
});
</script>
@endpush
