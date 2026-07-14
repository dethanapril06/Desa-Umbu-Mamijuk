@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                Selamat datang, {{ auth()->user()->name }}! 👋
                            </h5>

                            <p class="mb-3">
                                Gunakan dashboard ini untuk mengelola data desa,
                                kependudukan, berita, wisata, galeri, dan pengaduan masyarakat.
                            </p>
                            <a href="{{ route('admin.panduan') }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 shadow-sm" style="border-radius: 8px;">
                                <i class="bx bx-download"></i> Download Buku Panduan
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img
                                src="{{ asset('template/assets/img/illustrations/man-with-laptop-light.png') }}"
                                height="140"
                                alt="Dashboard Admin"
                                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div
                                class="card-title d-flex
                                align-items-start justify-content-between"
                            >
                                <div class="avatar flex-shrink-0">
                                    <span
                                        class="avatar-initial rounded bg-label-primary"
                                    >
                                        <i class="bx bx-user"></i>
                                    </span>
                                </div>
                            </div>

                            <span class="fw-semibold d-block mb-1">
                                Penduduk
                            </span>

                            <h3 class="card-title mb-2">
                                {{ number_format($statistik['total_penduduk']) }}
                            </h3>

                            <small class="text-muted">
                                Penduduk aktif
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div
                                class="card-title d-flex
                                align-items-start justify-content-between"
                            >
                                <div class="avatar flex-shrink-0">
                                    <span
                                        class="avatar-initial rounded bg-label-success"
                                    >
                                        <i class="bx bx-home-heart"></i>
                                    </span>
                                </div>
                            </div>

                            <span class="fw-semibold d-block mb-1">
                                Keluarga
                            </span>

                            <h3 class="card-title mb-2">
                                {{ number_format($statistik['total_keluarga']) }}
                            </h3>

                            <small class="text-muted">
                                Kartu keluarga
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div
                        class="d-flex align-items-start justify-content-between"
                    >
                        <div>
                            <span class="fw-semibold d-block mb-1">
                                Berita
                            </span>

                            <h3 class="card-title mb-2">
                                {{ number_format($statistik['total_berita']) }}
                            </h3>

                            <small class="text-muted">
                                Total artikel berita
                            </small>
                        </div>

                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-news"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div
                        class="d-flex align-items-start justify-content-between"
                    >
                        <div>
                            <span class="fw-semibold d-block mb-1">
                                Wisata
                            </span>

                            <h3 class="card-title mb-2">
                                {{ number_format($statistik['total_wisata']) }}
                            </h3>

                            <small class="text-muted">
                                Destinasi wisata
                            </small>
                        </div>

                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-map-alt"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div
                        class="d-flex align-items-start justify-content-between"
                    >
                        <div>
                            <span class="fw-semibold d-block mb-1">
                                Pengaduan Masuk
                            </span>

                            <h3 class="card-title mb-2">
                                {{ number_format($statistik['total_pengaduan_masuk']) }}
                            </h3>

                            <small class="text-muted">
                                Perlu ditinjau admin
                            </small>
                        </div>

                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="bx bx-message-square-dots"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection