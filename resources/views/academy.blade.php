@extends('layouts.main')
@section('banner')
    <section class="relative min-h-[400px] md:min-h-[600px] flex items-center bg-black overflow-hidden">
        <img src="{{ asset('img/general/bg-capster.webp') }}" alt="KULMN BARBER & SHOP"
            class="absolute inset-0 w-full h-full object-cover object-center opacity-60 z-0">
        <div
            class="container mx-auto relative z-10 flex flex-col md:flex-row items-center md:items-start px-6 py-20 md:py-24 xl:py-32 2xl:max-w-screen-xl">
            <div class="w-full md:w-1/2 text-white">
                <div class="font-poppins italic font-normal text-3xl md:text-5xl xl:text-7xl leading-tight mb-6">
                    JADI CAPSTER <br>
                    PROFESIONAL <br>
                    KULMN
                </div>
                <div class="font-poppins text-sm md:text-base font-light mb-8 max-w-lg">
                    KULMN membuka program pelatihan khusus bagi kamu yang ingin terjun ke dunia barbershop secara
                    profesional. Pelatihan ini dirancang untuk membentuk capster dengan kemampuan teknis, pelayanan, dan
                    etika kerja terbaik.
                </div>
                <a href="https://wa.me/62895411871843" target="_blank"
                    class="inline-block border border-[#E9C664] text-[#E9C664] uppercase font-poppins text-xs md:text-sm px-8 py-3 rounded transition hover:bg-[#E9C664] hover:text-black font-semibold tracking-widest">
                    daftar sekarang
                </a>
            </div>
        </div>
    </section>
@endsection
@section('content')
    <section class="bg-hitam">
        <div class="container mx-auto px-6 py-10 md:py-20 xl:py-24 max-w-screen-xl">
            <div class="flex flex-col lg:flex-row">
                <x-barber-images :image1="'img/general/capster-1.webp'" :alt1="'capster 1'" :image2="'img/general/capster-2.webp'" :alt2="'capster 2'" />
                @php
                    $terms = [
                        [
                            'title' => 'Siap Kerja',
                            'description' =>
                                'Capster yang sudah mendapatkan beasiswa akan terserap langsung keseluruh cabang KULMN',
                        ],
                        [
                            'title' => 'Mentor Profesional',
                            'description' =>
                                'Di dampingi mentor profesional yang memiliki pengelaman yang lama dalam barbershop',
                        ],
                        [
                            'title' => 'Lingkungan Belajar Nyaman',
                            'description' =>
                                'Belajar langsung di lokasi barbershop dengan klien nyata untuk membangun kepercayaan diri.',
                        ],
                        [
                            'title' => 'Akses Komunitas',
                            'description' =>
                                'Menjadi bagian dari komunitas barber KULMN, berjejaring dengan para profesional, dan dibantu mengembangkan karier.',
                        ],
                    ];
                @endphp
                <x-terms-and-conditions :terms="$terms" />
            </div>
        </div>
    </section>

    <section class="bg-hitam">
        <div class="container mx-auto px-6 py-10 md:py-20 xl:py-24 max-w-screen-xl">
            <div class="md:p-8">
                <div class="flex flex-col md:flex-row gap-8 pb-8 mb-8 font-poppins">
                    <div class="flex flex-col text-start md:p-8">
                        <h3 class="text-white text-4xl font-normal mb-4 italic">BEASISWA</h3>
                        <p class="text-white text-lg leading-relaxed mb-8">Bergabung sebagai mitra KULMN berarti membuka
                            peluang besar untuk berkembang bersama. Dapatkan dukungan promosi, akses ke jaringan luas, dan
                            sistem kolaborasi yang saling menguntungkan. Tumbuh dan Maju Bersama KULMN!</p>
                    </div>
                    <div class="border-l border-tombol"></div>
                    <div class="flex flex-col text-start md:p-8">
                        <h3 class="text-white text-4xl font-normal mb-4 italic">REGULAR</h3>
                        <p class="text-white text-lg leading-relaxed mb-8">Bergabung sebagai mitra KULMN berarti membuka
                            peluang besar untuk berkembang bersama. Dapatkan dukungan promosi, akses ke jaringan luas, dan
                            sistem kolaborasi yang saling menguntungkan. Tumbuh dan Maju Bersama KULMN!</p>
                    </div>
                </div>
                <div class="text-center w-full flex">
                    <a href="https://wa.me/62895411871843" target="_blank"
                        class="bg-transparent w-full cursor-pointer font-poppins border border-tombol text-white px-12 py-4 rounded-lg text-lg font-semibold hover:bg-yellow-500 hover:text-hitam transition duration-300">
                        DAFTAR SEKARANG
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
