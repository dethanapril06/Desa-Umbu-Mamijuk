@extends('admin.layouts.app')

@section('title', 'Edit Destinasi Wisata')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Wisata /</span> Kelola Destinasi Wisata
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

        <div class="nav-align-top mb-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-info-utama" aria-controls="navs-info-utama" aria-selected="true">
                        <i class="tf-icons bx bx-info-circle me-1"></i> Informasi Utama
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-fasilitas" aria-controls="navs-fasilitas" aria-selected="false">
                        <i class="tf-icons bx bx-list-check me-1"></i> Fasilitas ({{ $wisata->fasilitasWisata->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-tips" aria-controls="navs-tips" aria-selected="false">
                        <i class="tf-icons bx bx-bulb me-1"></i> Tips Kunjungan ({{ $wisata->tipsWisata->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-rute" aria-controls="navs-rute" aria-selected="false">
                        <i class="tf-icons bx bx-directions me-1"></i> Rute Perjalanan ({{ $wisata->ruteWisata->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-galeri" aria-controls="navs-galeri" aria-selected="false">
                        <i class="tf-icons bx bx-images me-1"></i> Galeri Foto ({{ $wisata->galeriWisata->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-penginapan" aria-controls="navs-penginapan" aria-selected="false">
                        <i class="tf-icons bx bx-home-heart me-1"></i> Info Penginapan ({{ $wisata->penginapanWisata->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-ulasan" aria-controls="navs-ulasan" aria-selected="false">
                        <i class="tf-icons bx bx-star me-1"></i> Ulasan Warga ({{ $wisata->ulasanWisata->count() }})
                    </button>
                </li>
            </ul>
            <div class="tab-content p-4">
                {{-- TAB 1: Informasi Utama --}}
                <div class="tab-pane fade show active" id="navs-info-utama" role="tabpanel">
                    <form action="{{ route('admin.wisata.update', $wisata->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label" for="nama">Nama Destinasi Wisata <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $wisata->nama) }}" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="kategori_wisata_id">Kategori Wisata <span class="text-danger">*</span></label>
                                    <select class="form-select" id="kategori_wisata_id" name="kategori_wisata_id" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('kategori_wisata_id', $wisata->kategori_wisata_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="gambar_utama">Gambar Utama (Cover Banner)</label>
                                    <input type="file" class="form-control" id="gambar_utama" name="gambar_utama" accept="image/*" />
                                    <div class="form-text">Biarkan kosong jika tidak ingin mengganti cover. <strong>Wajib orientasi mendatar (Landscape).</strong> Rekomendasi: 1200x800 px. Minimal: 400x250 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>

                                    @if ($wisata->gambar_utama)
                                        <div class="mt-3">
                                            <label class="d-block form-label">Cover Saat Ini:</label>
                                            <img src="{{ asset('storage/' . $wisata->gambar_utama) }}" alt="Cover" class="img-thumbnail" style="max-height: 120px;" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="telepon">No. Telepon / Pengelola</label>
                                    <input type="text" class="form-control" id="telepon" name="telepon" value="{{ old('telepon', $wisata->telepon) }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="jarak_dari_desa">Jarak dari Kantor Desa</label>
                                    <input type="text" class="form-control" id="jarak_dari_desa" name="jarak_dari_desa" value="{{ old('jarak_dari_desa', $wisata->jarak_dari_desa) }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="durasi_trek">Durasi Trekking / Kunjungan</label>
                                    <input type="text" class="form-control" id="durasi_trek" name="durasi_trek" value="{{ old('durasi_trek', $wisata->durasi_trek) }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="cocok_untuk">Cocok Untuk</label>
                                    <input type="text" class="form-control" id="cocok_untuk" name="cocok_untuk" value="{{ old('cocok_untuk', $wisata->cocok_untuk) }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="hari_buka">Hari Operasional</label>
                                    <input type="text" class="form-control" id="hari_buka" name="hari_buka" value="{{ old('hari_buka', $wisata->hari_buka) }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="jam_operasional">Jam Operasional</label>
                                    <input type="text" class="form-control" id="jam_operasional" name="jam_operasional" value="{{ old('jam_operasional', $wisata->jam_operasional) }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="harga_tiket">Harga Tiket Masuk (IDR)</label>
                                    <input type="number" class="form-control" id="harga_tiket" name="harga_tiket" value="{{ old('harga_tiket', $wisata->harga_tiket) }}" min="0" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="harga_parkir_motor">Tarif Parkir Motor</label>
                                    <input type="text" class="form-control" id="harga_parkir_motor" name="harga_parkir_motor" value="{{ old('harga_parkir_motor', $wisata->harga_parkir_motor) }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="harga_parkir_mobil">Tarif Parkir Mobil</label>
                                    <input type="text" class="form-control" id="harga_parkir_mobil" name="harga_parkir_mobil" value="{{ old('harga_parkir_mobil', $wisata->harga_parkir_mobil) }}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="koordinat_lat">Latitude</label>
                                    <input type="text" class="form-control" id="koordinat_lat" name="koordinat_lat" value="{{ old('koordinat_lat', $wisata->koordinat_lat) }}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="koordinat_lng">Longitude</label>
                                    <input type="text" class="form-control" id="koordinat_lng" name="koordinat_lng" value="{{ old('koordinat_lng', $wisata->koordinat_lng) }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="google_maps_embed_url">Google Maps Embed URL</label>
                                    <input type="text" class="form-control" id="google_maps_embed_url" name="google_maps_embed_url" value="{{ old('google_maps_embed_url', $wisata->google_maps_embed_url) }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi_singkat">Ringkasan Singkat Destinasi</label>
                                    <textarea class="form-control" id="deskripsi_singkat" name="deskripsi_singkat" rows="3">{{ old('deskripsi_singkat', $wisata->deskripsi_singkat) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="highlight_quote">Highlight Quote / Tagline Utama</label>
                                    <textarea class="form-control" id="highlight_quote" name="highlight_quote" rows="3">{{ old('highlight_quote', $wisata->highlight_quote) }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="editor">Deskripsi Lengkap Wisata</label>
                                    <textarea id="editor" name="deskripsi">{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 border-top pt-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="is_unggulan" name="is_unggulan" value="1" {{ old('is_unggulan', $wisata->is_unggulan) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_unggulan">Jadikan Destinasi Wisata Unggulan (Featured)</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $wisata->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_published">Publikasikan Wisata</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('admin.wisata.index') }}" class="btn btn-outline-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Informasi Utama</button>
                        </div>
                    </form>
                </div>

                {{-- TAB 2: Fasilitas Wisata --}}
                <div class="tab-pane fade" id="navs-fasilitas" role="tabpanel">
                    <div class="row">
                        <!-- Tambah Fasilitas -->
                        <div class="col-md-4 border-end pe-md-4">
                            <h6 class="fw-bold mb-3"><i class="bx bx-plus me-1 text-primary"></i> Tambah Fasilitas</h6>
                            <form action="{{ route('admin.fasilitas-wisata.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="wisata_id" value="{{ $wisata->id }}" />
                                
                                <div class="mb-3">
                                    <label class="form-label" for="fasilitas_nama">Nama Fasilitas <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="fasilitas_nama" name="nama" placeholder="Contoh: Toilet Umum, Tempat Parkir" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="fasilitas_icon">Icon (Opsional)</label>
                                    @include('admin.layouts.partials.icon-picker', [
                                        'id' => 'fasilitas_icon',
                                        'name' => 'icon',
                                        'value' => old('icon'),
                                        'type' => 'fasilitas'
                                    ])
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100">Tambah Fasilitas</button>
                            </form>
                        </div>

                        <!-- Daftar Fasilitas -->
                        <div class="col-md-8 ps-md-4 mt-4 mt-md-0">
                            <h6 class="fw-bold mb-3">Daftar Fasilitas Saat Ini</h6>
                            <div class="table-responsive text-nowrap">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Icon</th>
                                            <th>Nama Fasilitas</th>
                                            <th>Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($wisata->fasilitasWisata as $f)
                                            <tr>
                                                <td><i class="bx {{ $f->icon ?? 'bx-badge' }} fs-4 text-secondary"></i></td>
                                                <td><strong>{{ $f->nama }}</strong></td>
                                                <td>
                                                    <form action="{{ route('admin.fasilitas-wisata.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Hapus fasilitas ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-outline-danger p-1"><i class="bx bx-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-3 text-muted">Belum ada fasilitas terdaftar.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: Tips Kunjungan --}}
                <div class="tab-pane fade" id="navs-tips" role="tabpanel">
                    <div class="row">
                        <!-- Tambah Tips -->
                        <div class="col-md-4 border-end pe-md-4">
                            <h6 class="fw-bold mb-3"><i class="bx bx-plus me-1 text-primary"></i> Tambah Tips Wisata</h6>
                            <form action="{{ route('admin.tips-wisata.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="wisata_id" value="{{ $wisata->id }}" />

                                <div class="mb-3">
                                    <label class="form-label" for="tips_judul">Judul Tips <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tips_judul" name="judul" placeholder="Contoh: Datang Pagi Hari" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="tips_deskripsi">Deskripsi Tips</label>
                                    <textarea class="form-control" id="tips_deskripsi" name="deskripsi" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="tips_urutan">Urutan</label>
                                    <input type="number" class="form-control" id="tips_urutan" name="urutan" value="1" min="0" required />
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100">Tambah Tips</button>
                            </form>
                        </div>

                        <!-- Daftar Tips -->
                        <div class="col-md-8 ps-md-4 mt-4 mt-md-0">
                            <h6 class="fw-bold mb-3">Daftar Tips Kunjungan Saat Ini</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Urutan</th>
                                            <th>Judul</th>
                                            <th>Penjelasan</th>
                                            <th>Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($wisata->tipsWisata as $t)
                                            <tr>
                                                <td>{{ $t->urutan }}</td>
                                                <td><strong>{{ $t->judul }}</strong></td>
                                                <td class="text-wrap" style="max-width: 300px;">{{ $t->deskripsi }}</td>
                                                <td>
                                                    <form action="{{ route('admin.tips-wisata.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tips ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-outline-danger p-1"><i class="bx bx-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-3 text-muted">Belum ada tips kunjungan terdaftar.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 4: Rute Perjalanan --}}
                <div class="tab-pane fade" id="navs-rute" role="tabpanel">
                    <div class="row">
                        <!-- Tambah Rute -->
                        <div class="col-md-4 border-end pe-md-4">
                            <h6 class="fw-bold mb-3"><i class="bx bx-plus me-1 text-primary"></i> Tambah Rute Perjalanan</h6>
                            <form action="{{ route('admin.rute-wisata.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="wisata_id" value="{{ $wisata->id }}" />

                                <div class="mb-3">
                                    <label class="form-label" for="rute_transport">Jenis Transportasi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="rute_transport" name="jenis_transportasi" placeholder="Contoh: Rute Motor, Rute Mobil" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="rute_icon">Icon (Opsional)</label>
                                    @include('admin.layouts.partials.icon-picker', [
                                        'id' => 'rute_icon',
                                        'name' => 'icon',
                                        'value' => old('icon'),
                                        'type' => 'rute'
                                    ])
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="rute_warna">Warna Badge</label>
                                    <select class="form-select" id="rute_warna" name="warna_badge">
                                        <option value="primary">Biru (Primary)</option>
                                        <option value="success">Hijau (Success)</option>
                                        <option value="warning">Kuning (Warning)</option>
                                        <option value="info">Cyan (Info)</option>
                                        <option value="secondary">Abu-abu (Secondary)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="rute_deskripsi">Deskripsi Petunjuk Jalan</label>
                                    <textarea class="form-control" id="rute_deskripsi" name="deskripsi" rows="3" placeholder="Tuliskan petunjuk jalan singkat..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="rute_urutan">Urutan</label>
                                    <input type="number" class="form-control" id="rute_urutan" name="urutan" value="1" min="0" required />
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100">Tambah Rute</button>
                            </form>
                        </div>

                        <!-- Daftar Rute -->
                        <div class="col-md-8 ps-md-4 mt-4 mt-md-0">
                            <h6 class="fw-bold mb-3">Daftar Rute Perjalanan Saat Ini</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Urutan</th>
                                            <th>Jenis</th>
                                            <th>Badge</th>
                                            <th>Keterangan</th>
                                            <th>Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($wisata->ruteWisata as $r)
                                            <tr>
                                                <td>{{ $r->urutan }}</td>
                                                <td>
                                                    <i class="bx {{ $r->icon ?? 'bx-map-pin' }} me-1 text-primary"></i>
                                                    <strong>{{ $r->jenis_transportasi }}</strong>
                                                </td>
                                                <td><span class="badge bg-{{ $r->warna_badge }}">{{ $r->warna_badge }}</span></td>
                                                <td class="text-wrap" style="max-width: 250px;">{{ $r->deskripsi }}</td>
                                                <td>
                                                    <form action="{{ route('admin.rute-wisata.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Hapus rute ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-outline-danger p-1"><i class="bx bx-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-3 text-muted">Belum ada rute terdaftar.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 5: Galeri Foto Wisata --}}
                <div class="tab-pane fade" id="navs-galeri" role="tabpanel">
                    <div class="row">
                        <!-- Tambah Foto -->
                        <div class="col-md-4 border-end pe-md-4">
                            <h6 class="fw-bold mb-3"><i class="bx bx-plus me-1 text-primary"></i> Tambah Foto Galeri</h6>
                            <form action="{{ route('admin.galeri-wisata.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="wisata_id" value="{{ $wisata->id }}" />

                                <div class="mb-3">
                                    <label class="form-label" for="galeri_gambar">Pilih Foto <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="galeri_gambar" name="gambar" accept="image/*" required />
                                    <div class="form-text">Maksimal 2MB.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="galeri_caption">Caption Foto (Opsional)</label>
                                    <input type="text" class="form-control" id="galeri_caption" name="caption" placeholder="Teks keterangan foto..." />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="galeri_urutan">Urutan</label>
                                    <input type="number" class="form-control" id="galeri_urutan" name="urutan" value="1" min="0" required />
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100">Unggah Foto</button>
                            </form>
                        </div>

                        <!-- Daftar Foto -->
                        <div class="col-md-8 ps-md-4 mt-4 mt-md-0">
                            <h6 class="fw-bold mb-3">Daftar Galeri Tambahan Saat Ini</h6>
                            <div class="row g-3">
                                @forelse($wisata->galeriWisata as $g)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card h-100 border shadow-none">
                                            <img src="{{ asset('storage/' . $g->gambar) }}" class="card-img-top" alt="Galeri" style="height: 120px; object-fit: cover;" />
                                            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                <small class="text-muted text-truncate" style="max-width: 100px;">{{ $g->caption ?? 'Tanpa caption' }}</small>
                                                <form action="{{ route('admin.galeri-wisata.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-outline-danger p-1"><i class="bx bx-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-4 text-muted">Belum ada foto galeri tambahan.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 6: Info Penginapan --}}
                <div class="tab-pane fade" id="navs-penginapan" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3"><i class="bx bx-plus-circle me-1 text-primary"></i> Tambah Info Penginapan / Homestay</h6>
                                    <form action="{{ route('admin.penginapan-wisata.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="wisata_id" value="{{ $wisata->id }}">

                                        <div class="mb-3">
                                            <label class="form-label" for="nama_penginapan">Nama Penginapan <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama_penginapan" name="nama_penginapan" placeholder="Contoh: Homestay Asri Ibu Adri" required />
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="jenis">Jenis Penginapan</label>
                                                <select class="form-select" id="jenis" name="jenis">
                                                    <option value="Homestay">Homestay</option>
                                                    <option value="Villa">Villa</option>
                                                    <option value="Hotel">Hotel</option>
                                                    <option value="Guesthouse">Guesthouse</option>
                                                    <option value="Pondok Wisata">Pondok Wisata</option>
                                                    <option value="Camping Ground">Camping Ground</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="urutan_penginapan">Urutan Tampil</label>
                                                <input type="number" class="form-control" id="urutan_penginapan" name="urutan" value="0" min="0" />
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="kisaran_harga">Kisaran Harga / Malam</label>
                                            <input type="text" class="form-control" id="kisaran_harga" name="kisaran_harga" placeholder="Contoh: Rp 150.000 - Rp 300.000" />
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="jarak">Jarak dari Wisata</label>
                                                <input type="text" class="form-control" id="jarak" name="jarak" placeholder="Contoh: 200 meter" />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="no_telepon">No. WhatsApp</label>
                                                <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder="Contoh: 08123456789" />
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="fasilitas_singkat">Fasilitas Singkat</label>
                                            <input type="text" class="form-control" id="fasilitas_singkat" name="fasilitas_singkat" placeholder="Contoh: AC, Wi-Fi, Sarapan Pagi, Kamar Mandi Dalam" />
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="foto_penginapan">Foto Penginapan</label>
                                            <input type="file" class="form-control" id="foto_penginapan" name="foto" accept="image/*" />
                                            <div class="form-text"><strong>Wajib orientasi mendatar (Landscape).</strong> Rekomendasi: 1200x800 px. Minimal: 400x250 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-save me-1"></i> Simpan Penginapan</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <h6 class="fw-bold mb-3"><i class="bx bx-home-heart me-1 text-primary"></i> Daftar Penginapan di Destinasi Ini</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Foto</th>
                                            <th>Info Penginapan</th>
                                            <th>Harga & Jarak</th>
                                            <th>WhatsApp</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($wisata->penginapanWisata->sortBy('urutan') as $p)
                                            <tr>
                                                <td style="width: 80px;">
                                                    @if($p->foto)
                                                        <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto" class="rounded" style="width: 70px; height: 45px; object-fit: cover;" />
                                                    @else
                                                        <span class="badge bg-secondary">No Pic</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ $p->nama_penginapan }}</div>
                                                    <span class="badge bg-label-primary">{{ $p->jenis ?? 'Homestay' }}</span>
                                                    @if($p->fasilitas_singkat)
                                                        <div class="text-muted small mt-1"><i class="bx bx-check-circle text-success"></i> {{ $p->fasilitas_singkat }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="text-success fw-semibold small">{{ $p->kisaran_harga ?? '-' }}</div>
                                                    @if($p->jarak)
                                                        <div class="text-muted small"><i class="bx bx-map-pin"></i> {{ $p->jarak }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($p->no_telepon)
                                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $p->no_telepon)) }}" target="_blank" class="btn btn-xs btn-outline-success">
                                                            <i class="bx bxl-whatsapp me-1"></i> WA
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('admin.penginapan-wisata.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus info penginapan ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-danger"><i class="bx bx-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-3 text-muted">Belum ada data penginapan / homestay untuk destinasi wisata ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 7: Ulasan Pengunjung --}}
                <div class="tab-pane fade" id="navs-ulasan" role="tabpanel">
                    <h6 class="fw-bold mb-3"><i class="bx bx-star me-1 text-primary"></i> Daftar Ulasan Masuk</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Pengirim</th>
                                    <th>Rating</th>
                                    <th>Isi Ulasan</th>
                                    <th>Status</th>
                                    <th>Aksi Moderasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wisata->ulasanWisata as $u)
                                    <tr>
                                        <td><strong>{{ $u->nama }}</strong></td>
                                        <td>
                                            <span class="text-warning">
                                                @for($i=1; $i<=$u->rating; $i++)★@endfor
                                                @for($i=$u->rating+1; $i<=5; $i++)☆@endfor
                                            </span>
                                        </td>
                                        <td class="text-wrap" style="max-width: 300px;">{{ $u->ulasan }}</td>
                                        <td>
                                            <span class="badge {{ $u->is_approved ? 'bg-success' : 'bg-warning' }}">
                                                {{ $u->is_approved ? 'Disetujui' : 'Butuh Moderasi' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ulasan-wisata.index') }}" class="btn btn-xs btn-outline-primary">
                                                Kelola di Menu Ulasan
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted">Belum ada ulasan yang masuk untuk destinasi ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.ck-editor__editable_inline {
    min-height: 350px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });

    // Handle tab activation on redirect
    document.addEventListener("DOMContentLoaded", function() {
        var hash = window.location.hash;
        if (hash) {
            var triggerEl = document.querySelector('.nav-tabs button[data-bs-target="' + hash.replace('#tab-', '#navs-') + '"]');
            if (triggerEl) {
                var tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }
        }
    });
</script>
@endpush
@endsection
