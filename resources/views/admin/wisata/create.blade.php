@extends('admin.layouts.app')

@section('title', 'Tambah Destinasi Wisata')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Wisata /</span> Tambah Destinasi Wisata
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah Destinasi</h5>
                <a href="{{ route('admin.wisata.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.wisata.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <!-- Nama Wisata -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label" for="nama">Nama Destinasi Wisata <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Tuliskan nama tempat wisata..." required />
                            </div>
                        </div>

                        <!-- Kategori Wisata -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="kategori_wisata_id">Kategori Wisata <span class="text-danger">*</span></label>
                                <select class="form-select" id="kategori_wisata_id" name="kategori_wisata_id" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('kategori_wisata_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Gambar Cover Utama -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="gambar_utama">Gambar Utama (Cover Banner) <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="gambar_utama" name="gambar_utama" accept="image/*" required />
                                <div class="form-text"><strong>Wajib orientasi mendatar (Landscape).</strong> Rekomendasi: 1200x800 px (rasio 3:2 / 16:9). Minimal: 400x250 px. Format: jpeg, png, jpg, webp. Maksimal 2MB.</div>
                            </div>
                        </div>

                        <!-- Telepon / Kontak Pengelola -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="telepon">No. Telepon / Pengelola</label>
                                <input type="text" class="form-control" id="telepon" name="telepon" value="{{ old('telepon') }}" placeholder="Contoh: 08xxxxxxxxxx" />
                            </div>
                        </div>

                        <!-- Jarak dari Pusat Desa & Waktu Trekking -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="jarak_dari_desa">Jarak dari Kantor Desa</label>
                                <input type="text" class="form-control" id="jarak_dari_desa" name="jarak_dari_desa" value="{{ old('jarak_dari_desa') }}" placeholder="Contoh: 5 km atau 15 menit berkendara" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="durasi_trek">Durasi Trekking / Kunjungan</label>
                                <input type="text" class="form-control" id="durasi_trek" name="durasi_trek" value="{{ old('durasi_trek') }}" placeholder="Contoh: 30 menit jalan kaki, atau 2-3 jam" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="cocok_untuk">Cocok Untuk</label>
                                <input type="text" class="form-control" id="cocok_untuk" name="cocok_untuk" value="{{ old('cocok_untuk') }}" placeholder="Contoh: Keluarga, Petualang, Fotografi" />
                            </div>
                        </div>

                        <!-- Hari Buka & Jam Operasional -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="hari_buka">Hari Operasional</label>
                                <input type="text" class="form-control" id="hari_buka" name="hari_buka" value="{{ old('hari_buka') }}" placeholder="Contoh: Setiap Hari, Senin–Jumat" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="jam_operasional">Jam Operasional</label>
                                <input type="text" class="form-control" id="jam_operasional" name="jam_operasional" value="{{ old('jam_operasional') }}" placeholder="Contoh: 08:00 - 17:00 WITA, atau 24 Jam" />
                            </div>
                        </div>

                        <!-- Harga Tiket Masuk & Parkir -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="harga_tiket">Harga Tiket Masuk (IDR) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="harga_tiket" name="harga_tiket" value="{{ old('harga_tiket', 0) }}" min="0" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="harga_parkir_motor">Tarif Parkir Motor</label>
                                <input type="text" class="form-control" id="harga_parkir_motor" name="harga_parkir_motor" value="{{ old('harga_parkir_motor') }}" placeholder="Contoh: Rp 2.000 atau Gratis" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="harga_parkir_mobil">Tarif Parkir Mobil</label>
                                <input type="text" class="form-control" id="harga_parkir_mobil" name="harga_parkir_mobil" value="{{ old('harga_parkir_mobil') }}" placeholder="Contoh: Rp 5.000 atau Gratis" />
                            </div>
                        </div>

                        <!-- Google Maps Embed URL -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="google_maps_embed_url">Google Maps Embed URL</label>
                                <input type="text" class="form-control" id="google_maps_embed_url" name="google_maps_embed_url" value="{{ old('google_maps_embed_url') }}" placeholder="Link dari menu Share > Embed a map (src link saja)" />
                            </div>
                        </div>

                        <!-- Deskripsi Singkat & Highlight Quote -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="deskripsi_singkat">Ringkasan Singkat Destinasi</label>
                                <textarea class="form-control" id="deskripsi_singkat" name="deskripsi_singkat" rows="3" placeholder="Tuliskan rangkuman pendek destinasi wisata untuk kartu katalog depan...">{{ old('deskripsi_singkat') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="highlight_quote">Highlight Quote / Tagline Utama</label>
                                <textarea class="form-control" id="highlight_quote" name="highlight_quote" rows="3" placeholder="Teks sorotan yang ditaruh di tengah artikel pariwisata...">{{ old('highlight_quote') }}</textarea>
                            </div>
                        </div>

                        <!-- Deskripsi Lengkap (WYSIWYG) -->
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="editor">Deskripsi Lengkap Wisata</label>
                                <textarea id="editor" name="deskripsi">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>

                        <!-- Status Unggulan & Publikasi -->
                        <div class="col-12 border-top pt-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="is_unggulan" name="is_unggulan" value="1" {{ old('is_unggulan') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="is_unggulan">Jadikan Destinasi Wisata Unggulan (Featured)</label>
                                <div class="form-text">Wisata unggulan akan diposisikan paling atas di beranda depan.</div>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', 1) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="is_published">Langsung Publikasikan Wisata</label>
                                <div class="form-text">Jika tidak aktif, destinasi ini hanya akan disimpan sebagai Draft.</div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3">
                        <i class="bx bx-save me-1"></i> Simpan Destinasi & Lanjutkan Pengisian
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.ck-editor__editable_inline {
    min-height: 250px;
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

    document.addEventListener('DOMContentLoaded', function() {
        function toCapitalEachWord(str) {
            return str.toLowerCase().replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
        }

        const titleInputs = document.querySelectorAll('#nama, #cocok_untuk');
        titleInputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value) return;
                let cleaned = this.value.trim().replace(/\s+/g, ' ');
                this.value = toCapitalEachWord(cleaned);
            });
        });

        const stringInputs = document.querySelectorAll('#harga_parkir_motor, #harga_parkir_mobil, #jam_operasional, #hari_buka, #jarak_dari_desa, #durasi_trek, #telepon, #google_maps_embed_url, #deskripsi_singkat, #highlight_quote');
        stringInputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value) return;
                this.value = this.value.trim().replace(/\s+/g, ' ');
            });
        });
    });
</script>
@endpush
@endsection
