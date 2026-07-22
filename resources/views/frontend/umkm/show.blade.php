@extends('frontend.layouts.app')

@section('title', $umkm->nama_usaha . ' — UMKM')

@section('meta_description', Str::limit(strip_tags($umkm->deskripsi), 160))

@section('meta_image', $umkm->foto ? asset('storage/' . $umkm->foto) : asset('fe/assets/img/og-default.png'))

@section('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "{{ $umkm->nama_usaha }}",
    "description": "{{ Str::limit(strip_tags($umkm->deskripsi), 160) }}",
    "image": "{{ $umkm->foto ? asset('storage/' . $umkm->foto) : asset('fe/assets/img/og-default.png') }}",
    "url": "{{ url()->current() }}",
    "telephone": "{{ $umkm->no_telepon ?? '' }}",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "{{ $umkm->alamat ?? '' }}",
        "addressLocality": "Umbu Ratu Nggay Barat",
        "addressRegion": "Sumba Tengah",
        "addressCountry": "ID"
    },
    "openingHours": "{{ $umkm->jam_operasional ?? '' }}"
}
</script>
@endsection

@section('content')
    {{-- HERO --}}
    <section class="hero-wisata" style="padding-top: 140px; padding-bottom: 70px;">
        <div class="hero-bg" id="heroBg" style="background-image: url('{{ $umkm->foto ? asset('storage/' . $umkm->foto) : 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=800&q=80' }}'); filter: blur(3px) brightness(0.6);">
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="container">
                {{-- Breadcrumb --}}
                <div class="breadcrumb-custom">
                    <a href="{{ url('/') }}">Beranda</a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="{{ url('/umkm') }}">UMKM</a>
                    <i class="fas fa-chevron-right"></i>
                    <span style="color:rgba(255,255,255,0.9)">{{ $umkm->nama_usaha }}</span>
                </div>

                <div class="row align-items-end mt-4">
                    <div class="col-lg-8">
                        <div class="hero-category" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
                            <i class="fas fa-store me-1"></i>
                            {{ $umkm->kategori }}
                        </div>
                        <h1 class="hero-title-wisata mt-2" style="font-size: 3rem; text-shadow: 0 4px 10px rgba(0,0,0,0.5);">
                            {{ $umkm->nama_usaha }}
                        </h1>
                        <div class="hero-meta-row mt-3">
                            <div class="hero-meta-item">
                                <i class="fas fa-user"></i>
                                Pemilik: {{ $umkm->nama_pemilik }}
                            </div>
                            @if ($umkm->jam_operasional)
                                <div class="hero-meta-item">
                                    <i class="fas fa-clock"></i>
                                    Jam Buka: {{ $umkm->jam_operasional }}
                                </div>
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
                    <div class="section-label" style="color: var(--green-mid); font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; margin-bottom: 0.5rem;">Profil Usaha</div>
                    <h2 class="section-title mb-4" style="font-family: 'Playfair Display', serif; color: var(--green-deep); font-weight: 700;">
                        Tentang <em>{{ $umkm->nama_usaha }}</em>
                    </h2>
                    <div class="desc-content" style="color: var(--text-dark); line-height: 1.8; font-size: 1.05rem; white-space: pre-line;">
                        {!! nl2br(e($umkm->deskripsi)) !!}
                    </div>
                </section>

                {{-- TAMPILAN GAMBAR/LOGO --}}
                <section class="mb-5 reveal">
                    <div class="section-label" style="color: var(--green-mid); font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; margin-bottom: 0.5rem;">Foto Bisnis</div>
                    <div class="peta-desa-frame p-2 bg-white" style="border-radius:18px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                        @if($umkm->foto)
                            <img src="{{ asset('storage/' . $umkm->foto) }}" alt="Foto {{ $umkm->nama_usaha }}" style="width: 100%; border-radius: 12px; object-fit: cover; max-height: 450px;" />
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="width: 100%; height: 350px; background: #e9ecef; border-radius: 12px;">
                                <i class="bx bx-store" style="font-size: 5rem; color: #a5b5a9;"></i>
                                <span class="mt-3">Foto/Logo Usaha Belum Tersedia</span>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            {{-- RIGHT: Sticky Info Card --}}
            <div class="col-lg-4">
                <div class="floating-info" style="position: sticky; top: 100px; background: white; border-radius: 18px; box-shadow: 0 15px 40px rgba(0,0,0,0.06); overflow: hidden; border: 1px solid rgba(0,0,0,0.05);">
                    <div class="floating-info-header" style="background: var(--green-deep); color: white; padding: 1.5rem 2rem;">
                        <div class="tiket-label" style="font-size: 0.85rem; text-transform: uppercase; opacity: 0.8; letter-spacing: 0.5px;">Hubungi Usaha</div>
                        <div class="tiket-price" style="font-size: 1.3rem; font-weight: 700; font-family: 'Playfair Display', serif; margin-top: 4px;">
                            {{ $umkm->nama_pemilik }}
                        </div>
                    </div>
                    <div class="floating-info-body" style="padding: 2rem;">
                        @if ($umkm->jam_operasional)
                            <div class="info-row d-flex align-items-start mb-3" style="gap: 1rem;">
                                <div class="info-icon" style="color: var(--green-mid); font-size: 1.25rem;"><i class="fas fa-clock"></i></div>
                                <div>
                                    <div class="info-label" style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Jam Operasional</div>
                                    <div class="info-val" style="font-weight: 600; color: var(--text-dark);">{{ $umkm->jam_operasional }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="info-row d-flex align-items-start mb-3" style="gap: 1rem;">
                            <div class="info-icon" style="color: var(--green-mid); font-size: 1.25rem;"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <div class="info-label" style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Alamat Usaha</div>
                                <div class="info-val" style="font-weight: 600; color: var(--text-dark); font-size: 0.95rem;">{{ $umkm->alamat }}</div>
                            </div>
                        </div>

                        @if ($umkm->no_telepon)
                            <div class="info-row d-flex align-items-start mb-3" style="gap: 1rem;">
                                <div class="info-icon" style="color: var(--green-mid); font-size: 1.25rem;"><i class="fas fa-phone-alt"></i></div>
                                <div>
                                    <div class="info-label" style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">No. Telepon / WA</div>
                                    <div class="info-val" style="font-weight: 600; color: var(--text-dark);">{{ $umkm->no_telepon }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($umkm->email)
                            <div class="info-row d-flex align-items-start mb-3" style="gap: 1rem;">
                                <div class="info-icon" style="color: var(--green-mid); font-size: 1.25rem;"><i class="fas fa-envelope"></i></div>
                                <div>
                                    <div class="info-label" style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Email Usaha</div>
                                    <div class="info-val" style="font-weight: 600; color: var(--text-dark);">{{ $umkm->email }}</div>
                                </div>
                            </div>
                        @endif

                        @if ($umkm->no_telepon)
                            <div class="mt-4">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $umkm->no_telepon) }}" target="_blank" class="btn btn-success w-100 py-2 d-flex align-items-center justify-content-center gap-2" style="border-radius: 50px; font-weight: 600; font-size: 0.95rem;">
                                    <i class="fab fa-whatsapp" style="font-size: 1.2rem;"></i> Chat WhatsApp Pemilik
                                </a>
                            </div>
                        @endif

                        @if ($umkm->website_url)
                            <div class="mt-2">
                                <a href="{{ $umkm->website_url }}" target="_blank" class="btn btn-outline-success w-100 py-2 d-flex align-items-center justify-content-center gap-2" style="border-radius: 50px; font-weight: 600; font-size: 0.95rem;">
                                    <i class="fas fa-external-link-alt"></i> Kunjungi Marketplace / Medsos
                                </a>
                            </div>
                        @endif

                        <div class="mt-3">
                            <button class="btn-share w-100 py-2 d-flex align-items-center justify-content-center gap-2" onclick="shareUmkm()" style="background: var(--cream); border: 1px solid var(--green-pale); color: var(--green-dark); border-radius: 50px; font-weight: 600; font-size: 0.9rem;">
                                <i class="fas fa-share-alt"></i> Bagikan UMKM Ini
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /row --}}
    </div>{{-- /container --}}

    {{-- UMKM LAINNYA --}}
    @if ($umkmLainnya->count() > 0)
        <section class="detail-section alt py-5" style="background: #fdfcf7; border-top: 1px solid rgba(0,0,0,0.03);">
            <div class="container">
                <div class="row align-items-end mb-4">
                    <div class="col">
                        <div class="section-label" style="color: var(--green-mid); font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Produk Lokal</div>
                        <h2 class="section-title" style="font-family: 'Playfair Display', serif; color: var(--green-deep); font-weight: 700; font-size: 2rem;">UMKM <em>Lainnya</em></h2>
                    </div>
                    <div class="col-auto">
                        <a href="{{ url('/umkm') }}" class="btn-outline-green" style="border-color: var(--green-mid); color: var(--green-dark); border-radius: 50px; padding: 0.5rem 1.25rem; font-weight: 600; text-decoration: none;">
                            Semua UMKM <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="row g-4">
                    @foreach ($umkmLainnya as $lain)
                        <div class="col-md-4">
                            <a href="{{ url('/umkm/' . $lain->slug) }}" class="wisata-lain-card" style="text-decoration: none; color: inherit; display: block; border-radius: 12px; background: white; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.04); transition: transform 0.2s, box-shadow 0.2s;">
                                <div class="wisata-lain-img" style="height: 180px; overflow: hidden; background-color: #eee;">
                                    @if ($lain->foto)
                                        <img src="{{ asset('storage/' . $lain->foto) }}" alt="{{ $lain->nama_usaha }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                    @else
                                        <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="width: 100%; height: 100%; background: #e9ecef;">
                                            <i class="bx bx-store" style="font-size: 3rem; color: #a5b5a9;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="wisata-lain-body" style="padding: 1.25rem;">
                                    <span class="wisata-lain-cat" style="font-size: 0.7rem; color: var(--green-mid); text-transform: uppercase; font-weight: 700;">
                                        {{ $lain->kategori }}
                                    </span>
                                    <div class="wisata-lain-title" style="font-size: 1.15rem; font-weight: 700; color: var(--green-deep); margin-top: 0.25rem; font-family: 'Playfair Display', serif;">{{ $lain->nama_usaha }}</div>
                                    <div class="wisata-lain-meta d-flex justify-content-between text-muted" style="font-size: 0.8rem; margin-top: 0.75rem; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 0.50rem;">
                                        <span>Pemilik: {{ $lain->nama_pemilik }}</span>
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
        async function shareUmkm() {
            const shareData = {
                title: @json($umkm->nama_usaha . ' - UMKM Desa'),
                text: @json(Str::limit(strip_tags($umkm->deskripsi), 100)),
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

            copyUmkmLink(shareData.url);
        }

        async function copyUmkmLink(url) {
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

                alert('Link UMKM berhasil disalin!');
            } catch (error) {
                prompt('Salin link UMKM ini:', url);
            }
        }
    </script>
@endpush
