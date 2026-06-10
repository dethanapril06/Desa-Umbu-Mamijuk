@extends('frontend.layouts.app')

@section('title', 'Semua Berita')

@section(
    'meta_description',
    'Informasi terkini, pengumuman, dan liputan kegiatan dari ' . ($profilDesa?->nama_desa ?? 'desa') . '.'
)

@section('content')
    {{-- PAGE HEADER --}}
    <header class="page-header">
        <div class="container position-relative" style="z-index: 2;">
            <div class="breadcrumb-custom">
                <a href="{{ url('/') }}">Beranda</a>
                <i class="fas fa-chevron-right"></i>
                <span style="color:rgba(255,255,255,0.9)">Berita Desa</span>
            </div>
            <h1 class="page-title">Kabar <em>Berita Desa</em></h1>
            <p class="page-desc">
                Informasi terkini, pengumuman, dan liputan berbagai kegiatan dari pemerintah
                dan warga {{ $profilDesa?->nama_desa ?? 'desa' }}.
            </p>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="berita-grid-section">
        <div class="container">

            {{-- Filter & Search --}}
            <div class="filter-wrapper mb-5">
                <div class="filter-pills">
                    <a href="{{ url('/berita') }}"
                        class="filter-pill {{ !request('kategori') || request('kategori') === 'semua' ? 'active' : '' }}">
                        Semua Berita
                    </a>
                    @foreach ($kategori as $kat)
                        <a href="{{ url('/berita?kategori=' . $kat->slug . (request('q') ? '&q=' . request('q') : '')) }}"
                            class="filter-pill {{ request('kategori') === $kat->slug ? 'active' : '' }}">
                            {{ $kat->nama }}
                        </a>
                    @endforeach
                </div>

                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <form action="{{ url('/berita') }}" method="GET" id="searchBeritaForm">
                        @if (request('kategori'))
                            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        @endif
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Cari berita..." id="searchBerita">
                    </form>
                </div>
            </div>

            @if (request('q'))
                <div class="mb-4">
                    <p style="color: var(--text-mid); font-size: 0.9rem;">
                        Menampilkan <strong>{{ $berita->total() }}</strong> hasil untuk
                        "<strong>{{ request('q') }}</strong>"
                        <a href="{{ url('/berita' . (request('kategori') ? '?kategori=' . request('kategori') : '')) }}"
                            style="color: var(--green-fresh); margin-left: 0.5rem;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </p>
                </div>
            @endif

            {{-- Berita Grid --}}
            <div class="row g-4">
                @forelse ($berita as $item)
                    @php
                        $imgUrl = $item->gambar
                            ? asset('storage/' . $item->gambar)
                            : 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=600&q=80';
                    @endphp

                    <div class="col-lg-4 col-md-6">
                        <div class="berita-card">
                            <div class="berita-img">
                                <span class="berita-kategori">
                                    {{ $item->kategoriBerita?->nama ?? 'Berita Desa' }}
                                </span>
                                <img src="{{ $imgUrl }}" alt="{{ $item->judul }}" />
                            </div>

                            <div class="berita-body">
                                <div class="berita-date">
                                    {{ $item->published_at?->translatedFormat('d F Y') ?? $item->created_at->translatedFormat('d F Y') }}
                                </div>

                                <div class="berita-title">{{ $item->judul }}</div>

                                <div class="berita-excerpt">
                                    {{ $item->excerpt ?? Str::limit(strip_tags($item->konten), 130) }}
                                </div>

                                <a href="{{ url('/berita/' . $item->slug) }}" class="berita-link">
                                    Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-newspaper fa-3x mb-3 d-block" style="color: var(--green-pale);"></i>
                            <h5 style="color: var(--green-deep); font-family: 'Playfair Display', serif;">
                                @if (request('q') || request('kategori'))
                                    Berita tidak ditemukan
                                @else
                                    Belum ada berita yang dipublikasikan
                                @endif
                            </h5>
                            <p class="text-muted" style="font-size: 0.9rem;">
                                @if (request('q') || request('kategori'))
                                    Coba gunakan kata kunci lain atau
                                    <a href="{{ url('/berita') }}" style="color: var(--green-fresh);">lihat semua berita</a>.
                                @else
                                    Informasi desa sedang disiapkan. Nantikan kabar terbaru selanjutnya!
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($berita->hasPages())
                <div class="pagination-custom">
                    @if ($berita->onFirstPage())
                        <span class="page-item-custom" style="opacity:0.4; pointer-events:none;">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $berita->previousPageUrl() }}" class="page-item-custom">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach ($berita->getUrlRange(1, $berita->lastPage()) as $page => $url)
                        @if ($page <= 5 || $page === $berita->lastPage() || abs($page - $berita->currentPage()) <= 1)
                            <a href="{{ $url }}"
                                class="page-item-custom {{ $page === $berita->currentPage() ? 'active' : '' }}">
                                {{ $page }}
                            </a>
                        @elseif ($page === 6 && $berita->lastPage() > 7)
                            <span class="page-item-custom" style="pointer-events:none; border:none;">...</span>
                        @endif
                    @endforeach

                    @if ($berita->hasMorePages())
                        <a href="{{ $berita->nextPageUrl() }}" class="page-item-custom">
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
        document.getElementById('searchBerita')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchBeritaForm').submit();
            }
        });

        let beritaSearchTimer;
        document.getElementById('searchBerita')?.addEventListener('input', function() {
            clearTimeout(beritaSearchTimer);
            const val = this.value;
            beritaSearchTimer = setTimeout(() => {
                if (val.length >= 3 || val.length === 0) {
                    document.getElementById('searchBeritaForm').submit();
                }
            }, 800);
        });
    </script>
@endpush
