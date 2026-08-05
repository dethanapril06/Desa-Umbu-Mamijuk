@extends('frontend.layouts.app')

@section('title', 'Lembaga Kemasyarakatan Desa')

@section(
    'meta_description',
    'Mengenal lembaga kemasyarakatan Desa ' . ($profilDesa?->nama_desa) . ' seperti BPD, PKK, Karang Taruna, LPM, dan Posyandu yang berperan aktif memajukan desa.'
)

@section('content')
    {{-- PAGE HEADER --}}
    <header class="page-header">
        <div class="container position-relative" style="z-index: 2;">
            <div class="breadcrumb-custom">
                <a href="{{ url('/') }}">Beranda</a>
                <i class="fas fa-chevron-right"></i>
                <a href="{{ url('/profil-desa') }}" style="color: rgba(255,255,255,0.85); text-decoration: none;">Profil Desa</a>
                <i class="fas fa-chevron-right"></i>
                <span style="color:rgba(255,255,255,0.95)">Lembaga Desa</span>
            </div>
            <h1 class="page-title">Lembaga <em>Kemasyarakatan</em></h1>
            <p class="page-desc">
                Mengenal organisasi dan mitra kerja pemerintah Desa {{ $profilDesa?->nama_desa ?? '' }} dalam mengabdi dan memberdayakan masyarakat.
            </p>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="wisata-grid-section">
        <div class="container">

            {{-- Search Box --}}
            <div class="filter-wrapper mb-5 justify-content-end">
                <div class="search-box w-100 style-search-full" style="max-width: 450px;">
                    <i class="fas fa-search"></i>
                    <form action="{{ url('/lembaga-desa') }}" method="GET" id="searchForm">
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Cari lembaga desa atau ketua..." id="searchLembaga">
                    </form>
                </div>
            </div>

            {{-- Search result info --}}
            @if (request('q'))
                <div class="mb-4">
                    <p style="color: var(--text-mid); font-size: 0.9rem;">
                        Menampilkan <strong>{{ $lembagaList->total() }}</strong> hasil untuk
                        "<strong>{{ request('q') }}</strong>"
                        <a href="{{ url('/lembaga-desa') }}" style="color: var(--green-fresh); margin-left: 0.5rem;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </p>
                </div>
            @endif

            {{-- Lembaga Grid --}}
            <div class="row g-4">
                @forelse ($lembagaList as $item)
                    <div class="col-lg-4 col-md-6">
                        <div class="wisata-card-small" style="height: 100%; display: flex; flex-direction: column;">
                            <div class="wisata-card-img" style="position: relative; height: 180px; overflow: hidden; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid rgba(0,0,0,0.06);">
                                @if ($item->logo)
                                    <img src="{{ asset('storage/' . $item->logo) }}" alt="{{ $item->nama_lembaga }}" style="max-width: 80%; max-height: 80%; object-fit: contain;" />
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                        <i class="fas fa-sitemap" style="font-size: 3.5rem; color: #cbd5e1;"></i>
                                    </div>
                                @endif

                                @if($item->singkatan)
                                    <div style="position: absolute; top: 12px; right: 12px; background: var(--green-deep); color: #fff; font-weight: 700; font-size: 0.78rem; padding: 0.25rem 0.65rem; border-radius: 50px;">
                                        {{ $item->singkatan }}
                                    </div>
                                @endif
                            </div>

                            <div class="wisata-card-body d-flex flex-column justify-content-between flex-grow-1" style="padding: 1.5rem;">
                                <div>
                                    <h3 class="wisata-card-title" style="font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; line-height: 1.4;">
                                        <a href="{{ url('/lembaga-desa/' . $item->slug) }}" style="color: var(--green-deep); text-decoration: none; transition: color 0.2s;">
                                            {{ $item->nama_lembaga }}
                                        </a>
                                    </h3>

                                    @if($item->ketua)
                                        <div style="font-size: 0.85rem; color: var(--text-mid); margin-top: 0.6rem;">
                                            <i class="fas fa-user-tie text-primary me-1"></i>
                                            <strong>Ketua:</strong> {{ $item->ketua }}
                                        </div>
                                    @endif

                                    @if($item->alamat_sekretariat)
                                        <div style="font-size: 0.82rem; color: var(--text-mid); margin-top: 0.35rem;">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                            {{ Str::limit($item->alamat_sekretariat, 45) }}
                                        </div>
                                    @endif

                                    <p class="wisata-card-desc" style="font-size: 0.85rem; color: var(--text-mid); margin-top: 0.75rem; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit(strip_tags($item->deskripsi), 110) ?? 'Informasi deskripsi dan profil lembaga desa belum ditambahkan.' }}
                                    </p>
                                </div>

                                <div class="wisata-card-footer d-flex justify-content-between align-items-center" style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.06);">
                                    <a href="{{ url('/lembaga-desa/' . $item->slug) }}" class="btn btn-sm btn-outline-success w-100 rounded-pill font-weight-600" style="font-size: 0.82rem;">
                                        Detail Lembaga <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-sitemap fa-3x mb-3 d-block" style="color: var(--green-pale);"></i>
                            <h5 style="color: var(--green-deep); font-family: 'Playfair Display', serif;">
                                @if (request('q'))
                                    Lembaga Desa tidak ditemukan
                                @else
                                    Belum ada Lembaga Desa yang terdaftar
                                @endif
                            </h5>
                            <p class="text-muted" style="font-size: 0.9rem;">
                                @if (request('q'))
                                    Coba gunakan kata kunci lain atau
                                    <a href="{{ url('/lembaga-desa') }}" style="color: var(--green-fresh);">lihat semua lembaga</a>.
                                @else
                                    Data lembaga kemasyarakatan desa sedang disiapkan oleh pemerintah desa.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($lembagaList->hasPages())
                <div class="pagination-custom">
                    {{-- Previous --}}
                    @if ($lembagaList->onFirstPage())
                        <span class="page-item-custom" style="opacity:0.4; pointer-events:none;">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $lembagaList->previousPageUrl() }}" class="page-item-custom">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($lembagaList->getUrlRange(1, $lembagaList->lastPage()) as $page => $url)
                        @if ($page <= 5 || $page === $lembagaList->lastPage() || abs($page - $lembagaList->currentPage()) <= 1)
                            <a href="{{ $url }}"
                                class="page-item-custom {{ $page === $lembagaList->currentPage() ? 'active' : '' }}">
                                {{ $page }}
                            </a>
                        @elseif ($page === 6 && $lembagaList->lastPage() > 7)
                            <span class="page-item-custom" style="pointer-events:none; border:none;">…</span>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($lembagaList->hasMorePages())
                        <a href="{{ $lembagaList->nextPageUrl() }}" class="page-item-custom">
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
        document.getElementById('searchLembaga')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });

        // Debounced search (auto-submit after 800ms of typing)
        let searchTimer;
        document.getElementById('searchLembaga')?.addEventListener('input', function() {
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
