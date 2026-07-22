@extends('frontend.layouts.app')

@section('title', $berita->judul . ' — Berita Desa')

@section('meta_description', $berita->excerpt ?? Str::limit(strip_tags($berita->konten), 160))

@section('og_type', 'article')

@php
    $imgUrl = $berita->gambar
        ? asset('storage/' . $berita->gambar)
        : 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1200&q=80';
    $authorName = $berita->user?->name ?? 'Admin Desa';
    $shareUrl = url()->current();
    $shareText = $berita->judul . ' - ' . $shareUrl;
@endphp

@section('meta_image', $imgUrl)

@section('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "{{ $berita->judul }}",
    "description": "{{ $berita->excerpt ?? Str::limit(strip_tags($berita->konten), 160) }}",
    "image": "{{ $imgUrl }}",
    "datePublished": "{{ $berita->created_at?->toIso8601String() }}",
    "dateModified": "{{ $berita->updated_at?->toIso8601String() }}",
    "author": {
        "@type": "Person",
        "name": "{{ $authorName }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "Desa Umbu Mamijuk",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('fe/assets/img/logo-desa.png') }}"
        }
    },
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ $shareUrl }}"
    }
}
</script>
@endsection

@section('content')
    <div class="reading-progress">
        <div class="reading-progress-bar" id="progressBar"></div>
    </div>

    {{-- HERO ARTIKEL --}}
    <section class="hero-artikel">
        <div class="hero-artikel-inner">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="breadcrumb-artikel">
                            <a href="{{ url('/') }}">Beranda</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ url('/berita') }}">Berita</a>
                            <i class="fas fa-chevron-right"></i>
                            <span style="color: rgba(255, 255, 255, 0.8)">
                                {{ $berita->kategoriBerita?->nama ?? 'Berita Desa' }}
                            </span>
                        </div>

                        <div class="artikel-kategori-pill">
                            <i class="{{ $berita->kategoriBerita?->icon ?? 'fas fa-newspaper' }}"></i>
                            {{ $berita->kategoriBerita?->nama ?? 'Berita Desa' }}
                        </div>

                        <h1 class="artikel-headline">{{ $berita->judul }}</h1>

                        @if ($berita->excerpt)
                            <p class="artikel-lead">{{ $berita->excerpt }}</p>
                        @endif

                        <div class="artikel-meta-row">
                            <div class="author-chip">
                                <div class="author-avatar d-flex align-items-center justify-content-center"
                                    style="background:var(--green-mid); color:white; font-weight:800;">
                                    {{ strtoupper(substr($authorName, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="author-name">{{ $authorName }}</div>
                                    <div class="author-role">Pengelola Informasi Desa</div>
                                </div>
                            </div>
                            <div class="meta-divider"></div>
                            <div class="meta-item">
                                <i class="far fa-calendar-alt"></i>
                                {{ $berita->published_at?->translatedFormat('d F Y') ?? $berita->created_at->translatedFormat('d F Y') }}
                            </div>
                            <div class="meta-item">
                                <i class="far fa-clock"></i> {{ $readingMinutes }} menit membaca
                            </div>
                            <div class="meta-item">
                                <i class="far fa-eye"></i> {{ number_format($berita->views) }} dilihat
                            </div>
                            <div class="meta-item">
                                <i class="far fa-comment"></i> {{ number_format($approvedComments->count()) }} komentar
                            </div>
                        </div>

                        <div class="hero-image-wrapper">
                            <img src="{{ $imgUrl }}" alt="{{ $berita->judul }}" />
                            @if ($berita->caption_gambar)
                                <div class="hero-image-caption">{{ $berita->caption_gambar }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <div class="container py-5" style="padding-top: 50px !important">
        <div class="row g-5">
            <div class="col-lg-8">
                <article class="artikel-body reveal">
                    {!! $berita->konten !!}
                </article>

                @if ($berita->tags->count() > 0)
                    <div class="mt-4 mb-4 reveal">
                        <div class="fw-700 mb-2"
                            style="font-weight: 700; color: var(--green-deep); font-size: 0.85rem;">
                            Tags:
                        </div>
                        @foreach ($berita->tags as $tag)
                            <span class="tag-pill">{{ $tag->nama }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="share-bar reveal mb-5">
                    <span class="share-label">
                        <i class="fas fa-share-alt me-2" style="color: var(--green-fresh)"></i>
                        Bagikan Berita:
                    </span>
                    <a class="share-btn share-fb" target="_blank"
                        href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($shareUrl) }}">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a class="share-btn share-wa" target="_blank"
                        href="https://wa.me/?text={{ rawurlencode($shareText) }}">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a class="share-btn share-tw" target="_blank"
                        href="https://twitter.com/intent/tweet?text={{ rawurlencode($berita->judul) }}&url={{ rawurlencode($shareUrl) }}">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <button class="share-btn share-copy" id="copyBtn">
                        <i class="fas fa-link"></i> Salin Tautan
                    </button>
                </div>

                <div class="sidebar-card reveal mb-5" style="border-radius: 20px">
                    <div class="sidebar-body" style="padding: 1.8rem">
                        <div class="d-flex gap-4 align-items-start">
                            <div class="d-flex align-items-center justify-content-center"
                                style="width:72px;height:72px;border-radius:16px;background:var(--green-mist);border:3px solid var(--green-pale);color:var(--green-mid);font-weight:900;font-size:1.5rem;flex:0 0 auto;">
                                {{ strtoupper(substr($authorName, 0, 1)) }}
                            </div>
                            <div>
                                <div
                                    style="font-size: 0.72rem; font-weight: 700; color: var(--green-mid); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 4px;">
                                    Ditulis oleh
                                </div>
                                <div
                                    style="font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 700; color: var(--green-deep); margin-bottom: 4px;">
                                    {{ $authorName }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-light); margin-bottom: 0.7rem;">
                                    Pengelola Informasi dan Komunikasi Desa
                                </div>
                                <p style="font-size: 0.85rem; color: var(--text-mid); line-height: 1.7; margin: 0;">
                                    Bertanggung jawab dalam pengelolaan informasi publik, berita kegiatan, dan komunikasi
                                    resmi desa kepada masyarakat.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="reveal" id="komentar">
                    <div class="section-label">Komentar</div>
                    <h3
                        style="font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: var(--green-deep); margin-bottom: 1.5rem;">
                        {{ number_format($approvedComments->count()) }} Komentar
                    </h3>

                    @if (session('komentar_success'))
                        <div class="review-alert success mb-4">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ session('komentar_success') }}</span>
                        </div>
                    @endif

                    <div class="sidebar-card mb-4" style="border-radius: 20px">
                        <div class="sidebar-header">Tinggalkan Komentar</div>
                        <div class="sidebar-body" style="padding: 1.5rem">
                            <form action="{{ route('berita.komentar.store', $berita->slug) }}" method="POST">
                                @csrf
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <input type="text" name="nama"
                                            class="review-form-control @error('nama') is-invalid @enderror"
                                            value="{{ old('nama') }}" placeholder="Nama Anda" />
                                        @error('nama')
                                            <div class="review-form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" name="email"
                                            class="review-form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="Email (tidak dipublikasikan)" />
                                        @error('email')
                                            <div class="review-form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <textarea name="komentar" class="review-form-control mb-3 @error('komentar') is-invalid @enderror" rows="4"
                                    placeholder="Tulis komentar Anda...">{{ old('komentar') }}</textarea>
                                @error('komentar')
                                    <div class="review-form-error mb-3">{{ $message }}</div>
                                @enderror
                                <button class="btn-gold border-0" type="submit">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Komentar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse ($approvedComments as $komentar)
                            <div class="review-card" style="border-radius: 16px;">
                                <div class="d-flex gap-3">
                                    <div class="reviewer-avatar d-flex align-items-center justify-content-center"
                                        style="background:var(--green-mist); color:var(--green-mid); font-weight:800; flex:0 0 auto;">
                                        {{ strtoupper(substr($komentar->nama, 0, 1)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1 gap-3">
                                            <div class="reviewer-name">{{ $komentar->nama }}</div>
                                            <div class="reviewer-date">
                                                {{ $komentar->created_at->translatedFormat('d M Y, H:i') }}
                                            </div>
                                        </div>
                                        <p class="review-text" style="margin:0;">{{ $komentar->komentar }}</p>
                                        <div style="color: var(--green-mid); font-size: 0.78rem; font-weight: 600; padding-top: 6px;">
                                            <i class="far fa-thumbs-up me-1"></i>{{ number_format($komentar->likes) }} Suka
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-comment-dots fa-2x mb-3" style="color:var(--green-pale);"></i>
                                <p class="text-muted" style="font-size:0.9rem;">Belum ada komentar yang ditampilkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sticky-share">
                    @if ($beritaTerbaru->count() > 0)
                        <div class="sidebar-card">
                            <div class="sidebar-header">Berita Terbaru</div>
                            <div class="sidebar-body" style="padding: 0.5rem 1.5rem">
                                @foreach ($beritaTerbaru as $item)
                                    @php
                                        $thumbUrl = $item->gambar
                                            ? asset('storage/' . $item->gambar)
                                            : 'https://images.unsplash.com/photo-1473081556163-2a17de81fc97?w=150&q=70';
                                    @endphp
                                    <a href="{{ route('berita.show', $item->slug) }}" class="sidebar-news-item">
                                        <img src="{{ $thumbUrl }}" class="sidebar-news-thumb" alt="{{ $item->judul }}" />
                                        <div>
                                            <div class="sidebar-news-title">{{ $item->judul }}</div>
                                            <div class="sidebar-news-date">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $item->published_at?->translatedFormat('d M Y') }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($wisataUnggulan)
                        @php
                            $wisataImg = $wisataUnggulan->gambar_utama
                                ? asset('storage/' . $wisataUnggulan->gambar_utama)
                                : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500&q=80';
                        @endphp
                        <div class="sidebar-card">
                            <div class="sidebar-header">Destinasi Wisata Unggulan</div>
                            <div class="sidebar-body" style="padding: 1rem">
                                <div class="wisata-promo-card">
                                    <img src="{{ $wisataImg }}" alt="{{ $wisataUnggulan->nama }}" />
                                    <div class="wisata-promo-overlay">
                                        <div class="wisata-promo-title">{{ $wisataUnggulan->nama }}</div>
                                        <a href="{{ route('wisata.show', $wisataUnggulan->slug) }}" class="wisata-promo-cta">
                                            Lihat Detail <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($kategori->count() > 0)
                        <div class="sidebar-card">
                            <div class="sidebar-header">Kategori Berita</div>
                            <div class="sidebar-body" style="padding: 0.5rem 1.5rem">
                                @foreach ($kategori as $kat)
                                    <a href="{{ url('/berita?kategori=' . $kat->slug) }}" class="cat-link">
                                        <span>
                                            <i class="{{ $kat->icon ?? 'fas fa-folder' }} me-2"
                                                style="color: var(--green-fresh)"></i>
                                            {{ $kat->nama }}
                                        </span>
                                        <span class="cat-count">{{ $kat->berita_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($beritaTerkait->count() > 0)
        <section style="padding: 80px 0; background: var(--green-mist)">
            <div class="container">
                <div class="row align-items-end mb-5">
                    <div class="col">
                        <div class="section-label">Baca Juga</div>
                        <h2 class="section-title">Berita <em>Terkait</em> Lainnya</h2>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('berita.index') }}" class="btn-outline-green">
                            Semua Berita <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="row g-4">
                    @foreach ($beritaTerkait as $item)
                        @php
                            $relatedImg = $item->gambar
                                ? asset('storage/' . $item->gambar)
                                : 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?w=400&q=80';
                        @endphp
                        <div class="col-md-4">
                            <a href="{{ route('berita.show', $item->slug) }}" class="related-card">
                                <div class="related-img">
                                    <img src="{{ $relatedImg }}" alt="{{ $item->judul }}" />
                                </div>
                                <div class="related-body">
                                    <span class="related-cat">{{ $item->kategoriBerita?->nama ?? 'Berita Desa' }}</span>
                                    <div class="related-title">{{ $item->judul }}</div>
                                    <div class="related-date">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ $item->published_at?->translatedFormat('d M Y') }}
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
