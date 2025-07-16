<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Kulmn Barbershop')</title>
    <meta name="description" content="@yield('description', 'Kulmn Barbershop - Be Cool, Be a Man, with Kulmn. Temukan gayamu di Kulmn.')">
    <meta name="keywords" content="@yield('keywords', 'kulmn, barbershop, pangkas rambut, gaya rambut, potong rambut, pria, grooming')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Kulmn Barbershop')">
    <meta property="og:description" content="@yield('description', 'Kulmn Barbershop - Be Cool, Be a Man, with Kulmn. Temukan gayamu di Kulmn.')">
    <meta property="og:image" content="@yield('og:image', asset('img/general/og-image.webp'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Kulmn Barbershop')">
    <meta property="twitter:description" content="@yield('description', 'Kulmn Barbershop - Be Cool, Be a Man, with Kulmn. Temukan gayamu di Kulmn.')">
    <meta property="twitter:image" content="@yield('og:image', asset('img/general/og-image.webp'))">

    {{-- <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Kulmn Barbershop",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('img/general/logo.webp') }}",
      "description": "Kulmn Barbershop - Be Cool, Be a Man, with Kulmn. Temukan gayamu di Kulmn."
    }
    </script> --}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- SwiperJS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('kepala')
</head>

<body>
    <header class="relative">
        @include('layouts.navbar')
        @yield('banner')
    </header>
    @yield('content')
    @include('layouts.footer')
