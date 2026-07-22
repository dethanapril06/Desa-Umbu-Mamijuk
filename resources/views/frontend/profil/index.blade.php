@extends('frontend.layouts.app')

@section('title', 'Profil Desa ' . ($profilDesa?->nama_desa ?? ''))

@section('meta_description',
    'Profil lengkap ' .
    ($profilDesa?->nama_desa ?? 'Desa') .
    '. Informasi visi misi, data
    desa, struktur organisasi, peta wilayah, dan batas administratif desa.')

@section('content')
    {{-- PAGE HEADER --}}
    <header class="page-header">
        <div class="container position-relative" style="z-index: 2;">
            <div class="breadcrumb-custom">
                <a href="{{ url('/') }}">Beranda</a>
                <i class="fas fa-chevron-right"></i>
                <span style="color:rgba(255,255,255,0.9)">Profil Desa</span>
            </div>
            <h1 class="page-title">
                Profil <em>{{ $profilDesa?->nama_desa ?? 'Desa' }}</em>
            </h1>
            <p class="page-desc">
                Informasi lengkap mengenai visi, misi, data desa, struktur organisasi, dan wilayah administratif
                {{ $profilDesa?->nama_desa ?? 'Desa' }}.
            </p>
        </div>
    </header>

    <main>
        {{-- RINGKASAN, VISI, MISI --}}
        <section class="profil-detail-section">
            <div class="container">
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-5">
                        <div class="profil-detail-card dark">
                            <div class="section-label" style="color: var(--green-light);">Tentang Desa</div>
                            <h2 class="profil-detail-title">
                                @if ($profilDesa?->ketinggian)
                                    Desa di Ketinggian {{ $profilDesa->ketinggian }}
                                @else
                                    Mengenal Lebih Dekat {{ $profilDesa?->nama_desa ?? 'Desa Kami' }}
                                @endif
                            </h2>
                            <p class="profil-detail-text">
                                @if ($profilDesa?->sejarah_desa)
                                    {{ strip_tags($profilDesa->sejarah_desa) }}
                                @else
                                    {{ $profilDesa?->nama_desa ?? 'Desa' }} merupakan desa yang memiliki kekayaan alam,
                                    budaya lokal, dan potensi ekonomi berbasis pertanian serta pariwisata. Dengan semangat
                                    gotong royong, pemerintah desa terus mendorong pelayanan publik yang terbuka,
                                    pembangunan yang merata, dan pemberdayaan masyarakat yang berkelanjutan.
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="row g-4 h-100">
                            <div class="col-md-6">
                                <div class="profil-detail-card">
                                    <div class="section-label">Visi Desa</div>
                                    <h2 class="profil-detail-title" style="font-size: clamp(1.2rem, 2.5vw, 1.6rem);">
                                        @if ($profilDesa?->visi)
                                            {{ $profilDesa->visi }}
                                        @else
                                            Maju, Mandiri, Berdaya Saing
                                        @endif
                                    </h2>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profil-detail-card">
                                    <div class="section-label">Misi Desa</div>
                                    <ul class="profil-list">
                                        @if ($profilDesa?->misi)
                                            @foreach (array_filter(explode("\n", $profilDesa->misi)) as $misi)
                                                <li>{{ trim($misi) }}</li>
                                            @endforeach
                                        @else
                                            <li>Meningkatkan kualitas sumber daya manusia melalui pendidikan dan pelatihan.
                                            </li>
                                            <li>Mengembangkan potensi wisata berbasis kearifan lokal.</li>
                                            <li>Membangun infrastruktur desa yang memadai dan berkelanjutan.</li>
                                            <li>Meningkatkan perekonomian masyarakat desa.</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- INFORMASI DESA --}}
        <section class="profil-detail-section alt">
            <div class="container">
                <div class="text-center mb-5">
                    <div class="section-label" style="justify-content:center;">Informasi Desa</div>
                    <h2 class="section-title">Data Umum <em>{{ $profilDesa?->nama_desa ?? 'Desa' }}</em></h2>
                </div>
                <div class="profil-info-grid">
                    <div class="profil-info-box">
                        <i class="fas fa-user-tie"></i>
                        <div class="label">Kepala Desa</div>
                        <div class="value">{{ $kepalaDesa?->nama ?? '–' }}</div>
                    </div>
                    <div class="profil-info-box">
                        <i class="fas fa-map"></i>
                        <div class="label">Luas Wilayah</div>
                        <div class="value">
                            {{ $profilDesa?->luas_wilayah ? number_format((float) $profilDesa->luas_wilayah) . ' Hektar' : '–' }}
                        </div>
                    </div>
                    <div class="profil-info-box">
                        <i class="fas fa-home"></i>
                        <div class="label">Jumlah RT / RW</div>
                        <div class="value">{{ $totalRT }} RT / {{ $totalRW }} RW</div>
                    </div>
                    <div class="profil-info-box">
                        <i class="fas fa-mountain"></i>
                        <div class="label">Ketinggian</div>
                        <div class="value">{{ $profilDesa?->ketinggian ?? '–' }}</div>
                    </div>
                    <div class="profil-info-box">
                        <i class="fas fa-users"></i>
                        <div class="label">Jumlah Penduduk</div>
                        <div class="value">{{ number_format($totalPenduduk) }} Jiwa</div>
                    </div>
                    <div class="profil-info-box">
                        <i class="fas fa-clock"></i>
                        <div class="label">Jam Pelayanan</div>
                        <div class="value">{{ $profilDesa?->jam_pelayanan ?? 'Senin – Jumat, 08.00 – 15.00 WIB' }}</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- SAMBUTAN KEPALA DESA --}}
        @if ($kepalaDesa)
            <section class="profil-detail-section">
                <div class="container">
                    <div class="row align-items-end mb-4">
                        <div class="col-lg-8">
                            <div class="section-label">Sambutan</div>
                            <h2 class="section-title">Kata Sambutan <em>Kepala Desa</em></h2>
                        </div>
                    </div>
                    <div class="row g-4 align-items-stretch">
                        <div class="col-lg-4">
                            <div class="profil-detail-card dark d-flex flex-column align-items-center text-center"
                                style="padding: 2.5rem 1.5rem;">
                                <div
                                    style="width:150px; height:150px; border-radius:50%; overflow:hidden; border:4px solid rgba(255,255,255,0.2); margin-bottom:1.5rem;">
                                    @if ($kepalaDesa->foto)
                                        <img src="{{ asset('storage/' . $kepalaDesa->foto) }}"
                                            alt="{{ $kepalaDesa->nama }}"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    @endif
                                </div>
                                <h3
                                    style="color:white; font-family:'Playfair Display',serif; font-size:1.3rem; font-weight:700; margin-bottom:0.3rem;">
                                    {{ $kepalaDesa->nama }}
                                </h3>
                                <div
                                    style="color:var(--gold-light); font-size:0.82rem; font-weight:600; letter-spacing:1px; text-transform:uppercase;">
                                    Kepala Desa
                                </div>
                                @if ($kepalaDesa->periode_mulai)
                                    <div style="color:rgba(255,255,255,0.6); font-size:0.82rem; margin-top:0.5rem;">
                                        Periode {{ $kepalaDesa->periode_mulai }}
                                        {{ $kepalaDesa->periode_selesai ? '– ' . $kepalaDesa->periode_selesai : '– Sekarang' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="profil-detail-card h-100">
                                <div class="profil-detail-text" style="font-size: 0.95rem; line-height: 2;">
                                    @if ($kepalaDesa->sambutan)
                                        {!! nl2br(e($kepalaDesa->sambutan)) !!}
                                    @else
                                        <p>Assalamu'alaikum warahmatullahi wabarakatuh.</p>
                                        <p>Puji syukur kita panjatkan ke hadirat Allah SWT atas segala rahmat dan
                                            karunia-Nya.
                                            Website resmi ini kami hadirkan sebagai media informasi, komunikasi, dan
                                            transparansi
                                            bagi seluruh warga desa maupun masyarakat luas.</p>
                                        <p>Wassalamu'alaikum warahmatullahi wabarakatuh.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- PERANGKAT DESA --}}
        @if ($perangkatDesa->count() > 0)
            <section class="profil-detail-section alt">
                <div class="container">
                    <div class="text-center mb-5">
                        <div class="section-label" style="justify-content:center;">Pemerintahan Desa</div>
                        <h2 class="section-title">Perangkat <em>Desa</em></h2>
                    </div>
                    <div class="row g-4 justify-content-center">
                        @foreach ($perangkatDesa as $perangkat)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="profil-detail-card text-center" style="padding: 1.5rem 1rem;">
                                    <div
                                        style="width:100px; height:100px; border-radius:50%; overflow:hidden; border:3px solid var(--green-pale); margin:0 auto 1rem;">
                                        @if ($perangkat->foto)
                                            <img src="{{ asset('storage/' . $perangkat->foto) }}"
                                                alt="{{ $perangkat->nama }}"
                                                style="width:100%; height:100%; object-fit:cover;">
                                        @else
                                            <div
                                                style="width:100%; height:100%; background:var(--green-mist); display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-user" style="font-size:2rem; color:var(--green-mid);"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h4
                                        style="font-family:'Playfair Display',serif; font-size:1rem; font-weight:700; color:var(--green-deep); margin-bottom:0.25rem;">
                                        {{ $perangkat->nama }}
                                    </h4>
                                    <div
                                        style="color:var(--text-light); font-size:0.78rem; font-weight:600; letter-spacing:1px; text-transform:uppercase;">
                                        {{ $perangkat->jabatan }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- STRUKTUR ORGANISASI --}}
        @if ($profilDesa?->gambar_struktur_organisasi)
            <section class="profil-detail-section">
                <div class="container">
                    <div class="row align-items-end mb-4">
                        <div class="col-lg-8">
                            <div class="section-label">Pemerintahan Desa</div>
                            <h2 class="section-title">Bagan Struktur <em>Organisasi</em></h2>
                        </div>
                    </div>
                    <div class="struktur-card">
                        <img src="{{ asset('storage/' . $profilDesa->gambar_struktur_organisasi) }}"
                            alt="Bagan Struktur Organisasi {{ $profilDesa->nama_desa ?? 'Desa' }}" />
                    </div>
                </div>
            </section>
        @endif

        {{-- PETA DESA --}}
        @php
            $osmUrl =
                'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d47947.482707782045!2d119.63056245000001!3d-9.582116450000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2c4b17dcc3ac7dcf%3A0x448a4d7a7d9aeeaf!2sUmbu%20Mamijuk%2C%20Kec.%20Umbu%20Ratu%20Nggay%20Bar.%2C%20Kabupaten%20Sumba%20Tengah%2C%20Nusa%20Tenggara%20Tim.!5e1!3m2!1sid!2sid!4v1780389999315!5m2!1sid!2sid';
        @endphp
        <section class="profil-detail-section alt">
            <div class="container">
                <div class="text-center mb-5">
                    <div class="section-label" style="justify-content:center;">Wilayah Desa</div>
                    <h2 class="section-title">Peta dan <em>Batas Desa</em></h2>
                </div>
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-5">
                        <div class="profil-detail-card h-100">
                            <h3 class="profil-detail-title" style="font-size: 1.3rem;">Informasi Batas Wilayah</h3>
                            <div class="batas-desa-list">
                                <div class="batas-desa-item">
                                    <div class="batas-desa-icon"><i class="fas fa-arrow-up"></i></div>
                                    <div>
                                        <div class="batas-desa-label">Sebelah Utara</div>
                                        <div class="batas-desa-value">{{ $profilDesa?->batas_utara ?? '–' }}</div>
                                    </div>
                                </div>
                                <div class="batas-desa-item">
                                    <div class="batas-desa-icon"><i class="fas fa-arrow-right"></i></div>
                                    <div>
                                        <div class="batas-desa-label">Sebelah Timur</div>
                                        <div class="batas-desa-value">{{ $profilDesa?->batas_timur ?? '–' }}</div>
                                    </div>
                                </div>
                                <div class="batas-desa-item">
                                    <div class="batas-desa-icon"><i class="fas fa-arrow-down"></i></div>
                                    <div>
                                        <div class="batas-desa-label">Sebelah Selatan</div>
                                        <div class="batas-desa-value">{{ $profilDesa?->batas_selatan ?? '–' }}</div>
                                    </div>
                                </div>
                                <div class="batas-desa-item">
                                    <div class="batas-desa-icon"><i class="fas fa-arrow-left"></i></div>
                                    <div>
                                        <div class="batas-desa-label">Sebelah Barat</div>
                                        <div class="batas-desa-value">{{ $profilDesa?->batas_barat ?? '–' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="peta-desa-frame">
                            <iframe src="{!! $osmUrl !!}"
                                title="Peta Wilayah {{ $profilDesa?->nama_desa ?? 'Desa' }}" loading="lazy"
                                allow="geolocation; gyroscope; accelerometer; magnetometer"></iframe>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="peta-desa-frame">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!4v1780390396432!6m8!1m7!1stuig2O57F0OWMl94DUwY5A!2m2!1d-9.606914633308941!2d119.6242464065055!3f3.7020901175681047!4f-7.225535778415491!5f1.1924812503605782"
                                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allow="geolocation; gyroscope; accelerometer; magnetometer"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
