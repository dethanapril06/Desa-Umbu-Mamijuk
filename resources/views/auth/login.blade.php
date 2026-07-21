<!DOCTYPE html>
<html
    lang="id"
    class="light-style customizer-hide"
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

    <title>Login Admin | Desa Sukamaju</title>

    <meta
        name="description"
        content="Halaman login administrator website Desa Sukamaju"
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

    {{-- Page CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('template/assets/vendor/css/pages/page-auth.css') }}"
    />

    {{-- Helpers --}}
    <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('template/assets/js/config.js') }}"></script>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        {{-- Logo --}}
                        <div class="app-brand justify-content-center mb-4">
                            <a
                                href="{{ route('login') }}"
                                class="app-brand-link gap-2"
                            >
                                <span class="app-brand-logo demo">
                                    <img src="{{ asset('fe/assets/img/logo-desa.png') }}" alt="Logo" width="60px" height="60px"   >
                                </span>

                                <span class="app-brand-text demo text-body fw-bolder">
                                    Desa Umbu Mamijuk
                                </span>
                            </a>
                        </div>

                        <h4 class="mb-2">
                            Selamat Datang 👋
                        </h4>

                        <p class="mb-4">
                            Silakan masuk untuk mengakses dashboard administrator.
                        </p>

                        @if (session('success'))
                            <div
                                class="alert alert-success alert-dismissible"
                                role="alert"
                            >
                                {{ session('success') }}

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"
                                    aria-label="Close"
                                ></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div
                                class="alert alert-danger alert-dismissible"
                                role="alert"
                            >
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"
                                    aria-label="Close"
                                ></button>
                            </div>
                        @endif

                        <form
                            id="formAuthentication"
                            class="mb-3"
                            action="{{ route('login.store') }}"
                            method="POST"
                        >
                            @csrf

                            <div class="mb-3">
                                <label
                                    for="email"
                                    class="form-label"
                                >
                                    Email
                                </label>

                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Masukkan email admin"
                                    autocomplete="email"
                                    autofocus
                                />
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label
                                        class="form-label"
                                        for="password"
                                    >
                                        Password
                                    </label>
                                </div>

                                <div class="input-group input-group-merge">
                                    <input
                                        type="password"
                                        id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password"
                                        placeholder="············"
                                        autocomplete="current-password"
                                    />

                                    <span class="input-group-text cursor-pointer">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="remember"
                                        name="remember"
                                        value="1"
                                        {{ old('remember') ? 'checked' : '' }}
                                    />

                                    <label
                                        class="form-check-label"
                                        for="remember"
                                    >
                                        Ingat saya
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button
                                    class="btn btn-primary d-grid w-100"
                                    type="submit"
                                >
                                    Masuk
                                </button>
                            </div>
                        </form>

                        <p class="text-center mb-0 text-muted">
                            Website Resmi Pemerintah Desa Umbu Mamijuk
                        </p>
                    </div>
                </div>
            </div>
        </div>
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
</body>
</html>