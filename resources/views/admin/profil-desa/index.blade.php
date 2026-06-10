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
                                <input type="text" class="form-control" id="nama_desa" name="nama_desa" value="{{ old('nama_desa', $profil->nama_desa) }}" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="kecamatan">Kecamatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $profil->kecamatan) }}" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="kabupaten">Kabupaten <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kabupaten" name="kabupaten" value="{{ old('kabupaten', $profil->kabupaten) }}" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="provinsi">Provinsi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="provinsi" name="provinsi" value="{{ old('provinsi', $profil->provinsi) }}" required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="kode_pos">Kode Pos</label>
                                <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $profil->kode_pos) }}" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="telepon">No. Telepon / HP</label>
                                <input type="text" class="form-control" id="telepon" name="telepon" value="{{ old('telepon', $profil->telepon) }}" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="email">E-mail Resmi</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $profil->email) }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="jam_pelayanan">Jam Pelayanan Kantor Desa</label>
                                <input type="text" class="form-control" id="jam_pelayanan" name="jam_pelayanan" value="{{ old('jam_pelayanan', $profil->jam_pelayanan) }}" placeholder="Contoh: Senin - Jumat, 08:00 - 15:00" />
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="alamat_lengkap">Alamat Lengkap Kantor Desa</label>
                                <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows="3">{{ old('alamat_lengkap', $profil->alamat_lengkap) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Tab Geografis --}}
                    <div class="tab-pane fade" id="navs-geografis" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="luas_wilayah">Luas Wilayah</label>
                                <input type="text" class="form-control" id="luas_wilayah" name="luas_wilayah" value="{{ old('luas_wilayah', $profil->luas_wilayah) }}" placeholder="Contoh: 15 km² atau 1.500 Ha" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="ketinggian">Ketinggian Wilayah</label>
                                <input type="text" class="form-control" id="ketinggian" name="ketinggian" value="{{ old('ketinggian', $profil->ketinggian) }}" placeholder="Contoh: 250 mdpl" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="koordinat_lat">Garis Lintang (Latitude)</label>
                                <input type="text" class="form-control" id="koordinat_lat" name="koordinat_lat" value="{{ old('koordinat_lat', $profil->koordinat_lat) }}" placeholder="Contoh: -7.89201" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="koordinat_lng">Garis Bujur (Longitude)</label>
                                <input type="text" class="form-control" id="koordinat_lng" name="koordinat_lng" value="{{ old('koordinat_lng', $profil->koordinat_lng) }}" placeholder="Contoh: 110.23122" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="batas_utara">Batas Utara</label>
                                <input type="text" class="form-control" id="batas_utara" name="batas_utara" value="{{ old('batas_utara', $profil->batas_utara) }}" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="batas_timur">Batas Timur</label>
                                <input type="text" class="form-control" id="batas_timur" name="batas_timur" value="{{ old('batas_timur', $profil->batas_timur) }}" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="batas_selatan">Batas Selatan</label>
                                <input type="text" class="form-control" id="batas_selatan" name="batas_selatan" value="{{ old('batas_selatan', $profil->batas_selatan) }}" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="batas_barat">Batas Barat</label>
                                <input type="text" class="form-control" id="batas_barat" name="batas_barat" value="{{ old('batas_barat', $profil->batas_barat) }}" />
                            </div>
                        </div>
                    </div>

                    {{-- Tab Visi Misi --}}
                    <div class="tab-pane fade" id="navs-visi-misi" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label" for="visi">Visi Desa</label>
                                <textarea class="form-control" id="visi" name="visi" rows="3" placeholder="Tuliskan Visi Desa...">{{ old('visi', $profil->visi) }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="misi">Misi Desa</label>
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
