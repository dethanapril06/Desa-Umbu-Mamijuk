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

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Intercept forms with native confirm in onsubmit
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                const onsubmitAttr = form.getAttribute('onsubmit');
                if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                    // Extract the message inside confirm('...')
                    const match = onsubmitAttr.match(/confirm\(['"](.+?)['"]\)/);
                    const message = match ? match[1] : 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                    
                    // Remove the inline onsubmit attribute
                    form.removeAttribute('onsubmit');
                    
                    // Add submit event listener
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        
                        Swal.fire({
                            title: 'Konfirmasi Tindakan',
                            text: message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, Lanjutkan!',
                            cancelButtonText: 'Batal',
                            customClass: {
                                confirmButton: 'btn btn-primary me-2',
                                cancelButton: 'btn btn-secondary'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                }
            });

            // 2. Intercept flash messages from session
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    html: {!! json_encode(session('success')) !!},
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: {!! json_encode(session('error')) !!},
                    showConfirmButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Tutup'
                });
            @endif

            // 3. Override global alert with SweetAlert2
            window.alert = function (message) {
                Swal.fire({
                    title: 'Informasi',
                    text: message,
                    icon: 'info',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Tutup'
                });
            };
        });
    </script>

    @stack('scripts')
</body>
</html>