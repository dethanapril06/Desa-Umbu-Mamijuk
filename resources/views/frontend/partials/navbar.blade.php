@php
    $profil = $profilDesa ?? \App\Models\ProfilDesa::first();
@endphp

<style>
/* Dropdown Custom Styling for Navbar Desa */
.navbar-desa .dropdown-menu {
    background: rgba(15, 23, 42, 0.96) !important;
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 12px !important;
    padding: 0.6rem !important;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4) !important;
    margin-top: 0.5rem;
}

.navbar-desa .dropdown-item {
    color: rgba(255, 255, 255, 0.85) !important;
    font-size: 0.88rem;
    font-weight: 500;
    padding: 0.55rem 1rem !important;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.navbar-desa .dropdown-item:hover,
.navbar-desa .dropdown-item.active {
    color: #ffffff !important;
    background: linear-gradient(135deg, rgba(82, 169, 110, 0.35), rgba(16, 185, 129, 0.25)) !important;
    transform: translateX(3px);
}

.navbar-desa .dropdown-item i {
    width: 20px;
    text-align: center;
    color: var(--gold, #e8c97a);
}

/* Hover dropdown open on Desktop screens */
@media (min-width: 992px) {
    .navbar-desa .nav-item.dropdown:hover .dropdown-menu {
        display: block;
        animation: navDropdownFade 0.25s ease forwards;
    }
}

@keyframes navDropdownFade {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<nav class="navbar-desa navbar navbar-expand-lg">
    <div class="container-fluid">
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
            <ul class="navbar-nav ms-auto align-items-center">
                {{-- 1. Beranda --}}
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                        href="{{ url('/') }}"
                    >
                        Beranda
                    </a>
                </li>

                {{-- 2. Dropdown: Profil Desa --}}
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle {{ request()->is('profil-desa', 'lembaga-desa*') ? 'active' : '' }}"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        Profil Desa
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->is('profil-desa') ? 'active' : '' }}" href="{{ url('/profil-desa') }}">
                                <i class="fas fa-landmark me-2"></i> Profil & Sejarah
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->is('lembaga-desa*') ? 'active' : '' }}" href="{{ url('/lembaga-desa') }}">
                                <i class="fas fa-sitemap me-2"></i> Lembaga Desa
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 3. Dropdown: Informasi Desa --}}
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle {{ request()->is('kependudukan', 'berita*', 'galeri') ? 'active' : '' }}"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        Informasi Desa
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->is('kependudukan') ? 'active' : '' }}" href="{{ url('/kependudukan') }}">
                                <i class="fas fa-users me-2"></i> Kependudukan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->is('berita*') ? 'active' : '' }}" href="{{ url('/berita') }}">
                                <i class="fas fa-newspaper me-2"></i> Berita Desa
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->is('galeri') ? 'active' : '' }}" href="{{ url('/galeri') }}">
                                <i class="fas fa-images me-2"></i> Galeri Foto
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 4. Dropdown: Potensi & Ekonomi --}}
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle {{ request()->is('wisata*', 'penginapan*', 'umkm*') ? 'active' : '' }}"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        Potensi & Ekonomi
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->is('wisata*') ? 'active' : '' }}" href="{{ url('/wisata') }}">
                                <i class="fas fa-umbrella-beach me-2"></i> Wisata Desa
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->is('penginapan*') ? 'active' : '' }}" href="{{ url('/penginapan') }}">
                                <i class="fas fa-hotel me-2"></i> Penginapan & Homestay
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->is('umkm*') ? 'active' : '' }}" href="{{ url('/umkm') }}">
                                <i class="fas fa-store me-2"></i> UMKM Desa
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 5. Tombol Login --}}
                <li class="nav-item ms-lg-2">
                    <a
                        class="nav-link btn-login-nav"
                        href="{{ route('login') }}"
                        id="navbar-login-btn"
                    >
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>