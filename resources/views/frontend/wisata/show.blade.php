@extends('frontend.layouts.app')

@section('title', $wisata->nama . ' — Wisata')

@section('meta_description', $wisata->deskripsi_singkat ?? Str::limit(strip_tags($wisata->deskripsi), 160))

@section('content')
    {{-- HERO --}}
    <section class="hero-wisata">
        <div class="hero-bg" id="heroBg" style="background-image: url('{{ asset('storage/' . $wisata->gambar_utama) }}');">
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="container">
                {{-- Breadcrumb --}}
                <div class="breadcrumb-custom">
                    <a href="{{ url('/') }}">Beranda</a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="{{ url('/wisata') }}">Wisata</a>
                    <i class="fas fa-chevron-right"></i>
                    <span style="color:rgba(255,255,255,0.9)">{{ $wisata->nama }}</span>
                </div>

                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <div class="hero-category">
                            <i class="{{ $wisata->kategoriWisata?->icon ?? 'fas fa-mountain' }}"></i>
                            {{ $wisata->kategoriWisata?->nama ?? 'Wisata' }}
                        </div>
                        <h1 class="hero-title-wisata">
                            {{ $wisata->nama }}
                        </h1>
                        <div class="hero-meta-row">
                            @if ($wisata->jarak_dari_desa)
                                <div class="hero-meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $wisata->jarak_dari_desa }} dari Pusat Desa
                                </div>
                            @endif
                            @if ($wisata->jam_operasional)
                                <div class="hero-meta-item">
                                    <i class="fas fa-clock"></i>
                                    Buka {{ $wisata->jam_operasional }}
                                </div>
                            @endif
                            @if ($wisata->hari_buka)
                                <div class="hero-meta-item">
                                    <i class="fas fa-calendar"></i>
                                    {{ $wisata->hari_buka }}
                                </div>
                            @endif
                        </div>

                        @if ($totalUlasan > 0)
                            <div class="rating-big mb-4">
                                <div class="rating-num">{{ $avgRating }}</div>
                                <div>
                                    <div class="rating-stars-big">
                                        @for ($i = 1; $i <= 5; $i++)
                                            {{ $i <= round($avgRating) ? '★' : '☆' }}
                                        @endfor
                                    </div>
                                    <div class="rating-count">dari {{ number_format($totalUlasan) }} ulasan</div>
                                </div>
                            </div>
                        @endif

                        <div class="hero-actions-wisata">
                            <a href="#info" class="btn-gold">
                                <i class="fas fa-ticket-alt"></i> Info Tiket
                            </a>
                            @if ($wisata->galeriWisata->count() > 0)
                                <a href="#galeri" class="btn-outline-green"
                                    style="border-color:rgba(255,255,255,0.4);color:white;">
                                    <i class="fas fa-images"></i> Lihat Galeri
                                </a>
                            @endif
                            @if ($wisata->penginapanWisata->count() > 0)
                                <a href="#penginapan" class="btn-outline-green"
                                    style="border-color:rgba(255,255,255,0.4);color:white;">
                                    <i class="fas fa-home"></i> Penginapan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <div class="container py-5" style="padding-top: 60px !important;">
        <div class="row g-5">

            {{-- LEFT: Main Content --}}
            <div class="col-lg-8">

                {{-- DESKRIPSI --}}
                <section class="mb-5 reveal">
                    <div class="section-label">Tentang Wisata</div>
                    <h2 class="section-title mb-4">
                        @if ($wisata->deskripsi_singkat)
                            {{ $wisata->deskripsi_singkat }}
                        @else
                            Tentang <em>{{ $wisata->nama }}</em>
                        @endif
                    </h2>
                    <div class="desc-content">
                        {!! nl2br(e($wisata->deskripsi)) !!}

                        @if ($wisata->highlight_quote)
                            <div class="desc-highlight">
                                "{{ $wisata->highlight_quote }}"
                            </div>
                        @endif
                    </div>
                </section>

                {{-- GALERI --}}
                @if ($wisata->galeriWisata->count() > 0)
                    <section class="mb-5 reveal" id="galeri">
                        <div class="section-label">Galeri Foto</div>
                        <h2 class="section-title mb-4">Pesona <em>Visual</em></h2>
                        <div class="gallery-grid">
                            @foreach ($wisata->galeriWisata->take(5) as $index => $foto)
                                @php
                                    $imgUrl = asset('storage/' . $foto->gambar);
                                    $class = $index === 0 ? 'tall' : '';
                                @endphp

                                @if ($index === 4 && $wisata->galeriWisata->count() > 5)
                                    <div class="gallery-item" style="position:relative;"
                                        onclick='openLightbox(@json($imgUrl), @json($foto->caption ?? $wisata->nama), @json('Galeri Wisata'))'>
                                        <img src="{{ $imgUrl }}" alt="{{ $foto->caption ?? $wisata->nama }}" />
                                        <div class="gallery-overlay gallery-count-overlay" style="opacity:1;">
                                            <div style="text-align:center;">
                                                <div style="font-size:2rem;font-weight:900;">
                                                    +{{ $wisata->galeriWisata->count() - 5 }}</div>
                                                <div style="font-size:0.8rem;opacity:0.8;">foto lainnya</div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="gallery-item {{ $class }}"
                                        onclick='openLightbox(@json($imgUrl), @json($foto->caption ?? $wisata->nama), @json('Galeri Wisata'))'>
                                        <img src="{{ $imgUrl }}" alt="{{ $foto->caption ?? $wisata->nama }}" />
                                        <div class="gallery-overlay"><i class="fas fa-expand-alt fa-lg"></i></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- FASILITAS --}}
                @if ($wisata->fasilitasWisata->count() > 0)
                    <section class="mb-5 reveal">
                        <div class="section-label">Fasilitas</div>
                        <h2 class="section-title mb-4">Tersedia di <em>Lokasi</em></h2>
                        <div class="fasilitas-grid">
                            @foreach ($wisata->fasilitasWisata as $fasilitas)
                                <div class="fasilitas-item">
                                    <span class="fasilitas-icon"><i
                                            class="bx {{ $fasilitas->icon ?? 'bi bi-check-circle' }}"></i></span>
                                    <div class="fasilitas-label">{{ $fasilitas->nama }}</div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- TIPS --}}
                @if ($wisata->tipsWisata->count() > 0)
                    <section class="mb-5 reveal">
                        <div class="section-label">Tips Berkunjung</div>
                        <h2 class="section-title mb-4">Panduan untuk <em>Wisatawan</em></h2>
                        <div class="row g-3">
                            @foreach ($wisata->tipsWisata as $index => $tip)
                                <div class="col-md-6">
                                    <div class="tips-card">
                                        <div class="d-flex gap-3">
                                            <div class="tips-num">{{ $index + 1 }}</div>
                                            <div>
                                                <div class="tips-title">{{ $tip->judul }}</div>
                                                <div class="tips-desc">{{ $tip->deskripsi }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- LOKASI & RUTE --}}
                <section class="mb-5 reveal">
                    <div class="section-label">Lokasi & Rute</div>
                    <h2 class="section-title mb-4">Cara <em>Menuju</em> Lokasi</h2>

                    @if ($wisata->google_maps_embed_url || ($wisata->koordinat_lat && $wisata->koordinat_lng))
                        @php
                            $mapUrl = $wisata->google_maps_embed_url;

                            if (! $mapUrl) {
                                $lat = (float) $wisata->koordinat_lat;
                                $lng = (float) $wisata->koordinat_lng;
                                $mapUrl = "https://www.google.com/maps?q={$lat},{$lng}&hl=id&z=15&output=embed";
                            }
                        @endphp
                        <div class="peta-desa-frame mb-4" style="border-radius:18px; min-height:360px;">
                            <iframe src="{{ $mapUrl }}" title="Peta dan rute menuju {{ $wisata->nama }}"
                                style="width:100%; height:360px; border:none; display:block;" allowfullscreen
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    @else
                        <div class="map-placeholder mb-4">
                            <i class="fas fa-map-marked-alt"></i>
                            <p>Peta — {{ $wisata->nama }}</p>
                        </div>
                    @endif

                    @if ($wisata->ruteWisata->count() > 0)
                        <div class="row g-3">
                            @foreach ($wisata->ruteWisata as $rute)
                                <div class="col-md-4">
                                    <div class="tips-card">
                                        <div class="d-flex gap-3 align-items-start">
                                            <div class="tips-num"
                                                @if ($rute->warna_badge) style="background:{{ $rute->warna_badge }};" @endif>
                                                <i class="{{ $rute->icon ?? 'bi bi-car-front' }}"></i>
                                            </div>
                                            <div>
                                                <div class="tips-title">{{ $rute->jenis_transportasi }}</div>
                                                <div class="tips-desc">{{ $rute->deskripsi }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                {{-- PENGINAPAN & HOMESTAY TERDEKAT --}}
                @if ($wisata->penginapanWisata->count() > 0)
                    <section class="mb-5 reveal" id="penginapan">
                        <div class="section-label">Akomodasi & Homestay</div>
                        <h2 class="section-title mb-4">Penginapan Terdekat di <em>Destinasi</em></h2>
                        <div class="row g-4">
                            @foreach ($wisata->penginapanWisata as $penginapan)
                                @php
                                    $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $penginapan->no_telepon ?? ''));
                                    $pesanWa = urlencode("Halo {$penginapan->nama_penginapan}, saya melihat informasi penginapan Anda di website Desa Ibu Adri (Destinasi {$wisata->nama}). Saya ingin menanyakan ketersediaan kamar.");
                                @endphp
                                <div class="col-md-6">
                                    <div class="tips-card h-100 d-flex flex-column justify-content-between p-0" style="overflow: hidden; border-radius: 20px;">
                                        <div style="position: relative; height: 210px; overflow: hidden;">
                                            @if ($penginapan->foto)
                                                <img src="{{ asset('storage/' . $penginapan->foto) }}" alt="{{ $penginapan->nama_penginapan }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100" style="background: var(--green-mist); color: var(--green-mid);">
                                                    <i class="fas fa-home fa-3x"></i>
                                                </div>
                                            @endif
                                            @if ($penginapan->jenis)
                                                <span class="badge" style="position: absolute; top: 14px; left: 14px; background: var(--green-deep); color: var(--gold-light); font-weight: 600; font-size: 0.78rem; padding: 6px 14px; border-radius: 50px; border: 1px solid rgba(201, 168, 76, 0.4); box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                                                    <i class="fas fa-bed me-1" style="color: var(--gold-light);"></i> {{ $penginapan->jenis }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="p-4 d-flex flex-column justify-content-between flex-grow-1">
                                            <div>
                                                <h5 class="fw-bold mb-2" style="color: var(--green-deep); font-size: 1.15rem;">{{ $penginapan->nama_penginapan }}</h5>
                                                @if ($penginapan->kisaran_harga)
                                                    <div class="mb-2" style="color: var(--green-mid); font-weight: 700; font-size: 0.95rem;">
                                                        <i class="fas fa-tag me-1" style="color: var(--green-fresh);"></i> {{ $penginapan->kisaran_harga }}
                                                    </div>
                                                @endif
                                                @if ($penginapan->jarak)
                                                    <div class="small mb-2" style="color: var(--text-mid);">
                                                        <i class="fas fa-map-marker-alt me-1" style="color: var(--gold);"></i> {{ $penginapan->jarak }}
                                                    </div>
                                                @endif
                                                @if ($penginapan->fasilitas_singkat)
                                                    <div class="small mb-3" style="color: var(--text-mid); line-height: 1.5;">
                                                        <i class="fas fa-check-circle me-1" style="color: var(--green-fresh);"></i> {{ $penginapan->fasilitas_singkat }}
                                                    </div>
                                                @endif
                                            </div>
                                            @if ($waNumber)
                                                <a href="https://wa.me/{{ $waNumber }}?text={{ $pesanWa }}" target="_blank"
                                                    class="btn-pesan mt-auto text-decoration-none">
                                                    <i class="fab fa-whatsapp fa-lg"></i> Reservasi via WhatsApp
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- ULASAN --}}
                <section class="mb-5 reveal" id="ulasan">
                    <div class="section-label">Ulasan Pengunjung</div>
                    <h2 class="section-title mb-4">Apa Kata <em>Mereka?</em></h2>

                    @if (session('ulasan_success'))
                        <div class="review-alert success mb-4">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ session('ulasan_success') }}</span>
                        </div>
                    @endif

                    <div class="row g-4">
                        {{-- Rating summary --}}
                        <div class="col-md-4">
                            <div class="rating-summary-big">
                                <div class="rating-avg">{{ $avgRating > 0 ? $avgRating : '–' }}</div>
                                <div class="rating-stars-sum">
                                    @for ($i = 1; $i <= 5; $i++)
                                        {{ $i <= round($avgRating) ? '★' : '☆' }}
                                    @endfor
                                </div>
                                <div class="rating-total">{{ number_format($totalUlasan) }} Ulasan</div>
                                @if ($totalUlasan > 0)
                                    <div class="mt-4">
                                        @foreach ($ratingDistribution as $star => $data)
                                            <div class="rating-bar-row">
                                                <span>{{ $star }}★</span>
                                                <div class="rating-bar-track">
                                                    <div class="rating-bar-fill" style="width:{{ $data['persen'] }}%">
                                                    </div>
                                                </div>
                                                <span>{{ $data['persen'] }}%</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Review cards --}}
                        <div class="col-md-8">
                            <div class="d-flex flex-column gap-3">
                                @forelse ($wisata->ulasanWisata->take(5) as $ulasan)
                                    <div class="review-card">
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            @if ($ulasan->avatar)
                                                <img src="{{ asset('storage/' . $ulasan->avatar) }}"
                                                    class="reviewer-avatar" alt="{{ $ulasan->nama }}" />
                                            @else
                                                <div class="reviewer-avatar"
                                                    style="width:44px;height:44px;border-radius:50%;background:var(--green-mist);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--green-mid);font-size:1.1rem;">
                                                    {{ strtoupper(substr($ulasan->nama, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="reviewer-name">{{ $ulasan->nama }}</div>
                                                <div class="d-flex justify-content-between">
                                                    <div class="review-stars">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            {{ $i <= $ulasan->rating ? '★' : '☆' }}
                                                        @endfor
                                                    </div>
                                                    <div class="reviewer-date">
                                                        {{ $ulasan->created_at->translatedFormat('d M Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review-text">{{ $ulasan->ulasan }}</div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="fas fa-comment-dots fa-2x mb-3" style="color:var(--green-pale);"></i>
                                        <p class="text-muted" style="font-size:0.9rem;">
                                            Belum ada ulasan. Jadilah yang pertama memberikan ulasan!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="review-form-card mt-4">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                            <div>
                                <div class="review-form-kicker">Bagikan Pengalaman</div>
                                <h3 class="review-form-title">Tulis Ulasan Anda</h3>
                            </div>
                            <div class="review-form-icon">
                                <i class="fas fa-comment-dots"></i>
                            </div>
                        </div>

                        <form action="{{ route('wisata.ulasan.store', $wisata->slug) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nama" class="review-form-label">Nama</label>
                                    <input type="text" name="nama" id="nama"
                                        class="review-form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama') }}" placeholder="Nama Anda" required>
                                    @error('nama')
                                        <div class="review-form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="rating" class="review-form-label">Rating</label>
                                    <select name="rating" id="rating"
                                        class="review-form-control @error('rating') is-invalid @enderror" required>
                                        <option value="">Pilih rating</option>
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" @selected((int) old('rating') === $i)>
                                                {{ $i }} bintang
                                            </option>
                                        @endfor
                                    </select>
                                    @error('rating')
                                        <div class="review-form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="ulasan_text" class="review-form-label">Ulasan</label>
                                    <textarea name="ulasan" id="ulasan_text" rows="4"
                                        class="review-form-control @error('ulasan') is-invalid @enderror"
                                        placeholder="Ceritakan pengalaman Anda saat berkunjung..." required>{{ old('ulasan') }}</textarea>
                                    @error('ulasan')
                                        <div class="review-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="review-form-footer">
                                <p>Ulasan akan tampil setelah disetujui admin.</p>
                                <button type="submit" class="btn-gold border-0">
                                    <i class="fas fa-paper-plane"></i> Kirim Ulasan
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

            </div>{{-- /col-lg-8 --}}

            {{-- RIGHT: Sticky Info Card --}}
            <div class="col-lg-4" id="info">
                <div class="floating-info">
                    <div class="floating-info-header">
                        <div class="tiket-label">Harga Tiket Masuk</div>
                        <div class="tiket-price">
                            @if ($wisata->harga_tiket && $wisata->harga_tiket > 0)
                                Rp{{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                <span>/ orang</span>
                            @else
                                Gratis <span>masuk</span>
                            @endif
                        </div>
                        @if ($wisata->harga_parkir_motor || $wisata->harga_parkir_mobil)
                            <div style="color:rgba(255,255,255,0.5); font-size:0.78rem; margin-top:4px;">
                                @if ($wisata->harga_parkir_motor)
                                    + Parkir Motor
                                    Rp{{ number_format((float) $wisata->harga_parkir_motor, 0, ',', '.') }}
                                @endif
                                @if ($wisata->harga_parkir_motor && $wisata->harga_parkir_mobil)
                                    ·
                                @endif
                                @if ($wisata->harga_parkir_mobil)
                                    Mobil Rp{{ number_format((float) $wisata->harga_parkir_mobil, 0, ',', '.') }}
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="floating-info-body">
                        @if ($wisata->jam_operasional)
                            <div class="info-row">
                                <div class="info-icon"><i class="fas fa-clock"></i></div>
                                <div>
                                    <div class="info-label">Jam Operasional</div>
                                    <div class="info-val">{{ $wisata->jam_operasional }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($wisata->hari_buka)
                            <div class="info-row">
                                <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                                <div>
                                    <div class="info-label">Hari Buka</div>
                                    <div class="info-val">{{ $wisata->hari_buka }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($wisata->durasi_trek)
                            <div class="info-row">
                                <div class="info-icon"><i class="fas fa-hiking"></i></div>
                                <div>
                                    <div class="info-label">Durasi Trek</div>
                                    <div class="info-val">{{ $wisata->durasi_trek }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($wisata->cocok_untuk)
                            <div class="info-row">
                                <div class="info-icon"><i class="fas fa-users"></i></div>
                                <div>
                                    <div class="info-label">Cocok Untuk</div>
                                    <div class="info-val">{{ $wisata->cocok_untuk }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($wisata->telepon)
                            <div class="info-row">
                                <div class="info-icon"><i class="fas fa-phone"></i></div>
                                <div>
                                    <div class="info-label">Informasi & Pemesanan</div>
                                    <div class="info-val">{{ $wisata->telepon }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-3">
                            <button class="btn-share" onclick="shareWisata()">
                                <i class="fas fa-share-alt"></i> Bagikan Wisata Ini
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /row --}}
    </div>{{-- /container --}}

    {{-- WISATA LAINNYA --}}
    @if ($wisataLainnya->count() > 0)
        <section class="detail-section alt">
            <div class="container">
                <div class="row align-items-end mb-5">
                    <div class="col">
                        <div class="section-label">Jelajahi Lebih</div>
                        <h2 class="section-title">Wisata <em>Lainnya</em></h2>
                    </div>
                    <div class="col-auto">
                        <a href="{{ url('/wisata') }}" class="btn-outline-green">
                            Semua Wisata <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="row g-4">
                    @foreach ($wisataLainnya as $lain)
                        @php
                            $lainRating = $lain->ulasanWisata->avg('rating') ?? 0;
                            $lainTotal = $lain->ulasanWisata->count();
                        @endphp
                        <div class="col-md-4">
                            <a href="{{ url('/wisata/' . $lain->slug) }}" class="wisata-lain-card">
                                <div class="wisata-lain-img">
                                    @if ($lain->gambar_utama)
                                        <img src="{{ asset('storage/' . $lain->gambar_utama) }}"
                                            alt="{{ $lain->nama }}" />
                                    @else
                                        <img src="https://images.unsplash.com/photo-1501854140801-50d01698950b?w=400&q=80"
                                            alt="{{ $lain->nama }}" />
                                    @endif
                                </div>
                                <div class="wisata-lain-body">
                                    <span class="wisata-lain-cat">
                                        {{ $lain->kategoriWisata?->nama ?? 'Wisata' }}
                                    </span>
                                    <div class="wisata-lain-title">{{ $lain->nama }}</div>
                                    <div class="wisata-lain-meta">
                                        <span>
                                            <span style="color:var(--gold)">★</span>
                                            {{ $lainTotal > 0 ? number_format($lainRating, 1) . ' · ' . $lainTotal . ' ulasan' : 'Baru' }}
                                        </span>
                                        <span class="wisata-lain-price">
                                            @if ($lain->harga_tiket && $lain->harga_tiket > 0)
                                                Rp{{ number_format($lain->harga_tiket, 0, ',', '.') }}
                                            @else
                                                Gratis
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script>
        async function shareWisata() {
            const shareData = {
                title: @json($wisata->nama . ' - Wisata Desa'),
                text: @json($wisata->deskripsi_singkat ?? Str::limit(strip_tags($wisata->deskripsi), 100)),
                url: window.location.href,
            };

            if (navigator.share) {
                try {
                    await navigator.share(shareData);
                    return;
                } catch (error) {
                    if (error.name === 'AbortError') {
                        return;
                    }
                }
            }

            copyWisataLink(shareData.url);
        }

        async function copyWisataLink(url) {
            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(url);
                } else {
                    const input = document.createElement('textarea');
                    input.value = url;
                    input.setAttribute('readonly', '');
                    input.style.position = 'fixed';
                    input.style.left = '-9999px';
                    document.body.appendChild(input);
                    input.select();
                    document.execCommand('copy');
                    document.body.removeChild(input);
                }

                alert('Link wisata berhasil disalin!');
            } catch (error) {
                prompt('Salin link wisata ini:', url);
            }
        }
    </script>
@endpush
