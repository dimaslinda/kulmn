@extends('layouts.main')
@section('banner')
    <section class="relative min-h-[400px] md:min-h-[600px] flex items-center bg-black overflow-hidden">
        <img src="{{ asset('img/general/bg-mitra.webp') }}" alt="KULMN BARBER & SHOP"
            class="absolute inset-0 w-full h-full object-cover object-center opacity-60 z-0">
        <div
            class="container mx-auto relative z-10 flex flex-col md:flex-row items-center md:items-start px-6 py-20 md:py-24 xl:py-32 2xl:max-w-screen-xl">
            <div class="w-full md:w-1/2 text-white">
                <div class="font-poppins italic font-normal text-3xl md:text-5xl xl:text-7xl leading-tight mb-6">
                    BERGABUNG <br>
                    DENGAN <br>
                    MITRA KAMI
                </div>
                <div class="font-poppins text-sm md:text-base font-light mb-8 max-w-lg">
                    Bergabung sebagai mitra KULMN barbershop membuka peluang besar untuk berkembang bersama. Dapatkan
                    dukungan penuh, akses ke jaringan luas, dan sistem kolaborasi yang saling menguntungkan. Tumbuh dan maju
                    bersama KULMN.
                </div>
                <a href="https://wa.me/62895411871843" target="_blank"
                    class="inline-block border border-[#E9C664] text-[#E9C664] font-poppins text-xs md:text-sm px-8 py-3 rounded transition hover:bg-[#E9C664] hover:text-black font-semibold tracking-widest">
                    KONSULTASI SEKARANG
                </a>
            </div>
        </div>
    </section>
@endsection
@section('content')
    <section class="bg-hitam">
        <div class="container mx-auto px-6 py-10 md:py-20 xl:py-24 max-w-screen-xl">
            <div class="flex flex-col lg:flex-row">
                <x-barber-images :image1="'img/general/bergabung-1.webp'" :alt1="'barber 1'" :image2="'img/general/bergabung-2.webp'" :alt2="'barber 2'" />
                @php
                    $terms = [
                        ['title' => 'Komitmen', 'description' => 'Menjalankan seluruh komitmen yang telah di sepakati'],
                        [
                            'title' => 'Badan Hukum',
                            'description' => 'Memiliki legalitas badan hukum atau perseorangan yang harus di lampirkan',
                        ],
                        [
                            'title' => 'Kerjasama Jangka Panjang',
                            'description' =>
                                'mitra yang memiliki visi tumbuh bersama dan siap untuk menjalin kemitraan dalam jangka panjang.',
                        ],
                        [
                            'title' => 'Menjaga Reputasi',
                            'description' =>
                                'Menjaga integritas serta tidak melakukan tindakan yang dapat merugikan citra perusahaan dan Kerja sama yang sehat',
                        ],
                    ];
                @endphp
                <x-terms-and-conditions :terms="$terms" />
            </div>
        </div>
    </section>

    <section class="bg-hitam h-auto">
        <div class="container mx-auto py-20 p-6 xl:p-0 xl:py-20 2xl:max-w-screen-xl">
            <div class="flex flex-col lg:flex-row justify-center items-center gap-5">
                <div class="flex-1 font-poppins">
                    <div class="text-white italic text-4xl mb-5 uppercase">
                        BUSINESS ROADMAP
                    </div>
                    <div class="text-white text-lg my-10">
                        Ingin memiliki bisnis barbershop dengan sistem yang sudah teruji, brand yang kuat, dan dukungan
                        penuh dari tim profesional? Inilah saatnya Anda bergabung bersama kami sebagai mitra barbershop!
                    </div>
                    <div class="text-white text-lg my-10">
                        Kami membuka peluang kemitraan bagi Anda yang ingin memulai usaha di industri grooming pria dengan
                        modal yang terjangkau, sistem operasional yang mudah, dan potensi keuntungan yang stabil.
                    </div>
                    <div class="flex w-full">
                        <a href="/registrasi"
                            class="text-white w-full font-inter cursor-pointer uppercase bg-transparent border-2 border-tombol hover:text-hitam hover:bg-tombol focus:ring-4 focus:outline-none focus:ring-navbar font-medium text-sm px-6 md:px-9 py-2 text-center">
                            konsultasi sekarang
                        </a>
                    </div>
                </div>
                <div class="flex-1 flex flex-col justify-center w-full">

                    <div id="accordion-color" data-accordion="collapse" data-active-classes="text-white">
                        <div class="mb-5">
                            <h2 id="accordion-color-heading-1">
                                <button type="button"
                                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right bg-black text-white gap-3 cursor-pointer"
                                    data-accordion-target="#accordion-color-body-1" aria-expanded="true"
                                    aria-controls="accordion-color-body-1">
                                    <span class="text-white">1. FGD Cooperation</span>
                                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M9 5 5 1 1 5" />
                                    </svg>
                                </button>
                            </h2>
                            <div id="accordion-color-body-1" class="hidden" aria-labelledby="accordion-color-heading-1">
                                <div class="p-5 bg-black">
                                    <p class="mb-2 text-white">
                                        Flowbite is an open-source library of
                                        Pada tahap ini akan memulai pembahasan kerjasama kemitraan, mulai dari hak dan
                                        kewajiban hingga hal-hal detail kerjasama
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <h2 id="accordion-color-heading-2">
                                <button type="button"
                                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right bg-black text-white gap-3 cursor-pointer"
                                    data-accordion-target="#accordion-color-body-2" aria-expanded="false"
                                    aria-controls="accordion-color-body-2">
                                    <span class="text-white">2. Penandatanganan MoU</span>
                                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M9 5 5 1 1 5" />
                                    </svg>
                                </button>
                            </h2>
                            <div id="accordion-color-body-2" class="hidden" aria-labelledby="accordion-color-heading-2">
                                <div class="p-5 bg-black">
                                    <p class="mb-2 text-white">
                                        Segala hal-hal yang telah di sepakati akan dituangkan di dalam MoU yang akan di
                                        tandatangani kedua belah pihak
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <h2 id="accordion-color-heading-3">
                                <button type="button"
                                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right bg-black text-white gap-3 cursor-pointer"
                                    data-accordion-target="#accordion-color-body-3" aria-expanded="false"
                                    aria-controls="accordion-color-body-3">
                                    <span class="text-white">3. Report Triwulan</span>
                                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M9 5 5 1 1 5" />
                                    </svg>
                                </button>
                            </h2>
                            <div id="accordion-color-body-3" class="hidden" aria-labelledby="accordion-color-heading-3">
                                <div class="p-5 bg-black">
                                    <p class="mb-2 text-white">
                                        Per 3 bulan yang telah di sepakati pihak KULMN akan memberikan laporan perkembangan
                                        cabang yang telah dibuka
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <h2 id="accordion-color-heading-4">
                                <button type="button"
                                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right bg-black text-white gap-3 cursor-pointer"
                                    data-accordion-target="#accordion-color-body-4" aria-expanded="false"
                                    aria-controls="accordion-color-body-4">
                                    <span class="text-white">4. Pembagian Deviden</span>
                                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M9 5 5 1 1 5" />
                                    </svg>
                                </button>
                            </h2>
                            <div id="accordion-color-body-4" class="hidden" aria-labelledby="accordion-color-heading-4">
                                <div class="p-5 bg-black">
                                    <p class="mb-2 text-white">
                                        Setiap akhir tahun kerjasama akan dilakukan pembagian deviden (keuntungan) dari
                                        cabang yang sudah berjalan satu tahun.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
