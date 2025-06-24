@extends('layouts.main')

@section('title', 'Kulmn Barbershop - Be Cool, Be a Man, with Kulmn')
@section('description',
    'Kulmn Barbershop adalah tempat pangkas rambut pria modern yang menawarkan gaya terkini dan
    pelayanan profesional. Temukan gaya terbaikmu bersama kami.')
@section('keywords',
    'kulmn barbershop, pangkas rambut pria, gaya rambut modern, potong rambut profesional, barbershop
    terbaik')
@section('og:image', asset('img/general/og-image-homepage.webp'))

@section('kepala')

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Kulmn Barbershop - Be Cool, Be a Man, with Kulmn",
      "description": "Kulmn Barbershop adalah tempat pangkas rambut pria modern yang menawarkan gaya terkini dan pelayanan profesional. Temukan gaya terbaikmu bersama kami.",
      "url": "{{ url('/') }}",
      "publisher": {
        "@type": "Organization",
        "name": "Kulmn Barbershop",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ asset('img/general/logo.webp') }}"
        }
      }
    }
    </script>
    <!-- SwiperJS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endsection
@section('banner')
    <section
        class="pt-20 md:pt-52 min-h-[250px] md:min-h-[500px] xl:min-h-screen 2xl:min-h-[500px] relative z-10 bg-[url('../../public/img/general/bg-banner.webp')] bg-no-repeat bg-cover bg-right-top"
        style="background: linear-gradient(180deg, #191919 0%, #535353 100%);">
        <div class="container mx-auto p-6 xl:p-0 xl:py-10 2xl:max-w-screen-xl">
            <div
                class="uppercase font-poppins md:pb-20 font-bold text-white text-2xl md:text-7xl lg:text-8xl xl:text-9xl 2xl:text-9xl">
                be cool <br>
                be a man <br>
                with kulmn
            </div>
            <div class="absolute right-0 2xl:right-70 bottom-0">
                <img src="{{ asset('img/general/profile.webp') }}" class="h-[200px] md:h-[500px] xl:h-[700px] 2xl:hidden"
                    alt="profile">
                <img src="{{ asset('img/general/profile2.webp') }}"
                    class="hidden 2xl:block h-[200px] md:h-[500px] xl:h-[700px]" alt="profile">
            </div>
        </div>
    </section>
@endsection
@section('content')
    <section class="bg-hitam h-auto">
        <div class="container mx-auto py-20 p-6 xl:p-0 xl:py-20 2xl:max-w-screen-xl">
            <div class="flex flex-col lg:flex-row justify-center items-center gap-5">
                <div class="flex-1 font-poppins">
                    <div class="text-white italic text-4xl mb-5 uppercase">
                        Bergabung Dengan Mitra Kami
                    </div>
                    <div class="text-white text-lg my-10">
                        Bergabung sebagai mitra KULMN berarti membuka peluang besar untuk berkembang bersama. Dapatkan
                        dukungan promosi, akses ke jaringan luas, dan sistem kolaborasi yang saling menguntungkan.
                        Tumbuh dan Maju Bersama KULMN!
                    </div>
                    <div class="flex w-full">
                        <a href="/registrasi"
                            class="text-white w-full font-inter cursor-pointer uppercase bg-transparent border-2 border-tombol hover:text-hitam hover:bg-tombol focus:ring-4 focus:outline-none focus:ring-navbar font-medium text-sm px-6 md:px-9 py-2 text-center">
                            selengkapnya
                        </a>
                    </div>
                </div>
                <x-barber-images :image1="'img/general/bergabung-1.webp'" :alt1="'barber 1'" :image2="'img/general/bergabung-2.webp'" :alt2="'barber 2'" />
            </div>
        </div>
    </section>

    <section class="bg-hitam py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <div class="italic text-white text-2xl md:text-3xl font-poppins mb-2">TEMUKAN GAYAMU DI KULMN
                </div>
                <div class="text-white text-base md:text-lg font-poppins font-light">
                    Kami terus memperluas jangkauan agar lebih dekat dengan kebutuhan grooming-mu.<br>
                    Kunjungi cabang KULMN terdekatmu dan temukan gayamu!
                </div>
            </div>
            <!-- Swiper -->
            <div class="relative">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <!-- Slide 1 -->
                        @foreach ($branches as $branch)
                            <div class="swiper-slide gap-4 md:gap-8">
                                <div class="flex flex-col md:flex-row">
                                    <div class="flex-1 flex justify-end">
                                        @if ($branch->getMedia('images')->first())
                                            <img src="{{ $branch->getMedia('images')->first()->getUrl() }}"
                                                alt="{{ $branch->name }}" class="w-full md:w-3/4 object-cover shadow-lg">
                                        @else
                                            <img src="{{ asset('img/general/slideprofile.webp') }}"
                                                alt="{{ $branch->name }}" class="w-full md:w-3/4 object-cover shadow-lg">
                                        @endif
                                    </div>
                                    <div class="flex-1 flexjustify-start">
                                        {!! $branch->map_url !!}
                                    </div>
                                </div>
                                <div
                                    class="text-white font-poppins text-2xl md:text-4xl mt-5 text-center uppercase italic font-light">
                                    {{ $branch->name }}
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <!-- Navigasi panah -->
                    <div class="hidden md:block">
                        <div class="swiper-button-prev swiper-nav-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </div>
                        <div class="swiper-button-next swiper-nav-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-hitam h-auto">
        <div class="container mx-auto max-w-screen-xl">
            <div class="flex flex-col md:flex-row">
                <div class="flex-1 flex items-end">
                    <img src="{{ asset('img/general/owner.webp') }}" alt="owner">
                </div>
                <div class="flex-1 flex items-center relative p-6 md:p-0">
                    <div
                        class="absolute hidden xl:block inset-0 bg-[url('../../public/img/general/owner-1.webp')] bg-no-repeat bg-bottom-right opacity-5">
                    </div>
                    <div class="flex flex-col relative z-10">
                        <div
                            class="text-white text-center md:text-start font-poppins text-2xl md:text-7xl mt-2 md:mt-5 uppercase italic">
                            toing mt
                        </div>
                        <div
                            class="text-white text-center md:text-start font-poppins text-2xl md:text-4xl mt-2 md:mt-5 uppercase font-light italic">
                            owner kulmn barber
                        </div>
                        <div class="text-white font-poppins break-words text-base mt-5 font-extralight">
                            Owner KULMN sekaligus capster berpengalaman yang telah berkecimpung di dunia barbershop
                            selama 10 Tahun. Keahliannya dalam memadukan teknik potong rambut modern dan klasik
                            menjadikannya panutan di industri ini. Selain melayani langsung pelanggan, Tohir juga aktif
                            membimbing tim capster agar selalu memberikan hasil terbaik. Di bawah kepemimpinannya,
                            barbershop ini dikenal karena kualitas, kenyamanan, dan pelayanan profesional.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-hitam h-auto">
        <div class="container mx-auto max-w-screen-xl">
            <div class="py-16 flex flex-col items-center">
                <div class="italic text-white text-2xl md:text-3xl font-poppins text-center mb-10">
                    MENJADI CAPSTER PROFESIONAL <br>
                    BERSAMA KULMN
                </div>
                <div class="w-full flex flex-col md:flex-row bg-transparent">
                    <div class="bg-[#E9C664] flex-1 flex flex-col justify-center p-8 md:p-12">
                        <div class="font-poppins font-bold italic text-2xl md:text-5xl xl:text-7xl mb-4 text-hitam">
                            BE A <br> PROFESIONAL <br> CAPSTER
                        </div>
                        <div class="font-poppins text-hitam text-base md:text-lg font-light mb-8">
                            Tim scara terdiri dari capster terlatih melalui seleksi dan pelatihan ketat, mulai dari
                            teori terkini hingga layanan pelanggan yang profesional. Ingin jadi capster profesional?
                            Daftar sekarang di Academy Capster kami!
                        </div>
                        <a href="#"
                            class="inline-block bg-hitam text-white font-poppins font-semibold px-6 py-2 transition hover:bg-[#222] w-fit">
                            DAFTAR
                        </a>
                    </div>
                    <div class="flex-1 min-h-[260px] flex w-full items-center justify-center overflow-hidden">
                        <img src="{{ asset('img/general/capster.webp') }}" alt="Capster Academy"
                            class="object-cover w-full h-full" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-hitam h-auto">
        <div class="container mx-auto max-w-screen-xl">
            <div class="py-16 flex flex-col items-center">
                <div class="italic text-white text-2xl md:text-3xl font-poppins text-center mb-10 uppercase">
                    MEET OUR CAPSTER
                </div>
                <div class="relative w-full">
                    <div class="swiper capsterSwiper">
                        <div class="swiper-wrapper">
                            <!-- Slide 1 -->
                            <div class="swiper-slide flex flex-col items-center">
                                <div class="relative flex flex-col items-center">
                                    <img src='{{ asset('img/general/capster/toing-mt.webp') }}' alt="toing mt"
                                        class="w-full object-contain z-10 capster-img-outline" />
                                    <div class="w-full h-15 bg-[#E9C664] mt-[-60px] z-0"></div>
                                </div>
                                <div class="text-white uppercase text-center font-poppins text-lg mt-4 tracking-widest">
                                    toing mt
                                </div>
                            </div>
                            <!-- Slide 2 -->
                            <div class="swiper-slide flex flex-col items-center">
                                <div class="relative flex flex-col items-center">
                                    <img src='{{ asset('img/general/capster/ipul.webp') }}' alt="Ipul"
                                        class="w-full object-contain z-10 capster-img-outline" />
                                    <div class="w-full h-15 bg-[#E9C664] mt-[-60px] z-0"></div>
                                </div>
                                <div class="text-white uppercase text-center font-poppins text-lg mt-4 tracking-widest">
                                    IPUL
                                </div>
                            </div>
                            <!-- Slide 3 -->
                            <div class="swiper-slide flex flex-col items-center">
                                <div class="relative flex flex-col items-center">
                                    <img src='{{ asset('img/general/capster/sam.webp') }}' alt="sam"
                                        class="w-full object-contain z-10 capster-img-outline" />
                                    <div class="w-full h-15 bg-[#E9C664] mt-[-60px] z-0"></div>
                                </div>
                                <div class="text-white uppercase font-poppins text-lg mt-4 tracking-widest text-center">
                                    sam
                                </div>
                            </div>

                            <!-- Slide 4 -->
                            <div class="swiper-slide flex flex-col items-center">
                                <div class="relative flex flex-col items-center">
                                    <img src='{{ asset('img/general/capster/albert.webp') }}' alt="albert"
                                        class="w-full object-contain z-10 capster-img-outline" />
                                    <div class="w-full h-15 bg-[#E9C664] mt-[-60px] z-0"></div>
                                </div>
                                <div class="text-white uppercase text-center font-poppins text-lg mt-4 tracking-widest">
                                    albert
                                </div>
                            </div>
                            <!-- Slide 5 -->
                            <div class="swiper-slide flex flex-col items-center">
                                <div class="relative flex flex-col items-center">
                                    <img src='{{ asset('img/general/capster/purna.webp') }}' alt="purna"
                                        class="w-full object-contain z-10 capster-img-outline" />
                                    <div class="w-full h-15 bg-[#E9C664] mt-[-60px] z-0"></div>
                                </div>
                                <div class="text-white uppercase text-center font-poppins text-lg mt-4 tracking-widest">
                                    purna
                                </div>
                            </div>
                            <!-- Slide 6 -->
                            <div class="swiper-slide flex flex-col items-center">
                                <div class="relative flex flex-col items-center">
                                    <img src='{{ asset('img/general/capster/raden.webp') }}' alt="raden"
                                        class="w-full object-contain z-10 capster-img-outline" />
                                    <div class="w-full h-15 bg-[#E9C664] mt-[-60px] z-0"></div>
                                </div>
                                <div class="text-white uppercase font-poppins text-lg mt-4 tracking-widest text-center">
                                    raden
                                </div>
                            </div>

                            <!-- Slide 6 -->
                            <div class="swiper-slide flex flex-col items-center">
                                <div class="relative flex flex-col items-center">
                                    <img src='{{ asset('img/general/capster/levi.webp') }}' alt="levi"
                                        class="w-full object-contain z-10 capster-img-outline" />
                                    <div class="w-full h-15 bg-[#E9C664] mt-[-60px] z-0"></div>
                                </div>
                                <div class="text-white uppercase font-poppins text-lg mt-4 tracking-widest text-center">
                                    levi
                                </div>
                            </div>
                        </div>
                        <!-- Navigasi panah -->
                        <div class="capster-swiper-nav-btn-prev">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </div>
                        <div class="capster-swiper-nav-btn-next">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="h-auto bg-[linear-gradient(180deg,_#FFF_0%,_#CFCFCF_100%)]">
        <div class="container mx-auto max-w-screen-xl">
            <div class="py-16 flex flex-col items-center">
                <div class="italic text-hitam text-2xl md:text-3xl font-poppins text-center mb-10 uppercase">
                    Produk
                </div>
                <div class="relative w-full">
                    <div class="swiper productSwiper">
                        <div class="swiper-wrapper">
                            <!-- Slide 1 -->
                            <div class="swiper-slide flex flex-col items-center h-full">
                                <div
                                    class="flex flex-col justify-between h-full w-full bg-transparent p-4 rounded-lg min-h-[500px]">
                                    <div>
                                        <div class="relative flex flex-col items-center min-h-[180px]">
                                            <img src='{{ asset('img/general/product/pomade-clay.webp') }}'
                                                alt="pomade clay" class="w-full object-contain z-10 max-h-[200px]" />
                                        </div>
                                        <div
                                            class="text-hitam uppercase text-center font-poppins text-lg my-4 font-bold tracking-widest line-clamp-2">
                                            Pomade Clay Mud
                                        </div>
                                        <div class="mb-5 text-center font-poppins">
                                            Cocok untuk gaya rambut natural, bertekstur, dan bervolume tanpa terasa
                                            lengket atau berminyak.
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <a href="#"
                                            class="inline-block bg-black text-white font-poppins font-semibold px-6 py-3 transition hover:bg-[#222] w-full text-center uppercase">
                                            pesan sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide 2 -->
                            <div class="swiper-slide flex flex-col items-center h-full">
                                <div
                                    class="flex flex-col justify-between h-full w-full bg-transparent p-4 rounded-lg min-h-[500px]">
                                    <div>
                                        <div class="relative flex flex-col items-center min-h-[180px]">
                                            <img src='{{ asset('img/general/product/clay-mud.webp') }}' alt="clay mud"
                                                class="w-full object-contain z-10 max-h-[200px]" />
                                        </div>
                                        <div
                                            class="text-hitam uppercase text-center font-poppins text-lg my-4 font-bold tracking-widest line-clamp-2">
                                            CLAY MUD CHARCOAL
                                        </div>
                                        <div class="mb-5 text-center font-poppins">
                                            Untuk gaya rambut natural dengan tekstur yang kuat namun tetap ringan.
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <a href="#"
                                            class="inline-block bg-black text-white font-poppins font-semibold px-6 py-3 transition hover:bg-[#222] w-full text-center uppercase">
                                            pesan sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide 3 -->
                            <div class="swiper-slide flex flex-col items-center h-full">
                                <div
                                    class="flex flex-col justify-between h-full w-full bg-transparent p-4 rounded-lg min-h-[500px]">
                                    <div>
                                        <div class="relative flex flex-col items-center min-h-[180px]">
                                            <img src='{{ asset('img/general/product/pomade-cool.webp') }}'
                                                alt="pomade cool" class="w-full object-contain z-10 max-h-[200px]" />
                                        </div>
                                        <div
                                            class="text-hitam uppercase text-center font-poppins text-lg my-4 font-bold tracking-widest line-clamp-2">
                                            Pomade Cool Mens Water Based
                                        </div>
                                        <div class="mb-5 text-center font-poppins">
                                            Pomade berbahan dasar air yang memberikan kilau sedang, daya rekat kuat, dan
                                            aroma maskulin menyegarkan.
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <a href="#"
                                            class="inline-block bg-black text-white font-poppins font-semibold px-6 py-3 transition hover:bg-[#222] w-full text-center uppercase">
                                            pesan sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 1 -->
                            <div class="swiper-slide flex flex-col items-center h-full">
                                <div
                                    class="flex flex-col justify-between h-full w-full bg-transparent p-4 rounded-lg min-h-[500px]">
                                    <div>
                                        <div class="relative flex flex-col items-center min-h-[180px]">
                                            <img src='{{ asset('img/general/product/pomade-clay.webp') }}'
                                                alt="pomade clay" class="w-full object-contain z-10 max-h-[200px]" />
                                        </div>
                                        <div
                                            class="text-hitam uppercase text-center font-poppins text-lg my-4 font-bold tracking-widest line-clamp-2">
                                            Pomade Clay Mud
                                        </div>
                                        <div class="mb-5 text-center font-poppins">
                                            Cocok untuk gaya rambut natural, bertekstur, dan bervolume tanpa terasa
                                            lengket atau berminyak.
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <a href="#"
                                            class="inline-block bg-black text-white font-poppins font-semibold px-6 py-3 transition hover:bg-[#222] w-full text-center uppercase">
                                            pesan sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide 2 -->
                            <div class="swiper-slide flex flex-col items-center h-full">
                                <div
                                    class="flex flex-col justify-between h-full w-full bg-transparent p-4 rounded-lg min-h-[500px]">
                                    <div>
                                        <div class="relative flex flex-col items-center min-h-[180px]">
                                            <img src='{{ asset('img/general/product/clay-mud.webp') }}' alt="clay mud"
                                                class="w-full object-contain z-10 max-h-[200px]" />
                                        </div>
                                        <div
                                            class="text-hitam uppercase text-center font-poppins text-lg my-4 font-bold tracking-widest line-clamp-2">
                                            CLAY MUD CHARCOAL
                                        </div>
                                        <div class="mb-5 text-center font-poppins">
                                            Untuk gaya rambut natural dengan tekstur yang kuat namun tetap ringan.
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <a href="#"
                                            class="inline-block bg-black text-white font-poppins font-semibold px-6 py-3 transition hover:bg-[#222] w-full text-center uppercase">
                                            pesan sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide 3 -->
                            <div class="swiper-slide flex flex-col items-center h-full">
                                <div
                                    class="flex flex-col justify-between h-full w-full bg-transparent p-4 rounded-lg min-h-[500px]">
                                    <div>
                                        <div class="relative flex flex-col items-center min-h-[180px]">
                                            <img src='{{ asset('img/general/product/pomade-cool.webp') }}'
                                                alt="pomade cool" class="w-full object-contain z-10 max-h-[200px]" />
                                        </div>
                                        <div
                                            class="text-hitam uppercase text-center font-poppins text-lg my-4 font-bold tracking-widest line-clamp-2">
                                            Pomade Cool Mens Water Based
                                        </div>
                                        <div class="mb-5 text-center font-poppins">
                                            Pomade berbahan dasar air yang memberikan kilau sedang, daya rekat kuat, dan
                                            aroma maskulin menyegarkan.
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <a href="#"
                                            class="inline-block bg-black text-white font-poppins font-semibold px-6 py-3 transition hover:bg-[#222] w-full text-center uppercase">
                                            pesan sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Navigasi panah -->
                        <div class="product-swiper-nav-btn-prev">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </div>
                        <div class="product-swiper-nav-btn-next">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-hitam h-auto">
        <div class="container mx-auto max-w-screen-xl py-10 md:py-20">
            <div class="flex flex-col items-center">
                <div class="text-tombol uppercase text-xl md:text-2xl font-normal font-poppins mb-5">
                    artikel
                </div>
                <div class="italic text-white text-2xl md:text-6xl font-poppins text-center mb-10 uppercase">
                    latest news
                </div>
            </div>

            <div class="relative w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 xl:gap-5 p-6 xl:p-0">
                <div class="max-w-screen-xl mx-auto bg-black">
                    <div class="relative flex flex-col items-center min-h-[180px]">
                        <img src="{{ asset('img/general/img.webp') }}" class="w-full h-full object-cover"
                            alt="artikel">
                    </div>
                    <div class="font-poppins p-3 xl:p-6">
                        <div class="text-white font-bold text-xl md:text-2xl capitalize line-clamp-2">
                            Vel Pellentesque
                        </div>
                        <div class="text-white font-light line-clamp-2 md:text-base text-sm my-5">
                            Duis porta, ligula rhoncus
                            euismod pretium, nisi tellus
                        </div>
                        <div class="text-tombol font-semibold mt-5 uppercase">
                            READ MORE
                        </div>
                    </div>
                </div>
                <div class="max-w-screen-xl mx-auto bg-black">
                    <div class="relative flex flex-col items-center min-h-[180px]">
                        <img src="{{ asset('img/general/img.webp') }}" class="w-full h-full object-cover"
                            alt="artikel">
                    </div>
                    <div class="font-poppins p-3 xl:p-6">
                        <div class="text-white font-bold text-xl md:text-2xl capitalize line-clamp-2">
                            Vel Pellentesque
                        </div>
                        <div class="text-white font-light line-clamp-2 md:text-base text-sm my-5">
                            Duis porta, ligula rhoncus
                            euismod pretium, nisi tellus
                        </div>
                        <div class="text-tombol font-semibold mt-5 uppercase">
                            READ MORE
                        </div>
                    </div>
                </div>
                <div class="max-w-screen-xl mx-auto bg-black">
                    <div class="relative flex flex-col items-center min-h-[180px]">
                        <img src="{{ asset('img/general/img.webp') }}" class="w-full h-full object-cover"
                            alt="artikel">
                    </div>
                    <div class="font-poppins p-3 xl:p-6">
                        <div class="text-white font-bold text-xl md:text-2xl capitalize line-clamp-2">
                            Vel Pellentesque
                        </div>
                        <div class="text-white font-light line-clamp-2 md:text-base text-sm my-5">
                            Duis porta, ligula rhoncus
                            euismod pretium, nisi tellus
                        </div>
                        <div class="text-tombol font-semibold mt-5 uppercase">
                            READ MORE
                        </div>
                    </div>
                </div>
                <div class="max-w-screen-xl mx-auto bg-black">
                    <div class="relative flex flex-col items-center min-h-[180px]">
                        <img src="{{ asset('img/general/img.webp') }}" class="w-full h-full object-cover"
                            alt="artikel">
                    </div>
                    <div class="font-poppins p-3 xl:p-6">
                        <div class="text-white font-bold text-xl md:text-2xl capitalize line-clamp-2">
                            Vel Pellentesque
                        </div>
                        <div class="text-white font-light line-clamp-2 md:text-base text-sm my-5">
                            Duis porta, ligula rhoncus
                            euismod pretium, nisi tellus
                        </div>
                        <div class="text-tombol font-semibold mt-5 uppercase">
                            READ MORE
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
@section('kaki')
    <!-- SwiperJS JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('navbar-bg');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 0) {
                    navbar.classList.remove('bg-transparent');
                    navbar.classList.add('bg-[rgba(0,0,0,0.5)]');
                } else {
                    navbar.classList.add('bg-transparent');
                    navbar.classList.remove('bg-[rgba(0,0,0,0.5)]');
                }
            });

            // Inisialisasi Swiper utama
            new Swiper('.mySwiper', {
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                slidesPerView: 1,
                spaceBetween: 30,
            });

            // Inisialisasi Swiper capster
            new Swiper('.capsterSwiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.capster-swiper-nav-btn-next',
                    prevEl: '.capster-swiper-nav-btn-prev',
                },
                slidesPerView: 3,
                spaceBetween: 30,
                breakpoints: {
                    0: {
                        slidesPerView: 1
                    },
                    768: {
                        slidesPerView: 2
                    },
                    1024: {
                        slidesPerView: 3
                    }
                }
            });

            // Inisialisasi Swiper Product
            new Swiper('.productSwiper', {
                loop: true,
                navigation: {
                    nextEl: '.product-swiper-nav-btn-next',
                    prevEl: '.product-swiper-nav-btn-prev',
                },
                slidesPerView: 3,
                spaceBetween: 30,
                breakpoints: {
                    0: {
                        slidesPerView: 1
                    },
                    768: {
                        slidesPerView: 2
                    },
                    1024: {
                        slidesPerView: 3
                    }
                }
            });
        });
    </script>
@endsection
