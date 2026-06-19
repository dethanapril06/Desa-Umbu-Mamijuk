@extends('admin.layouts.app')

@section('title', 'Tambah UMKM Baru')

@section('content')
<div class="row">
    <div class="col-12 col-md-10 offset-md-1">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">UMKM /</span> Tambah UMKM Baru
        </h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Tambah UMKM</h5>
                <a href="{{ route('admin.umkm.index') }}" class="btn btn-sm btn-secondary">
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

                <form action="{{ route('admin.umkm.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="nama_usaha">Nama Usaha / Bisnis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_usaha" name="nama_usaha" value="{{ old('nama_usaha') }}" placeholder="Contoh: Kopi Asli Sumba" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="nama_pemilik">Nama Pemilik <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik" value="{{ old('nama_pemilik') }}" placeholder="Nama lengkap pemilik usaha" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="kategori">Kategori Usaha <span class="text-danger">*</span></label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Kuliner" {{ old('kategori') == 'Kuliner' ? 'selected' : '' }}>Kuliner (Makanan & Minuman)</option>
                                <option value="Fashion" {{ old('kategori') == 'Fashion' ? 'selected' : '' }}>Fashion (Pakaian, Tenun & Aksesoris)</option>
                                <option value="Kerajinan" {{ old('kategori') == 'Kerajinan' ? 'selected' : '' }}>Kerajinan Tangan / Kesenian</option>
                                <option value="Pertanian" {{ old('kategori') == 'Pertanian' ? 'selected' : '' }}>Pertanian / Peternakan</option>
                                <option value="Jasa" {{ old('kategori') == 'Jasa' ? 'selected' : '' }}>Jasa (Bengkel, Salon, dll)</option>
                                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="jam_operasional">Jam Operasional</label>
                            <input type="text" class="form-control" id="jam_operasional" name="jam_operasional" value="{{ old('jam_operasional') }}" placeholder="Contoh: 08.00 - 18.00 WITA" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="no_telepon">No. Telepon / WhatsApp</label>
                            <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 08123456789" />
                            <div class="form-text">Gunakan format angka tanpa spasi/simbol untuk integrasi chat instan WhatsApp.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="email">Alamat Email (Opsional)</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Contoh: info@usaha.com" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="status">Status Keaktifan <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="website_url">Link Marketplace / Sosial Media Bisnis (Opsional)</label>
                        <input type="url" class="form-control" id="website_url" name="website_url" value="{{ old('website_url') }}" placeholder="Contoh: https://shopee.co.id/toko-saya atau link instagram" />
                        <div class="form-text">Pastikan diawali dengan <code>http://</code> atau <code>https://</code></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="alamat">Alamat Fisik Usaha <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap lokasi usaha..." required>{{ old('alamat') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="deskripsi">Deskripsi Usaha / Produk / Layanan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" placeholder="Tuliskan profil usaha Anda secara detail, produk utama, kelebihan usaha, dll..." required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="foto">Foto Cover / Logo Usaha / Produk</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewImage(this)" />
                        <div class="form-text">Format: jpeg, png, jpg. Maksimal 2MB.</div>
                        <div class="mt-3">
                            <img id="image-preview" src="#" alt="Preview Foto" class="img-fluid rounded" style="max-height: 200px; display: none;" />
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-save me-1"></i> Simpan Data UMKM
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    var preview = document.getElementById('image-preview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
}
</script>
@endsection
