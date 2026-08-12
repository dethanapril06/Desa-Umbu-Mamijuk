@extends('frontend.layouts.app')

@section('title', 'Lembaga Kemasyarakatan Desa')

@section(
    'meta_description',
    'Mengenal struktur kepengurusan dan anggota lembaga kemasyarakatan Desa ' . ($profilDesa?->nama_desa) . ' yang berperan aktif memajukan desa.'
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
                Mengenal kepengurusan dan anggota lembaga desa {{ $profilDesa?->nama_desa ?? '' }} dalam mengabdi dan memberdayakan masyarakat.
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
                            placeholder="Cari nama atau jabatan..." id="searchLembaga">
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
            <div class="row g-4 justify-content-center">
                @forelse ($lembagaList as $item)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="profil-detail-card text-center h-100 d-flex flex-column justify-content-between" style="padding: 1.75rem 1.25rem; border-radius: 16px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.04); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div>
                                <div style="width: 110px; height: 110px; border-radius: 50%; overflow: hidden; border: 3px solid var(--green-pale, #d1fae5); margin: 0 auto 1.25rem; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}"
                                            alt="{{ $item->nama }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="width: 100%; height: 100%; background: var(--green-mist, #ecfdf5); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-user" style="font-size: 2.2rem; color: var(--green-mid, #10b981);"></i>
                                        </div>
                                    @endif
                                </div>

                                <h4 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--green-deep, #064e3b); margin-bottom: 0.35rem; line-height: 1.3;">
                                    {{ $item->nama }}
                                </h4>

                                <div style="display: inline-block; background: #f1f5f9; color: var(--green-deep, #064e3b); font-size: 0.78rem; font-weight: 700; padding: 0.3rem 0.75rem; border-radius: 50px; margin-bottom: 0.5rem; letter-spacing: 0.5px;">
                                    {{ $item->jabatan }}
                                </div>

                                @if ($item->nip)
                                    <div style="color: var(--text-light, #64748b); font-size: 0.78rem; margin-top: 0.25rem;">
                                        <i class="far fa-id-badge me-1"></i> NIP: {{ $item->nip }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-sitemap fa-3x mb-3 d-block" style="color: var(--green-pale, #a7f3d0);"></i>
                            <h5 style="color: var(--green-deep, #064e3b); font-family: 'Playfair Display', serif;">
                                @if (request('q'))
                                    Data tidak ditemukan
                                @else
                                    Belum ada data Lembaga Desa
                                @endif
                            </h5>
                            <p class="text-muted" style="font-size: 0.9rem;">
                                @if (request('q'))
                                    Coba gunakan kata kunci lain atau
                                    <a href="{{ url('/lembaga-desa') }}" style="color: var(--green-fresh, #059669);">lihat semua</a>.
                                @else
                                    Data anggota lembaga desa sedang disiapkan oleh pemerintah desa.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($lembagaList->hasPages())
                <div class="pagination-custom mt-5">
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
