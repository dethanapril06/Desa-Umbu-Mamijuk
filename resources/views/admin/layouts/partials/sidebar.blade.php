<aside
    id="layout-menu"
    class="layout-menu menu-vertical menu bg-menu-theme"
>
    <div class="app-brand demo">
        <a
            href="{{ route('admin.dashboard') }}"
            class="app-brand-link"
        >
            <span class="app-brand-logo demo">
                <i class="bx bx-building-house fs-2 text-primary"></i>
            </span>

            <span class="app-brand-text demo menu-text fw-bolder fs-4 ms-2">
                Desa Sukamaju
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

        <li class="menu-item">
            <a
                href="javascript:void(0);"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-buildings"></i>
                <div>Profil Desa</div>
            </a>
        </li>

        <li class="menu-item">
            <a
                href="javascript:void(0);"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div>Kepala Desa</div>
            </a>
        </li>

        <li class="menu-item">
            <a
                href="javascript:void(0);"
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

        <li class="menu-item">
            <a
                href="javascript:void(0);"
                class="menu-link menu-toggle"
            >
                <i class="menu-icon tf-icons bx bx-map"></i>
                <div>Wilayah</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
                        class="menu-link"
                    >
                        <div>Dusun</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
                        class="menu-link"
                    >
                        <div>RT / RW</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item">
            <a
                href="javascript:void(0);"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-home-heart"></i>
                <div>Keluarga</div>
            </a>
        </li>

        <li class="menu-item">
            <a
                href="javascript:void(0);"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Penduduk</div>
            </a>
        </li>

        <li class="menu-item">
            <a
                href="javascript:void(0);"
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

        <li class="menu-item">
            <a
                href="javascript:void(0);"
                class="menu-link menu-toggle"
            >
                <i class="menu-icon tf-icons bx bx-news"></i>
                <div>Berita</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
                        class="menu-link"
                    >
                        <div>Daftar Berita</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
                        class="menu-link"
                    >
                        <div>Kategori</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
                        class="menu-link"
                    >
                        <div>Tag</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
                        class="menu-link"
                    >
                        <div>Komentar</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item">
            <a
                href="javascript:void(0);"
                class="menu-link menu-toggle"
            >
                <i class="menu-icon tf-icons bx bx-camera"></i>
                <div>Galeri</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
                        class="menu-link"
                    >
                        <div>Album</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
                        class="menu-link"
                    >
                        <div>Foto Galeri</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item">
            <a
                href="javascript:void(0);"
                class="menu-link menu-toggle"
            >
                <i class="menu-icon tf-icons bx bx-map-alt"></i>
                <div>Wisata</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
                        class="menu-link"
                    >
                        <div>Destinasi Wisata</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
                        class="menu-link"
                    >
                        <div>Kategori Wisata</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Pengaduan --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">
                Pelayanan Publik
            </span>
        </li>

        <li class="menu-item">
            <a
                href="javascript:void(0);"
                class="menu-link menu-toggle"
            >
                <i class="menu-icon tf-icons bx bx-message-square-dots"></i>
                <div>Pengaduan</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
                        class="menu-link"
                    >
                        <div>Daftar Pengaduan</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a
                        href="javascript:void(0);"
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

        <li class="menu-item">
            <a
                href="javascript:void(0);"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-slideshow"></i>
                <div>Slider</div>
            </a>
        </li>

        <li class="menu-item">
            <a
                href="javascript:void(0);"
                class="menu-link"
            >
                <i class="menu-icon tf-icons bx bx-share-alt"></i>
                <div>Sosial Media</div>
            </a>
        </li>
    </ul>
</aside>