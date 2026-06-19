@extends('frontend.layouts.app')

@section('title', $profilDesa?->nama_desa ?? 'Beranda')

@section(
    'meta_description',
    'Website resmi ' . ($profilDesa?->nama_desa ?? 'Desa') . '. Jelajahi profil desa, destinasi wisata, statistik kependudukan, galeri, dan berita terbaru.'
)

@push('styles')
<style>
.carousel-indicators [data-bs-target] {
    width: 10px !important;
    height: 10px !important;
    border-radius: 50% !important;
    margin: 0 6px !important;
    background-color: rgba(255, 255, 255, 0.5) !important;
    border: none !important;
    transition: all 0.3s ease;
}
.carousel-indicators .active {
    background-color: var(--gold) !important;
    transform: scale(1.2);
}
.hero-carousel-control {
    width: 6%;
}
.hero-scroll-hint {
    bottom: 70px !important;
    color: var(--green-mid) !important;
}
</style>
@endpush

@section('content')
    {{-- HERO --}}
    @if ($sliders->count() > 0)
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000" style="background: var(--green-deep); position: relative; overflow: hidden; min-height: 100vh;">
            {{-- Indicators --}}
            <div class="carousel-indicators" style="z-index: 5; bottom: 40px;">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                @foreach ($sliders as $index => $slider)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index + 1 }}" aria-label="Slide {{ $index + 2 }}"></button>
                @endforeach
            </div>

            <div class="carousel-inner">
                {{-- Slide 1: Welcome Slide (Default) --}}
                <div class="carousel-item active">
                    <section class="hero" style="padding-top: 80px;">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-7">
                                    <div class="hero-badge">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Kec. {{ $profilDesa?->kecamatan ?? '–' }},
                                        Kab. {{ $profilDesa?->kabupaten ?? '–' }}
                                    </div>

                                    <h1 class="hero-title">
                                        Selamat Datang di Desa
                                        <span>{{ $profilDesa?->nama_desa }}</span>
                                    </h1>

                                    <p class="hero-desc">
                                        @if ($profilDesa?->sejarah_desa)
                                            {{ Str::limit(strip_tags($profilDesa->sejarah_desa), 180) }}
                                        @else
                                            Desa yang kaya akan keindahan alam, budaya lokal, dan destinasi wisata memukau.
                                            Temukan pengalaman tak terlupakan di antara hijaunya alam nusantara.
                                        @endif
                                    </p>

                                    <div class="hero-actions">
                                        <a href="#wisata" class="btn-hero-primary">
                                            <i class="fas fa-compass me-2"></i>
                                            Jelajahi Wisata
                                        </a>

                                        <a href="#profil" class="btn-hero-outline">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Profil Desa
                                        </a>
                                    </div>
                                </div>

                                {{-- Statistik Desktop --}}
                                <div class="col-lg-4 offset-lg-1 d-none d-lg-block">
                                    <div class="hero-stats-scattered">
                                        <div class="scattered-stat s1">
                                            <div class="hero-stat-num">
                                                {{ $wisataLainnya->count() + ($wisataUnggulan ? 1 : 0) }}+
                                            </div>
                                            <div class="hero-stat-label">Destinasi Wisata</div>
                                        </div>

                                        <div class="scattered-stat s2">
                                            <div class="hero-stat-num">
                                                {{ $totalPenduduk > 999 ? number_format($totalPenduduk / 1000, 1) . 'K' : $totalPenduduk }}
                                            </div>
                                            <div class="hero-stat-label">Penduduk</div>
                                        </div>

                                        <div class="scattered-stat s3">
                                            <div class="hero-stat-num">
                                                {{ $profilDesa?->luas_wilayah ? number_format((float) $profilDesa->luas_wilayah) : '–' }}
                                            </div>
                                            <div class="hero-stat-label">Hektar Luas</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- Slide 2+: Dynamic Uploaded Sliders --}}
                @foreach ($sliders as $index => $slider)
                    <div class="carousel-item">
                        <section class="hero" style="background: linear-gradient(160deg, rgba(26, 58, 42, 0.88) 0%, rgba(45, 90, 61, 0.75) 50%, rgba(26, 58, 42, 0.92) 100%), url('{{ asset('storage/' . $slider->gambar) }}') center/cover no-repeat; padding-top: 80px;">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-7">
                                        <div class="hero-badge">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Kec. {{ $profilDesa?->kecamatan ?? '–' }},
                                            Kab. {{ $profilDesa?->kabupaten ?? '–' }}
                                        </div>

                                        <h1 class="hero-title">
                                            @if ($slider->judul)
                                                {!! $slider->judul !!}
                                            @else
                                                Selamat Datang di Desa
                                                <span>{{ $profilDesa?->nama_desa }}</span>
                                            @endif
                                        </h1>

                                        <p class="hero-desc">
                                            @if ($slider->deskripsi)
                                                {{ $slider->deskripsi }}
                                            @else
                                                @if ($profilDesa?->sejarah_desa)
                                                    {{ Str::limit(strip_tags($profilDesa->sejarah_desa), 180) }}
                                                @else
                                                    Desa yang kaya akan keindahan alam, budaya lokal, dan destinasi wisata memukau.
                                                    Temukan pengalaman tak terlupakan di antara hijaunya alam nusantara.
                                                @endif
                                            @endif
                                        </p>

                                        <div class="hero-actions">
                                            @if ($slider->link)
                                                <a href="{{ $slider->link }}" class="btn-hero-primary">
                                                    <i class="fas fa-link me-2"></i>
                                                    Lihat Detail
                                                </a>
                                            @else
                                                <a href="#wisata" class="btn-hero-primary">
                                                    <i class="fas fa-compass me-2"></i>
                                                    Jelajahi Wisata
                                                </a>
                                            @endif

                                            <a href="#profil" class="btn-hero-outline">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Profil Desa
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Statistik Desktop --}}
                                    <div class="col-lg-4 offset-lg-1 d-none d-lg-block">
                                        <div class="hero-stats-scattered">
                                            <div class="scattered-stat s1">
                                                <div class="hero-stat-num">
                                                    {{ $wisataLainnya->count() + ($wisataUnggulan ? 1 : 0) }}+
                                                </div>
                                                <div class="hero-stat-label">Destinasi Wisata</div>
                                            </div>

                                            <div class="scattered-stat s2">
                                                <div class="hero-stat-num">
                                                    {{ $totalPenduduk > 999 ? number_format($totalPenduduk / 1000, 1) . 'K' : $totalPenduduk }}
                                                </div>
                                                <div class="hero-stat-label">Penduduk</div>
                                            </div>

                                            <div class="scattered-stat s3">
                                                <div class="hero-stat-num">
                                                    {{ $profilDesa?->luas_wilayah ? number_format((float) $profilDesa->luas_wilayah) : '–' }}
                                                </div>
                                                <div class="hero-stat-label">Hektar Luas</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                @endforeach
            </div>

            {{-- Controls --}}
            <!-- <button class="carousel-control-prev hero-carousel-control" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="z-index: 5;">
                <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next hero-carousel-control" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="z-index: 5;">
                <span class="carousel-control-next-icon" aria-hidden="true" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));"></span>
                <span class="visually-hidden">Next</span>
            </button> -->

            <div class="hero-scroll-hint" style="z-index: 5;">
                <span>Scroll</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    @else
        <section
            class="hero"
            style="padding-top: 80px"
        >
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="hero-badge">
                            <i class="fas fa-map-marker-alt"></i>
                            Kec. {{ $profilDesa?->kecamatan ?? '–' }},
                            Kab. {{ $profilDesa?->kabupaten ?? '–' }}
                        </div>

                        <h1 class="hero-title">
                            Selamat Datang di Desa
                            <span>{{ $profilDesa?->nama_desa }}</span>
                        </h1>

                        <p class="hero-desc">
                            @if ($profilDesa?->sejarah_desa)
                                {{ Str::limit(strip_tags($profilDesa->sejarah_desa), 180) }}
                            @else
                                Desa yang kaya akan keindahan alam, budaya lokal, dan destinasi wisata memukau.
                                Temukan pengalaman tak terlupakan di antara hijaunya alam nusantara.
                            @endif
                        </p>

                        <div class="hero-actions">
                            <a
                                href="#wisata"
                                class="btn-hero-primary"
                            >
                                <i class="fas fa-compass me-2"></i>
                                Jelajahi Wisata
                            </a>

                            <a
                                href="#profil"
                                class="btn-hero-outline"
                            >
                                <i class="fas fa-info-circle me-2"></i>
                                Profil Desa
                            </a>
                        </div>
                    </div>

                    {{-- Statistik Desktop --}}
                    <div class="col-lg-4 offset-lg-1 d-none d-lg-block">
                        <div class="hero-stats-scattered">
                            <div class="scattered-stat s1">
                                <div class="hero-stat-num">
                                    {{ $wisataLainnya->count() + ($wisataUnggulan ? 1 : 0) }}+
                                </div>
                                <div class="hero-stat-label">Destinasi Wisata</div>
                            </div>

                            <div class="scattered-stat s2">
                                <div class="hero-stat-num">
                                    {{ $totalPenduduk > 999 ? number_format($totalPenduduk / 1000, 1) . 'K' : $totalPenduduk }}
                                </div>
                                <div class="hero-stat-label">Penduduk</div>
                            </div>

                            <div class="scattered-stat s3">
                                <div class="hero-stat-num">
                                    {{ $profilDesa?->luas_wilayah ? number_format((float) $profilDesa->luas_wilayah) : '–' }}
                                </div>
                                <div class="hero-stat-label">Hektar Luas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-scroll-hint">
                <span>Scroll</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </section>
    @endif

    {{-- WISATA --}}
    <section
        class="wisata-section"
        id="wisata"
    >
        <div class="container">
            <div class="row align-items-end mb-5">
                <div class="col-lg-7">
                    <div class="section-label">Pariwisata</div>

                    <h2 class="section-title">
                        Destinasi <em>Unggulan</em>
                        <br />
                        Desa {{ $profilDesa?->nama_desa ?? 'Desa Kami' }}
                    </h2>
                </div>

                <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                    <a
                        href="{{ url('/wisata') }}"
                        class="btn-outline-green"
                    >
                        Lihat Semua Wisata
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="row g-4">
                {{-- Wisata Featured --}}
                <div class="col-lg-7">
                    @if ($wisataUnggulan)
                        <div class="wisata-featured">
                            @if ($wisataUnggulan->gambar_utama)
                                <img
                                    src="{{ asset('storage/' . $wisataUnggulan->gambar_utama) }}"
                                    alt="{{ $wisataUnggulan->nama }}"
                                />
                            @else
                                <img
                                    src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=900&q=80"
                                    alt="{{ $wisataUnggulan->nama }}"
                                />
                            @endif

                            <div class="wisata-featured-overlay">
                                <span class="wisata-tag">⭐ Unggulan</span>

                                <h3 class="wisata-featured-title">
                                    <a href="{{ url('/wisata/' . $wisataUnggulan->slug) }}">
                                        {{ $wisataUnggulan->nama }}
                                    </a>
                                </h3>

                                <p class="wisata-featured-desc">
                                    {{ $wisataUnggulan->deskripsi_singkat ?? Str::limit($wisataUnggulan->deskripsi, 150) }}
                                </p>

                                <div class="wisata-meta">
                                    @if ($wisataUnggulan->jarak_dari_desa)
                                        <span>
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $wisataUnggulan->jarak_dari_desa }} dari pusat desa
                                        </span>
                                    @endif

                                    @if ($wisataUnggulan->jam_operasional)
                                        <span>
                                            <i class="fas fa-clock"></i>
                                            {{ $wisataUnggulan->jam_operasional }}
                                        </span>
                                    @endif

                                    @if ($wisataUnggulan->harga_tiket)
                                        <span>
                                            <i class="fas fa-ticket-alt"></i>
                                            Rp{{ number_format($wisataUnggulan->harga_tiket, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            class="wisata-featured d-flex align-items-center justify-content-center"
                            style="background: rgba(255,255,255,0.05); border-radius: 20px; min-height:300px;"
                        >
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-mountain fa-3x mb-3 d-block"></i>
                                Belum ada wisata unggulan
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Wisata Lainnya (grid kecil) --}}
                <div class="col-lg-5">
                    <div class="row g-3">
                        @forelse ($wisataLainnya as $item)
                            <div class="col-6">
                                <div class="wisata-card-small">
                                    <div class="wisata-card-img">
                                        @if ($item->gambar_utama)
                                            <img
                                                src="{{ asset('storage/' . $item->gambar_utama) }}"
                                                alt="{{ $item->nama }}"
                                            />
                                        @else
                                            <img
                                                src="https://images.unsplash.com/photo-1501854140801-50d01698950b?w=400&q=80"
                                                alt="{{ $item->nama }}"
                                            />
                                        @endif
                                    </div>

                                    <div class="wisata-card-body">
                                        <span class="wisata-card-tag">
                                            {{ $item->kategoriWisata?->nama ?? 'Wisata' }}
                                        </span>

                                        <div class="wisata-card-title">
                                            <a href="{{ url('/wisata/' . $item->slug) }}">
                                                {{ $item->nama }}
                                            </a>
                                        </div>

                                        <div class="wisata-card-info">
                                            @if ($item->harga_tiket)
                                                <span style="color: var(--text-light); font-size: 0.78rem;">
                                                    Rp{{ number_format($item->harga_tiket, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span style="color: var(--text-light); font-size: 0.78rem;">Gratis</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted text-center py-3">Belum ada wisata lainnya.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PROFIL DESA --}}
    <section
        class="profil-section"
        id="profil"
    >
        <div class="container position-relative">
            <div class="row g-5">
                <div class="col-lg-5">
                    <div
                        class="section-label"
                        style="color: var(--green-light)"
                    >
                        Tentang Kami
                    </div>

                    <h2
                        class="section-title"
                        style="color: white"
                    >
                        Profil
                        <em style="color: var(--gold-light)">Desa</em>
                        <br />
                        {{ $profilDesa?->nama_desa ?? 'Kami' }}
                    </h2>

                    <p
                        style="
                            color: rgba(255, 255, 255, 0.7);
                            font-size: 0.95rem;
                            line-height: 1.9;
                            margin: 1.5rem 0 2rem;
                        "
                    >
                        @if ($profilDesa?->sejarah_desa)
                            {{ Str::limit(strip_tags($profilDesa->sejarah_desa), 200) }}
                        @else
                            Desa yang terletak di tengah alam yang asri, dikenal dengan
                            keramahan warga, potensi wisata, dan semangat gotong royong.
                        @endif
                    </p>

                    <a
                        href="{{ url('/profil-desa') }}"
                        class="btn-profil-detail mb-4"
                    >
                        Lihat Selengkapnya
                        <i class="fas fa-arrow-right"></i>
                    </a>

                    {{-- Visi --}}
                    @if ($profilDesa?->visi)
                        <div class="visi-misi-card mb-4">
                            <div class="visi-title">Visi Desa</div>
                            <div class="visi-text">
                                "{{ $profilDesa->visi }}"
                            </div>
                        </div>
                    @endif

                    {{-- Misi --}}
                    @if ($profilDesa?->misi)
                        <div class="visi-misi-card">
                            <div class="visi-title mb-3">Misi Desa</div>
                            @foreach (array_filter(explode("\n", $profilDesa->misi)) as $misi)
                                <div class="misi-item">
                                    <div class="misi-dot"></div>
                                    {{ trim($misi) }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-lg-7">
                    {{-- Sambutan Kepala Desa --}}
                    @if ($kepalaDesa)
                        <div class="kepala-desa-card mb-4">
                            <div class="kepala-desa-photo">
                                @if ($kepalaDesa->foto)
                                    <img
                                        src="{{ asset('storage/' . $kepalaDesa->foto) }}"
                                        alt="{{ $kepalaDesa->nama }}"
                                    />
                                @else
                                    <img
                                        src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=500&q=80"
                                        alt="{{ $kepalaDesa->nama }}"
                                    />
                                @endif
                            </div>

                            <div class="kepala-desa-content">
                                <div class="kepala-desa-label">Sambutan Kepala Desa</div>

                                <h3 class="kepala-desa-name">
                                    {{ $kepalaDesa->nama }}
                                </h3>

                                <div class="kepala-desa-text">
                                    @if ($kepalaDesa->sambutan)
                                        {!! nl2br(e(Str::limit($kepalaDesa->sambutan, 400))) !!}
                                    @else
                                        <p>
                                            Assalamu'alaikum warahmatullahi wabarakatuh.
                                            Puji syukur kita panjatkan ke hadirat Allah SWT atas segala rahmat dan karunia-Nya.
                                        </p>
                                        <p>
                                            Website resmi ini kami hadirkan sebagai media informasi, komunikasi,
                                            dan transparansi bagi seluruh warga desa maupun masyarakat luas.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Informasi Desa --}}
                    <div class="profil-info-card">
                        <h5
                            style="
                                color: white;
                                font-family: 'Playfair Display', serif;
                                font-weight: 700;
                                font-size: 1.1rem;
                                margin-bottom: 0.5rem;
                                padding-bottom: 1rem;
                                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                            "
                        >
                            Informasi Desa
                        </h5>

                        @if ($kepalaDesa)
                            <div class="profil-info-item">
                                <div class="profil-info-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <div class="profil-info-label">Kepala Desa</div>
                                    <div class="profil-info-value">{{ $kepalaDesa->nama }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($profilDesa?->luas_wilayah)
                            <div class="profil-info-item">
                                <div class="profil-info-icon">
                                    <i class="fas fa-map"></i>
                                </div>
                                <div>
                                    <div class="profil-info-label">Luas Wilayah</div>
                                    <div class="profil-info-value">
                                        {{ number_format((float) $profilDesa->luas_wilayah) }} Hektar
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($profilDesa?->ketinggian)
                            <div class="profil-info-item">
                                <div class="profil-info-icon">
                                    <i class="fas fa-mountain"></i>
                                </div>
                                <div>
                                    <div class="profil-info-label">Ketinggian</div>
                                    <div class="profil-info-value">{{ $profilDesa->ketinggian }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($profilDesa?->telepon)
                            <div class="profil-info-item">
                                <div class="profil-info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <div class="profil-info-label">Telepon Kantor Desa</div>
                                    <div class="profil-info-value">{{ $profilDesa->telepon }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($profilDesa?->email)
                            <div class="profil-info-item">
                                <div class="profil-info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <div class="profil-info-label">Email</div>
                                    <div class="profil-info-value">{{ $profilDesa->email }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($profilDesa?->jam_pelayanan)
                            <div class="profil-info-item">
                                <div class="profil-info-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <div class="profil-info-label">Jam Pelayanan</div>
                                    <div class="profil-info-value">{{ $profilDesa->jam_pelayanan }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PETA WILAYAH --}}
    @php
        $osmUrl = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d47947.482707782045!2d119.63056245000001!3d-9.582116450000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2c4b17dcc3ac7dcf%3A0x448a4d7a7d9aeeaf!2sUmbu%20Mamijuk%2C%20Kec.%20Umbu%20Ratu%20Nggay%20Bar.%2C%20Kabupaten%20Sumba%20Tengah%2C%20Nusa%20Tenggara%20Tim.!5e1!3m2!1sid!2sid!4v1780389999315!5m2!1sid!2sid";
    @endphp
    <section class="peta-section" style="padding: 100px 0; background: var(--cream);">
        <div class="container">
            <div class="row align-items-end mb-5">
                <div class="col-12">
                    <div class="section-label">Peta Wilayah</div>
                    <h2 class="section-title">
                        Lokasi dan Wilayah <em>Desa {{ $profilDesa->nama_desa }}</em>
                    </h2>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="peta-desa-frame" style="border-radius: 28px; overflow: hidden; box-shadow: 0 15px 45px rgba(26, 58, 42, 0.08); border: 1px solid var(--green-pale);">
                        <iframe
                            src="{!! $osmUrl !!}"
                            title="Peta Wilayah {{ $profilDesa?->nama_desa ?? 'Desa' }}"
                            style="width: 100%; height: 480px; border: none; display: block; filter: grayscale(5%) contrast(105%);"
                            loading="lazy"
                            allow="geolocation; gyroscope; accelerometer; magnetometer"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- DATA KEPENDUDUKAN --}}
    <section
        class="data-section"
        id="kependudukan"
    >
        <div class="container">
            <div class="text-center mb-5">
                <div
                    class="section-label"
                    style="justify-content: center"
                >
                    Data Kependudukan
                </div>

                <h2 class="section-title">
                    Statistik <em>Penduduk</em> Desa
                </h2>

                <p
                    class="text-muted mt-2"
                    style="font-size: 0.9rem"
                >
                    Data terkini per {{ now()->translatedFormat('F Y') }}
                </p>

                <a
                    href="{{ url('/kependudukan') }}"
                    class="btn-outline-green mt-3"
                >
                    Lihat Data Lengkap
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            {{-- Kartu Statistik Utama --}}
            <div class="row g-4 mb-5">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number">
                            {{ number_format($totalPenduduk) }}
                            <span class="stat-unit">jiwa</span>
                        </div>
                        <div class="stat-label">Total Penduduk</div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="stat-number">
                            {{ number_format($totalKK) }}
                            <span class="stat-unit">KK</span>
                        </div>
                        <div class="stat-label">Kepala Keluarga</div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-male"></i>
                        </div>
                        <div class="stat-number">
                            {{ number_format($totalLakiLaki) }}
                            <span class="stat-unit">jiwa</span>
                        </div>
                        <div class="stat-label">Penduduk Laki-laki</div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-female"></i>
                        </div>
                        <div class="stat-number">
                            {{ number_format($totalPerempuan) }}
                            <span class="stat-unit">jiwa</span>
                        </div>
                        <div class="stat-label">Penduduk Perempuan</div>
                    </div>
                </div>
            </div>

            {{-- Grafik Demografi --}}
            <div class="row g-4">
                {{-- Kelompok Usia --}}
                <div class="col-lg-4">
                    <div class="demografi-card">
                        <div class="demografi-title">Kelompok Usia</div>

                        @forelse ($demografiUsia as $label => $data)
                            <div class="demografi-bar-item">
                                <div class="demografi-bar-label">
                                    <span>{{ $label }}</span>
                                    <span>{{ number_format($data['jumlah']) }}</span>
                                </div>
                                <div class="demografi-bar-track">
                                    <div
                                        class="demografi-bar-fill"
                                        style="width: {{ $data['persen'] }}%"
                                    ></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3" style="font-size:0.85rem">
                                Data belum tersedia
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- Mata Pencaharian --}}
                <div class="col-lg-4">
                    <div class="demografi-card">
                        <div class="demografi-title">Mata Pencaharian</div>

                        @forelse ($demografiPekerjaan as $label => $data)
                            <div class="demografi-bar-item">
                                <div class="demografi-bar-label">
                                    <span>{{ Str::limit($label, 22) }}</span>
                                    <span>{{ number_format($data['jumlah']) }}</span>
                                </div>
                                <div class="demografi-bar-track">
                                    <div
                                        class="demografi-bar-fill"
                                        style="width: {{ $data['persen'] }}%"
                                    ></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3" style="font-size:0.85rem">
                                Data belum tersedia
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- Pendidikan Terakhir --}}
                <div class="col-lg-4">
                    <div class="demografi-card">
                        <div class="demografi-title">Pendidikan Terakhir</div>

                        @forelse ($demografiPendidikan as $label => $data)
                            <div class="demografi-bar-item">
                                <div class="demografi-bar-label">
                                    <span>{{ $label }}</span>
                                    <span>{{ number_format($data['jumlah']) }}</span>
                                </div>
                                <div class="demografi-bar-track">
                                    <div
                                        class="demografi-bar-fill"
                                        style="width: {{ $data['persen'] }}%"
                                    ></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3" style="font-size:0.85rem">
                                Data belum tersedia
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- GALERI --}}
    <section class="galeri-section">
        <div class="container">
            <div class="row align-items-end mb-4">
                <div class="col">
                    <div class="section-label">Galeri</div>

                    <h2 class="section-title">
                        Potret &amp; <em>Kegiatan</em> Desa
                    </h2>
                </div>

                <div class="col-auto">
                    <a
                        href="{{ url('/galeri') }}"
                        class="btn-outline-green btn-sm"
                    >
                        Lihat Semua
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="galeri-grid">
                @forelse ($galeri as $index => $foto)
                    @php
                        $imgUrl = $foto->gambar
                            ? asset('storage/' . $foto->gambar)
                            : 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=1200&q=80';
                        $kelas = $index === 0 ? 'large' : '';
                        $caption = $foto->caption ?: ($foto->albumGaleri?->nama ?? 'Galeri Desa');
                    @endphp

                    <div
                        class="galeri-item {{ $kelas }}"
                        onclick='openLightbox(@json($imgUrl), @json($caption), @json($foto->albumGaleri?->nama ?? "Galeri Desa"))'
                    >
                        <img
                            src="{{ $imgUrl }}"
                            alt="{{ $caption }}"
                        />

                        <div class="galeri-overlay">
                            <i class="fas fa-expand-alt"></i>
                            <div class="galeri-overlay-text">
                                <span>{{ $foto->albumGaleri?->nama ?? 'Galeri Desa' }}</span>
                                <strong>{{ $caption }}</strong>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Fallback galeri statis jika DB kosong --}}
                    @foreach ([
                        ['https://images.unsplash.com/photo-1448375240586-882707db888b?w=1200&q=80', 'Hutan Desa', 'large'],
                        ['https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?w=1200&q=80', 'Danau Desa', ''],
                        ['https://images.unsplash.com/photo-1470252649378-9c29740c9fa8?w=1200&q=80', 'Sawah Desa', ''],
                        ['https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=1200&q=80', 'Air Terjun', ''],
                        ['https://images.unsplash.com/photo-1505118380757-91f5f5632de0?w=1200&q=80', 'Lembah Desa', ''],
                    ] as [$src, $alt, $kls])
                        <div
                            class="galeri-item {{ $kls }}"
                            onclick='openLightbox(@json($src), @json($alt), @json("Galeri Desa"))'
                        >
                            <img src="{{ $src }}" alt="{{ $alt }}" />
                            <div class="galeri-overlay">
                                <i class="fas fa-expand-alt"></i>
                                <div class="galeri-overlay-text">
                                    <span>Galeri Desa</span>
                                    <strong>{{ $alt }}</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- BERITA --}}
    <section class="berita-section">
        <div class="container">
            <div class="row align-items-end mb-5">
                <div class="col-lg-7">
                    <div class="section-label">Berita &amp; Informasi</div>

                    <h2 class="section-title">
                        Kabar Terbaru <em>Desa</em>
                    </h2>
                </div>

                <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                    <a
                        href="{{ url('/berita') }}"
                        class="btn-outline-green"
                    >
                        Semua Berita
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($beritaTerbaru as $item)
                    <div class="col-md-4">
                        <div class="berita-card">
                            <div class="berita-img">
                                @if ($item->gambar)
                                    <img
                                        src="{{ asset('storage/' . $item->gambar) }}"
                                        alt="{{ $item->judul }}"
                                    />
                                @else
                                    <img
                                        src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=400&q=80"
                                        alt="{{ $item->judul }}"
                                    />
                                @endif
                            </div>

                            <div class="berita-body">
                                <div class="berita-date">
                                    {{ $item->published_at?->translatedFormat('d F Y') ?? $item->created_at->translatedFormat('d F Y') }}
                                </div>

                                <div class="berita-title">
                                    {{ $item->judul }}
                                </div>

                                <div class="berita-excerpt">
                                    {{ $item->excerpt ?? Str::limit(strip_tags($item->konten), 120) }}
                                </div>

                                <a
                                    href="{{ url('/berita/' . $item->slug) }}"
                                    class="berita-link"
                                >
                                    Baca Selengkapnya
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-newspaper fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted">Belum ada berita yang dipublikasikan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
