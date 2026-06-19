@extends('frontend.layouts.app')

@section('title', 'Direktori UMKM')

@section(
    'meta_description',
    'Jelajahi produk lokal unggulan dan direktori UMKM di ' . ($profilDesa?->nama_desa) . '. Temukan kuliner khas, kerajinan tenun, fashion, dan jasa terbaik.'
)

@section('content')
    {{-- PAGE HEADER --}}
    <header class="page-header">
        <div class="container position-relative" style="z-index: 2;">
            <div class="breadcrumb-custom">
                <a href="{{ url('/') }}">Beranda</a>
                <i class="fas fa-chevron-right"></i>
                <span style="color:rgba(255,255,255,0.9)">UMKM Desa</span>
            </div>
            <h1 class="page-title">Direktori <em>UMKM</em></h1>
            <p class="page-desc">
                Dukung produk lokal! Jelajahi berbagai Usaha Mikro, Kecil, dan Menengah karya warga Desa untuk memenuhi kebutuhan Anda.
            </p>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="wisata-grid-section">
        <div class="container">

            {{-- Filter & Search --}}
            <div class="filter-wrapper mb-5">
                <div class="filter-pills" id="filterPills">
                    <a href="{{ url('/umkm') }}"
                        class="filter-pill {{ !request('kategori') || request('kategori') === 'semua' ? 'active' : '' }}">
                        Semua Kategori
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ url('/umkm?kategori=' . $cat . (request('q') ? '&q=' . request('q') : '')) }}"
                            class="filter-pill {{ request('kategori') === $cat ? 'active' : '' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <form action="{{ url('/umkm') }}" method="GET" id="searchForm">
                        @if (request('kategori'))
                            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        @endif
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Cari UMKM atau produk..." id="searchUmkm">
                    </form>
                </div>
            </div>

            {{-- Search result info --}}
            @if (request('q'))
                <div class="mb-4">
                    <p style="color: var(--text-mid); font-size: 0.9rem;">
                        Menampilkan <strong>{{ $umkmList->total() }}</strong> hasil untuk
                        "<strong>{{ request('q') }}</strong>"
                        <a href="{{ url('/umkm' . (request('kategori') ? '?kategori=' . request('kategori') : '')) }}"
                            style="color: var(--green-fresh); margin-left: 0.5rem;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </p>
                </div>
            @endif

            {{-- UMKM Grid --}}
            <div class="row g-4">
                @forelse ($umkmList as $item)
                    <div class="col-lg-4 col-md-6">
                        <div class="wisata-card-small">
                            <div class="wisata-card-img" style="position: relative; height: 220px; overflow: hidden; background-color: #eee;">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_usaha }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="width: 100%; height: 100%; background: #e9ecef;">
                                        <i class="bx bx-store" style="font-size: 3.5rem; color: #a5b5a9;"></i>
                                        <span class="mt-2" style="font-size: 0.85rem; font-weight: 500;">Foto Usaha Belum Tersedia</span>
                                    </div>
                                @endif
                            </div>
                            <div class="wisata-card-body" style="padding: 1.5rem;">
                                <span class="wisata-card-tag" style="background-color: var(--green-mist); color: var(--green-dark); padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                    {{ $item->kategori }}
                                </span>
                                <h3 class="wisata-card-title" style="margin-top: 0.75rem; font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700;">
                                    <a href="{{ url('/umkm/' . $item->slug) }}" style="color: var(--green-deep); text-decoration: none; transition: color 0.2s;">
                                        {{ $item->nama_usaha }}
                                    </a>
                                </h3>
                                <p style="color: var(--text-mid); font-size: 0.875rem; margin-top: 0.5rem; line-height: 1.5;">
                                    <strong>Pemilik:</strong> {{ $item->nama_pemilik }}
                                </p>
                                <p class="wisata-card-desc" style="font-size: 0.875rem; color: var(--text-mid); margin-top: 0.5rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; height: 4.5em; line-height: 1.5;">
                                    {{ Str::limit(strip_tags($item->deskripsi), 130) }}
                                </p>
                                <div class="wisata-card-footer d-flex justify-content-between align-items-center" style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.06);">
                                    <div style="font-size: 0.8rem; color: var(--text-light);">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ Str::limit($item->alamat, 20) }}
                                    </div>
                                    @if($item->no_telepon)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_telepon) }}" target="_blank" class="btn btn-sm btn-success" style="border-radius: 50px; padding: 0.25rem 0.75rem; font-size: 0.8rem;">
                                            <i class="fab fa-whatsapp me-1"></i> Hubungi
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-store-slash fa-3x mb-3 d-block" style="color: var(--green-pale);"></i>
                            <h5 style="color: var(--green-deep); font-family: 'Playfair Display', serif;">
                                @if (request('q'))
                                    UMKM tidak ditemukan
                                @else
                                    Belum ada UMKM yang terdaftar
                                @endif
                            </h5>
                            <p class="text-muted" style="font-size: 0.9rem;">
                                @if (request('q'))
                                    Coba gunakan kata kunci lain atau
                                    <a href="{{ url('/umkm') }}" style="color: var(--green-fresh);">lihat semua UMKM</a>.
                                @else
                                    Daftar UMKM sedang disiapkan oleh pemerintah desa.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($umkmList->hasPages())
                <div class="pagination-custom">
                    {{-- Previous --}}
                    @if ($umkmList->onFirstPage())
                        <span class="page-item-custom" style="opacity:0.4; pointer-events:none;">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $umkmList->previousPageUrl() }}" class="page-item-custom">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($umkmList->getUrlRange(1, $umkmList->lastPage()) as $page => $url)
                        @if ($page <= 5 || $page === $umkmList->lastPage() || abs($page - $umkmList->currentPage()) <= 1)
                            <a href="{{ $url }}"
                                class="page-item-custom {{ $page === $umkmList->currentPage() ? 'active' : '' }}">
                                {{ $page }}
                            </a>
                        @elseif ($page === 6 && $umkmList->lastPage() > 7)
                            <span class="page-item-custom" style="pointer-events:none; border:none;">…</span>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($umkmList->hasMorePages())
                        <a href="{{ $umkmList->nextPageUrl() }}" class="page-item-custom">
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
        document.getElementById('searchUmkm')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });

        // Debounced search (auto-submit after 800ms of typing)
        let searchTimer;
        document.getElementById('searchUmkm')?.addEventListener('input', function() {
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
