@extends('frontend.layouts.app')

@section('title', 'Semua Wisata')

@section(
    'meta_description',
    'Jelajahi semua destinasi wisata di ' . ($profilDesa?->nama_desa) . '. Temukan keindahan alam, sejarah, petualangan, dan panorama memukau.'
)

@section('content')
    {{-- PAGE HEADER --}}
    <header class="page-header">
        <div class="container position-relative" style="z-index: 2;">
            <div class="breadcrumb-wisata">
                <a href="{{ url('/') }}">Beranda</a>
                <i class="fas fa-chevron-right"></i>
                <span style="color:rgba(255,255,255,0.9)">Wisata Desa</span>
            </div>
            <h1 class="page-title">Destinasi <em>Wisata</em></h1>
            <p class="page-desc">
                Jelajahi keindahan alam, kekayaan sejarah, dan berbagai pesona menarik yang ditawarkan
                untuk pengalaman tak terlupakan.
            </p>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="wisata-grid-section">
        <div class="container">

            {{-- Filter & Search --}}
            <div class="filter-wrapper mb-5">
                <div class="filter-pills" id="filterPills">
                    <a href="{{ url('/wisata') }}"
                        class="filter-pill {{ !request('kategori') || request('kategori') === 'semua' ? 'active' : '' }}">
                        Semua Wisata
                    </a>
                    @foreach ($kategori as $kat)
                        <a href="{{ url('/wisata?kategori=' . $kat->slug . (request('q') ? '&q=' . request('q') : '')) }}"
                            class="filter-pill {{ request('kategori') === $kat->slug ? 'active' : '' }}">
                            {{ $kat->nama }}
                        </a>
                    @endforeach
                </div>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <form action="{{ url('/wisata') }}" method="GET" id="searchForm">
                        @if (request('kategori'))
                            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        @endif
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Cari destinasi wisata..." id="searchWisata">
                    </form>
                </div>
            </div>

            {{-- Search result info --}}
            @if (request('q'))
                <div class="mb-4">
                    <p style="color: var(--text-mid); font-size: 0.9rem;">
                        Menampilkan <strong>{{ $wisata->total() }}</strong> hasil untuk
                        "<strong>{{ request('q') }}</strong>"
                        <a href="{{ url('/wisata' . (request('kategori') ? '?kategori=' . request('kategori') : '')) }}"
                            style="color: var(--green-fresh); margin-left: 0.5rem;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </p>
                </div>
            @endif

            {{-- Wisata Grid --}}
            <div class="row g-4">
                @forelse ($wisata as $item)
                    @php
                        $avgRating = $item->ulasanWisata->where('is_approved', true)->avg('rating') ?? 0;
                        $totalUlasan = $item->ulasanWisata->where('is_approved', true)->count();
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="wisata-card-small">
                            <div class="wisata-card-img">
                                @if ($item->is_unggulan)
                                    <span class="wisata-badge-unggulan">
                                        <i class="fas fa-star text-white me-1"></i> Unggulan
                                    </span>
                                @endif
                                @if ($item->gambar_utama)
                                    <img src="{{ asset('storage/' . $item->gambar_utama) }}"
                                        alt="{{ $item->nama }}" />
                                @endif
                            </div>
                            <div class="wisata-card-body">
                                <span class="wisata-card-tag">
                                    {{ $item->kategoriWisata?->nama }}
                                </span>
                                <h3 class="wisata-card-title">
                                    <a href="{{ url('/wisata/' . $item->slug) }}">{{ $item->nama }}</a>
                                </h3>
                                <p class="wisata-card-desc">
                                    {{ $item->deskripsi_singkat ?? Str::limit(strip_tags($item->deskripsi), 120) }}
                                </p>
                                <div class="wisata-card-footer">
                                    <div class="wisata-card-info">
                                        @if ($totalUlasan > 0)
                                            <span class="rating-stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    {{ $i <= round($avgRating) ? '★' : '☆' }}
                                                @endfor
                                            </span>
                                            <span
                                                style="color:var(--text-dark); font-weight: 600;">{{ number_format($avgRating, 1) }}</span>
                                            <span>({{ $totalUlasan }})</span>
                                        @else
                                            <span style="color: var(--text-light); font-size: 0.82rem;">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $item->jarak_dari_desa }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="wisata-price">
                                        @if ($item->harga_tiket && $item->harga_tiket > 0)
                                            Rp{{ number_format($item->harga_tiket, 0, ',', '.') }}
                                        @else
                                            Gratis
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-mountain fa-3x mb-3 d-block" style="color: var(--green-pale);"></i>
                            <h5 style="color: var(--green-deep); font-family: 'Playfair Display', serif;">
                                @if (request('q'))
                                    Wisata tidak ditemukan
                                @else
                                    Belum ada wisata yang tersedia
                                @endif
                            </h5>
                            <p class="text-muted" style="font-size: 0.9rem;">
                                @if (request('q'))
                                    Coba gunakan kata kunci lain atau
                                    <a href="{{ url('/wisata') }}" style="color: var(--green-fresh);">lihat semua wisata</a>.
                                @else
                                    Destinasi wisata sedang disiapkan. Nantikan informasi selanjutnya!
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($wisata->hasPages())
                <div class="pagination-custom">
                    {{-- Previous --}}
                    @if ($wisata->onFirstPage())
                        <span class="page-item-custom" style="opacity:0.4; pointer-events:none;">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $wisata->previousPageUrl() }}" class="page-item-custom">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($wisata->getUrlRange(1, $wisata->lastPage()) as $page => $url)
                        @if ($page <= 5 || $page === $wisata->lastPage() || abs($page - $wisata->currentPage()) <= 1)
                            <a href="{{ $url }}"
                                class="page-item-custom {{ $page === $wisata->currentPage() ? 'active' : '' }}">
                                {{ $page }}
                            </a>
                        @elseif ($page === 6 && $wisata->lastPage() > 7)
                            <span class="page-item-custom" style="pointer-events:none; border:none;">…</span>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($wisata->hasMorePages())
                        <a href="{{ $wisata->nextPageUrl() }}" class="page-item-custom">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="page-item-custom" style="opacity:0.4; pointer-events:none;">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            @endif

        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // Submit search on Enter
        document.getElementById('searchWisata')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });

        // Debounced search (auto-submit after 800ms of typing)
        let searchTimer;
        document.getElementById('searchWisata')?.addEventListener('input', function() {
            clearTimeout(searchTimer);
            const val = this.value;
            searchTimer = setTimeout(() => {
                if (val.length >= 3 || val.length === 0) {
                    document.getElementById('searchForm').submit();
                }
            }, 800);
        });
    </script>
@endpush
