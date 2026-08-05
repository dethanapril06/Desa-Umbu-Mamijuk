@extends('frontend.layouts.app')

@section('title', $lembaga->nama_lembaga . ' - Lembaga Desa')

@section(
    'meta_description',
    'Profil dan struktur lembaga kemasyarakatan ' . $lembaga->nama_lembaga . ' (' . ($lembaga->singkatan ?? '-') . ') di Desa ' . ($profilDesa?->nama_desa) . '. Ketua: ' . ($lembaga->ketua ?? '-')
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
                <a href="{{ url('/lembaga-desa') }}" style="color: rgba(255,255,255,0.85); text-decoration: none;">Lembaga Desa</a>
                <i class="fas fa-chevron-right"></i>
                <span style="color:rgba(255,255,255,0.95)">{{ $lembaga->nama_lembaga }}</span>
            </div>
            <h1 class="page-title">{{ $lembaga->nama_lembaga }}</h1>
            @if($lembaga->singkatan)
                <p class="page-desc">
                    <span class="badge bg-warning text-dark me-2" style="font-size: 0.9rem; font-weight: 700;">{{ $lembaga->singkatan }}</span>
                    Lembaga Kemasyarakatan Desa {{ $profilDesa?->nama_desa }}
                </p>
            @endif
        </div>
    </header>

    <main class="py-5" style="background-color: var(--body-bg, #f8fafc);">
        <div class="container">
            <div class="row g-4">
                {{-- Main Content --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center gap-4 mb-4 flex-wrap pb-4 border-bottom">
                                <div style="width: 100px; height: 100px; border-radius: 16px; overflow: hidden; background: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(0,0,0,0.08);">
                                    @if ($lembaga->logo)
                                        <img src="{{ asset('storage/' . $lembaga->logo) }}" alt="{{ $lembaga->nama_lembaga }}" style="max-width: 85%; max-height: 85%; object-fit: contain;" />
                                    @else
                                        <i class="fas fa-sitemap fa-3x text-secondary"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif; color: var(--green-deep);">
                                        {{ $lembaga->nama_lembaga }}
                                    </h3>
                                    @if($lembaga->singkatan)
                                        <span class="badge bg-label-success text-success border border-success me-2" style="font-weight: 600;">
                                            {{ $lembaga->singkatan }}
                                        </span>
                                    @endif
                                    <span class="text-muted small">Mitra Kerja Pemerintah Desa</span>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <div class="p-3 rounded-3 bg-light border">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Ketua / Penanggung Jawab</small>
                                        <span class="fw-semibold text-dark fs-6"><i class="fas fa-user-tie text-primary me-1"></i>{{ $lembaga->ketua ?? 'Belum ditentukan' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 rounded-3 bg-light border">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Kontak / No. Telepon</small>
                                        <span class="fw-semibold text-dark fs-6"><i class="fas fa-phone text-success me-1"></i>{{ $lembaga->no_telepon ?? 'Tidak ada' }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded-3 bg-light border">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Alamat Sekretariat / Kantor</small>
                                        <span class="fw-semibold text-dark fs-6"><i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $lembaga->alamat_sekretariat ?? 'Kantor Desa ' . ($profilDesa?->nama_desa ?? '') }}</span>
                                    </div>
                                </div>
                            </div>

                            <h5 class="fw-bold mt-4 mb-3" style="color: var(--green-deep);">
                                <i class="fas fa-file-alt me-2" style="color: var(--gold);"></i>Profil & Deskripsi Lembaga
                            </h5>
                            <div class="p-4 rounded-3" style="background-color: #ffffff; border: 1px solid #e2e8f0; line-height: 1.8; color: #334155;">
                                @if($lembaga->deskripsi)
                                    {!! nl2br(e($lembaga->deskripsi)) !!}
                                @else
                                    <em class="text-muted">Deskripsi dan rincian profil lembaga desa belum ditambahkan.</em>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    {{-- Contact Card --}}
                    @if($lembaga->no_telepon)
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4 text-center">
                                <div class="mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm" style="width: 60px; height: 60px;">
                                        <i class="fab fa-whatsapp fa-2x"></i>
                                    </div>
                                </div>
                                <h5 class="fw-bold mb-1">Hubungi Sekretariat</h5>
                                <p class="text-muted small mb-3">Kontak sekretariat {{ $lembaga->singkatan ?? $lembaga->nama_lembaga }} untuk informasi kegiatan & keorganisasian.</p>

                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lembaga->no_telepon) }}" target="_blank" class="btn btn-success btn-lg w-100 rounded-pill mb-2 fw-semibold">
                                    <i class="fab fa-whatsapp me-2"></i> Chat WhatsApp
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Other Institutions Widget --}}
                    @if($lembagaLainnya->isNotEmpty())
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3" style="color: var(--green-deep); font-family: 'Playfair Display', serif;">
                                    Lembaga Desa Lainnya
                                </h6>
                                <div class="d-flex flex-column gap-3">
                                    @foreach($lembagaLainnya as $other)
                                        <div class="d-flex align-items-center gap-3 p-2 rounded-3 border bg-white">
                                            <div style="width: 55px; height: 55px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                                                @if($other->logo)
                                                    <img src="{{ asset('storage/' . $other->logo) }}" alt="{{ $other->nama_lembaga }}" style="max-width: 80%; max-height: 80%; object-fit: contain;" />
                                                @else
                                                    <i class="fas fa-sitemap text-secondary"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                @if($other->singkatan)
                                                    <span class="badge bg-light text-dark border me-1" style="font-size: 0.68rem;">{{ $other->singkatan }}</span>
                                                @endif
                                                <h6 class="mb-1 fw-bold" style="font-size: 0.88rem;">
                                                    <a href="{{ url('/lembaga-desa/' . $other->slug) }}" class="text-decoration-none text-dark hover-green">
                                                        {{ $other->nama_lembaga }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted" style="font-size: 0.75rem;">Ketua: {{ $other->ketua ?? '-' }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
