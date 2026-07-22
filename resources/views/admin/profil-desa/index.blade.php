@extends('admin.layouts.app')

@section('title', 'Profil Desa')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Pengaturan /</span> Profil Desa
        </h4>

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

        <form action="{{ route('admin.profil-desa.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="nav-align-top mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-umum" aria-controls="navs-umum" aria-selected="true">
                            <i class="tf-icons bx bx-info-circle me-1"></i> Informasi Umum
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-geografis" aria-controls="navs-geografis" aria-selected="false">
                            <i class="tf-icons bx bx-map me-1"></i> Geografis
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-visi-misi" aria-controls="navs-visi-misi" aria-selected="false">
                            <i class="tf-icons bx bx-bullseye me-1"></i> Visi, Misi & Sejarah
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-media" aria-controls="navs-media" aria-selected="false">
                            <i class="tf-icons bx bx-image me-1"></i> Media & Logo
                        </button>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    {{-- Tab Informasi Umum --}}
                    <div class="tab-pane fade show active" id="navs-umum" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="nama_desa">Nama Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_desa" name="nama_desa" value="{{ old('nama_desa', $profil->nama_desa) }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="kecamatan">Kecamatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $profil->kecamatan) }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="kabupaten">Kabupaten <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kabupaten" name="kabupaten" value="{{ old('kabupaten', $profil->kabupaten) }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="provinsi">Provinsi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="provinsi" name="provinsi" value="{{ old('provinsi', $profil->provinsi) }}" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="kode_pos">Kode Pos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $profil->kode_pos) }}" inputmode="numeric" autocomplete="off" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="telepon">No. Telepon / HP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="telepon" name="telepon" value="{{ old('telepon', $profil->telepon) }}" inputmode="numeric" autocomplete="off" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="email">E-mail Resmi</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $profil->email) }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="jam_pelayanan">Jam Pelayanan Kantor Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jam_pelayanan" name="jam_pelayanan" value="{{ old('jam_pelayanan', $profil->jam_pelayanan) }}" placeholder="Contoh: Senin - Jumat, 08:00 - 15:00" />
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="alamat_lengkap">Alamat Lengkap Kantor Desa <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows="3">{{ old('alamat_lengkap', $profil->alamat_lengkap) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Tab Geografis --}}
                    <div class="tab-pane fade" id="navs-geografis" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="luas_wilayah">Luas Wilayah <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="luas_wilayah" name="luas_wilayah" value="{{ old('luas_wilayah', $profil->luas_wilayah) }}" placeholder="Contoh: 15 km² atau 1.500 Ha" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="ketinggian">Ketinggian Wilayah <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ketinggian" name="ketinggian" value="{{ old('ketinggian', $profil->ketinggian) }}" placeholder="Contoh: 250 mdpl" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="batas_utara">Batas Utara <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="batas_utara" name="batas_utara" value="{{ old('batas_utara', $profil->batas_utara) }}" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="batas_timur">Batas Timur <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="batas_timur" name="batas_timur" value="{{ old('batas_timur', $profil->batas_timur) }}" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="batas_selatan">Batas Selatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="batas_selatan" name="batas_selatan" value="{{ old('batas_selatan', $profil->batas_selatan) }}" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="batas_barat">Batas Barat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="batas_barat" name="batas_barat" value="{{ old('batas_barat', $profil->batas_barat) }}" />
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="peta_wilayah">URL Embed Google Maps</label>
                                <textarea class="form-control" id="peta_wilayah" name="peta_wilayah" rows="3" placeholder="Masukkan URL 'src' dari Google Maps (contoh: https://www.google.com/maps/embed?...)">{{ old('peta_wilayah', $profil->peta_wilayah) }}</textarea>
                                <div class="form-text">Buka Google Maps > Bagikan > Sematkan peta > Salin URL pada atribut src="..." (Bukan seluruh tag iframe)</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab Visi Misi --}}
                    <div class="tab-pane fade" id="navs-visi-misi" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label" for="visi">Visi Desa <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="visi" name="visi" rows="3" placeholder="Tuliskan Visi Desa...">{{ old('visi', $profil->visi) }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="misi">Misi Desa <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="misi" name="misi" rows="6" placeholder="Tuliskan Misi Desa (bisa berupa poin-poin)...">{{ old('misi', $profil->misi) }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="sejarah_desa">Sejarah Desa</label>
                                <textarea class="form-control" id="sejarah_desa" name="sejarah_desa" rows="8" placeholder="Tuliskan Sejarah Lengkap Desa...">{{ old('sejarah_desa', $profil->sejarah_desa) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Tab Media --}}
                    <div class="tab-pane fade" id="navs-media" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="logo">Logo Desa</label>
                                <input class="form-control" type="file" id="logo" name="logo" accept="image/*" />
                                <div class="form-text">Format yang diperbolehkan: jpeg, png, jpg, svg. Maksimal 2MB.</div>
                                
                                @if ($profil->logo)
                                    <div class="mt-3">
                                        <label class="d-block form-label">Logo Saat Ini:</label>
                                        <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo Desa" class="img-thumbnail" style="max-height: 120px;" />
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="gambar_struktur_organisasi">Gambar Struktur Organisasi</label>
                                <input class="form-control" type="file" id="gambar_struktur_organisasi" name="gambar_struktur_organisasi" accept="image/*" />
                                <div class="form-text">Format yang diperbolehkan: jpeg, png, jpg, svg. Maksimal 2MB.</div>

                                @if ($profil->gambar_struktur_organisasi)
                                    <div class="mt-3">
                                        <label class="d-block form-label">Gambar Saat Ini:</label>
                                        <img src="{{ asset('storage/' . $profil->gambar_struktur_organisasi) }}" alt="Struktur Organisasi" class="img-thumbnail" style="max-height: 150px;" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-end gap-2 p-3">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-reset me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const umumInputs = document.querySelectorAll('#navs-umum input[type="text"]:not(#kode_pos):not(#telepon), #navs-umum input[type="email"], #navs-umum textarea, #navs-geografis input[type="text"]');
    
    function toCapitalEachWord(str) {
        return str.toLowerCase().replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    }

    umumInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value) return;
            
            // 1. Bersihkan spasi berlebih (awal, akhir, dan spasi ganda di tengah)
            let val = this.value.trim().replace(/\s+/g, ' ');
            
            // 2. Format Capital Each Word (kecuali email menjadi lowercase)
            if (this.id === 'email' || this.name === 'email') {
                val = val.toLowerCase();
            } else {
                val = toCapitalEachWord(val);
            }
            
            this.value = val;
        });
    });

    const visiMisiInputs = document.querySelectorAll('#navs-visi-misi textarea');
    visiMisiInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value) return;
            // Bersihkan spasi horizontal tiap baris tapi pertahankan enter/newline paragraf
            let lines = this.value.split(/\r?\n/);
            let cleanedLines = lines.map(line => line.trim().replace(/[^\S\r\n]+/g, ' '));
            let text = cleanedLines.join('\n');
            // Ringkas jika ada enter berlebih (lebih dari 2 baris kosong berurutan)
            text = text.replace(/\n{3,}/g, '\n\n').trim();
            this.value = text;
        });
    });

    // ── Field: kode_pos & telepon → digit only, no space ───────────────────
    function sanitizeDigits(input) {
        let pos = input.selectionStart;
        let oldVal = input.value;
        let newVal = oldVal.replace(/[^0-9]/g, '');
        if (oldVal !== newVal) {
            let removed = 0;
            if (pos !== null) {
                for (let i = 0; i < pos && i < oldVal.length; i++) {
                    if (!/[0-9]/.test(oldVal[i])) removed++;
                }
            }
            input.value = newVal;
            if (pos !== null && input.setSelectionRange) {
                let newPos = Math.max(0, pos - removed);
                try { input.setSelectionRange(newPos, newPos); } catch(e) {}
            }
        }
    }

    const digitFields = document.querySelectorAll('#kode_pos, #telepon');
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
            let merged = (sel_start !== null && sel_end !== null) ? (cur.slice(0, sel_start) + digits + cur.slice(sel_end)) : (cur + digits);
            if (maxLen && merged.length > maxLen) merged = merged.slice(0, maxLen);
            this.value = merged;
        });

        input.addEventListener('input', function() { sanitizeDigits(this); });
        input.addEventListener('blur',  function() { sanitizeDigits(this); });
    });
});
</script>
@endpush
