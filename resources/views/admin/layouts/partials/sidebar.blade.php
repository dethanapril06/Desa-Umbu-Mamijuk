@php
    $profilDesa = null;
    try {
        $profilDesa = \App\Models\ProfilDesa::first();
    } catch (\Exception $e) {}
@endphp
<aside
    id="layout-menu"
    class="layout-menu menu-vertical menu bg-menu-theme"
>
    <div class="app-brand demo justify-content-center" style="padding-left:1rem">
        <a
            href="{{ route('admin.dashboard') }}"
            class="app-brand-link"
        >
            <span class="app-brand-logo demo">
                <img
                    src="{{ $profilDesa && $profilDesa->logo ? asset('storage/' . $profilDesa->logo) : asset('fe/assets/img/logo-desa.png') }}"
                    alt="Logo Desa"
                    width="45px"
                    height="45px"
                    style="object-fit: contain"
                />
            </span>

            <span class="app-brand-text demo menu-text fw-bolder fs-5 ms-1">
                Desa {{ $profilDesa ? $profilDesa->nama_desa : 'Umbu Mamijuk' }}
            </span>
        </a>

        <a
            href="javascript:void(0);"
            class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none"
        >
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        {{-- Dashboard --}}
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a
                href="{{ route('admin.dashboard') }}"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        {{-- Profil Desa --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">
                Profil Desa
            </span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.profil-desa.*') ? 'active' : '' }}">
            <a
                href="{{ route('admin.profil-desa.index') }}"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-buildings"></i>
                <div>Profil Desa</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.kepala-desa.*') ? 'active' : '' }}">
            <a
                href="{{ route('admin.kepala-desa.index') }}"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div>Kepala Desa</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.perangkat-desa.*') ? 'active' : '' }}">
            <a
                href="{{ route('admin.perangkat-desa.index') }}"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div>Perangkat Desa</div>
            </a>
        </li>

        {{-- Kependudukan --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">
                Kependudukan
            </span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.dusun.*') || request()->routeIs('admin.rt-rw.*') ? 'active open' : '' }}">
            <a
                href="javascript:void(0);"
                class="menu-link menu-toggle"
            >
                <i class="menu-icon tf-icons bx bx-map"></i>
                <div>Wilayah</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.dusun.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.dusun.index') }}"
                        class="menu-link"
                    >
                        <div>Dusun</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.rt-rw.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.rt-rw.index') }}"
                        class="menu-link"
                    >
                        <div>RT / RW</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.keluarga.*') ? 'active' : '' }}">
            <a
                href="{{ route('admin.keluarga.index') }}"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-home-heart"></i>
                <div>Keluarga</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.penduduk.*') ? 'active' : '' }}">
            <a
                href="{{ route('admin.penduduk.index') }}"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Penduduk</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.mutasi-penduduk.*') ? 'active' : '' }}">
            <a
                href="{{ route('admin.mutasi-penduduk.index') }}"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-transfer"></i>
                <div>Mutasi Penduduk</div>
            </a>
        </li>

        {{-- Konten Website --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">
                Konten Website
            </span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.berita.*') || request()->routeIs('admin.kategori-berita.*') || request()->routeIs('admin.tag.*') || request()->routeIs('admin.komentar-berita.*') ? 'active open' : '' }}">
            <a
                href="javascript:void(0);"
                class="menu-link menu-toggle"
            >
                <i class="menu-icon tf-icons bx bx-news"></i>
                <div>Berita</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.berita.index') }}"
                        class="menu-link"
                    >
                        <div>Daftar Berita</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.kategori-berita.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.kategori-berita.index') }}"
                        class="menu-link"
                    >
                        <div>Kategori</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.tag.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.tag.index') }}"
                        class="menu-link"
                    >
                        <div>Tag</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.komentar-berita.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.komentar-berita.index') }}"
                        class="menu-link"
                    >
                        <div>Komentar</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.album-galeri.*') || request()->routeIs('admin.galeri.*') ? 'active open' : '' }}">
            <a
                href="javascript:void(0);"
                class="menu-link menu-toggle"
            >
                <i class="menu-icon tf-icons bx bx-camera"></i>
                <div>Galeri</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.album-galeri.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.album-galeri.index') }}"
                        class="menu-link"
                    >
                        <div>Album</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.galeri.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.galeri.index') }}"
                        class="menu-link"
                    >
                        <div>Foto Galeri</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.wisata.*') || request()->routeIs('admin.kategori-wisata.*') || request()->routeIs('admin.ulasan-wisata.*') ? 'active open' : '' }}">
            <a
                href="javascript:void(0);"
                class="menu-link menu-toggle"
            >
                <i class="menu-icon tf-icons bx bx-map-alt"></i>
                <div>Wisata</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.wisata.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.wisata.index') }}"
                        class="menu-link"
                    >
                        <div>Destinasi Wisata</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.kategori-wisata.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.kategori-wisata.index') }}"
                        class="menu-link"
                    >
                        <div>Kategori Wisata</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.ulasan-wisata.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.ulasan-wisata.index') }}"
                        class="menu-link"
                    >
                        <div>Ulasan Wisata</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- UMKM --}}
        <li class="menu-item {{ request()->routeIs('admin.umkm.*') ? 'active' : '' }}">
            <a
                href="{{ route('admin.umkm.index') }}"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div>UMKM</div>
            </a>
        </li>

        {{-- Pengaduan --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">
                Pelayanan Publik
            </span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.pengaduan.*') || request()->routeIs('admin.kategori-pengaduan.*') ? 'active open' : '' }}">
            <a
                href="javascript:void(0);"
                class="menu-link menu-toggle"
            >
                <i class="menu-icon tf-icons bx bx-message-square-dots"></i>
                <div>Pengaduan</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.pengaduan.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.pengaduan.index') }}"
                        class="menu-link"
                    >
                        <div>Daftar Pengaduan</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.kategori-pengaduan.*') ? 'active' : '' }}">
                    <a
                        href="{{ route('admin.kategori-pengaduan.index') }}"
                        class="menu-link"
                    >
                        <div>Kategori Pengaduan</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Pengaturan --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">
                Pengaturan
            </span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.slider.*') ? 'active' : '' }}">
            <a
                href="{{ route('admin.slider.index') }}"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-slideshow"></i>
                <div>Slider</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.sosial-media.*') ? 'active' : '' }}">
            <a
                href="{{ route('admin.sosial-media.index') }}"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-share-alt"></i>
                <div>Sosial Media</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <a
                href="{{ route('admin.profile.edit') }}"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Profil & Password</div>
            </a>
        </li>
    </ul>
</aside>