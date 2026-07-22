@extends('frontend.layouts.app')

@section('title', 'Galeri Desa')

@section(
    'meta_description',
    'Kumpulan dokumentasi kegiatan, pembangunan, dan potret kehidupan di ' . ($profilDesa?->nama_desa ?? 'Desa') . '.'
)

@section('content')
    {{-- PAGE HEADER --}}
    <header class="page-header">
        <div class="container position-relative" style="z-index: 2;">
            <div class="breadcrumb-custom">
                <a href="{{ url('/') }}">Beranda</a>
                <i class="fas fa-chevron-right"></i>
                <span style="color:rgba(255,255,255,0.9)">Galeri</span>
            </div>
            <h1 class="page-title">Galeri <em>Desa</em></h1>
            <p class="page-desc">
                Kumpulan memori, dokumentasi kegiatan warga, dan potret kehidupan
                {{ $profilDesa?->nama_desa ?? 'desa' }}.
            </p>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="gallery-section">
        <div class="container">

            {{-- Filter & Search --}}
            <div class="filter-wrapper mb-5">
                <div class="filter-pills" id="filterPills">
                    <a href="{{ url('/galeri') }}"
                        class="filter-pill {{ !request('album') || request('album') === 'semua' ? 'active' : '' }}">
                        Semua
                    </a>
                    @foreach ($album as $item)
                        <a href="{{ url('/galeri?album=' . $item->slug . (request('q') ? '&q=' . request('q') : '')) }}"
                            class="filter-pill {{ request('album') === $item->slug ? 'active' : '' }}">
                            {{ $item->nama }}
                        </a>
                    @endforeach
                </div>

                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <form action="{{ url('/galeri') }}" method="GET" id="searchGaleriForm">
                        @if (request('album'))
                            <input type="hidden" name="album" value="{{ request('album') }}">
                        @endif
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Cari foto galeri..." id="searchGaleri">
                    </form>
                </div>
            </div>

            @if (request('q'))
                <div class="mb-4">
                    <p style="color: var(--text-mid); font-size: 0.9rem;">
                        Menampilkan <strong>{{ $galeri->total() }}</strong> hasil untuk
                        "<strong>{{ request('q') }}</strong>"
                        <a href="{{ url('/galeri' . (request('album') ? '?album=' . request('album') : '')) }}"
                            style="color: var(--green-fresh); margin-left: 0.5rem;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </p>
                </div>
            @endif

            {{-- Masonry Grid --}}
            @if ($galeri->count() > 0)
                <div class="masonry-grid">
                    @foreach ($galeri as $foto)
                        @php
                            $imgUrl = $foto->gambar
                                ? asset('storage/' . $foto->gambar);
                            $caption = $foto->caption ?: 'Dokumentasi Desa';
                        @endphp

                        <div class="gallery-item"
                            onclick='openLightbox(@json($imgUrl), @json($caption), @json($foto->albumGaleri?->nama ?? "Galeri Desa"))'>
                            <img src="{{ $imgUrl }}" alt="{{ $caption }}" />
                            <div class="gallery-expand-icon"><i class="fas fa-expand"></i></div>
                            <div class="gallery-overlay">
                                <span class="gallery-category">
                                    {{ $foto->albumGaleri?->nama ?? 'Galeri Desa' }}
                                </span>
                                <div class="gallery-title">{{ $caption }}</div>
                                <div class="gallery-date">
                                    {{ $foto->created_at?->translatedFormat('d M Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-images fa-3x mb-3 d-block" style="color: var(--green-pale);"></i>
                    <h5 style="color: var(--green-deep); font-family: 'Playfair Display', serif;">
                        @if (request('q') || request('album'))
                            Galeri tidak ditemukan
                        @else
                            Belum ada foto galeri
                        @endif
                    </h5>
                    <p class="text-muted" style="font-size: 0.9rem;">
                        @if (request('q') || request('album'))
                            Coba gunakan kata kunci lain atau
                            <a href="{{ url('/galeri') }}" style="color: var(--green-fresh);">lihat semua galeri</a>.
                        @else
                            Dokumentasi desa sedang disiapkan. Nantikan foto terbaru selanjutnya!
                        @endif
                    </p>
                </div>
            @endif

            {{-- Pagination --}}
            @if ($galeri->hasPages())
                <div class="pagination-custom">
                    @if ($galeri->onFirstPage())
                        <span class="page-item-custom" style="opacity:0.4; pointer-events:none;">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $galeri->previousPageUrl() }}" class="page-item-custom">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach ($galeri->getUrlRange(1, $galeri->lastPage()) as $page => $url)
                        @if ($page <= 5 || $page === $galeri->lastPage() || abs($page - $galeri->currentPage()) <= 1)
                            <a href="{{ $url }}"
                                class="page-item-custom {{ $page === $galeri->currentPage() ? 'active' : '' }}">
                                {{ $page }}
                            </a>
                        @elseif ($page === 6 && $galeri->lastPage() > 7)
                            <span class="page-item-custom" style="pointer-events:none; border:none;">...</span>
                        @endif
                    @endforeach

                    @if ($galeri->hasMorePages())
                        <a href="{{ $galeri->nextPageUrl() }}" class="page-item-custom">
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
        document.getElementById('searchGaleri')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchGaleriForm').submit();
            }
        });

        let galeriSearchTimer;
        document.getElementById('searchGaleri')?.addEventListener('input', function() {
            clearTimeout(galeriSearchTimer);
            const val = this.value;
            galeriSearchTimer = setTimeout(() => {
                if (val.length >= 3 || val.length === 0) {
                    document.getElementById('searchGaleriForm').submit();
                }
            }, 800);
        });
    </script>
@endpush
