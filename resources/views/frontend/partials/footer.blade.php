@php
    $profil = $profilDesa ?? \App\Models\ProfilDesa::first();
    $categories = \App\Models\KategoriPengaduan::all();
    $publicPengaduans = \App\Models\Pengaduan::where('is_publik', true)
        ->where('created_at', '>=', now()->subMonth())
        ->orderBy('created_at', 'desc')
        ->get();
@endphp

<!-- TV News Ticker Bar (Pengaduan Warga Publik 1 Bulan Terakhir) -->
<div class="pengaduan-news-ticker-bar" style="background: #0f172a; border-top: 2px solid var(--gold, #e8c97a); border-bottom: 1px solid rgba(255, 255, 255, 0.1); position: relative; z-index: 20; overflow: hidden; box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.4);">
    <div class="d-flex align-items-center">
        <!-- Badge / Label Berita -->
        <div class="ticker-badge d-flex align-items-center px-3 px-md-4 py-2.5 flex-shrink-0" style="background: linear-gradient(135deg, #c53030, #e53e3e); color: #fff; font-weight: 800; font-size: 0.85rem; letter-spacing: 0.5px; box-shadow: 4px 0 12px rgba(0,0,0,0.3); z-index: 2;">
            <span class="pulsing-live-dot me-2" style="width: 8px; height: 8px; background: #fff; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px #fff; animation: pulseDot 1.2s infinite;"></span>
            <i class="fas fa-bullhorn me-2 d-none d-sm-inline"></i>
            <span>Aspirasi dan Aduan</span>
        </div>

        <!-- Marquee Track Wrapper -->
        <div class="ticker-wrapper flex-grow-1 overflow-hidden py-2" id="pengaduanTickerWrapper" style="white-space: nowrap; position: relative;">
            <div class="ticker-track d-inline-block" id="pengaduanTickerTrack">
                @if($publicPengaduans->isNotEmpty())
                    @foreach($publicPengaduans as $item)
                        <span class="ticker-item mx-4 d-inline-flex align-items-center" onclick="openAndTrackComplaint('{{ $item->no_tiket }}')" style="cursor: pointer; transition: color 0.2s;" title="Klik untuk melihat detail & tanggapan laporan ({{ $item->no_tiket }})">
                            <span class="text-white fw-semibold hover-gold" style="font-size: 0.92rem;">
                                {{ $item->judul }}
                            </span>
                            <span class="text-muted ms-2" style="font-size: 0.82rem;">
                                ({{ $item->created_at->translatedFormat('d M Y') }})
                            </span>
                        </span>
                        @if(!$loop->last)
                            <span class="ticker-separator text-warning mx-2" style="opacity: 0.6;">★</span>
                        @endif
                    @endforeach
                @else
                    <span class="ticker-item mx-4 text-light" style="font-size: 0.9rem;">
                        Belum ada laporan pengaduan publik yang masuk dalam 1 bulan terakhir. Warga dapat menyampaikan aspirasi dan pengaduan layanan desa melalui tombol <strong>Pengaduan Warga</strong> di pojok kanan bawah.
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

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
                    @if(isset($sosialMedias) && $sosialMedias->count() > 0)
                        @foreach($sosialMedias as $sm)
                            <a
                                href="{{ $sm->url }}"
                                class="social-icon"
                                aria-label="{{ $sm->platform }} Desa Kami"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="bx {{ $sm->icon }}"></i>
                            </a>
                        @endforeach
                    @endif
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
                <a
                    href="{{ url('/umkm') }}"
                    class="footer-link"
                >
                    UMKM
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
        
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs nav-fill mb-3" id="pengaduanTabs" role="tablist" style="border-bottom: 1px solid rgba(255, 255, 255, 0.15);">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold text-white border-0 bg-transparent py-2" id="buat-laporan-tab" data-bs-toggle="tab" data-bs-target="#buat-laporan-pane" type="button" role="tab" aria-controls="buat-laporan-pane" aria-selected="true" style="font-size: 0.85rem;">
                    Buat Laporan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-white border-0 bg-transparent py-2" id="lacak-laporan-tab" data-bs-toggle="tab" data-bs-target="#lacak-laporan-pane" type="button" role="tab" aria-controls="lacak-laporan-pane" aria-selected="false" style="font-size: 0.85rem;">
                    Lacak Laporan
                </button>
            </li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content" id="pengaduanTabsContent">
            
            <!-- Pane 1: Buat Laporan -->
            <div class="tab-pane fade show active" id="buat-laporan-pane" role="tabpanel" aria-labelledby="buat-laporan-tab">
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
                        <select name="kategori_pengaduan_id" id="kategori_pengaduan_id" class="form-select bg-dark text-white border-secondary" style="font-size: 0.9rem;">
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
                        <input type="text" name="nama_pelapor" id="nama_pelapor" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;" placeholder="Masukkan nama lengkap Anda">
                        <div class="invalid-feedback text-danger" id="err-nama_pelapor"></div>
                    </div>

                    <!-- NIK Pelapor -->
                    <div class="mb-3">
                        <label for="nik_pelapor" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">NIK (16 Digit) *</label>
                        <input type="text" name="nik_pelapor" id="nik_pelapor" maxlength="16" class="form-control bg-dark text-white border-secondary font-monospace" style="font-size: 0.9rem;" placeholder="16 digit NIK sesuai KTP" inputmode="numeric" autocomplete="off">
                        <div class="invalid-feedback text-danger" id="err-nik_pelapor"></div>
                    </div>

                    <!-- No Telepon -->
                    <div class="mb-3">
                        <label for="no_telepon" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">No. Telepon / WhatsApp *</label>
                        <input type="text" name="no_telepon" id="no_telepon" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;" placeholder="08xxxxxxxxxx" inputmode="numeric" autocomplete="off">
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
                        <textarea name="alamat" id="alamat" rows="2" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;" placeholder="Alamat rumah / lokasi kejadian"></textarea>
                        <div class="invalid-feedback text-danger" id="err-alamat"></div>
                    </div>

                    <!-- Judul Pengaduan -->
                    <div class="mb-3">
                        <label for="judul" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">Judul Pengaduan *</label>
                        <input type="text" name="judul" id="judul" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;" placeholder="Ringkasan singkat keluhan">
                        <div class="invalid-feedback text-danger" id="err-judul"></div>
                    </div>

                    <!-- Isi Laporan -->
                    <div class="mb-3">
                        <label for="isi_pengaduan" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">Detail Laporan Pengaduan *</label>
                        <textarea name="isi_pengaduan" id="isi_pengaduan" rows="4" class="form-control bg-dark text-white border-secondary" style="font-size: 0.9rem;" placeholder="Ceritakan kronologi atau keluhan Anda secara jelas"></textarea>
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

            <!-- Pane 2: Lacak Laporan -->
            <div class="tab-pane fade" id="lacak-laporan-pane" role="tabpanel" aria-labelledby="lacak-laporan-tab">
                <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; line-height: 1.6; margin-bottom: 1.2rem;">
                    Masukkan nomor tiket pengaduan Anda untuk memeriksa status tindak lanjut terkini dari Pemerintah Desa.
                </p>

                <!-- Tracking Form -->
                <div class="mb-4">
                    <label for="track_ticket_num" class="form-label" style="font-size: 0.82rem; color: #8dd4a0; font-weight: 600;">Nomor Tiket Laporan</label>
                    <div class="input-group">
                        <input type="text" id="track_ticket_num" class="form-control bg-dark text-white border-secondary font-monospace" placeholder="Contoh: ADU-2026-0001" style="font-size: 0.9rem;">
                        <button class="btn btn-outline-success" type="button" id="btnTrackReport" style="border-color: #8dd4a0; color: #8dd4a0; background: transparent;">
                            <i class="fas fa-search"></i> Lacak
                        </button>
                    </div>
                </div>

                <!-- Error Message -->
                <div id="trackError" class="alert alert-danger d-none" role="alert" style="background: rgba(132, 32, 32, 0.2); border: 1px solid #ff9494; color: #ff9494; font-size: 0.82rem; padding: 0.75rem;"></div>

                <!-- Result Container -->
                <div id="trackResult" class="d-none">
                    <div class="border rounded p-3 mb-4" style="background: rgba(255, 255, 255, 0.05); border-color: rgba(255, 255, 255, 0.1) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span id="trackKategori" class="badge bg-secondary" style="font-size: 0.75rem;"></span>
                            <span id="trackStatus" class="badge text-white" style="font-size: 0.75rem;"></span>
                        </div>
                        <h6 id="trackJudul" class="fw-bold text-warning mb-1" style="font-size: 1rem;"></h6>
                        <small id="trackTanggal" class="text-muted d-block mb-3" style="font-size: 0.75rem;"></small>
                        
                        <p id="trackIsi" class="mb-3 text-white-50" style="font-size: 0.85rem; white-space: pre-wrap; line-height: 1.5;"></p>
                        
                        <div id="trackLampiranContainer" class="d-none border-top pt-2 mt-2">
                            <a id="trackLampiranLink" href="#" target="_blank" class="text-success d-inline-flex align-items-center" style="font-size: 0.8rem; text-decoration: none;">
                                <i class="fas fa-image me-1"></i> Lihat Lampiran Laporan
                            </a>
                        </div>
                    </div>

                    <!-- Responses/Timeline -->
                    <h6 class="fw-bold text-white mb-3" style="font-size: 0.9rem;"><i class="fas fa-history me-1 text-info"></i> Tindak Lanjut & Tanggapan:</h6>
                    <div id="trackTanggapanList" class="ps-3 border-start border-secondary" style="border-width: 2px !important; border-color: rgba(255, 255, 255, 0.15) !important;">
                        <!-- Responses loaded here -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
#pengaduanTabs .nav-link {
    border-bottom: 2px solid transparent !important;
    border-radius: 0;
    transition: all 0.2s ease;
    opacity: 0.65;
}
#pengaduanTabs .nav-link.active {
    border-bottom: 2px solid #e8c97a !important;
    color: #e8c97a !important;
    opacity: 1;
}

@keyframes pulseDot {
    0% { transform: scale(0.95); opacity: 0.8; }
    50% { transform: scale(1.3); opacity: 1; box-shadow: 0 0 12px #fff; }
    100% { transform: scale(0.95); opacity: 0.8; }
}

@keyframes tickerScroll {
    0% { transform: translate3d(0, 0, 0); }
    100% { transform: translate3d(-50%, 0, 0); }
}

.hover-gold:hover {
    color: var(--gold, #e8c97a) !important;
    text-decoration: underline;
}

#pengaduanTickerWrapper:hover #pengaduanTickerTrack {
    animation-play-state: paused !important;
}
</style>

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
        
        // Reset tracking pane as well
        document.getElementById('track_ticket_num').value = '';
        document.getElementById('trackError').classList.add('d-none');
        document.getElementById('trackResult').classList.add('d-none');
    });

    // Tracking ticket logic
    const btnTrack = document.getElementById('btnTrackReport');
    const inputTicket = document.getElementById('track_ticket_num');
    const trackError = document.getElementById('trackError');
    const trackResult = document.getElementById('trackResult');

    btnTrack.addEventListener('click', function() {
        const ticketNum = inputTicket.value.trim();
        if (!ticketNum) {
            alert('Masukkan nomor tiket terlebih dahulu!');
            return;
        }

        btnTrack.disabled = true;
        btnTrack.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        trackError.classList.add('d-none');
        trackResult.classList.add('d-none');

        // Build route dynamically
        const url = "{{ route('pengaduan.track', ':ticket') }}".replace(':ticket', encodeURIComponent(ticketNum));

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            btnTrack.disabled = false;
            btnTrack.innerHTML = '<i class="fas fa-search"></i> Lacak';

            if (res.status === 404 || !res.body.success) {
                trackError.textContent = res.body.message || 'Nomor tiket tidak ditemukan.';
                trackError.classList.remove('d-none');
            } else if (res.status === 200 && res.body.success) {
                const complaint = res.body.data;
                
                // Populate elements
                document.getElementById('trackKategori').textContent = complaint.kategori;
                document.getElementById('trackJudul').textContent = complaint.judul;
                document.getElementById('trackTanggal').textContent = 'Dikirim: ' + complaint.tanggal;
                document.getElementById('trackIsi').textContent = complaint.isi_pengaduan;

                // Status Badge Color coding
                const statusBadge = document.getElementById('trackStatus');
                statusBadge.textContent = complaint.status.toUpperCase();
                statusBadge.className = 'badge'; // Reset classes
                if (complaint.status === 'masuk') {
                    statusBadge.classList.add('bg-info');
                } else if (complaint.status === 'diproses') {
                    statusBadge.classList.add('bg-warning', 'text-dark');
                } else if (complaint.status === 'selesai') {
                    statusBadge.classList.add('bg-success');
                } else if (complaint.status === 'ditolak') {
                    statusBadge.classList.add('bg-danger');
                }

                // Lampiran
                const lampContainer = document.getElementById('trackLampiranContainer');
                if (complaint.lampiran) {
                    document.getElementById('trackLampiranLink').href = complaint.lampiran;
                    lampContainer.classList.remove('d-none');
                } else {
                    lampContainer.classList.add('d-none');
                }

                // Populate Tanggapan List
                const tanggapanList = document.getElementById('trackTanggapanList');
                tanggapanList.innerHTML = '';
                
                if (complaint.tanggapan.length === 0) {
                    tanggapanList.innerHTML = `<div class="text-white-50 text-center py-2" style="font-size: 0.8rem; font-style: italic;">Laporan Anda belum ditanggapi oleh petugas.</div>`;
                } else {
                    complaint.tanggapan.forEach(t => {
                        const tItem = document.createElement('div');
                        tItem.className = 'mb-3 pb-2 border-bottom';
                        tItem.style.borderColor = 'rgba(255, 255, 255, 0.1)';
                        tItem.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-info" style="font-size: 0.85rem;"><i class="fas fa-user-shield me-1"></i>${t.petugas}</strong>
                                <small class="text-muted" style="font-size: 0.75rem;">${t.tanggal}</small>
                            </div>
                            <p class="mb-1 text-white-50" style="font-size: 0.85rem; white-space: pre-wrap; line-height: 1.4;">${t.isi_tanggapan}</p>
                            ${t.lampiran ? `
                                <div class="mt-1">
                                    <a href="${t.lampiran}" target="_blank" class="text-success d-inline-flex align-items-center" style="font-size: 0.78rem; text-decoration: none;">
                                        <i class="fas fa-paperclip me-1"></i> Lihat Lampiran Petugas
                                    </a>
                                </div>
                            ` : ''}
                        `;
                        tanggapanList.appendChild(tItem);
                    });
                }

                trackResult.classList.remove('d-none');
            } else {
                alert('Gagal mengambil data status laporan.');
            }
        })
        .catch(err => {
            btnTrack.disabled = false;
            btnTrack.innerHTML = '<i class="fas fa-search"></i> Lacak';
            alert('Terjadi kesalahan koneksi.');
        });
    });

    // Auto normalize input on blur (Capital Each Word & Clean Spaces)
    function toTitleCaseClean(str) {
        return str.replace(/\s+/g, ' ').trim().replace(/\w\S*/g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }

    function toCleanSpaces(str) {
        return str.replace(/\s+/g, ' ').trim();
    }

    const titleInputs = ['nama_pelapor', 'judul'];
    titleInputs.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('blur', function() {
                this.value = toTitleCaseClean(this.value);
            });
        }
    });

    const spaceInputs = ['email', 'alamat', 'isi_pengaduan', 'track_ticket_num'];
    spaceInputs.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('blur', function() {
                this.value = toCleanSpaces(this.value);
            });
        }
    });

    // ── Field: nik_pelapor & no_telepon → digit only, no space ─────────────
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

    const digitFields = document.querySelectorAll('#nik_pelapor, #no_telepon');
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

    // Inisialisasi TV News Ticker Marquee dengan kecepatan konstan seperti siaran TV berita
    function initNewsTicker() {
        const track = document.getElementById('pengaduanTickerTrack');
        const wrapper = document.getElementById('pengaduanTickerWrapper');
        if (!track || !wrapper) return;

        // Duplikasi konten agar animasi looping sempurna tanpa putus
        const originalContent = track.innerHTML;
        track.innerHTML = originalContent + '<span class="ticker-separator text-warning mx-2" style="opacity: 0.6;">★</span>' + originalContent;

        // Hitung lebar separuh track setelah diduplikasi
        const trackWidth = track.scrollWidth / 2;
        // Kecepatan standar siaran berita TV: 80 piksel per detik
        const speedPxPerSec = 80;
        const duration = Math.max(12, trackWidth / speedPxPerSec);

        track.style.animation = `tickerScroll ${duration}s linear infinite`;
    }

    initNewsTicker();
});

// Fungsi global saat warga mengklik salah satu judul pengaduan di TV News Ticker
window.openAndTrackComplaint = function(ticketNum) {
    if (!ticketNum) return;

    // Buka offcanvas pengaduan
    const offcanvasEl = document.getElementById('offcanvasPengaduan');
    if (offcanvasEl) {
        let bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
        bsOffcanvas.show();
    }

    // Pindah ke tab Lacak Status
    const tabEl = document.querySelector('#pengaduanTabs button[data-bs-target="#nav-lacak"]');
    if (tabEl && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
        const tab = bootstrap.Tab.getInstance(tabEl) || new bootstrap.Tab(tabEl);
        tab.show();
    } else if (tabEl) {
        tabEl.click();
    }

    // Isi nomor tiket dan picu pelacakan detail & tanggapan
    setTimeout(() => {
        const inputTicket = document.getElementById('track_ticket_num');
        const btnTrack = document.getElementById('btnTrackReport');
        if (inputTicket && btnTrack) {
            inputTicket.value = ticketNum;
            btnTrack.click();
        }
    }, 350);
};
</script>