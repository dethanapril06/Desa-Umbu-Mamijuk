@php
    $profil = $profilDesa ?? \App\Models\ProfilDesa::first();
    $categories = \App\Models\KategoriPengaduan::all();
@endphp

<footer class="footer-desa">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-3 mb-1">
                    <div class="brand-logo" style="overflow: hidden; display: flex; align-items: center; justify-content: center;">
                        <img
                            src="{{ $profil?->logo ? asset('storage/' . $profil->logo) : asset('fe/assets/img/logo-desa.png') }}"
                            alt="Logo {{ $profil?->nama_desa }}"
                            style="width: 100%; height: 100%; object-fit: contain; padding: 4px;"
                        >
                    </div>

                    <div class="footer-brand">
                        Desa {{ $profil?->nama_desa }}
                    </div>
                </div>

                <p class="footer-desc mt-3">
                    Portal resmi {{ $profil?->nama_desa }}, Kecamatan {{ $profil?->kecamatan }},
                    {{ $profil?->kabupaten }}. Melayani masyarakat dengan
                    transparan, akuntabel, dan profesional.
                </p>

                <div class="social-icons">
                    <a
                        href="#"
                        class="social-icon"
                        aria-label="Facebook Desa Kami"
                    >
                        <i class="fab fa-facebook-f"></i>
                    </a>

                    <a
                        href="#"
                        class="social-icon"
                        aria-label="Instagram Desa Kami"
                    >
                        <i class="fab fa-instagram"></i>
                    </a>

                    <a
                        href="#"
                        class="social-icon"
                        aria-label="YouTube Desa Kami"
                    >
                        <i class="fab fa-youtube"></i>
                    </a>

                    <a
                        href="#"
                        class="social-icon"
                        aria-label="WhatsApp Desa Kami"
                    >
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="footer-title">
                    Navigasi
                </div>

                <a
                    href="{{ url('/') }}"
                    class="footer-link"
                >
                    Beranda
                </a>

                <a
                    href="{{ url('/') }}#profil"
                    class="footer-link"
                >
                    Profil Desa
                </a>

                <a
                    href="{{ url('/') }}#kependudukan"
                    class="footer-link"
                >
                    Kependudukan
                </a>

                <a
                    href="{{ url('/wisata') }}"
                    class="footer-link"
                >
                    Wisata
                </a>

                <a
                    href="{{ url('/berita') }}"
                    class="footer-link"
                >
                    Berita
                </a>

                <a
                    href="{{ url('/galeri') }}"
                    class="footer-link"
                >
                    Galeri
                </a>
            </div>

            <div class="col-lg-4">
                <div class="footer-title">
                    Kontak
                </div>

                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>

                    <span>
                        {{ $profil?->alamat_lengkap }}
                    </span>
                </div>

                <div class="footer-contact-item">
                    <i class="fas fa-phone"></i>

                    <span>
                        {{ $profil?->telepon }}
                    </span>
                </div>

                <div class="footer-contact-item">
                    <i class="fas fa-envelope"></i>

                    <span>
                        {{ $profil?->email }}
                    </span>
                </div>

                <div class="footer-contact-item">
                    <i class="fas fa-clock"></i>

                    <span>
                        {{ $profil?->jam_pelayanan }}
                    </span>
                </div>
            </div>
        </div>

        <hr class="footer-divider" />

        <div class="footer-bottom">
            © {{ date('Y') }} Pemerintah {{ $profil?->nama_desa }} ·
            Kecamatan {{ $profil?->kecamatan }}, {{ $profil?->kabupaten }} ·
            Dibuat dengan ❤ untuk masyarakat desa
        </div>
    </div>
</footer>

<!-- Sticky Pengaduan CTA -->
<a
    href="javascript:void(0);"
    class="sticky-wisata-cta"
    data-bs-toggle="offcanvas"
    data-bs-target="#offcanvasPengaduan"
    aria-controls="offcanvasPengaduan"
    style="background: linear-gradient(135deg, #ffa8a8, #ff9494); color: #401010; box-shadow: 0 10px 30px rgba(255, 168, 168, 0.35);"
>
    <i class="fas fa-bullhorn animate-pulse"></i>
    Pengaduan
</a>

<!-- Offcanvas Pengaduan -->
<div 
    class="offcanvas offcanvas-end" 
    tabindex="-1" 
    id="offcanvasPengaduan" 
    aria-labelledby="offcanvasPengaduanLabel"
    style="background: #1a3a2a; border-left: 1px solid rgba(82, 169, 110, 0.3); color: white; width: 450px; max-width: 100%; z-index: 1055;"
>
    <div class="offcanvas-header" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding: 1.5rem;">
        <h5 class="offcanvas-title" id="offcanvasPengaduanLabel" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #e8c97a;">
            <i class="fas fa-bullhorn me-2 text-danger"></i> Formulir Pengaduan
        </h5>
        <button 
            type="button" 
            class="btn-close btn-close-white" 
            data-bs-dismiss="offcanvas" 
            aria-label="Close"
        ></button>
    </div>
    
    <div class="offcanvas-body" style="padding: 1.5rem; overflow-y: auto;">
        <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; line-height: 1.6; margin-bottom: 1.5rem;">
            Silakan lengkapi formulir di bawah ini untuk mengirim pengaduan atau keluhan Anda. Tim admin kami akan meninjau dan menindaklanjuti laporan Anda segera.
        </p>

        <!-- Dynamic Success Message Alert -->
        <div id="pengaduanAlertSuccess" class="alert alert-success d-none" role="alert" style="background: rgba(46, 117, 81, 0.2); border: 1px solid var(--green-fresh); color: #8dd4a0;">
            <div class="text-center py-2">
                <i class="fas fa-check-circle fa-2x mb-2 text-success"></i> 
                <h6 class="fw-bold mb-1" style="color: #8dd4a0;">Laporan Berhasil Terkirim!</h6>
            </div>
            <div class="mt-2 pt-2 border-top border-success-subtle">
                <strong>Nomor Tiket Anda:</strong> 
                <div class="text-center my-2">
                    <span id="successTicketNum" class="badge bg-success font-monospace" style="font-size: 1.1rem; padding: 0.5rem 1rem;"></span>
                </div>
                <p class="mb-0 mt-1 text-center" style="font-size: 0.78rem; opacity: 0.9;">Simpan nomor tiket ini untuk melacak status laporan Anda di kemudian hari.</p>
            </div>
        </div>

        <form id="formPengaduan" enctype="multipart/form-data">
            @csrf
            
            <!-- Kategori -->
            <div class="mb-3">
                <label for="kategori_pengaduan_id" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">Kategori Pengaduan *</label>
                <select name="kategori_pengaduan_id" id="kategori_pengaduan_id" class="form-select bg-dark text-white border-secondary" style="font-size: 0.9rem;" required>
                    <option value="" disabled selected>Pilih Kategori...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback text-danger" id="err-kategori_pengaduan_id"></div>
            </div>

            <!-- Nama Pelapor -->
            <div class="mb-3">
                <label for="nama_pelapor" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">Nama Lengkap Pelapor *</label>
                <input type="text" name="nama_pelapor" id="nama_pelapor" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;" placeholder="Masukkan nama lengkap Anda" required>
                <div class="invalid-feedback text-danger" id="err-nama_pelapor"></div>
            </div>

            <!-- NIK Pelapor -->
            <div class="mb-3">
                <label for="nik_pelapor" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">NIK (16 Digit) *</label>
                <input type="text" name="nik_pelapor" id="nik_pelapor" maxlength="16" class="form-control bg-dark text-white border-secondary font-monospace" style="font-size: 0.9rem;" placeholder="16 digit NIK sesuai KTP" required>
                <div class="invalid-feedback text-danger" id="err-nik_pelapor"></div>
            </div>

            <!-- No Telepon -->
            <div class="mb-3">
                <label for="no_telepon" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">No. Telepon / WhatsApp *</label>
                <input type="text" name="no_telepon" id="no_telepon" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;" placeholder="08xxxxxxxxxx" required>
                <div class="invalid-feedback text-danger" id="err-no_telepon"></div>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">Email (Opsional)</label>
                <input type="email" name="email" id="email" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;" placeholder="nama@email.com">
                <div class="invalid-feedback text-danger" id="err-email"></div>
            </div>

            <!-- Alamat -->
            <div class="mb-3">
                <label for="alamat" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">Alamat Lengkap *</label>
                <textarea name="alamat" id="alamat" rows="2" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;" placeholder="Alamat rumah / lokasi kejadian" required></textarea>
                <div class="invalid-feedback text-danger" id="err-alamat"></div>
            </div>

            <!-- Judul Pengaduan -->
            <div class="mb-3">
                <label for="judul" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">Judul Pengaduan *</label>
                <input type="text" name="judul" id="judul" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;" placeholder="Ringkasan singkat keluhan" required>
                <div class="invalid-feedback text-danger" id="err-judul"></div>
            </div>

            <!-- Isi Laporan -->
            <div class="mb-3">
                <label for="isi_pengaduan" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">Detail Laporan Pengaduan *</label>
                <textarea name="isi_pengaduan" id="isi_pengaduan" rows="4" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;" placeholder="Ceritakan kronologi atau keluhan Anda secara jelas" required></textarea>
                <div class="invalid-feedback text-danger" id="err-isi_pengaduan"></div>
            </div>

            <!-- Lampiran Gambar -->
            <div class="mb-3">
                <label for="lampiran" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">Foto Lampiran / Bukti (Maks. 2MB)</label>
                <input type="file" name="lampiran" id="lampiran" accept="image/*" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;">
                <div class="invalid-feedback text-danger" id="err-lampiran"></div>
            </div>

            <button type="submit" id="btnSubmitPengaduan" class="btn w-100 py-2.5 fw-bold" style="background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--green-deep); border: none; font-size: 0.95rem; border-radius: 8px;">
                <i class="fas fa-paper-plane me-1"></i> Kirim Laporan Pengaduan
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formPengaduan');
    const successAlert = document.getElementById('pengaduanAlertSuccess');
    const btnSubmit = document.getElementById('btnSubmitPengaduan');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset validation errors
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        document.querySelectorAll('.form-control, .form-select').forEach(el => el.classList.remove('is-invalid'));
        
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';

        const formData = new FormData(form);

        fetch("{{ route('pengaduan.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Kirim Laporan Pengaduan';

            if (res.status === 422) {
                // Validation errors
                const errors = res.body.errors;
                for (const key in errors) {
                    const inputEl = document.getElementById(key);
                    const errorEl = document.getElementById('err-' + key);
                    if (inputEl) {
                        inputEl.classList.add('is-invalid');
                    }
                    if (errorEl) {
                        errorEl.textContent = errors[key][0];
                    }
                }
            } else if (res.status === 200 && res.body.success) {
                // Success submit
                form.reset();
                form.classList.add('d-none');
                
                document.getElementById('successTicketNum').textContent = res.body.no_tiket;
                successAlert.classList.remove('d-none');
                successAlert.scrollIntoView({ behavior: 'smooth' });
            } else {
                alert('Terjadi kesalahan sistem, silakan coba beberapa saat lagi.');
            }
        })
        .catch(err => {
            console.error('Error submitting form:', err);
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Kirim Laporan Pengaduan';
            alert('Gagal mengirim pengaduan. Periksa koneksi internet Anda.');
        });
    });

    // Reset form state on offcanvas hide/show
    const myOffcanvas = document.getElementById('offcanvasPengaduan');
    myOffcanvas.addEventListener('hidden.bs.offcanvas', function () {
        form.reset();
        form.classList.remove('d-none');
        successAlert.classList.add('d-none');
        document.querySelectorAll('.form-control, .form-select').forEach(el => el.classList.remove('is-invalid'));
    });
});
</script>