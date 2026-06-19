<!DOCTYPE html>
<html
    lang="id"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ asset('template/assets/') }}"
    data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8" />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0,
        user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>
        @yield('title', 'Dashboard Admin') | Desa {{ \App\Models\ProfilDesa::first()?->nama_desa ?? 'Sukamaju' }}
    </title>

    <meta
        name="description"
        content="Dashboard administrator website Desa {{ \App\Models\ProfilDesa::first()?->nama_desa ?? 'Sukamaju' }}"
    />

    {{-- Favicon --}}
    <link
        rel="icon"
        type="image/x-icon"
        href="{{ asset('template/assets/img/favicon/favicon.ico') }}"
    />

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:
        ital,wght@0,300;0,400;0,500;0,600;0,700;
        1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
    />

    {{-- Icons --}}
    <link
        rel="stylesheet"
        href="{{ asset('template/assets/vendor/fonts/boxicons.css') }}"
    />

    {{-- Core CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('template/assets/vendor/css/core.css') }}"
        class="template-customizer-core-css"
    />

    <link
        rel="stylesheet"
        href="{{ asset('template/assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css"
    />

    <link
        rel="stylesheet"
        href="{{ asset('template/assets/css/demo.css') }}"
    />

    {{-- Vendors CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}"
    />

    @stack('styles')

    {{-- Helpers --}}
    <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('template/assets/js/config.js') }}"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            @include('admin.layouts.partials.sidebar')

            <div class="layout-page">

                @include('admin.layouts.partials.navbar')

                <div class="content-wrapper">

                    <div class="container-xxl flex-grow-1 container-p-y">
                        @if (session('success'))
                            <div
                                class="alert alert-success alert-dismissible"
                                role="alert"
                            >
                                {!! session('success') !!}

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"
                                    aria-label="Close"
                                ></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div
                                class="alert alert-danger alert-dismissible"
                                role="alert"
                            >
                                {{ session('error') }}

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"
                                    aria-label="Close"
                                ></button>
                            </div>
                        @endif

                        @yield('content')
                    </div>

                    @include('admin.layouts.partials.footer')

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    {{-- Core JS --}}
    <script src="{{ asset('template/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/js/bootstrap.js') }}"></script>

    <script
        src="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"
    ></script>

    <script src="{{ asset('template/assets/vendor/js/menu.js') }}"></script>

    {{-- Main JS --}}
    <script src="{{ asset('template/assets/js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>