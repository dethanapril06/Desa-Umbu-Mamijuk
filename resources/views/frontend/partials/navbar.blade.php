@php
    $profil = $profilDesa ?? \App\Models\ProfilDesa::first();
@endphp

<nav class="navbar-desa navbar navbar-expand-lg">
    <div class="container">
        <a
            class="navbar-brand"
            href="{{ url('/') }}"
        >
            <div class="brand-logo" style="overflow: hidden; display: flex; align-items: center; justify-content: center;">
                <img
                    src="{{ $profil?->logo ? asset('storage/' . $profil->logo) : asset('fe/assets/img/logo-desa.png') }}"
                    alt="Logo {{ $profil?->nama_desa }}"
                    style="width: 100%; height: 100%; object-fit: contain; padding: 4px;"
                >
            </div>

            <div class="brand-text">
                <div class="brand-name">
                    Desa {{ $profil?->nama_desa }}
                </div>

                <div class="brand-sub">
                    Kec. {{ $profil?->kecamatan }} · Kab. {{ $profil?->kabupaten }}
                </div>
            </div>
        </a>

        <button
            class="navbar-toggler border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNav"
            aria-controls="mainNav"
            aria-expanded="false"
            aria-label="Buka navigasi"
        >
            <span style="color: white; font-size: 1.3rem">
                <i class="fas fa-bars"></i>
            </span>
        </button>

        <div
            class="collapse navbar-collapse"
            id="mainNav"
        >
            <ul class="navbar-nav ms-auto align-items-center gap-1">
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                        href="{{ url('/') }}"
                    >
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->is('profil-desa') ? 'active' : '' }}"
                        href="{{ url('/profil-desa') }}"
                    >
                        Profil Desa
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->is('kependudukan') ? 'active' : '' }}"
                        href="{{ url('/kependudukan') }}"
                    >
                        Kependudukan
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="{{ url('/berita') }}"
                    >
                        Berita
                    </a>
                </li>

                <li class="nav-item ms-lg-2">
                    <a
                        class="nav-link btn-wisata-nav {{ request()->is('wisata*') ? 'active' : '' }}"
                        href="{{ url('/wisata') }}"
                    >
                        🌿 Tempat Wisata
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>