<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>
        @yield('title', 'Website Resmi') | Desa Umbu Mamijuk
    </title>

    <meta name="description" content="@yield('meta_description', 'Website resmi Desa Umbu Mamijuk. Portal informasi desa, data kependudukan, wisata, berita, galeri, dan pelayanan publik.')" />

    {{-- Open Graph / Facebook / WhatsApp --}}
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('title', 'Website Resmi') | Desa Umbu Mamijuk" />
    <meta property="og:description" content="@yield('meta_description', 'Website resmi Desa Umbu Mamijuk. Portal informasi desa, data kependudukan, wisata, berita, galeri, dan pelayanan publik.')" />
    <meta property="og:image" content="@yield('meta_image', asset('fe/assets/img/logo-desa.png'))" />

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="@yield('title', 'Website Resmi') | Desa Umbu Mamijuk" />
    <meta property="twitter:description" content="@yield('meta_description', 'Website resmi Desa Umbu Mamijuk. Portal informasi desa, data kependudukan, wisata, berita, galeri, dan pelayanan publik.')" />
    <meta property="twitter:image" content="@yield('meta_image', asset('fe/assets/img/logo-desa.png'))" />

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

    {{-- Boxicons --}}
    <link href="{{ asset('template/assets/vendor/fonts/boxicons.css') }}" rel="stylesheet" />

    {{-- Google Fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet" />

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('fe/assets/img/logo-desa.png') }}" />
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('fe/assets/img/logo-desa.png') }}" />
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('fe/assets/img/logo-desa.png') }}" />
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('fe/assets/img/logo-desa.png') }}" />

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('fe/assets/css/main.css') }}" />

    @stack('styles')
</head>

<body>
    @include('frontend.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('frontend.partials.lightbox')
    @include('frontend.partials.footer')

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom JS --}}
    <script src="{{ asset('fe/assets/js/all.js') }}"></script>

    @stack('scripts')
</body>

</html>
