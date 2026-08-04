@extends('frontend.layouts.app')

@section('title', 'Penginapan & Homestay Desa')

@section(
    'meta_description',
    'Temukan penginapan, homestay, vila, dan guesthouse terbaik di ' . ($profilDesa?->nama_desa) . '. Nikmati suasanan desa yang asri dengan akomodasi nyaman dan terjangkau.'
)

@section('content')
    {{-- PAGE HEADER --}}
    <header class="page-header">
        <div class="container position-relative" style="z-index: 2;">
            <div class="breadcrumb-custom">
                <a href="{{ url('/') }}">Beranda</a>
                <i class="fas fa-chevron-right"></i>
                <span style="color:rgba(255,255,255,0.9)">Penginapan & Homestay</span>
            </div>
            <h1 class="page-title">Direktori <em>Penginapan</em></h1>
            <p class="page-desc">
                Nikmati kenyamanan berlibur! Temukan akomodasi homestay, vila, dan guesthouse ramah warga di Desa {{ $profilDesa?->nama_desa ?? '' }}.
            </p>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="wisata-grid-section">
        <div class="container">

            {{-- Filter & Search --}}
            <div class="filter-wrapper mb-5">
                <div class="filter-pills" id="filterPills">
                    <a href="{{ url('/penginapan') }}"
                        class="filter-pill {{ !request('jenis') || request('jenis') === 'semua' ? 'active' : '' }}">
                        Semua Jenis
                    </a>
                    @foreach ($jenisList as $j)
                        <a href="{{ url('/penginapan?jenis=' . urlencode($j) . (request('q') ? '&q=' . urlencode(request('q')) : '')) }}"
                            class="filter-pill {{ request('jenis') === $j ? 'active' : '' }}">
                            {{ $j }}
                        </a>
                    @endforeach
                </div>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <form action="{{ url('/penginapan') }}" method="GET" id="searchForm">
                        @if (request('jenis'))
                            <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                        @endif
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Cari penginapan atau fasilitas..." id="searchPenginapan">
                    </form>
                </div>
            </div>

            {{-- Search result info --}}
            @if (request('q'))
                <div class="mb-4">
                    <p style="color: var(--text-mid); font-size: 0.9rem;">
                        Menampilkan <strong>{{ $penginapanList->total() }}</strong> hasil untuk
                        "<strong>{{ request('q') }}</strong>"
                        <a href="{{ url('/penginapan' . (request('jenis') ? '?jenis=' . request('jenis') : '')) }}"
                            style="color: var(--green-fresh); margin-left: 0.5rem;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </p>
                </div>
            @endif

            {{-- Penginapan Grid --}}
            <div class="row g-4">
                @forelse ($penginapanList as $item)
                    <div class="col-lg-4 col-md-6">
                        <div class="wisata-card-small" style="height: 100%; display: flex; flex-direction: column;">
                            <div class="wisata-card-img" style="position: relative; height: 220px; overflow: hidden; background-color: #eee;">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_penginapan }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="width: 100%; height: 100%; background: #e9ecef;">
                                        <i class="fas fa-hotel" style="font-size: 3.5rem; color: #a5b5a9;"></i>
                                        <span class="mt-2" style="font-size: 0.85rem; font-weight: 500;">Foto Penginapan Belum Tersedia</span>
                                    </div>
                                @endif

                                @if($item->kisaran_harga)
                                    <div style="position: absolute; bottom: 12px; left: 12px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(4px); color: #e8c97a; font-weight: 700; font-size: 0.82rem; padding: 0.35rem 0.75rem; border-radius: 6px; border: 1px solid rgba(232, 201, 122, 0.3);">
                                        <i class="fas fa-tag me-1"></i> {{ $item->kisaran_harga }}
                                    </div>
                                @endif
                            </div>

                            <div class="wisata-card-body d-flex flex-column justify-content-between flex-grow-1" style="padding: 1.5rem;">
                                <div>
                                    <span class="wisata-card-tag" style="background-color: var(--green-mist); color: var(--green-dark); padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                        {{ $item->jenis }}
                                    </span>
                                    <h3 class="wisata-card-title" style="margin-top: 0.75rem; font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700;">
                                        <a href="{{ url('/penginapan/' . $item->id) }}" style="color: var(--green-deep); text-decoration: none; transition: color 0.2s;">
                                            {{ $item->nama_penginapan }}
                                        </a>
                                    </h3>

                                    <div style="font-size: 0.85rem; color: var(--text-mid); margin-top: 0.5rem;">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        <strong>Lokasi / Jarak:</strong> {{ $item->jarak }}
                                    </div>

                                    <div style="font-size: 0.85rem; color: var(--text-mid); margin-top: 0.5rem; line-height: 1.5;">
                                        <i class="fas fa-concierge-bell me-1" style="color: var(--gold);"></i>
                                        <strong>Fasilitas:</strong> {{ Str::limit($item->fasilitas_singkat, 90) }}
                                    </div>

                                    @if($item->wisata->isNotEmpty())
                                        <div class="mt-3">
                                            <small class="text-muted d-block mb-1" style="font-size: 0.78rem; font-weight: 600;">Wisata Terdekat:</small>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($item->wisata as $w)
                                                    <a href="{{ url('/wisata/' . $w->slug) }}" class="badge bg-light text-dark border text-decoration-none" style="font-weight: 500; font-size: 0.72rem; padding: 0.25rem 0.5rem;">
                                                        <i class="fas fa-umbrella-beach me-1 text-success"></i>{{ $w->nama }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="wisata-card-footer d-flex justify-content-between align-items-center" style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.06);">
                                    <a href="{{ url('/penginapan/' . $item->id) }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 50px; padding: 0.25rem 0.85rem; font-size: 0.8rem; font-weight: 600;">
                                        Detail Info
                                    </a>

                                    @if($item->no_telepon)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_telepon) }}" target="_blank" class="btn btn-sm btn-success" style="border-radius: 50px; padding: 0.25rem 0.85rem; font-size: 0.8rem; font-weight: 600;">
                                            <i class="fab fa-whatsapp me-1"></i> Pesan / Chat
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-hotel fa-3x mb-3 d-block" style="color: var(--green-pale);"></i>
                            <h5 style="color: var(--green-deep); font-family: 'Playfair Display', serif;">
                                @if (request('q'))
                                    Penginapan tidak ditemukan
                                @else
                                    Belum ada penginapan yang terdaftar
                                @endif
                            </h5>
                            <p class="text-muted" style="font-size: 0.9rem;">
                                @if (request('q'))
                                    Coba gunakan kata kunci lain atau
                                    <a href="{{ url('/penginapan') }}" style="color: var(--green-fresh);">lihat semua penginapan</a>.
                                @else
                                    Daftar penginapan & homestay sedang disiapkan oleh pemerintah desa.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($penginapanList->hasPages())
                <div class="pagination-custom">
                    {{-- Previous --}}
                    @if ($penginapanList->onFirstPage())
                        <span class="page-item-custom" style="opacity:0.4; pointer-events:none;">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $penginapanList->previousPageUrl() }}" class="page-item-custom">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($penginapanList->getUrlRange(1, $penginapanList->lastPage()) as $page => $url)
                        @if ($page <= 5 || $page === $penginapanList->lastPage() || abs($page - $penginapanList->currentPage()) <= 1)
                            <a href="{{ $url }}"
                                class="page-item-custom {{ $page === $penginapanList->currentPage() ? 'active' : '' }}">
                                {{ $page }}
                            </a>
                        @elseif ($page === 6 && $penginapanList->lastPage() > 7)
                            <span class="page-item-custom" style="pointer-events:none; border:none;">…</span>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($penginapanList->hasMorePages())
                        <a href="{{ $penginapanList->nextPageUrl() }}" class="page-item-custom">
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
        document.getElementById('searchPenginapan')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });

        // Debounced search (auto-submit after 800ms of typing)
        let searchTimer;
        document.getElementById('searchPenginapan')?.addEventListener('input', function() {
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
