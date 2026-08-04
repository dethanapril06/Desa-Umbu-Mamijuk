@extends('frontend.layouts.app')

@section('title', $penginapan->nama_penginapan . ' - Penginapan Desa')

@section(
    'meta_description',
    'Detail informasi penginapan ' . $penginapan->nama_penginapan . ' di Desa ' . ($profilDesa?->nama_desa) . '. Fasilitas: ' . $penginapan->fasilitas_singkat . '. Tarikh / Harga: ' . $penginapan->kisaran_harga
)

@section('content')
    {{-- PAGE HEADER --}}
    <header class="page-header">
        <div class="container position-relative" style="z-index: 2;">
            <div class="breadcrumb-custom">
                <a href="{{ url('/') }}">Beranda</a>
                <i class="fas fa-chevron-right"></i>
                <a href="{{ url('/penginapan') }}" style="color: rgba(255,255,255,0.85); text-decoration: none;">Penginapan</a>
                <i class="fas fa-chevron-right"></i>
                <span style="color:rgba(255,255,255,0.95)">{{ $penginapan->nama_penginapan }}</span>
            </div>
            <h1 class="page-title">{{ $penginapan->nama_penginapan }}</h1>
            <p class="page-desc">
                <span class="badge bg-success me-2" style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">{{ $penginapan->jenis }}</span>
                <i class="fas fa-map-marker-alt text-warning me-1"></i> {{ $penginapan->jarak }}
            </p>
        </div>
    </header>

    <main class="py-5" style="background-color: var(--body-bg, #f8fafc);">
        <div class="container">
            <div class="row g-4">
                {{-- Main Content --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div style="position: relative; max-height: 450px; overflow: hidden; background: #000;">
                            @if ($penginapan->foto)
                                <img src="{{ asset('storage/' . $penginapan->foto) }}" alt="{{ $penginapan->nama_penginapan }}" style="width: 100%; height: 100%; max-height: 450px; object-fit: cover;" />
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5" style="background: #e9ecef; min-height: 300px;">
                                    <i class="fas fa-hotel" style="font-size: 4rem; color: #a5b5a9;"></i>
                                    <span class="mt-2 fw-medium">Foto Penginapan Belum Tersedia</span>
                                </div>
                            @endif

                            @if($penginapan->kisaran_harga)
                                <div style="position: absolute; bottom: 16px; left: 16px; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(6px); color: #e8c97a; font-weight: 700; font-size: 1rem; padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid rgba(232, 201, 122, 0.4);">
                                    <i class="fas fa-tag me-1"></i> {{ $penginapan->kisaran_harga }}
                                </div>
                            @endif
                        </div>

                        <div class="card-body p-4 p-md-5">
                            <h3 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; color: var(--green-deep);">
                                Informasi Penginapan & Homestay
                            </h3>

                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <div class="p-3 rounded-3 bg-light border">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Jenis Akomodasi</small>
                                        <span class="fw-semibold text-dark fs-6">{{ $penginapan->jenis }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 rounded-3 bg-light border">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Kisaran Tarif / Harga</small>
                                        <span class="fw-semibold text-success fs-6">{{ $penginapan->kisaran_harga ?? 'Hubungi Pengelola' }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded-3 bg-light border">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Lokasi / Jarak</small>
                                        <span class="fw-semibold text-dark fs-6"><i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $penginapan->jarak }}</span>
                                    </div>
                                </div>
                            </div>

                            <h5 class="fw-bold mt-4 mb-3" style="color: var(--green-deep);">
                                <i class="fas fa-concierge-bell me-2" style="color: var(--gold);"></i>Fasilitas Penginapan
                            </h5>
                            <div class="p-3 rounded-3 mb-4" style="background-color: var(--green-mist, #f0f7f4); border: 1px solid rgba(16, 185, 129, 0.15);">
                                <p class="mb-0 text-dark" style="line-height: 1.7; font-size: 0.95rem;">
                                    {{ $penginapan->fasilitas_singkat }}
                                </p>
                            </div>

                            @if($penginapan->wisata->isNotEmpty())
                                <h5 class="fw-bold mt-4 mb-3" style="color: var(--green-deep);">
                                    <i class="fas fa-umbrella-beach me-2 text-success"></i>Destinasi Wisata Terdekat
                                </h5>
                                <div class="row g-3">
                                    @foreach($penginapan->wisata as $w)
                                        <div class="col-md-6">
                                            <div class="card h-100 border rounded-3 p-3 transition-hover bg-white">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: #eee;">
                                                        @if($w->gambar_utama)
                                                            <img src="{{ asset('storage/' . $w->gambar_utama) }}" alt="{{ $w->nama }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                                        @else
                                                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                                <i class="fas fa-tree"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">
                                                            <a href="{{ url('/wisata/' . $w->slug) }}" class="text-decoration-none text-dark hover-green">
                                                                {{ $w->nama }}
                                                            </a>
                                                        </h6>
                                                        <small class="text-muted"><i class="fas fa-clock me-1"></i>{{ $w->jam_operasional }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    {{-- Contact Card --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm" style="width: 60px; height: 60px;">
                                    <i class="fab fa-whatsapp fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-1">Reservasi & Kontak</h5>
                            <p class="text-muted small mb-3">Hubungi pengelola untuk pemesanan atau pertanyaan fasilitas.</p>

                            @if($penginapan->no_telepon)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $penginapan->no_telepon) }}" target="_blank" class="btn btn-success btn-lg w-100 rounded-pill mb-2 fw-semibold">
                                    <i class="fab fa-whatsapp me-2"></i> Chat WhatsApp
                                </a>
                                <div class="small text-muted">
                                    No. Telepon: <strong>{{ $penginapan->no_telepon }}</strong>
                                </div>
                            @else
                                <div class="alert alert-warning py-2 small mb-0">Nomor telepon belum tersedia.</div>
                            @endif
                        </div>
                    </div>

                    {{-- Other Accommodations Widget --}}
                    @if($penginapanLainnya->isNotEmpty())
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3" style="color: var(--green-deep); font-family: 'Playfair Display', serif;">
                                    Penginapan Lainnya
                                </h6>
                                <div class="d-flex flex-column gap-3">
                                    @foreach($penginapanLainnya as $other)
                                        <div class="d-flex align-items-center gap-3 p-2 rounded-3 border bg-white">
                                            <div style="width: 65px; height: 65px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: #eee;">
                                                @if($other->foto)
                                                    <img src="{{ asset('storage/' . $other->foto) }}" alt="{{ $other->nama_penginapan }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                        <i class="fas fa-hotel"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="badge bg-light text-dark border me-1" style="font-size: 0.68rem;">{{ $other->jenis }}</span>
                                                <h6 class="mb-1 fw-bold" style="font-size: 0.9rem;">
                                                    <a href="{{ url('/penginapan/' . $other->id) }}" class="text-decoration-none text-dark hover-green">
                                                        {{ $other->nama_penginapan }}
                                                    </a>
                                                </h6>
                                                <small class="text-success fw-semibold" style="font-size: 0.78rem;">{{ $other->kisaran_harga ?? 'Tersedia' }}</small>
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
